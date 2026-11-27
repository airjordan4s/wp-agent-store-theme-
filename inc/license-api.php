<?php
/**
 * 防 AI 绕过授权中心与签名校验 REST API（Ed25519 非对称签名 + Nonce 防重放 + 设备绑定 + 受保护载荷下发）
 *
 * 端点（/wp-json/skillcraft/v1/license/*）：
 * - POST /activate   激活授权码、绑定 machine_id，返回 Ed25519 签名的 24h 短期租约 Token
 * - POST /verify     启动/定时静默续签，绑定 client_nonce 防本地伪造与重放
 * - POST /payload    校验通过后才下发受保护云端规则/配置与安装包地址（未授权 403）
 * - POST /deactivate 从当前设备解绑释放名额
 *
 * @package SkillCraft
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ==========================================================================
   1. Base64URL 与 Ed25519 密钥对管理（私钥仅存服务端，客户端只内嵌公钥）
   ========================================================================== */

function sc_b64url_encode( $bin ) {
	return rtrim( strtr( base64_encode( $bin ), '+/', '-_' ), '=' );
}

function sc_b64url_decode( $str ) {
	$pad = strlen( $str ) % 4;
	if ( $pad ) {
		$str .= str_repeat( '=', 4 - $pad );
	}
	return base64_decode( strtr( $str, '-_', '+/' ) );
}

/** 获取或自动生成站点专属 Ed25519 密钥对（若宿主无 libsodium 则降级 HMAC-SHA256） */
function sc_license_keys() {
	if ( function_exists( 'sodium_crypto_sign_keypair' ) ) {
		$saved = get_option( 'skillcraft_license_ed25519', array() );
		if ( is_array( $saved ) && ! empty( $saved['priv'] ) && ! empty( $saved['pub'] ) ) {
			return array(
				'alg'  => 'EdDSA',
				'priv' => sc_b64url_decode( $saved['priv'] ),
				'pub'  => sc_b64url_decode( $saved['pub'] ),
				'pub_b64' => $saved['pub'],
			);
		}
		$kp   = sodium_crypto_sign_keypair();
		$priv = sodium_crypto_sign_secretkey( $kp );
		$pub  = sodium_crypto_sign_publickey( $kp );
		$data = array(
			'priv' => sc_b64url_encode( $priv ),
			'pub'  => sc_b64url_encode( $pub ),
		);
		update_option( 'skillcraft_license_ed25519', $data, false );
		return array(
			'alg'     => 'EdDSA',
			'priv'    => $priv,
			'pub'     => $pub,
			'pub_b64' => $data['pub'],
		);
	}

	$secret = get_option( 'skillcraft_license_hmac', '' );
	if ( '' === $secret ) {
		$secret = wp_generate_password( 64, true, true );
		update_option( 'skillcraft_license_hmac', $secret, false );
	}
	return array(
		'alg'     => 'HS256',
		'priv'    => $secret,
		'pub'     => $secret,
		'pub_b64' => '',
	);
}

/** 签发短期加密租约 Token（包含 client_nonce、machine_id、expires_at、lease_exp） */
function sc_license_sign_lease( array $claims ) {
	$keys    = sc_license_keys();
	$header  = array( 'alg' => $keys['alg'], 'typ' => 'SCL' );
	$h_b64   = sc_b64url_encode( wp_json_encode( $header ) );
	$p_b64   = sc_b64url_encode( wp_json_encode( $claims ) );
	$signing = $h_b64 . '.' . $p_b64;

	if ( 'EdDSA' === $keys['alg'] && function_exists( 'sodium_crypto_sign_detached' ) ) {
		$sig = sodium_crypto_sign_detached( $signing, $keys['priv'] );
	} else {
		$sig = hash_hmac( 'sha256', $signing, $keys['priv'], true );
	}
	return $signing . '.' . sc_b64url_encode( $sig );
}

/* ==========================================================================
   2. IP 限流与核心订单校验逻辑
   ========================================================================== */

function sc_license_rate_limit_ok() {
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '0.0.0.0';
	$key = 'sc_rl_' . md5( $ip );
	$cnt = (int) get_transient( $key );
	if ( $cnt >= 30 ) {
		return false;
	}
	set_transient( $key, $cnt + 1, MINUTE_IN_SECONDS );
	return true;
}

function sc_license_find_order( $license_key ) {
	$license_key = strtoupper( trim( sanitize_text_field( $license_key ) ) );
	if ( '' === $license_key ) {
		return null;
	}
	$posts = get_posts( array(
		'post_type'   => 'sc_order',
		'post_status' => 'publish',
		'numberposts' => 1,
		'meta_key'    => '_sc_license_key',
		'meta_value'  => $license_key,
	) );
	return ! empty( $posts ) ? $posts[0] : null;
}

/** 校验订单有效性、周期与设备绑定；成功返回数组，失败返回 WP_Error */
function sc_license_evaluate( $license_key, $machine_id, $pack_slug = '', $machine_label = '', $auto_bind = false ) {
	$order = sc_license_find_order( $license_key );
	if ( ! $order ) {
		return new WP_Error( 'invalid_license', '授权码不存在或无效', array( 'status' => 403 ) );
	}

	$status = get_post_meta( $order->ID, '_sc_status', true ) ?: 'pending';
	if ( 'refunded' === $status ) {
		return new WP_Error( 'order_refunded', '该订单已退款，授权已收回', array( 'status' => 403 ) );
	}
	if ( 'paid' !== $status ) {
		return new WP_Error( 'order_unpaid', '该订单尚未支付生效', array( 'status' => 403 ) );
	}

	$order_pack = get_post_meta( $order->ID, '_sc_pack_slug', true );
	if ( '' !== $pack_slug && '' !== $order_pack && $pack_slug !== $order_pack ) {
		return new WP_Error( 'pack_mismatch', '授权码与当前工作台/插件不匹配', array( 'status' => 403 ) );
	}

	$now        = time();
	$expires_at = (int) get_post_meta( $order->ID, '_sc_expires_at', true );
	if ( $expires_at > 0 && $expires_at < $now ) {
		return new WP_Error( 'license_expired', '授权已到期，请续费后继续使用', array( 'status' => 403 ) );
	}

	$machine_id = sanitize_text_field( trim( $machine_id ) );
	if ( strlen( $machine_id ) < 8 ) {
		return new WP_Error( 'invalid_machine', '缺少有效的设备指纹（machine_id）', array( 'status' => 400 ) );
	}
	$mid_hash    = substr( hash( 'sha256', $machine_id ), 0, 24 );
	$devices     = sc_order_get_devices( $order->ID );
	$max_devices = (int) get_post_meta( $order->ID, '_sc_max_devices', true ) ?: 2;

	$found_idx = -1;
	foreach ( $devices as $idx => $d ) {
		if ( isset( $d['id'] ) && hash_equals( (string) $d['id'], $mid_hash ) ) {
			$found_idx = $idx;
			break;
		}
	}

	if ( $found_idx >= 0 ) {
		$devices[ $found_idx ]['last_seen'] = $now;
		if ( '' !== $machine_label ) {
			$devices[ $found_idx ]['label'] = sanitize_text_field( $machine_label );
		}
		update_post_meta( $order->ID, '_sc_devices', wp_json_encode( $devices ) );
	} elseif ( $auto_bind ) {
		if ( count( $devices ) >= $max_devices ) {
			return new WP_Error( 'device_limit_reached', '已达到最大绑定设备数，请先在会员中心解绑旧设备', array( 'status' => 403 ) );
		}
		$devices[] = array(
			'id'        => $mid_hash,
			'label'     => $machine_label ? sanitize_text_field( $machine_label ) : 'Device-' . ( count( $devices ) + 1 ),
			'bound_at'  => $now,
			'last_seen' => $now,
		);
		update_post_meta( $order->ID, '_sc_devices', wp_json_encode( $devices ) );
	} else {
		return new WP_Error( 'device_not_bound', '当前设备尚未激活，请先调用 /activate 完成绑定', array( 'status' => 403 ) );
	}

	return array(
		'order_id'     => $order->ID,
		'order_no'     => $order->post_title,
		'skill_id'     => (int) get_post_meta( $order->ID, '_sc_skill_id', true ),
		'pack_slug'    => $order_pack ?: $pack_slug,
		'machine_hash' => $mid_hash,
		'expires_at'   => $expires_at,
		'devices_used' => count( $devices ),
		'max_devices'  => $max_devices,
	);
}

/* ==========================================================================
   3. 注册 REST API 路由：/wp-json/skillcraft/v1/license/*
   ========================================================================== */

function sc_register_license_rest_routes() {
	$ns = 'skillcraft/v1';

	register_rest_route( $ns, '/license/activate', array(
		'methods'             => 'POST',
		'callback'            => 'sc_rest_license_activate',
		'permission_callback' => '__return_true',
	) );
	register_rest_route( $ns, '/license/verify', array(
		'methods'             => 'POST',
		'callback'            => 'sc_rest_license_verify',
		'permission_callback' => '__return_true',
	) );
	register_rest_route( $ns, '/license/payload', array(
		'methods'             => 'POST',
		'callback'            => 'sc_rest_license_payload',
		'permission_callback' => '__return_true',
	) );
	register_rest_route( $ns, '/license/deactivate', array(
		'methods'             => 'POST',
		'callback'            => 'sc_rest_license_deactivate',
		'permission_callback' => '__return_true',
	) );
}
add_action( 'rest_api_init', 'sc_register_license_rest_routes' );

function sc_rest_build_lease_response( array $eval, $client_nonce, $include_payload = false ) {
	$now       = time();
	$lease_exp = $now + DAY_IN_SECONDS; // 24 小时短期租约，过期必须静默重签
	if ( $eval['expires_at'] > 0 && $eval['expires_at'] < $lease_exp ) {
		$lease_exp = $eval['expires_at'];
	}

	$claims = array(
		'iss'          => wp_parse_url( home_url(), PHP_URL_HOST ),
		'order_no'     => $eval['order_no'],
		'pack_slug'    => $eval['pack_slug'],
		'machine_hash' => $eval['machine_hash'],
		'client_nonce' => sanitize_text_field( (string) $client_nonce ),
		'expires_at'   => $eval['expires_at'],
		'iat'          => $now,
		'lease_exp'    => $lease_exp,
		'devices_used' => $eval['devices_used'],
		'max_devices'  => $eval['max_devices'],
	);

	$token = sc_license_sign_lease( $claims );
	$res   = array(
		'valid'       => true,
		'alg'         => sc_license_keys()['alg'],
		'lease_token' => $token,
		'claims'      => $claims,
	);

	if ( $include_payload && ! empty( $eval['skill_id'] ) ) {
		$raw_payload         = get_post_meta( $eval['skill_id'], '_sc_protected_payload', true );
		$decoded             = json_decode( (string) $raw_payload, true );
		$res['download_url'] = get_post_meta( $eval['skill_id'], '_sc_download_url', true );
		$res['payload']      = ( JSON_ERROR_NONE === json_last_error() && is_array( $decoded ) ) ? $decoded : $raw_payload;
	}
	return new WP_REST_Response( $res, 200 );
}

function sc_rest_license_activate( WP_REST_Request $req ) {
	if ( ! sc_license_rate_limit_ok() ) {
		return new WP_Error( 'rate_limited', '请求过于频繁，请 1 分钟后再试', array( 'status' => 429 ) );
	}
	$eval = sc_license_evaluate(
		(string) $req->get_param( 'license_key' ),
		(string) $req->get_param( 'machine_id' ),
		(string) $req->get_param( 'pack_slug' ),
		(string) $req->get_param( 'machine_label' ),
		true
	);
	if ( is_wp_error( $eval ) ) {
		return $eval;
	}
	return sc_rest_build_lease_response( $eval, $req->get_param( 'client_nonce' ), true );
}

function sc_rest_license_verify( WP_REST_Request $req ) {
	if ( ! sc_license_rate_limit_ok() ) {
		return new WP_Error( 'rate_limited', '请求过于频繁，请 1 分钟后再试', array( 'status' => 429 ) );
	}
	$eval = sc_license_evaluate(
		(string) $req->get_param( 'license_key' ),
		(string) $req->get_param( 'machine_id' ),
		(string) $req->get_param( 'pack_slug' ),
		(string) $req->get_param( 'machine_label' ),
		false
	);
	if ( is_wp_error( $eval ) ) {
		return $eval;
	}
	return sc_rest_build_lease_response( $eval, $req->get_param( 'client_nonce' ), false );
}

function sc_rest_license_payload( WP_REST_Request $req ) {
	if ( ! sc_license_rate_limit_ok() ) {
		return new WP_Error( 'rate_limited', '请求过于频繁，请 1 分钟后再试', array( 'status' => 429 ) );
	}
	$eval = sc_license_evaluate(
		(string) $req->get_param( 'license_key' ),
		(string) $req->get_param( 'machine_id' ),
		(string) $req->get_param( 'pack_slug' ),
		'',
		false
	);
	if ( is_wp_error( $eval ) ) {
		return $eval;
	}
	return sc_rest_build_lease_response( $eval, $req->get_param( 'client_nonce' ), true );
}

function sc_rest_license_deactivate( WP_REST_Request $req ) {
	if ( ! sc_license_rate_limit_ok() ) {
		return new WP_Error( 'rate_limited', '请求过于频繁，请 1 分钟后再试', array( 'status' => 429 ) );
	}
	$order = sc_license_find_order( (string) $req->get_param( 'license_key' ) );
	if ( ! $order ) {
		return new WP_Error( 'invalid_license', '授权码无效', array( 'status' => 403 ) );
	}
	$mid_hash = substr( hash( 'sha256', trim( (string) $req->get_param( 'machine_id' ) ) ), 0, 24 );
	$devices  = sc_order_get_devices( $order->ID );
	$next     = array_values( array_filter( $devices, function ( $d ) use ( $mid_hash ) {
		return ! isset( $d['id'] ) || ! hash_equals( (string) $d['id'], $mid_hash );
	} ) );
	update_post_meta( $order->ID, '_sc_devices', wp_json_encode( $next ) );
	return new WP_REST_Response( array( 'deactivated' => true, 'devices_used' => count( $next ) ), 200 );
}

/* ==========================================================================
   4. WordPress 后台子菜单：查看 Ed25519 公钥与接入文档
   ========================================================================== */

function sc_register_license_admin_page() {
	add_submenu_page(
		'skillcraft-content',
		__( '授权 API 与公钥', 'skillcraft' ),
		__( '授权 API 与公钥', 'skillcraft' ),
		'manage_options',
		'skillcraft-license-api',
		'sc_render_license_admin_page'
	);
}
add_action( 'admin_menu', 'sc_register_license_admin_page', 20 );

function sc_render_license_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$keys = sc_license_keys();
	$base = rest_url( 'skillcraft/v1/license' );
	?>
	<div class="wrap" style="max-width:920px">
		<h1>授权鉴权中心（Ed25519 公钥 & 防 AI 绕过 REST API）</h1>
		<p>将下方 <strong>Ed25519 公钥</strong> 内嵌在你的 <code>dsh</code> 宿主、桌面端二进制或 MCP 网关里。私钥仅存放在当前 WordPress 数据库，任何本地客户端或 AI 均无法伪造签名租约。</p>

		<div style="background:#fff;border:1px solid #dcdcde;padding:20px;border-radius:6px;margin:16px 0">
			<h2 style="margin-top:0">1. 站点签名算法与公钥（只读）</h2>
			<p>当前算法：<code><?php echo esc_html( $keys['alg'] ); ?></code></p>
			<?php if ( 'EdDSA' === $keys['alg'] ) : ?>
				<label style="font-weight:600;display:block;margin-bottom:6px">Ed25519 Public Key (Base64URL)</label>
				<input type="text" readonly class="large-text code" value="<?php echo esc_attr( $keys['pub_b64'] ); ?>" onclick="this.select()">
			<?php else : ?>
				<p style="color:#b45309">当前 PHP 环境未启用 libsodium，已自动降级为 HS256。建议在 PHP 中开启 <code>sodium</code> 扩展以启用非对称 Ed25519 签名。</p>
			<?php endif; ?>
		</div>

		<div style="background:#fff;border:1px solid #dcdcde;padding:20px;border-radius:6px;margin:16px 0">
			<h2 style="margin-top:0">2. REST API 端点清单</h2>
			<table class="widefat striped">
				<thead><tr><th>方法</th><th>端点</th><th>用途</th></tr></thead>
				<tbody>
					<tr><td><code>POST</code></td><td><code><?php echo esc_html( $base . '/activate' ); ?></code></td><td>激活授权码并绑定 <code>machine_id</code>，签发 24h 租约 Token</td></tr>
					<tr><td><code>POST</code></td><td><code><?php echo esc_html( $base . '/verify' ); ?></code></td><td>定时静默续签，绑定 <code>client_nonce</code> 防止重放与本地伪造</td></tr>
					<tr><td><code>POST</code></td><td><code><?php echo esc_html( $base . '/payload' ); ?></code></td><td>校验有效后下发受保护云端配置与安装包地址（防明文绕过）</td></tr>
					<tr><td><code>POST</code></td><td><code><?php echo esc_html( $base . '/deactivate' ); ?></code></td><td>解绑当前设备，释放设备名额</td></tr>
				</tbody>
			</table>
		</div>

		<div style="background:#fff;border:1px solid #dcdcde;padding:20px;border-radius:6px">
			<h2 style="margin-top:0">3. 防 AI 绕过最佳实践</h2>
			<ol style="line-height:1.8">
				<li>客户端每次请求生成随机 <code>client_nonce</code>，收到 <code>lease_token</code> 后用上面的 <strong>Ed25519 公钥</strong>验签，并核对 <code>claims.client_nonce === 本地 nonce</code>、<code>claims.machine_hash</code> 与 <code>claims.lease_exp &gt; Date.now()/1000</code>。</li>
				<li>不要把高价值业务规则/核心 API 密钥硬编码在本地前端里——放进商品的「受保护云端规则」字段，仅当 <code>/payload</code> 返回 200 时加载到内存或由远程 MCP 网关执行。</li>
				<li>如买家退款，在 WP 后台把订单状态改为「已退款」，最迟 24 小时内（或下次调用 <code>/verify</code> 时）授权自动失效。</li>
			</ol>
		</div>
	</div>
	<?php
}
