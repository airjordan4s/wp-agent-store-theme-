<?php
/**
 * SkillCraft 后台可视化内容管理面板（零插件依赖）
 *
 * 支持在 WordPress 后台直接编辑：
 * 1. 首页 9 大模块显示开关 + 全站中英双语文案（Hero / 演示窗 / 各区块标题 / 档案袋 / 页脚 / 二维码）
 * 2. 常见问题 FAQ 与排查指南（可视化增删改）
 * 3. 更新日志 Changelog（可视化增删改）
 *
 * @package SkillCraft
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** 首页与全局默认文案与模块开关 */
function sc_default_content() {
	return array(
		// 首页 9 大模块开关
		'show_hero'           => 1,
		'show_demo'           => 1,
		'show_creators'       => 1,
		'show_products'       => 1,
		'show_start'          => 1,
		'show_agents'         => 1,
		'show_security'       => 1,
		'show_faq'            => 1,
		'show_envelope'       => 1,
		'show_blog_comments'  => 1,
		'blog_default_layout' => 'grid',
		'usd_rate'            => '7.2',

		// Hero 区
		'hero_title'          => "为每种业务，打造\n开箱即用的 AI 工作台",
		'hero_title_en'       => "Ready-to-run AI Workbenches\nfor every business",
		'hero_cta_top'        => '选一套业务工作台，把繁琐交给 Agent',
		'hero_cta_top_en'     => 'Pick a Workbench, hand ops to your Agent',
		'hero_cta_under'      => '进入作品集市浏览全部',
		'hero_cta_under_en'   => 'Explore All Workbenches',

		// macOS 演示窗
		'demo_heading'        => '切换上方业务场景，预览实机工作台与 Agent 接管流程。',
		'demo_heading_en'     => 'Switch workspaces above to preview live AI Workbenches.',
		'demo_prompt'         => '巡检全部在线节点负载，定位内存占用最高的实例并自动生成清理建议。',
		'demo_prompt_en'      => 'Inspect all online nodes, find the highest memory instance, and draft a cleanup plan.',
		'demo_copy_cmd'       => 'npx -y @agentdesk/cli@latest install --pack ops-server-workbench',

		// 各区块主标题
		'creators_title'      => "来自一线业务团队的\n真实使用反馈",
		'creators_title_en'   => "Real feedback from\nproduction teams",
		'products_title'      => "工作台 · MCP · Skill，\n按业务场景即选即用",
		'products_title_en'   => "Workbenches, MCP & Skills,\nready to deploy instantly",
		'start_title'         => '选型到部署，一切简简单单',
		'start_title_en'      => 'From selection to live desk, effortlessly',
		'agents_title'        => "独立运行或接入主流 Agents\n全栈生态无缝协作",
		'agents_title_en'     => "Run standalone or mount into\nyour favorite Agents",
		'agents_desc'         => '业务工作台支持桌面端与 Web 独立运行，MCP 与 Skill 一行命令无缝挂进主流客户端。',
		'agents_desc_en'      => 'Workbenches run standalone on Desktop & Web; MCP & Skills mount in one line.',
		'security_title'      => "全栈自研，本地优先，\n核心业务数据牢牢掌握在手",
		'security_title_en'   => "Built in-house, local-first,\nyour data stays yours",
		'faq_title'           => '常见问题',
		'faq_title_en'        => 'FAQ',

		// 档案袋信笺
		'envelope_zh'         => '让业务井然有序，把繁琐交给 Agent',
		'envelope_en'         => 'Keep operations orderly, hand the busywork to Agents.',

		// 页脚 & 悬浮支持
		'footer_slogan'       => '官方自研 AI 业务工作台、MCP 插件与 Skill 商店，下单即发，持续迭代。',
		'footer_slogan_en'    => 'First-party AI Workbenches, MCP plugins & Skills — instant delivery, ongoing updates.',
		'footer_contact'      => '售后与定制咨询：support@agentdesk.dev',
		'footer_contact_en'   => 'Support & custom inquiries: support@agentdesk.dev',
		'support_title'       => '官方售后与交流群',
		'support_title_en'    => 'Official Support',
		'support_desc'        => '扫码添加客服或加入用户群，获取部署协助与新品通知',
		'support_desc_en'     => 'Scan for setup help, updates, and user community access',
		'support_qr_url'      => '',
	);
}

/** 读取合并后的内容配置 */
function sc_content() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	$saved = get_option( 'skillcraft_content', array() );
	if ( is_string( $saved ) ) {
		$saved = json_decode( $saved, true );
	}
	$cache = array_merge( sc_default_content(), is_array( $saved ) ? $saved : array() );
	return $cache;
}

/** 按当前语言获取配置字段（英文留空回退中文） */
function sc_txt( $key ) {
	$c = sc_content();
	if ( 'en' === sc_current_lang() && ! empty( $c[ $key . '_en' ] ) ) {
		return (string) $c[ $key . '_en' ];
	}
	return isset( $c[ $key ] ) ? (string) $c[ $key ] : '';
}

/** 输出支持换行的标题 */
function sc_nl_title( $key ) {
	return nl2br( esc_html( sc_txt( $key ) ) );
}

/** 首页模块开关 */
function sc_section_enabled( $key ) {
	$c = sc_content();
	return ! empty( $c[ 'show_' . $key ] );
}

/* ==========================================================================
   WordPress 后台菜单与表单保存
   ========================================================================== */

function sc_register_admin_menu() {
	add_menu_page(
		__( 'SkillCraft 内容管理', 'skillcraft' ),
		__( 'SkillCraft 内容', 'skillcraft' ),
		'manage_options',
		'skillcraft-content',
		'sc_render_admin_page',
		'dashicons-layout',
		29
	);
}
add_action( 'admin_menu', 'sc_register_admin_menu' );

function sc_admin_enqueue_media( $hook ) {
	if ( 'toplevel_page_skillcraft-content' === $hook && function_exists( 'wp_enqueue_media' ) ) {
		wp_enqueue_media();
	}
}
add_action( 'admin_enqueue_scripts', 'sc_admin_enqueue_media' );

function sc_handle_admin_save() {
	if ( ! is_admin() || 'POST' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) {
		return;
	}
	if ( empty( $_POST['sc_admin_action'] ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$action = sanitize_key( wp_unslash( $_POST['sc_admin_action'] ) );
	$nonce  = isset( $_POST['sc_admin_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['sc_admin_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'sc_admin_' . $action ) ) {
		return;
	}

	if ( 'reset_all' === $action ) {
		delete_option( 'skillcraft_content' );
		delete_option( 'skillcraft_faqs' );
		delete_option( 'skillcraft_faq_guides' );
		delete_option( 'skillcraft_changelog' );
		wp_safe_redirect( admin_url( 'admin.php?page=skillcraft-content&reset=1' ) );
		exit;
	}

	if ( 'save_content' === $action ) {
		$def = sc_default_content();
		$out = array();
		foreach ( $def as $k => $v ) {
			if ( 0 === strpos( $k, 'show_' ) ) {
				$out[ $k ] = ! empty( $_POST['sc'][ $k ] ) ? 1 : 0;
			} elseif ( 'support_qr_url' === $k ) {
				$out[ $k ] = esc_url_raw( wp_unslash( $_POST['sc'][ $k ] ?? '' ) );
			} else {
				$out[ $k ] = sanitize_textarea_field( wp_unslash( $_POST['sc'][ $k ] ?? '' ) );
			}
		}
		update_option( 'skillcraft_content', $out );
		wp_safe_redirect( admin_url( 'admin.php?page=skillcraft-content&tab=general&saved=1' ) );
		exit;
	}

	if ( 'save_faqs' === $action ) {
		$faqs   = array();
		$raw_q  = $_POST['faq_q'] ?? array();
		$raw_qe = $_POST['faq_q_en'] ?? array();
		$raw_a  = $_POST['faq_a'] ?? array();
		$raw_ae = $_POST['faq_a_en'] ?? array();
		foreach ( (array) $raw_q as $i => $q ) {
			$q = trim( sanitize_text_field( wp_unslash( $q ) ) );
			if ( '' === $q ) {
				continue;
			}
			$faqs[] = array(
				'q'    => $q,
				'q_en' => trim( sanitize_text_field( wp_unslash( $raw_qe[ $i ] ?? '' ) ) ),
				'a'    => trim( sanitize_textarea_field( wp_unslash( $raw_a[ $i ] ?? '' ) ) ),
				'a_en' => trim( sanitize_textarea_field( wp_unslash( $raw_ae[ $i ] ?? '' ) ) ),
			);
		}
		update_option( 'skillcraft_faqs', $faqs );

		$guides = array();
		$g_cat  = $_POST['guide_cat'] ?? array();
		$g_cate = $_POST['guide_cat_en'] ?? array();
		$g_t    = $_POST['guide_title'] ?? array();
		$g_te   = $_POST['guide_title_en'] ?? array();
		$g_s    = $_POST['guide_summary'] ?? array();
		$g_se   = $_POST['guide_summary_en'] ?? array();
		$g_u    = $_POST['guide_url'] ?? array();
		foreach ( (array) $g_t as $i => $t ) {
			$t = trim( sanitize_text_field( wp_unslash( $t ) ) );
			if ( '' === $t ) {
				continue;
			}
			$guides[] = array(
				'cat'        => trim( sanitize_text_field( wp_unslash( $g_cat[ $i ] ?? '' ) ) ),
				'cat_en'     => trim( sanitize_text_field( wp_unslash( $g_cate[ $i ] ?? '' ) ) ),
				'title'      => $t,
				'title_en'   => trim( sanitize_text_field( wp_unslash( $g_te[ $i ] ?? '' ) ) ),
				'summary'    => trim( sanitize_textarea_field( wp_unslash( $g_s[ $i ] ?? '' ) ) ),
				'summary_en' => trim( sanitize_textarea_field( wp_unslash( $g_se[ $i ] ?? '' ) ) ),
				'url'        => esc_url_raw( wp_unslash( $g_u[ $i ] ?? home_url( '/blog/' ) ) ),
			);
		}
		update_option( 'skillcraft_faq_guides', $guides );
		wp_safe_redirect( admin_url( 'admin.php?page=skillcraft-content&tab=faq&saved=1' ) );
		exit;
	}

	if ( 'save_changelog' === $action ) {
		$list   = array();
		$vers   = $_POST['cl_ver'] ?? array();
		$dates  = $_POST['cl_date'] ?? array();
		$types  = $_POST['cl_type'] ?? array();
		$titles = $_POST['cl_title'] ?? array();
		$titlee = $_POST['cl_title_en'] ?? array();
		$sums   = $_POST['cl_summary'] ?? array();
		$sume   = $_POST['cl_summary_en'] ?? array();
		$lines  = $_POST['cl_items'] ?? array();
		$linese = $_POST['cl_items_en'] ?? array();

		foreach ( (array) $vers as $i => $ver ) {
			$ver = trim( sanitize_text_field( wp_unslash( $ver ) ) );
			if ( '' === $ver ) {
				continue;
			}
			$type = ( 'improve' === ( $types[ $i ] ?? '' ) ) ? 'improve' : 'feature';
			$zh_l = array_values( array_filter( array_map( 'trim', explode( "\n", sanitize_textarea_field( wp_unslash( $lines[ $i ] ?? '' ) ) ) ) ) );
			$en_l = array_values( array_filter( array_map( 'trim', explode( "\n", sanitize_textarea_field( wp_unslash( $linese[ $i ] ?? '' ) ) ) ) ) );
			$items = array();
			foreach ( $zh_l as $j => $text ) {
				$items[] = array( 'zh' => $text, 'en' => $en_l[ $j ] ?? $text );
			}
			$list[] = array(
				'version'    => $ver,
				'date'       => trim( sanitize_text_field( wp_unslash( $dates[ $i ] ?? '' ) ) ),
				'type'       => $type,
				'badge'      => 'improve' === $type ? '体验优化' : '新功能',
				'badge_en'   => 'improve' === $type ? 'Improvement' : 'New Feature',
				'title'      => trim( sanitize_text_field( wp_unslash( $titles[ $i ] ?? '' ) ) ),
				'title_en'   => trim( sanitize_text_field( wp_unslash( $titlee[ $i ] ?? '' ) ) ),
				'summary'    => trim( sanitize_textarea_field( wp_unslash( $sums[ $i ] ?? '' ) ) ),
				'summary_en' => trim( sanitize_textarea_field( wp_unslash( $sume[ $i ] ?? '' ) ) ),
				'items'      => $items,
			);
		}
		update_option( 'skillcraft_changelog', $list );
		wp_safe_redirect( admin_url( 'admin.php?page=skillcraft-content&tab=changelog&saved=1' ) );
		exit;
	}
}
add_action( 'admin_init', 'sc_handle_admin_save' );

/** 渲染后台编辑界面 */
function sc_render_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$tab   = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'general';
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$saved = ! empty( $_GET['saved'] );
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$reset = ! empty( $_GET['reset'] );
	$c     = sc_content();
	?>
	<div class="wrap sc-admin-wrap">
		<style>
			.sc-admin-wrap{max-width:1040px}
			.sc-adm-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px}
			.sc-adm-card{background:#fff;border:1px solid #dcdcde;border-radius:6px;padding:18px 20px;margin:16px 0}
			.sc-adm-card h2{margin:0 0 14px;padding-bottom:10px;border-bottom:1px solid #f0f0f1;font-size:15px}
			.sc-adm-field{display:flex;flex-direction:column;gap:5px}
			.sc-adm-field label{font-weight:600;font-size:12px;color:#1d2327}
			.sc-adm-field input,.sc-adm-field textarea,.sc-adm-field select{width:100%}
			.sc-adm-toggles{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
			.sc-adm-row{border:1px solid #e5e7eb;background:#fafafa;padding:14px;border-radius:6px;margin-bottom:12px}
			.sc-adm-row-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px}
		</style>

		<h1>SkillCraft 内容管理</h1>
		<p class="description">在这里直接编辑首页各模块文案、显示开关、常见问题与更新日志（所有字段支持中英双语，英文留空自动回退中文）。</p>

		<?php if ( $saved ) : ?>
			<div class="notice notice-success is-dismissible"><p>更改已保存并立即生效。</p></div>
		<?php endif; ?>
		<?php if ( $reset ) : ?>
			<div class="notice notice-info is-dismissible"><p>已恢复为默认示例内容。</p></div>
		<?php endif; ?>

		<h2 class="nav-tab-wrapper">
			<a class="nav-tab <?php echo 'general' === $tab ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=skillcraft-content&tab=general' ) ); ?>">首页与全局文案</a>
			<a class="nav-tab <?php echo 'faq' === $tab ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=skillcraft-content&tab=faq' ) ); ?>">常见问题 & 指南</a>
			<a class="nav-tab <?php echo 'changelog' === $tab ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=skillcraft-content&tab=changelog' ) ); ?>">更新日志</a>
		</h2>

		<?php if ( 'general' === $tab ) : ?>
			<form method="post">
				<?php wp_nonce_field( 'sc_admin_save_content', 'sc_admin_nonce' ); ?>
				<input type="hidden" name="sc_admin_action" value="save_content">

				<div class="sc-adm-card">
					<h2>首页模块与博客功能开关</h2>
					<div class="sc-adm-toggles">
						<?php
						$mods = array(
							'show_hero'          => '1. Hero 主视觉 + 滚动流',
							'show_demo'          => '2. macOS 交互演示窗',
							'show_creators'      => '3. 开发者案例长廊',
							'show_products'      => '4. 4 列作品集市网格',
							'show_start'         => '5. 纯黑 3 步上手区',
							'show_agents'        => '6. Agents 生态墙',
							'show_security'      => '7. 安全三大支柱',
							'show_faq'           => '8. 首页 FAQ 区块',
							'show_envelope'      => '9. 底部宣纸档案袋',
							'show_blog_comments' => '10. 博客文章开启评论区',
						);
						foreach ( $mods as $k => $label ) :
							?>
							<label><input type="checkbox" name="sc[<?php echo esc_attr( $k ); ?>]" value="1" <?php checked( ! empty( $c[ $k ] ) ); ?>> <?php echo esc_html( $label ); ?></label>
						<?php endforeach; ?>
					</div>
					<div class="sc-adm-grid" style="margin-top:14px">
						<div class="sc-adm-field">
							<label>博客列表默认展示模式（前台访客仍可自由切换）</label>
							<select name="sc[blog_default_layout]">
								<option value="grid" <?php selected( $c['blog_default_layout'] ?? 'grid', 'grid' ); ?>>卡片画廊模式（3 列封面卡）</option>
								<option value="list" <?php selected( $c['blog_default_layout'] ?? 'grid', 'list' ); ?>>极简列表模式（单列架构行）</option>
							</select>
						</div>
						<div class="sc-adm-field">
							<label>美元汇率（1 USD = ? CNY，英文默认展示 USD）</label>
							<input type="text" name="sc[usd_rate]" value="<?php echo esc_attr( $c['usd_rate'] ?? '7.2' ); ?>" placeholder="7.2">
						</div>
					</div>
				</div>

				<div class="sc-adm-card">
					<h2>Hero 主视觉与撕贴 CTA</h2>
					<div class="sc-adm-grid">
						<div class="sc-adm-field"><label>主标题（中文，换行即折行）</label><textarea rows="2" name="sc[hero_title]"><?php echo esc_textarea( $c['hero_title'] ); ?></textarea></div>
						<div class="sc-adm-field"><label>Hero Title (EN)</label><textarea rows="2" name="sc[hero_title_en]"><?php echo esc_textarea( $c['hero_title_en'] ); ?></textarea></div>
						<div class="sc-adm-field"><label>黑条贴纸文案（中）</label><input type="text" name="sc[hero_cta_top]" value="<?php echo esc_attr( $c['hero_cta_top'] ); ?>"></div>
						<div class="sc-adm-field"><label>Sticker CTA (EN)</label><input type="text" name="sc[hero_cta_top_en]" value="<?php echo esc_attr( $c['hero_cta_top_en'] ); ?>"></div>
						<div class="sc-adm-field"><label>揭开底层文案（中）</label><input type="text" name="sc[hero_cta_under]" value="<?php echo esc_attr( $c['hero_cta_under'] ); ?>"></div>
						<div class="sc-adm-field"><label>Underlay CTA (EN)</label><input type="text" name="sc[hero_cta_under_en]" value="<?php echo esc_attr( $c['hero_cta_under_en'] ); ?>"></div>
					</div>
				</div>

				<div class="sc-adm-card">
					<h2>macOS 演示窗口与指令</h2>
					<div class="sc-adm-grid">
						<div class="sc-adm-field"><label>窗口居中提示（中）</label><input type="text" name="sc[demo_heading]" value="<?php echo esc_attr( $c['demo_heading'] ); ?>"></div>
						<div class="sc-adm-field"><label>Window Heading (EN)</label><input type="text" name="sc[demo_heading_en]" value="<?php echo esc_attr( $c['demo_heading_en'] ); ?>"></div>
						<div class="sc-adm-field"><label>默认预填演示指令（中）</label><textarea rows="3" name="sc[demo_prompt]"><?php echo esc_textarea( $c['demo_prompt'] ); ?></textarea></div>
						<div class="sc-adm-field"><label>Demo Prompt (EN)</label><textarea rows="3" name="sc[demo_prompt_en]"><?php echo esc_textarea( $c['demo_prompt_en'] ); ?></textarea></div>
					</div>
					<div class="sc-adm-field"><label>底部「点击复制指令」实际复制的命令</label><input type="text" name="sc[demo_copy_cmd]" value="<?php echo esc_attr( $c['demo_copy_cmd'] ); ?>"></div>
				</div>

				<div class="sc-adm-card">
					<h2>首页各区块标题 & 档案袋宣言</h2>
					<div class="sc-adm-grid">
						<div class="sc-adm-field"><label>开发者案例标题（中）</label><textarea rows="2" name="sc[creators_title]"><?php echo esc_textarea( $c['creators_title'] ); ?></textarea></div>
						<div class="sc-adm-field"><label>Creators Title (EN)</label><textarea rows="2" name="sc[creators_title_en]"><?php echo esc_textarea( $c['creators_title_en'] ); ?></textarea></div>
						<div class="sc-adm-field"><label>作品集市标题（中）</label><textarea rows="2" name="sc[products_title]"><?php echo esc_textarea( $c['products_title'] ); ?></textarea></div>
						<div class="sc-adm-field"><label>Marketplace Title (EN)</label><textarea rows="2" name="sc[products_title_en]"><?php echo esc_textarea( $c['products_title_en'] ); ?></textarea></div>
						<div class="sc-adm-field"><label>纯黑步骤区标题（中）</label><input type="text" name="sc[start_title]" value="<?php echo esc_attr( $c['start_title'] ); ?>"></div>
						<div class="sc-adm-field"><label>Dark Steps Title (EN)</label><input type="text" name="sc[start_title_en]" value="<?php echo esc_attr( $c['start_title_en'] ); ?>"></div>
						<div class="sc-adm-field"><label>Agents 生态标题（中）</label><textarea rows="2" name="sc[agents_title]"><?php echo esc_textarea( $c['agents_title'] ); ?></textarea></div>
						<div class="sc-adm-field"><label>Agents Title (EN)</label><textarea rows="2" name="sc[agents_title_en]"><?php echo esc_textarea( $c['agents_title_en'] ); ?></textarea></div>
						<div class="sc-adm-field"><label>安全保障标题（中）</label><textarea rows="2" name="sc[security_title]"><?php echo esc_textarea( $c['security_title'] ); ?></textarea></div>
						<div class="sc-adm-field"><label>Security Title (EN)</label><textarea rows="2" name="sc[security_title_en]"><?php echo esc_textarea( $c['security_title_en'] ); ?></textarea></div>
						<div class="sc-adm-field"><label>档案袋信笺中文宣言</label><input type="text" name="sc[envelope_zh]" value="<?php echo esc_attr( $c['envelope_zh'] ); ?>"></div>
						<div class="sc-adm-field"><label>档案袋信笺英文宣言</label><input type="text" name="sc[envelope_en]" value="<?php echo esc_attr( $c['envelope_en'] ); ?>"></div>
					</div>
				</div>

				<div class="sc-adm-card">
					<h2>页脚与右下角技术支持卡片</h2>
					<div class="sc-adm-grid">
						<div class="sc-adm-field"><label>页脚标语（中）</label><input type="text" name="sc[footer_slogan]" value="<?php echo esc_attr( $c['footer_slogan'] ); ?>"></div>
						<div class="sc-adm-field"><label>Footer Slogan (EN)</label><input type="text" name="sc[footer_slogan_en]" value="<?php echo esc_attr( $c['footer_slogan_en'] ); ?>"></div>
						<div class="sc-adm-field"><label>页脚联系/侵权说明（中）</label><input type="text" name="sc[footer_contact]" value="<?php echo esc_attr( $c['footer_contact'] ); ?>"></div>
						<div class="sc-adm-field"><label>Footer Contact (EN)</label><input type="text" name="sc[footer_contact_en]" value="<?php echo esc_attr( $c['footer_contact_en'] ); ?>"></div>
						<div class="sc-adm-field"><label>支持弹窗标题（中）</label><input type="text" name="sc[support_title]" value="<?php echo esc_attr( $c['support_title'] ); ?>"></div>
						<div class="sc-adm-field"><label>Support Title (EN)</label><input type="text" name="sc[support_title_en]" value="<?php echo esc_attr( $c['support_title_en'] ); ?>"></div>
						<div class="sc-adm-field"><label>支持弹窗说明（中）</label><input type="text" name="sc[support_desc]" value="<?php echo esc_attr( $c['support_desc'] ); ?>"></div>
						<div class="sc-adm-field"><label>Support Desc (EN)</label><input type="text" name="sc[support_desc_en]" value="<?php echo esc_attr( $c['support_desc_en'] ); ?>"></div>
					</div>
					<div class="sc-adm-field">
						<label>自定义微信群二维码图片（支持媒体库上传，留空显示默认矢量图）</label>
						<div style="display:flex;gap:8px">
							<input id="sc-qr-input" type="url" name="sc[support_qr_url]" value="<?php echo esc_attr( $c['support_qr_url'] ); ?>" placeholder="https://...">
							<button type="button" class="button" onclick="scPickQr()">选择图片</button>
						</div>
					</div>
					<script>
					function scPickQr(){
						if(!window.wp||!wp.media)return;
						var f=wp.media({title:'选择客服二维码图片',button:{text:'使用此图片'},multiple:false});
						f.on('select',function(){var a=f.state().get('selection').first().toJSON();document.getElementById('sc-qr-input').value=a.url;});
						f.open();
					}
					</script>
				</div>

				<p class="submit">
					<button type="submit" class="button button-primary button-large">保存全部文案</button>
				</p>
			</form>

		<?php elseif ( 'faq' === $tab ) :
			$faqs   = sc_faqs();
			$guides = sc_faq_guides();
			?>
			<form method="post">
				<?php wp_nonce_field( 'sc_admin_save_faqs', 'sc_admin_nonce' ); ?>
				<input type="hidden" name="sc_admin_action" value="save_faqs">

				<div class="sc-adm-card">
					<h2>快速问答列表（同步显示在首页与 /faq/）</h2>
					<div id="sc-faq-rows">
						<?php foreach ( $faqs as $i => $f ) : ?>
							<div class="sc-adm-row">
								<div class="sc-adm-row-head"><strong>问答 #<?php echo esc_html( $i + 1 ); ?></strong><button type="button" class="button-link-delete" onclick="this.closest('.sc-adm-row').remove()">删除</button></div>
								<div class="sc-adm-grid">
									<div class="sc-adm-field"><label>问题（中）</label><input type="text" name="faq_q[]" value="<?php echo esc_attr( $f['q'] ); ?>"></div>
									<div class="sc-adm-field"><label>Question (EN)</label><input type="text" name="faq_q_en[]" value="<?php echo esc_attr( $f['q_en'] ); ?>"></div>
									<div class="sc-adm-field"><label>回答（中）</label><textarea rows="2" name="faq_a[]"><?php echo esc_textarea( $f['a'] ); ?></textarea></div>
									<div class="sc-adm-field"><label>Answer (EN)</label><textarea rows="2" name="faq_a_en[]"><?php echo esc_textarea( $f['a_en'] ); ?></textarea></div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
					<button type="button" class="button" onclick="scAddFaqRow()">+ 新增一条问答</button>
				</div>

				<div class="sc-adm-card">
					<h2>排查与接入指南（/faq/ 第 1 个 Tab）</h2>
					<div id="sc-guide-rows">
						<?php foreach ( $guides as $i => $g ) : ?>
							<div class="sc-adm-row">
								<div class="sc-adm-row-head"><strong>指南 #<?php echo esc_html( $i + 1 ); ?></strong><button type="button" class="button-link-delete" onclick="this.closest('.sc-adm-row').remove()">删除</button></div>
								<div class="sc-adm-grid">
									<div class="sc-adm-field"><label>分类标签（中）</label><input type="text" name="guide_cat[]" value="<?php echo esc_attr( $g['cat'] ); ?>"></div>
									<div class="sc-adm-field"><label>Category (EN)</label><input type="text" name="guide_cat_en[]" value="<?php echo esc_attr( $g['cat_en'] ); ?>"></div>
									<div class="sc-adm-field"><label>标题（中）</label><input type="text" name="guide_title[]" value="<?php echo esc_attr( $g['title'] ); ?>"></div>
									<div class="sc-adm-field"><label>Title (EN)</label><input type="text" name="guide_title_en[]" value="<?php echo esc_attr( $g['title_en'] ); ?>"></div>
									<div class="sc-adm-field"><label>摘要（中）</label><textarea rows="2" name="guide_summary[]"><?php echo esc_textarea( $g['summary'] ); ?></textarea></div>
									<div class="sc-adm-field"><label>Summary (EN)</label><textarea rows="2" name="guide_summary_en[]"><?php echo esc_textarea( $g['summary_en'] ); ?></textarea></div>
								</div>
								<div class="sc-adm-field"><label>详情跳转链接</label><input type="text" name="guide_url[]" value="<?php echo esc_attr( $g['url'] ); ?>"></div>
							</div>
						<?php endforeach; ?>
					</div>
					<button type="button" class="button" onclick="scAddGuideRow()">+ 新增一条指南</button>
				</div>

				<p class="submit"><button type="submit" class="button button-primary button-large">保存 FAQ 与指南</button></p>
			</form>
			<script>
			function scAddFaqRow(){
				var d=document.createElement('div');d.className='sc-adm-row';
				d.innerHTML='<div class="sc-adm-row-head"><strong>新问答</strong><button type="button" class="button-link-delete" onclick="this.closest(\'.sc-adm-row\').remove()">删除</button></div><div class="sc-adm-grid"><div class="sc-adm-field"><label>问题（中）</label><input type="text" name="faq_q[]"></div><div class="sc-adm-field"><label>Question (EN)</label><input type="text" name="faq_q_en[]"></div><div class="sc-adm-field"><label>回答（中）</label><textarea rows="2" name="faq_a[]"></textarea></div><div class="sc-adm-field"><label>Answer (EN)</label><textarea rows="2" name="faq_a_en[]"></textarea></div></div>';
				document.getElementById('sc-faq-rows').appendChild(d);
			}
			function scAddGuideRow(){
				var d=document.createElement('div');d.className='sc-adm-row';
				d.innerHTML='<div class="sc-adm-row-head"><strong>新指南</strong><button type="button" class="button-link-delete" onclick="this.closest(\'.sc-adm-row\').remove()">删除</button></div><div class="sc-adm-grid"><div class="sc-adm-field"><label>分类标签（中）</label><input type="text" name="guide_cat[]"></div><div class="sc-adm-field"><label>Category (EN)</label><input type="text" name="guide_cat_en[]"></div><div class="sc-adm-field"><label>标题（中）</label><input type="text" name="guide_title[]"></div><div class="sc-adm-field"><label>Title (EN)</label><input type="text" name="guide_title_en[]"></div><div class="sc-adm-field"><label>摘要（中）</label><textarea rows="2" name="guide_summary[]"></textarea></div><div class="sc-adm-field"><label>Summary (EN)</label><textarea rows="2" name="guide_summary_en[]"></textarea></div></div><div class="sc-adm-field"><label>详情跳转链接</label><input type="text" name="guide_url[]" value="/blog/"></div>';
				document.getElementById('sc-guide-rows').appendChild(d);
			}
			</script>

		<?php else :
			$entries = sc_changelog_entries();
			?>
			<form method="post">
				<?php wp_nonce_field( 'sc_admin_save_changelog', 'sc_admin_nonce' ); ?>
				<input type="hidden" name="sc_admin_action" value="save_changelog">

				<div class="sc-adm-card">
					<h2>更新日志条目（/changelog/）</h2>
					<div id="sc-cl-rows">
						<?php foreach ( $entries as $i => $e ) :
							$zh_lines = implode( "\n", array_column( $e['items'], 'zh' ) );
							$en_lines = implode( "\n", array_column( $e['items'], 'en' ) );
							?>
							<div class="sc-adm-row">
								<div class="sc-adm-row-head"><strong>版本 <?php echo esc_html( $e['version'] ); ?></strong><button type="button" class="button-link-delete" onclick="this.closest('.sc-adm-row').remove()">删除</button></div>
								<div class="sc-adm-grid">
									<div class="sc-adm-field"><label>版本号</label><input type="text" name="cl_ver[]" value="<?php echo esc_attr( $e['version'] ); ?>"></div>
									<div class="sc-adm-field"><label>发布日期（YYYY/MM/DD）</label><input type="text" name="cl_date[]" value="<?php echo esc_attr( $e['date'] ); ?>"></div>
									<div class="sc-adm-field"><label>类型胶囊</label><select name="cl_type[]"><option value="feature" <?php selected( $e['type'], 'feature' ); ?>>新功能（蓝）</option><option value="improve" <?php selected( $e['type'], 'improve' ); ?>>体验优化（灰）</option></select></div>
									<div class="sc-adm-field"><label>标题（中）</label><input type="text" name="cl_title[]" value="<?php echo esc_attr( $e['title'] ); ?>"></div>
									<div class="sc-adm-field"><label>Title (EN)</label><input type="text" name="cl_title_en[]" value="<?php echo esc_attr( $e['title_en'] ); ?>"></div>
									<div class="sc-adm-field"><label>摘要（中）</label><input type="text" name="cl_summary[]" value="<?php echo esc_attr( $e['summary'] ); ?>"></div>
									<div class="sc-adm-field"><label>Summary (EN)</label><input type="text" name="cl_summary_en[]" value="<?php echo esc_attr( $e['summary_en'] ); ?>"></div>
									<div class="sc-adm-field"><label>更新明细（每行一条，中文）</label><textarea rows="3" name="cl_items[]"><?php echo esc_textarea( $zh_lines ); ?></textarea></div>
									<div class="sc-adm-field"><label>Details (1 per line, EN)</label><textarea rows="3" name="cl_items_en[]"><?php echo esc_textarea( $en_lines ); ?></textarea></div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
					<button type="button" class="button" onclick="scAddClRow()">+ 新增一个版本</button>
				</div>

				<p class="submit"><button type="submit" class="button button-primary button-large">保存更新日志</button></p>
			</form>
			<script>
			function scAddClRow(){
				var d=document.createElement('div');d.className='sc-adm-row';
				d.innerHTML='<div class="sc-adm-row-head"><strong>新版本</strong><button type="button" class="button-link-delete" onclick="this.closest(\'.sc-adm-row\').remove()">删除</button></div><div class="sc-adm-grid"><div class="sc-adm-field"><label>版本号</label><input type="text" name="cl_ver[]" placeholder="v1.6.0"></div><div class="sc-adm-field"><label>发布日期</label><input type="text" name="cl_date[]" placeholder="2026/12/01"></div><div class="sc-adm-field"><label>类型胶囊</label><select name="cl_type[]"><option value="feature">新功能（蓝）</option><option value="improve">体验优化（灰）</option></select></div><div class="sc-adm-field"><label>标题（中）</label><input type="text" name="cl_title[]"></div><div class="sc-adm-field"><label>Title (EN)</label><input type="text" name="cl_title_en[]"></div><div class="sc-adm-field"><label>摘要（中）</label><input type="text" name="cl_summary[]"></div><div class="sc-adm-field"><label>Summary (EN)</label><input type="text" name="cl_summary_en[]"></div><div class="sc-adm-field"><label>更新明细（每行一条，中文）</label><textarea rows="3" name="cl_items[]"></textarea></div><div class="sc-adm-field"><label>Details (1 per line, EN)</label><textarea rows="3" name="cl_items_en[]"></textarea></div></div>';
				document.getElementById('sc-cl-rows').prepend(d);
			}
			</script>
		<?php endif; ?>

		<form method="post" style="margin-top:24px" onsubmit="return confirm('确定要恢复所有文案、FAQ 与更新日志为默认示例内容吗？');">
			<?php wp_nonce_field( 'sc_admin_reset_all', 'sc_admin_nonce' ); ?>
			<input type="hidden" name="sc_admin_action" value="reset_all">
			<button type="submit" class="button button-link-delete">恢复全部默认示例内容</button>
		</form>
	</div>
	<?php
}
