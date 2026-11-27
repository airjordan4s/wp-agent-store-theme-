<?php
/**
 * WordPress 原生「更新日志」发布模块（CPT: sc_changelog）
 *
 * 在 WP 后台点击「更新日志 → 发布新更新」，填写版本号、类型、摘要与更新明细并点「发布」，
 * 即刻自动按发布时间倒序展示在 /changelog/ 页面顶部。
 *
 * @package SkillCraft
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sc_register_changelog_cpt() {
	register_post_type( 'sc_changelog', array(
		'labels' => array(
			'name'          => __( '更新日志', 'skillcraft' ),
			'singular_name' => __( '更新日志', 'skillcraft' ),
			'add_new_item'  => __( '发布新更新', 'skillcraft' ),
			'edit_item'     => __( '编辑更新日志', 'skillcraft' ),
		),
		'public'        => false,
		'show_ui'       => true,
		'show_in_menu'  => true,
		'menu_icon'     => 'dashicons-megaphone',
		'menu_position' => 29,
		'supports'      => array( 'title' ),
	) );
}
add_action( 'init', 'sc_register_changelog_cpt' );

function sc_changelog_add_meta_box() {
	add_meta_box(
		'sc_changelog_props',
		__( '版本信息与更新明细（发布即自动上墙 /changelog/）', 'skillcraft' ),
		'sc_changelog_render_meta_box',
		'sc_changelog',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'sc_changelog_add_meta_box' );

function sc_changelog_render_meta_box( $post ) {
	wp_nonce_field( 'sc_changelog_save', 'sc_changelog_nonce' );
	$version    = get_post_meta( $post->ID, '_sc_cl_version', true ) ?: 'v2.5.0';
	$type       = get_post_meta( $post->ID, '_sc_cl_type', true ) ?: 'feature';
	$title_en   = get_post_meta( $post->ID, '_sc_cl_title_en', true );
	$summary    = get_post_meta( $post->ID, '_sc_cl_summary', true );
	$summary_en = get_post_meta( $post->ID, '_sc_cl_summary_en', true );
	$items_zh   = get_post_meta( $post->ID, '_sc_cl_items_zh', true );
	$items_en   = get_post_meta( $post->ID, '_sc_cl_items_en', true );
	?>
	<style>
		.sc-cl-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:12px}
		.sc-cl-field{display:flex;flex-direction:column;gap:5px;margin-bottom:12px}
		.sc-cl-field label{font-weight:600;font-size:12.5px}
		.sc-cl-field input,.sc-cl-field select,.sc-cl-field textarea{width:100%}
	</style>
	<div class="sc-cl-grid">
		<div class="sc-cl-field">
			<label for="sc_cl_version">版本号（例如 v2.5.0）</label>
			<input id="sc_cl_version" name="sc_cl_version" type="text" value="<?php echo esc_attr( $version ); ?>" required>
		</div>
		<div class="sc-cl-field">
			<label for="sc_cl_type">更新类型</label>
			<select id="sc_cl_type" name="sc_cl_type">
				<option value="feature" <?php selected( $type, 'feature' ); ?>>新特性（New Feature）</option>
				<option value="improve" <?php selected( $type, 'improve' ); ?>>体验优化（Improvement）</option>
				<option value="fix" <?php selected( $type, 'fix' ); ?>>问题修复（Bug Fix）</option>
			</select>
		</div>
	</div>
	<div class="sc-cl-field">
		<label for="sc_cl_title_en">英文标题（选填，留空自动沿用中文标题）</label>
		<input id="sc_cl_title_en" name="sc_cl_title_en" type="text" value="<?php echo esc_attr( $title_en ); ?>">
	</div>
	<div class="sc-cl-grid">
		<div class="sc-cl-field">
			<label for="sc_cl_summary">中文摘要</label>
			<textarea id="sc_cl_summary" name="sc_cl_summary" rows="2"><?php echo esc_textarea( $summary ); ?></textarea>
		</div>
		<div class="sc-cl-field">
			<label for="sc_cl_summary_en">英文摘要（选填）</label>
			<textarea id="sc_cl_summary_en" name="sc_cl_summary_en" rows="2"><?php echo esc_textarea( $summary_en ); ?></textarea>
		</div>
	</div>
	<div class="sc-cl-grid">
		<div class="sc-cl-field">
			<label for="sc_cl_items_zh">更新明细 - 中文（每行一条）</label>
			<textarea id="sc_cl_items_zh" name="sc_cl_items_zh" rows="5" placeholder="新增跨境电商工作台自动同步模块&#10;优化 Ed25519 授权验签响应速度"><?php echo esc_textarea( $items_zh ); ?></textarea>
		</div>
		<div class="sc-cl-field">
			<label for="sc_cl_items_en">更新明细 - 英文（每行一条，选填）</label>
			<textarea id="sc_cl_items_en" name="sc_cl_items_en" rows="5"><?php echo esc_textarea( $items_en ); ?></textarea>
		</div>
	</div>
	<?php
}

function sc_changelog_save_meta( $post_id ) {
	if ( ! isset( $_POST['sc_changelog_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sc_changelog_nonce'] ) ), 'sc_changelog_save' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$text = array(
		'sc_cl_version'  => '_sc_cl_version',
		'sc_cl_type'     => '_sc_cl_type',
		'sc_cl_title_en' => '_sc_cl_title_en',
	);
	foreach ( $text as $pk => $mk ) {
		if ( isset( $_POST[ $pk ] ) ) {
			update_post_meta( $post_id, $mk, sanitize_text_field( wp_unslash( $_POST[ $pk ] ) ) );
		}
	}
	$areas = array(
		'sc_cl_summary'    => '_sc_cl_summary',
		'sc_cl_summary_en' => '_sc_cl_summary_en',
		'sc_cl_items_zh'   => '_sc_cl_items_zh',
		'sc_cl_items_en'   => '_sc_cl_items_en',
	);
	foreach ( $areas as $pk => $mk ) {
		if ( isset( $_POST[ $pk ] ) ) {
			update_post_meta( $post_id, $mk, sanitize_textarea_field( wp_unslash( $_POST[ $pk ] ) ) );
		}
	}
}
add_action( 'save_post_sc_changelog', 'sc_changelog_save_meta' );

function sc_changelog_admin_columns( $cols ) {
	return array(
		'cb'            => $cols['cb'],
		'sc_cl_ver'     => __( '版本号', 'skillcraft' ),
		'title'         => __( '更新标题', 'skillcraft' ),
		'sc_cl_type'    => __( '类型', 'skillcraft' ),
		'date'          => __( '发布日期', 'skillcraft' ),
	);
}
add_filter( 'manage_sc_changelog_posts_columns', 'sc_changelog_admin_columns' );

function sc_changelog_admin_column_values( $col, $post_id ) {
	if ( 'sc_cl_ver' === $col ) {
		echo '<code>' . esc_html( get_post_meta( $post_id, '_sc_cl_version', true ) ?: 'v1.0.0' ) . '</code>';
	} elseif ( 'sc_cl_type' === $col ) {
		$t   = get_post_meta( $post_id, '_sc_cl_type', true ) ?: 'feature';
		$map = array( 'feature' => '新特性', 'improve' => '体验优化', 'fix' => '问题修复' );
		echo esc_html( $map[ $t ] ?? $t );
	}
}
add_action( 'manage_sc_changelog_posts_custom_column', 'sc_changelog_admin_column_values', 10, 2 );

/** 读取后台已发布的更新日志 CPT 条目 */
function sc_changelog_from_cpt() {
	if ( ! function_exists( 'get_posts' ) ) {
		return array();
	}
	$posts = get_posts( array(
		'post_type'   => 'sc_changelog',
		'post_status' => 'publish',
		'numberposts' => 30,
		'orderby'     => 'date',
		'order'       => 'DESC',
	) );
	if ( empty( $posts ) ) {
		return array();
	}
	$badge_map = array(
		'feature' => array( 'zh' => '新特性',   'en' => 'New Feature' ),
		'improve' => array( 'zh' => '体验优化', 'en' => 'Improvement' ),
		'fix'     => array( 'zh' => '问题修复', 'en' => 'Bug Fix' ),
	);
	$out = array();
	foreach ( $posts as $p ) {
		$type     = get_post_meta( $p->ID, '_sc_cl_type', true ) ?: 'feature';
		$b        = $badge_map[ $type ] ?? $badge_map['feature'];
		$zh_lines = array_values( array_filter( array_map( 'trim', explode( "\n", (string) get_post_meta( $p->ID, '_sc_cl_items_zh', true ) ) ) ) );
		$en_lines = array_values( array_filter( array_map( 'trim', explode( "\n", (string) get_post_meta( $p->ID, '_sc_cl_items_en', true ) ) ) ) );
		$items    = array();
		foreach ( $zh_lines as $i => $line ) {
			$items[] = array( 'zh' => $line, 'en' => $en_lines[ $i ] ?? $line );
		}
		$title_zh = get_the_title( $p->ID );
		$sum_zh   = (string) get_post_meta( $p->ID, '_sc_cl_summary', true );
		$out[]    = array(
			'version'    => get_post_meta( $p->ID, '_sc_cl_version', true ) ?: 'v1.0.0',
			'date'       => get_the_date( 'Y-m-d', $p->ID ),
			'type'       => $type,
			'badge'      => $b['zh'],
			'badge_en'   => $b['en'],
			'title'      => $title_zh,
			'title_en'   => get_post_meta( $p->ID, '_sc_cl_title_en', true ) ?: $title_zh,
			'summary'    => $sum_zh,
			'summary_en' => get_post_meta( $p->ID, '_sc_cl_summary_en', true ) ?: $sum_zh,
			'items'      => $items,
		);
	}
	return $out;
}
