<?php
/**
 * 虚拟商品订单、授权周期、设备绑定与自动邮件发货（基于 WordPress 原生 CPT `sc_order`）
 *
 * @package SkillCraft
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ==========================================================================
   1. 注册仅后台可见的订单类型 sc_order
   ========================================================================== */

function sc_register_order_post_type() {
	register_post_type( 'sc_order', array(
		'labels' => array(
			'name'          => __( '商品订单', 'skillcraft' ),
			'singular_name' => __( '商品订单', 'skillcraft' ),
			'add_new_item'  => __( '手动补录订单', 'skillcraft' ),
			'edit_item'     => __( '管理订单与授权', 'skillcraft' ),
		),
		'public'       => false,
		'show_ui'      => true,
		'show_in_menu' => true,
		'menu_icon'    => 'dashicons-cart',
		'menu_position'=> 30,
		'supports'     => array( 'title' ),
	) );
}
add_action( 'init', 'sc_register_order_post_type' );

/* ==========================================================================
   2. 订单列表自定义列（含到期时间与绑定设备数）
   ========================================================================== */

function sc_order_admin_columns( $cols ) {
	return array(
		'cb'          => $cols['cb'],
		'title'       => __( '订单号', 'skillcraft' ),
		'sc_o_skill'  => __( '购买商品', 'skillcraft' ),
		'sc_o_buyer'  => __( '买家邮箱', 'skillcraft' ),
		'sc_o_price'  => __( '金额', 'skillcraft' ),
		'sc_o_key'    => __( '授权码', 'skillcraft' ),
		'sc_o_exp'    => __( '有效期', 'skillcraft' ),
		'sc_o_dev'    => __( '设备绑定', 'skillcraft' ),
		'sc_o_stat'   => __( '状态', 'skillcraft' ),
		'date'        => __( '下单时间', 'skillcraft' ),
	);
}
add_filter( 'manage_sc_order_posts_columns', 'sc_order_admin_columns' );

function sc_order_get_devices( $order_id ) {
	$raw = get_post_meta( $order_id, '_sc_devices', true );
	if ( is_string( $raw ) && '' !== $raw ) {
		$dec = json_decode( $raw, true );
		return is_array( $dec ) ? $dec : array();
	}
	return is_array( $raw ) ? $raw : array();
}

function sc_order_admin_column_values( $col, $post_id ) {
	$status_map = array(
		'paid'     => '<span style="color:#15803d;font-weight:600">● 已支付</span>',
		'pending'  => '<span style="color:#d97706;font-weight:600">● 待确认</span>',
		'refunded' => '<span style="color:#b91c1c;font-weight:600">● 已退款</span>',
	);
	if ( 'sc_o_skill' === $col ) {
		echo esc_html( get_post_meta( $post_id, '_sc_skill_title', true ) ?: '—' );
	} elseif ( 'sc_o_buyer' === $col ) {
		echo esc_html( get_post_meta( $post_id, '_sc_buyer_email', true ) ?: '—' );
	} elseif ( 'sc_o_price' === $col ) {
		echo '<strong>' . esc_html( get_post_meta( $post_id, '_sc_price', true ) ?: '—' ) . '</strong>';
	} elseif ( 'sc_o_key' === $col ) {
		echo '<code>' . esc_html( get_post_meta( $post_id, '_sc_license_key', true ) ?: '—' ) . '</code>';
	} elseif ( 'sc_o_exp' === $col ) {
		$exp = (int) get_post_meta( $post_id, '_sc_expires_at', true );
		if ( 0 === $exp ) {
			echo '<span style="color:#15803d">永久有效</span>';
		} elseif ( $exp < time() ) {
			echo '<span style="color:#b91c1c">已过期 (' . esc_html( gmdate( 'Y-m-d', $exp ) ) . ')</span>';
		} else {
			echo esc_html( gmdate( 'Y-m-d', $exp ) );
		}
	} elseif ( 'sc_o_dev' === $col ) {
		$devs = sc_order_get_devices( $post_id );
		$max  = (int) get_post_meta( $post_id, '_sc_max_devices', true ) ?: 2;
		echo esc_html( count( $devs ) . ' / ' . $max . ' 台' );
	} elseif ( 'sc_o_stat' === $col ) {
		$st = get_post_meta( $post_id, '_sc_status', true ) ?: 'paid';
		echo $status_map[ $st ] ?? esc_html( $st ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
add_action( 'manage_sc_order_posts_custom_column', 'sc_order_admin_column_values', 10, 2 );

/* ==========================================================================
   3. 订单详情 Meta Box（支持人工改状态、到期时间、设备解绑与重发邮件）
   ========================================================================== */

function sc_order_add_meta_boxes() {
	add_meta_box(
		'sc_order_details',
		__( '订单、授权周期与设备绑定管理', 'skillcraft' ),
		'sc_order_render_meta_box',
		'sc_order',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'sc_order_add_meta_boxes' );

function sc_order_render_meta_box( $post ) {
	wp_nonce_field( 'sc_order_save_meta', 'sc_order_meta_nonce' );
	$skill_title = get_post_meta( $post->ID, '_sc_skill_title', true );
	$pack_slug   = get_post_meta( $post->ID, '_sc_pack_slug', true );
	$price       = get_post_meta( $post->ID, '_sc_price', true );
	$buyer_email = get_post_meta( $post->ID, '_sc_buyer_email', true );
	$user_id     = (int) get_post_meta( $post->ID, '_sc_user_id', true );
	$license_key = get_post_meta( $post->ID, '_sc_license_key', true ) ?: sc_generate_license_key();
	$install_cmd = get_post_meta( $post->ID, '_sc_install_cmd', true );
	$status      = get_post_meta( $post->ID, '_sc_status', true ) ?: 'paid';
	$expires_at  = (int) get_post_meta( $post->ID, '_sc_expires_at', true );
	$expires_str = $expires_at > 0 ? gmdate( 'Y-m-d', $expires_at ) : '';
	$max_devices = (int) get_post_meta( $post->ID, '_sc_max_devices', true ) ?: 2;
	$devices     = sc_order_get_devices( $post->ID );
	?>
	<style>
		.sc-ob-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
		.sc-ob-field{display:flex;flex-direction:column;gap:5px;margin-bottom:12px}
		.sc-ob-field label{font-weight:600;font-size:12.5px}
		.sc-ob-field input,.sc-ob-field select{width:100%}
		.sc-ob-devs{background:#f6f7f7;border:1px solid #dcdcde;border-radius:4px;padding:10px 12px;margin-bottom:12px;font-size:12.5px}
	</style>
	<div class="sc-ob-grid">
		<div class="sc-ob-field">
			<label>购买商品名称</label>
			<input type="text" name="sc_o_skill_title" value="<?php echo esc_attr( $skill_title ); ?>">
		</div>
		<div class="sc-ob-field">
			<label>插件包标识（pack_slug）</label>
			<input type="text" name="sc_o_pack_slug" value="<?php echo esc_attr( $pack_slug ); ?>" placeholder="ops-server-workbench">
		</div>
		<div class="sc-ob-field">
			<label>成交金额</label>
			<input type="text" name="sc_o_price" value="<?php echo esc_attr( $price ); ?>" placeholder="¥299">
		</div>
		<div class="sc-ob-field">
			<label>买家邮箱</label>
			<input type="email" name="sc_o_buyer_email" value="<?php echo esc_attr( $buyer_email ); ?>">
		</div>
		<div class="sc-ob-field">
			<label>绑定用户 ID（可选）</label>
			<input type="number" name="sc_o_user_id" value="<?php echo esc_attr( $user_id ); ?>">
		</div>
		<div class="sc-ob-field">
			<label>专属授权码</label>
			<input type="text" name="sc_o_license_key" value="<?php echo esc_attr( $license_key ); ?>">
		</div>
		<div class="sc-ob-field">
			<label>授权到期日期（留空表示买断永久有效）</label>
			<input type="date" name="sc_o_expires_date" value="<?php echo esc_attr( $expires_str ); ?>">
		</div>
		<div class="sc-ob-field">
			<label>最大绑定设备数</label>
			<input type="number" name="sc_o_max_devices" min="1" max="100" value="<?php echo esc_attr( $max_devices ); ?>">
		</div>
		<div class="sc-ob-field">
			<label>订单状态（改为「已退款」后客户端校验将立即失效）</label>
			<select name="sc_o_status">
				<option value="paid" <?php selected( $status, 'paid' ); ?>>已支付（授权生效中）</option>
				<option value="pending" <?php selected( $status, 'pending' ); ?>>待确认（未激活）</option>
				<option value="refunded" <?php selected( $status, 'refunded' ); ?>>已退款（收回授权）</option>
			</select>
		</div>
	</div>
	<div class="sc-ob-field">
		<label>自动发货安装 / 启动指令</label>
		<input type="text" name="sc_o_install_cmd" value="<?php echo esc_attr( $install_cmd ); ?>">
	</div>

	<div class="sc-ob-devs">
		<strong>已绑定设备（<?php echo esc_html( count( $devices ) . ' / ' . $max_devices ); ?>）：</strong>
		<?php if ( empty( $devices ) ) : ?>
			<span>尚未激活任何设备</span>
		<?php else : ?>
			<ul style="margin:6px 0 0 18px">
				<?php foreach ( $devices as $d ) : ?>
					<li>
						<code><?php echo esc_html( $d['id'] ?? '' ); ?></code>
						<?php if ( ! empty( $d['label'] ) ) : ?> · <?php echo esc_html( $d['label'] ); ?><?php endif; ?>
						<?php if ( ! empty( $d['last_seen'] ) ) : ?> · 最近活跃 <?php echo esc_html( gmdate( 'Y-m-d H:i', (int) $d['last_seen'] ) ); ?> UTC<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<label style="display:flex;align-items:center;gap:6px;margin-top:8px">
			<input type="checkbox" name="sc_o_clear_devices" value="1">
			<span>保存时清空已绑定设备（允许买家更换电脑重新激活）</span>
		</label>
		<label style="display:flex;align-items:center;gap:6px;margin-top:6px">
			<input type="checkbox" name="sc_o_resend_email" value="1">
			<span>保存时重新发送自动发货授权邮件给买家</span>
		</label>
	</div>
	<?php
}

function sc_order_save_meta( $post_id ) {
	if ( ! isset( $_POST['sc_order_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sc_order_meta_nonce'] ) ), 'sc_order_save_meta' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$map = array(
		'sc_o_skill_title' => '_sc_skill_title',
		'sc_o_pack_slug'   => '_sc_pack_slug',
		'sc_o_price'       => '_sc_price',
		'sc_o_buyer_email' => '_sc_buyer_email',
		'sc_o_license_key' => '_sc_license_key',
		'sc_o_status'      => '_sc_status',
		'sc_o_install_cmd' => '_sc_install_cmd',
	);
	foreach ( $map as $pk => $mk ) {
		if ( isset( $_POST[ $pk ] ) ) {
			update_post_meta( $post_id, $mk, sanitize_text_field( wp_unslash( $_POST[ $pk ] ) ) );
		}
	}
	if ( isset( $_POST['sc_o_user_id'] ) ) {
		update_post_meta( $post_id, '_sc_user_id', absint( $_POST['sc_o_user_id'] ) );
	}
	if ( isset( $_POST['sc_o_max_devices'] ) ) {
		update_post_meta( $post_id, '_sc_max_devices', max( 1, absint( $_POST['sc_o_max_devices'] ) ) );
	}
	if ( isset( $_POST['sc_o_expires_date'] ) ) {
		$d = sanitize_text_field( wp_unslash( $_POST['sc_o_expires_date'] ) );
		update_post_meta( $post_id, '_sc_expires_at', $d ? strtotime( $d . ' 23:59:59 UTC' ) : 0 );
	}
	if ( ! empty( $_POST['sc_o_clear_devices'] ) ) {
		update_post_meta( $post_id, '_sc_devices', wp_json_encode( array() ) );
	}
	if ( ! empty( $_POST['sc_o_resend_email'] ) ) {
		sc_send_order_delivery_email( $post_id );
	}
}
add_action( 'save_post_sc_order', 'sc_order_save_meta' );

/* ==========================================================================
   4. 自动发邮件（wp_mail）、授权码生成、查询已购 & 前台 AJAX 下单 / 解绑
   ========================================================================== */

function sc_generate_license_key() {
	$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
	$seg   = function () use ( $chars ) {
		$s = '';
		for ( $i = 0; $i < 4; $i++ ) {
			$s .= $chars[ wp_rand( 0, strlen( $chars ) - 1 ) ];
		}
		return $s;
	};
	return 'SC-' . $seg() . '-' . $seg() . '-' . $seg();
}

/** 自动发送发货与授权邮件 */
function sc_send_order_delivery_email( $order_id ) {
	if ( ! function_exists( 'wp_mail' ) ) {
		return false;
	}
	$email = get_post_meta( $order_id, '_sc_buyer_email', true );
	if ( ! $email || ! is_email( $email ) ) {
		return false;
	}
	$site        = function_exists( 'sc_site_name' ) ? sc_site_name() : get_bloginfo( 'name' );
	$order_no    = get_the_title( $order_id );
	$skill_title = get_post_meta( $order_id, '_sc_skill_title', true );
	$license_key = get_post_meta( $order_id, '_sc_license_key', true );
	$install_cmd = get_post_meta( $order_id, '_sc_install_cmd', true );
	$expires_at  = (int) get_post_meta( $order_id, '_sc_expires_at', true );
	$max_dev     = (int) get_post_meta( $order_id, '_sc_max_devices', true ) ?: 2;
	$exp_txt     = $expires_at > 0 ? gmdate( 'Y-m-d', $expires_at ) : sc_t( '永久有效（买断版）', 'Lifetime' );

	$subject = sprintf( '[%s] %s — %s', $site, sc_t( '你的专属授权码已自动发货', 'Your License Key & Order Delivery' ), $skill_title );
	$body    = implode( "\n", array(
		sprintf( sc_t( '感谢你在 %s 下单！你的订单已自动发货，以下为专属授权信息：', 'Thank you for your order at %s! Here are your license details:' ), $site ),
		'',
		sc_t( '订单编号：', 'Order No: ' ) . $order_no,
		sc_t( '购买商品：', 'Product: ' ) . $skill_title,
		sc_t( '专属授权码：', 'License Key: ' ) . $license_key,
		sc_t( '有效期限：', 'Valid Until: ' ) . $exp_txt,
		sc_t( '设备上限：', 'Max Devices: ' ) . $max_dev,
		'',
		sc_t( '一行启动 / 安装指令：', 'Launch / Install Command:' ),
		$install_cmd . ' --key ' . $license_key,
		'',
		sc_t( '你随时可以登录会员中心查看订单、管理设备绑定或获取最新版本：', 'Manage your licenses and bound devices anytime in your Member Console:' ),
		function_exists( 'sc_account_url' ) ? sc_account_url( 'orders' ) : home_url( '/account/' ),
	) );

	return wp_mail( $email, $subject, $body );
}

/** 获取当前用户已支付的订单列表（含有效期与设备数） */
function sc_user_orders( $user_id ) {
	if ( ! $user_id || ! function_exists( 'get_posts' ) ) {
		return array();
	}
	$posts = get_posts( array(
		'post_type'   => 'sc_order',
		'post_status' => 'publish',
		'numberposts' => 50,
		'meta_query'  => array(
			array( 'key' => '_sc_user_id', 'value' => (int) $user_id ),
			array( 'key' => '_sc_status',  'value' => 'paid' ),
		),
	) );
	$out = array();
	foreach ( $posts as $p ) {
		$exp   = (int) get_post_meta( $p->ID, '_sc_expires_at', true );
		$devs  = sc_order_get_devices( $p->ID );
		$max   = (int) get_post_meta( $p->ID, '_sc_max_devices', true ) ?: 2;
		$is_ex = ( $exp > 0 && $exp < time() );
		$out[] = array(
			'order_id'      => $p->ID,
			'order_no'      => $p->post_title,
			'skill_title'   => get_post_meta( $p->ID, '_sc_skill_title', true ),
			'pack_slug'     => get_post_meta( $p->ID, '_sc_pack_slug', true ),
			'price'         => get_post_meta( $p->ID, '_sc_price', true ),
			'license_key'   => get_post_meta( $p->ID, '_sc_license_key', true ),
			'install_cmd'   => get_post_meta( $p->ID, '_sc_install_cmd', true ),
			'expires_at'    => $exp,
			'expires_label' => 0 === $exp ? sc_t( '永久有效', 'Lifetime' ) : ( $is_ex ? sc_t( '已过期', 'Expired' ) : gmdate( 'Y-m-d', $exp ) ),
			'is_expired'    => $is_ex,
			'devices_count' => count( $devs ),
			'max_devices'   => $max,
			'date'          => get_the_date( 'Y/m/d', $p->ID ),
		);
	}
	return $out;
}

/** 订单支付完成统一发货入口（可供支付宝/微信/易支付/Stripe 回调直接调用） */
function sc_fulfill_order( $order_id ) {
	$order_id = absint( $order_id );
	if ( ! $order_id ) {
		return false;
	}
	update_post_meta( $order_id, '_sc_status', 'paid' );
	$skill_id   = (int) get_post_meta( $order_id, '_sc_skill_id', true );
	$cycle_days = $skill_id ? (int) get_post_meta( $skill_id, '_sc_cycle_days', true ) : 0;
	if ( $cycle_days > 0 ) {
		$cur_exp = (int) get_post_meta( $order_id, '_sc_expires_at', true );
		$base    = max( time(), $cur_exp );
		update_post_meta( $order_id, '_sc_expires_at', $base + ( $cycle_days * DAY_IN_SECONDS ) );
	}
	sc_send_order_delivery_email( $order_id );
	do_action( 'sc_order_fulfilled', $order_id );
	return true;
}

/** 前台 AJAX 创建订单并自动发货 */
function sc_ajax_create_order() {
	check_ajax_referer( 'sc_order_nonce', 'nonce' );

	$skill_id    = absint( $_POST['skill_id'] ?? 0 );
	$skill_title = sanitize_text_field( wp_unslash( $_POST['skill_title'] ?? '' ) );
	$price       = sanitize_text_field( wp_unslash( $_POST['price'] ?? '' ) );
	$install_cmd = sanitize_text_field( wp_unslash( $_POST['install_cmd'] ?? '' ) );
	$email       = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$user_id     = is_user_logged_in() ? get_current_user_id() : 0;

	if ( $user_id && '' === $email ) {
		$u     = wp_get_current_user();
		$email = $u->user_email;
	}
	if ( '' === $skill_title ) {
		wp_send_json_error( array( 'message' => sc_t( '商品信息无效。', 'Invalid product.' ) ) );
	}

	// 从真实商品读取周期、设备数与包标识（防前端篡改）
	$pack_slug   = $skill_id ? ( get_post_meta( $skill_id, '_sc_pack_slug', true ) ?: get_post_field( 'post_name', $skill_id ) ) : 'default-pack';
	$cycle_days  = $skill_id ? (int) get_post_meta( $skill_id, '_sc_cycle_days', true ) : 0;
	$max_devices = $skill_id ? ( (int) get_post_meta( $skill_id, '_sc_max_devices', true ) ?: 2 ) : 2;
	$expires_at  = $cycle_days > 0 ? ( time() + $cycle_days * DAY_IN_SECONDS ) : 0;

	$order_no    = 'ORD-' . gmdate( 'Ymd' ) . '-' . wp_rand( 1000, 9999 );
	$license_key = sc_generate_license_key();

	$oid = wp_insert_post( array(
		'post_type'   => 'sc_order',
		'post_status' => 'publish',
		'post_title'  => $order_no,
		'meta_input'  => array(
			'_sc_skill_id'    => $skill_id,
			'_sc_skill_title' => $skill_title,
			'_sc_pack_slug'   => $pack_slug,
			'_sc_price'       => $price,
			'_sc_buyer_email' => $email,
			'_sc_user_id'     => $user_id,
			'_sc_license_key' => $license_key,
			'_sc_install_cmd' => $install_cmd,
			'_sc_expires_at'  => $expires_at,
			'_sc_max_devices' => $max_devices,
			'_sc_devices'     => wp_json_encode( array() ),
			'_sc_status'      => 'paid',
		),
	) );

	if ( is_wp_error( $oid ) || ! $oid ) {
		wp_send_json_error( array( 'message' => sc_t( '创建订单失败，请重试。', 'Could not create order.' ) ) );
	}

	sc_send_order_delivery_email( $oid );

	wp_send_json_success( array(
		'order_no'    => $order_no,
		'license_key' => $license_key,
		'expires_at'  => $expires_at,
		'install_cmd' => $install_cmd . ' --key ' . $license_key,
	) );
}
add_action( 'wp_ajax_sc_create_order', 'sc_ajax_create_order' );
add_action( 'wp_ajax_nopriv_sc_create_order', 'sc_ajax_create_order' );

/** 会员中心 AJAX：用户自助解绑自己订单的全部设备 */
function sc_ajax_unbind_devices() {
	check_ajax_referer( 'sc_order_nonce', 'nonce' );
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => sc_t( '请先登录。', 'Please sign in first.' ) ) );
	}
	$order_id = absint( $_POST['order_id'] ?? 0 );
	$owner_id = (int) get_post_meta( $order_id, '_sc_user_id', true );
	if ( ! $order_id || ( $owner_id !== get_current_user_id() && ! current_user_can( 'manage_options' ) ) ) {
		wp_send_json_error( array( 'message' => sc_t( '无权操作此订单。', 'Unauthorized order.' ) ) );
	}
	update_post_meta( $order_id, '_sc_devices', wp_json_encode( array() ) );
	wp_send_json_success( array( 'message' => sc_t( '已解绑全部设备，现在可在新电脑重新激活。', 'All devices unbound. You can reactivate on a new machine.' ) ) );
}
add_action( 'wp_ajax_sc_unbind_devices', 'sc_ajax_unbind_devices' );
