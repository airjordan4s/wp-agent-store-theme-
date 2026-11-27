<?php
/**
 * 定制与团购咨询线索管理（CPT: sc_inquiry）
 *
 * - 所有表单咨询自动入库 WP 后台，即使未配置 SMTP 也不会漏单
 * - 隐形蜜罐字段 + IP 频率限制，无需验证码拦截灌水机器人
 * - 自动发送管理员通知邮件（带 Reply-To）+ 客户确认回执邮件
 *
 * @package SkillCraft
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sc_register_inquiry_cpt() {
	register_post_type( 'sc_inquiry', array(
		'labels' => array(
			'name'          => __( '咨询线索', 'skillcraft' ),
			'singular_name' => __( '咨询线索', 'skillcraft' ),
			'edit_item'     => __( '查看与跟进线索', 'skillcraft' ),
		),
		'public'        => false,
		'show_ui'       => true,
		'show_in_menu'  => true,
		'menu_icon'     => 'dashicons-email-alt',
		'menu_position' => 31,
		'supports'      => array( 'title', 'editor' ),
	) );
}
add_action( 'init', 'sc_register_inquiry_cpt' );

function sc_inquiry_admin_columns( $cols ) {
	return array(
		'cb'           => $cols['cb'],
		'title'        => __( '客户称呼', 'skillcraft' ),
		'sc_inq_type'  => __( '咨询类型', 'skillcraft' ),
		'sc_inq_email' => __( '联系邮箱', 'skillcraft' ),
		'sc_inq_seats' => __( '规模 / 预算', 'skillcraft' ),
		'sc_inq_stat'  => __( '跟进状态', 'skillcraft' ),
		'date'         => __( '提交时间', 'skillcraft' ),
	);
}
add_filter( 'manage_sc_inquiry_posts_columns', 'sc_inquiry_admin_columns' );

function sc_inquiry_admin_column_values( $col, $post_id ) {
	$type_map = array(
		'custom' => '1 对 1 定制开发',
		'team'   => '团队批量团购',
		'both'   => '定制 + 团队授权',
	);
	$stat_map = array(
		'new'       => '<span style="color:#d97706;font-weight:600">● 待跟进</span>',
		'contacted' => '<span style="color:#1677ff;font-weight:600">● 已联系</span>',
		'won'       => '<span style="color:#15803d;font-weight:600">● 已成交</span>',
		'closed'    => '<span style="color:#6b7280">● 已归档</span>',
	);
	if ( 'sc_inq_type' === $col ) {
		$t = get_post_meta( $post_id, '_sc_inq_type', true ) ?: 'custom';
		echo esc_html( $type_map[ $t ] ?? $t );
	} elseif ( 'sc_inq_email' === $col ) {
		$e = get_post_meta( $post_id, '_sc_inq_email', true );
		echo $e ? '<a href="mailto:' . esc_attr( $e ) . '">' . esc_html( $e ) . '</a>' : '—';
	} elseif ( 'sc_inq_seats' === $col ) {
		echo esc_html( get_post_meta( $post_id, '_sc_inq_seats', true ) ?: '—' );
	} elseif ( 'sc_inq_stat' === $col ) {
		$s = get_post_meta( $post_id, '_sc_inq_status', true ) ?: 'new';
		echo $stat_map[ $s ] ?? esc_html( $s ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
add_action( 'manage_sc_inquiry_posts_custom_column', 'sc_inquiry_admin_column_values', 10, 2 );

function sc_inquiry_add_meta_box() {
	add_meta_box(
		'sc_inquiry_meta',
		__( '线索详情与跟进状态', 'skillcraft' ),
		'sc_inquiry_render_meta_box',
		'sc_inquiry',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'sc_inquiry_add_meta_box' );

function sc_inquiry_render_meta_box( $post ) {
	wp_nonce_field( 'sc_inquiry_save', 'sc_inquiry_nonce' );
	$email  = get_post_meta( $post->ID, '_sc_inq_email', true );
	$seats  = get_post_meta( $post->ID, '_sc_inq_seats', true );
	$type   = get_post_meta( $post->ID, '_sc_inq_type', true ) ?: 'custom';
	$status = get_post_meta( $post->ID, '_sc_inq_status', true ) ?: 'new';
	$ip     = get_post_meta( $post->ID, '_sc_inq_ip', true );
	?>
	<p><strong>联系邮箱：</strong><br><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ?: '—' ); ?></a></p>
	<p><strong>咨询类型：</strong> <?php echo esc_html( $type ); ?></p>
	<p><strong>规模/预算：</strong> <?php echo esc_html( $seats ?: '—' ); ?></p>
	<p><strong>来源 IP：</strong> <code><?php echo esc_html( $ip ?: '—' ); ?></code></p>
	<p>
		<label for="sc_inq_status"><strong>跟进状态：</strong></label><br>
		<select id="sc_inq_status" name="sc_inq_status" style="width:100%;margin-top:4px">
			<option value="new" <?php selected( $status, 'new' ); ?>>待跟进</option>
			<option value="contacted" <?php selected( $status, 'contacted' ); ?>>已联系</option>
			<option value="won" <?php selected( $status, 'won' ); ?>>已成交</option>
			<option value="closed" <?php selected( $status, 'closed' ); ?>>已归档</option>
		</select>
	</p>
	<?php
}

function sc_inquiry_save_meta( $post_id ) {
	if ( ! isset( $_POST['sc_inquiry_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sc_inquiry_nonce'] ) ), 'sc_inquiry_save' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['sc_inq_status'] ) ) {
		update_post_meta( $post_id, '_sc_inq_status', sanitize_key( wp_unslash( $_POST['sc_inq_status'] ) ) );
	}
}
add_action( 'save_post_sc_inquiry', 'sc_inquiry_save_meta' );

/**
 * 处理定制与团购表单提交：蜜罐拦截 + IP 限流 + 入库 WP + 管理员通知 + 客户回执邮件
 *
 * @param array $raw 原始 POST 数据
 * @return array{ok:bool, error:string}
 */
function sc_submit_custom_inquiry( array $raw ) {
	// 1. 隐形蜜罐：机器人自动填了隐藏字段时静默返回成功，不入库也不发信
	if ( ! empty( $raw['c_website'] ) ) {
		return array( 'ok' => true, 'error' => '' );
	}

	// 2. IP 频率限制（10 分钟内最多提交 5 次）
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '0.0.0.0';
	if ( function_exists( 'get_transient' ) ) {
		$rl_key = 'sc_inq_rl_' . md5( $ip );
		$cnt    = (int) get_transient( $rl_key );
		if ( $cnt >= 5 ) {
			return array(
				'ok'    => false,
				'error' => sc_t( '提交过于频繁，请 10 分钟后再试或直接发送邮件联系我们。', 'Too many submissions. Please try again in 10 minutes.' ),
			);
		}
		set_transient( $rl_key, $cnt + 1, 10 * MINUTE_IN_SECONDS );
	}

	$name  = trim( sanitize_text_field( wp_unslash( $raw['c_name'] ?? '' ) ) );
	$email = trim( sanitize_email( wp_unslash( $raw['c_email'] ?? '' ) ) );
	$type  = sanitize_key( wp_unslash( $raw['c_type'] ?? 'custom' ) );
	$seats = trim( sanitize_text_field( wp_unslash( $raw['c_seats'] ?? '' ) ) );
	$brief = trim( sanitize_textarea_field( wp_unslash( $raw['c_brief'] ?? '' ) ) );

	if ( '' === $name || ! is_email( $email ) || '' === $brief ) {
		return array(
			'ok'    => false,
			'error' => sc_t( '请填写你的称呼、有效邮箱和需求说明。', 'Please provide your name, a valid email, and project details.' ),
		);
	}

	$type_labels = array(
		'custom' => sc_t( '1 对 1 私有工作台定制开发', '1-on-1 Custom Workbench' ),
		'team'   => sc_t( '团队批量授权团购', 'Team Volume License' ),
		'both'   => sc_t( '定制开发 + 团队授权', 'Custom + Team License' ),
	);
	$type_txt = $type_labels[ $type ] ?? $type;

	// 3. 同步入库 WP 后台（防 SMTP 未配置时丢单）
	if ( function_exists( 'wp_insert_post' ) ) {
		wp_insert_post( array(
			'post_type'    => 'sc_inquiry',
			'post_status'  => 'publish',
			'post_title'   => $name . ' — ' . $type_txt,
			'post_content' => $brief,
			'meta_input'   => array(
				'_sc_inq_name'   => $name,
				'_sc_inq_email'  => $email,
				'_sc_inq_type'   => $type,
				'_sc_inq_seats'  => $seats,
				'_sc_inq_status' => 'new',
				'_sc_inq_ip'     => $ip,
			),
		) );
	}

	$site = function_exists( 'sc_site_name' ) ? sc_site_name() : get_bloginfo( 'name' );

	// 4. 通知店主邮箱（带 Reply-To 方便直接回复）
	$admin_email = get_option( 'admin_email' );
	if ( $admin_email && function_exists( 'wp_mail' ) ) {
		wp_mail(
			$admin_email,
			'[' . $site . ' 新咨询线索] ' . $name . ' · ' . $type_txt,
			"咨询类型：{$type_txt}\n客户称呼：{$name}\n联系邮箱：{$email}\n团队规模/预算：{$seats}\n来源 IP：{$ip}\n\n需求说明：\n{$brief}\n\n后台查看全部线索：" . admin_url( 'edit.php?post_type=sc_inquiry' ),
			array( 'Reply-To: ' . $name . ' <' . $email . '>' )
		);
	}

	// 5. 自动给客户发送「已收到需求」确认回执邮件
	if ( function_exists( 'wp_mail' ) ) {
		$cust_subject = sprintf( '[%s] %s', $site, sc_t( '我们已收到你的定制/团购咨询', 'We received your custom inquiry' ) );
		$cust_body    = implode( "\n", array(
			sprintf( sc_t( '你好 %s，', 'Hi %s,' ), $name ),
			'',
			sprintf( sc_t( '感谢联系 %s！我们已收到你提交的「%s」需求，工程师团队会在 1 个工作日内通过邮件与你联系并提供方案与报价。', 'Thank you for reaching out to %s! We have received your "%s" inquiry and our team will reply within 1 business day.' ), $site, $type_txt ),
			'',
			sc_t( '你提交的需求摘要：', 'Your inquiry summary:' ),
			$brief,
			'',
			'— ' . $site,
			home_url( '/' ),
		) );
		wp_mail( $email, $cust_subject, $cust_body );
	}

	return array( 'ok' => true, 'error' => '' );
}
