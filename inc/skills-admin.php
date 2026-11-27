<?php
/**
 * WordPress 后台「商品（工作台 / MCP / Skill）」可视化管理
 *
 * 支持：产品形态（业务工作台 / MCP 插件 / Skill 能力包）、工作台演示截图上传（媒体库）、
 * 内置模块标签、分类、定价、浏览量/热度、镇店精选开关、以及 8 款官方示例商品初始化。
 *
 * @package SkillCraft
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** 产品形态字典（用于前台筛选、卡片徽章与后台下拉） */
function sc_product_types() {
	return array(
		'workbench' => array( 'zh' => '业务工作台', 'en' => 'Workbench',   'tint' => '#eef5ff', 'color' => '#1677ff', 'icon' => 'desktop' ),
		'mcp'       => array( 'zh' => 'MCP 插件',   'en' => 'MCP Plugin',  'tint' => '#f5f3ff', 'color' => '#7c3aed', 'icon' => 'plug' ),
		'skill'     => array( 'zh' => 'Skill 能力包', 'en' => 'Skill Pack', 'tint' => '#ecfdf5', 'color' => '#059669', 'icon' => 'spark' ),
	);
}

/** 兼容旧分类函数 */
function sc_skill_categories() {
	return sc_product_types();
}

/* ==========================================================================
   1. 商品编辑页 Meta Box（形态 / 演示截图 / 定价 / 模块 / 能力清单 / 安装指令）
   ========================================================================== */

function sc_skill_add_meta_boxes() {
	add_meta_box(
		'sc_skill_props',
		__( '商品属性、工作台演示截图与定价', 'skillcraft' ),
		'sc_skill_render_meta_box',
		'sc_skill',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'sc_skill_add_meta_boxes' );

function sc_skill_admin_enqueue_media( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( $screen && 'sc_skill' === $screen->post_type ) {
		wp_enqueue_media();
	}
}
add_action( 'admin_enqueue_scripts', 'sc_skill_admin_enqueue_media' );

function sc_skill_render_meta_box( $post ) {
	wp_nonce_field( 'sc_skill_save_meta', 'sc_skill_meta_nonce' );

	$cat         = get_post_meta( $post->ID, '_sc_cat', true ) ?: 'workbench';
	$billing     = get_post_meta( $post->ID, '_sc_billing', true ) ?: '买断制';
	$price       = get_post_meta( $post->ID, '_sc_price', true ) ?: '¥299';
	$featured    = (int) get_post_meta( $post->ID, '_sc_featured', true );
	$title_en    = get_post_meta( $post->ID, '_sc_title_en', true );
	$tags        = get_post_meta( $post->ID, '_sc_tags', true );
	$preview_img = get_post_meta( $post->ID, '_sc_preview_img', true );
	$gallery_raw = get_post_meta( $post->ID, '_sc_gallery_imgs', true );
	$subtitle    = get_post_meta( $post->ID, '_sc_subtitle', true );
	$highlights  = get_post_meta( $post->ID, '_sc_highlights', true );
	$install_cmd = get_post_meta( $post->ID, '_sc_install_cmd', true );
	$clients     = get_post_meta( $post->ID, '_sc_clients', true ) ?: '桌面端 / Web / Cursor / Claude';
	$views             = (int) get_post_meta( $post->ID, '_sc_views', true );
	$heat              = (int) get_post_meta( $post->ID, '_sc_heat', true ) ?: 94;
	$pack_slug         = get_post_meta( $post->ID, '_sc_pack_slug', true ) ?: $post->post_name;
	$cycle_days        = get_post_meta( $post->ID, '_sc_cycle_days', true );
	if ( '' === $cycle_days ) {
		$cycle_days = ( '订阅制' === $billing ) ? 30 : 0;
	}
	$max_devices       = (int) get_post_meta( $post->ID, '_sc_max_devices', true ) ?: 2;
	$download_url      = get_post_meta( $post->ID, '_sc_download_url', true );
	$protected_payload = get_post_meta( $post->ID, '_sc_protected_payload', true );
	?>
	<style>
		.sc-mb-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px}
		.sc-mb-field{display:flex;flex-direction:column;gap:5px;margin-bottom:12px}
		.sc-mb-field label{font-weight:600;font-size:12.5px;color:#1d2327}
		.sc-mb-field input,.sc-mb-field select,.sc-mb-field textarea{width:100%}
		.sc-mb-check{display:flex;align-items:center;gap:8px;padding:10px 12px;background:#f6f7f7;border:1px solid #dcdcde;border-radius:4px;margin-bottom:14px}
		.sc-mb-media{display:flex;gap:8px;align-items:center}
		.sc-mb-thumb{margin-top:8px;max-width:420px;border:1px solid #dcdcde;border-radius:6px;display:block}
	</style>

	<label class="sc-mb-check">
		<input type="checkbox" name="sc_featured" value="1" <?php checked( $featured, 1 ); ?>>
		<strong>设为「本月镇店精选」（显示在作品集市顶部暗黑 Spotlight 横幅）</strong>
	</label>

	<div class="sc-mb-grid">
		<div class="sc-mb-field">
			<label for="sc_cat">产品形态</label>
			<select id="sc_cat" name="sc_cat">
				<?php foreach ( sc_product_types() as $k => $info ) : ?>
					<option value="<?php echo esc_attr( $k ); ?>" <?php selected( $cat, $k ); ?>><?php echo esc_html( $info['zh'] . ' / ' . $info['en'] ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="sc-mb-field">
			<label for="sc_billing">计费模式</label>
			<select id="sc_billing" name="sc_billing">
				<option value="买断制" <?php selected( $billing, '买断制' ); ?>>买断制（One-time）</option>
				<option value="订阅制" <?php selected( $billing, '订阅制' ); ?>>订阅制（Monthly）</option>
				<option value="免费" <?php selected( $billing, '免费' ); ?>>免费（Free）</option>
			</select>
		</div>
		<div class="sc-mb-field">
			<label for="sc_price">展示价格（例如：¥299 / ¥199/月 / 免费）</label>
			<input id="sc_price" name="sc_price" type="text" value="<?php echo esc_attr( $price ); ?>" placeholder="¥299">
		</div>
		<div class="sc-mb-field">
			<label for="sc_tags">内置模块 / 标签（英文逗号分隔，如：服务器, 网站, 面板, 监控）</label>
			<input id="sc_tags" name="sc_tags" type="text" value="<?php echo esc_attr( $tags ); ?>" placeholder="服务器, 网站, 知识库, 智能体">
		</div>
		<div class="sc-mb-field">
			<label for="sc_title_en">英文标题（选填，切英文时显示）</label>
			<input id="sc_title_en" name="sc_title_en" type="text" value="<?php echo esc_attr( $title_en ); ?>" placeholder="Server & Infra Command Workbench">
		</div>
		<div class="sc-mb-field">
			<label for="sc_clients">运行环境 / 兼容客户端</label>
			<input id="sc_clients" name="sc_clients" type="text" value="<?php echo esc_attr( $clients ); ?>">
		</div>
		<div class="sc-mb-field">
			<label for="sc_views">浏览量（访问自动递增，可设初始值）</label>
			<input id="sc_views" name="sc_views" type="number" min="0" value="<?php echo esc_attr( $views ); ?>">
		</div>
		<div class="sc-mb-field">
			<label for="sc_heat">热度值（如 98）</label>
			<input id="sc_heat" name="sc_heat" type="number" min="0" value="<?php echo esc_attr( $heat ); ?>">
		</div>
	</div>

	<div class="sc-mb-field">
		<label for="sc_preview_img">工作台 / 产品演示截图（建议 16:10 高清图，前台详情页与集市卡片自动展示）</label>
		<div class="sc-mb-media">
			<input id="sc_preview_img" name="sc_preview_img" type="url" value="<?php echo esc_url( $preview_img ); ?>" placeholder="https://... 或点击右侧从媒体库上传">
			<button type="button" class="button" id="sc-pick-preview">选择/上传截图</button>
			<button type="button" class="button" id="sc-clear-preview">清空</button>
		</div>
		<?php if ( $preview_img ) : ?>
			<img id="sc-preview-thumb" class="sc-mb-thumb" src="<?php echo esc_url( $preview_img ); ?>" alt="">
		<?php else : ?>
			<img id="sc-preview-thumb" class="sc-mb-thumb" src="" alt="" style="display:none">
		<?php endif; ?>
	</div>

	<div class="sc-mb-field">
		<label for="sc_gallery_imgs">更多工作台模块截图（选填，每行一张图片 URL，前台详情页支持缩略图切换 + 点击全屏灯箱）</label>
		<textarea id="sc_gallery_imgs" name="sc_gallery_imgs" rows="3" placeholder="https://.../panel-sites.png&#10;https://.../panel-kb.png"><?php echo esc_textarea( $gallery_raw ); ?></textarea>
		<div><button type="button" class="button" id="sc-add-gallery">从媒体库追加截图</button></div>
	</div>

	<div class="sc-mb-field">
		<label for="sc_subtitle">一句话卖点简介（用于首页、集市卡片与详情页副标题）</label>
		<textarea id="sc_subtitle" name="sc_subtitle" rows="2" placeholder="集中掌管多台服务器、网站、面板与日志，让基础设施井然有序，把繁琐运维交给 Agent。"><?php echo esc_textarea( $subtitle ); ?></textarea>
	</div>

	<div class="sc-mb-field">
		<label for="sc_highlights">核心模块与能力清单（每行一条，前台自动渲染为 ✓ 列表）</label>
		<textarea id="sc_highlights" name="sc_highlights" rows="4" placeholder="多节点服务器 CPU / 内存 / 磁盘实时监控与 SSH 一键接管&#10;内置网站、面板、知识库与提示词资产管理模块"><?php echo esc_textarea( $highlights ); ?></textarea>
	</div>

	<div class="sc-mb-field">
		<label for="sc_install_cmd">下单自动发货的安装 / 启动指令</label>
		<input id="sc_install_cmd" name="sc_install_cmd" type="text" value="<?php echo esc_attr( $install_cmd ); ?>" placeholder="npx -y @workbench/cli@latest init --pack ops-servers">
	</div>

	<hr style="margin:18px 0;border:0;border-top:1px solid #dcdcde">
	<h4 style="margin:0 0 12px">授权周期、设备数与防绕过受保护载荷（dsh / 桌面端 / MCP 鉴权）</h4>
	<div class="sc-mb-grid">
		<div class="sc-mb-field">
			<label for="sc_pack_slug">插件包唯一标识（pack_slug，用于 API 校验）</label>
			<input id="sc_pack_slug" name="sc_pack_slug" type="text" value="<?php echo esc_attr( $pack_slug ); ?>" placeholder="ops-server-workbench">
		</div>
		<div class="sc-mb-field">
			<label for="sc_cycle_days">授权周期天数（0 = 买断永久，30 = 月付，365 = 年付）</label>
			<input id="sc_cycle_days" name="sc_cycle_days" type="number" min="0" value="<?php echo esc_attr( $cycle_days ); ?>">
		</div>
		<div class="sc-mb-field">
			<label for="sc_max_devices">单个授权码最大绑定设备数</label>
			<input id="sc_max_devices" name="sc_max_devices" type="number" min="1" max="100" value="<?php echo esc_attr( $max_devices ); ?>">
		</div>
		<div class="sc-mb-field">
			<label for="sc_download_url">受保护安装包下载地址（仅授权有效时由 API 下发）</label>
			<input id="sc_download_url" name="sc_download_url" type="url" value="<?php echo esc_url( $download_url ); ?>" placeholder="https://cdn.example.com/releases/ops-workbench.zip">
		</div>
	</div>
	<div class="sc-mb-field">
		<label for="sc_protected_payload">受保护云端规则 / 核心配置（JSON 或文本，未授权绝不下发，防止本地明文被 AI 直接读取绕过）</label>
		<textarea id="sc_protected_payload" name="sc_protected_payload" rows="4" placeholder='{"mcp_endpoint":"https://mcp.yourdomain.com/ops","rules_version":"2.4.0"}'><?php echo esc_textarea( $protected_payload ); ?></textarea>
	</div>

	<script>
	jQuery(function($){
		var frame;
		$('#sc-pick-preview').on('click', function(e){
			e.preventDefault();
			if (frame) { frame.open(); return; }
			frame = wp.media({ title: '选择工作台演示截图', button: { text: '设为演示截图' }, multiple: false });
			frame.on('select', function(){
				var att = frame.state().get('selection').first().toJSON();
				$('#sc_preview_img').val(att.url);
				$('#sc-preview-thumb').attr('src', att.url).show();
			});
			frame.open();
		});
		$('#sc-clear-preview').on('click', function(){
			$('#sc_preview_img').val('');
			$('#sc-preview-thumb').hide().attr('src', '');
		});
		var gFrame;
		$('#sc-add-gallery').on('click', function(e){
			e.preventDefault();
			if (gFrame) { gFrame.open(); return; }
			gFrame = wp.media({ title: '追加工作台截图', button: { text: '追加到画廊' }, multiple: true });
			gFrame.on('select', function(){
				var urls = [];
				gFrame.state().get('selection').each(function(m){ urls.push(m.toJSON().url); });
				var cur = $.trim($('#sc_gallery_imgs').val());
				$('#sc_gallery_imgs').val((cur ? cur + "\n" : '') + urls.join("\n"));
			});
			gFrame.open();
		});
	});
	</script>
	<?php
}

function sc_skill_save_meta( $post_id ) {
	if ( ! isset( $_POST['sc_skill_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sc_skill_meta_nonce'] ) ), 'sc_skill_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	update_post_meta( $post_id, '_sc_featured', ! empty( $_POST['sc_featured'] ) ? 1 : 0 );
	if ( isset( $_POST['sc_views'] ) ) {
		update_post_meta( $post_id, '_sc_views', absint( $_POST['sc_views'] ) );
	}
	if ( isset( $_POST['sc_heat'] ) ) {
		update_post_meta( $post_id, '_sc_heat', absint( $_POST['sc_heat'] ) );
	}
	if ( isset( $_POST['sc_preview_img'] ) ) {
		update_post_meta( $post_id, '_sc_preview_img', esc_url_raw( wp_unslash( $_POST['sc_preview_img'] ) ) );
	}
	if ( isset( $_POST['sc_download_url'] ) ) {
		update_post_meta( $post_id, '_sc_download_url', esc_url_raw( wp_unslash( $_POST['sc_download_url'] ) ) );
	}
	if ( isset( $_POST['sc_cycle_days'] ) ) {
		update_post_meta( $post_id, '_sc_cycle_days', absint( $_POST['sc_cycle_days'] ) );
	}
	if ( isset( $_POST['sc_max_devices'] ) ) {
		update_post_meta( $post_id, '_sc_max_devices', max( 1, absint( $_POST['sc_max_devices'] ) ) );
	}

	$text_fields = array(
		'sc_cat'         => '_sc_cat',
		'sc_billing'     => '_sc_billing',
		'sc_price'       => '_sc_price',
		'sc_tags'        => '_sc_tags',
		'sc_title_en'    => '_sc_title_en',
		'sc_clients'     => '_sc_clients',
		'sc_install_cmd' => '_sc_install_cmd',
		'sc_pack_slug'   => '_sc_pack_slug',
	);
	foreach ( $text_fields as $post_key => $meta_key ) {
		if ( isset( $_POST[ $post_key ] ) ) {
			update_post_meta( $post_id, $meta_key, sanitize_text_field( wp_unslash( $_POST[ $post_key ] ) ) );
		}
	}

	$area_fields = array(
		'sc_subtitle'          => '_sc_subtitle',
		'sc_highlights'        => '_sc_highlights',
		'sc_protected_payload' => '_sc_protected_payload',
		'sc_gallery_imgs'      => '_sc_gallery_imgs',
	);
	foreach ( $area_fields as $post_key => $meta_key ) {
		if ( isset( $_POST[ $post_key ] ) ) {
			update_post_meta( $post_id, $meta_key, sanitize_textarea_field( wp_unslash( $_POST[ $post_key ] ) ) );
		}
	}
}
add_action( 'save_post_sc_skill', 'sc_skill_save_meta' );

/* ==========================================================================
   2. 后台商品列表页显示：形态 / 计费模式 / 价格 / 浏览与热度 / 镇店精选
   ========================================================================== */

function sc_skill_admin_columns( $cols ) {
	$new = array();
	foreach ( $cols as $k => $v ) {
		$new[ $k ] = $v;
		if ( 'title' === $k ) {
			$new['sc_cat']      = __( '形态', 'skillcraft' );
			$new['sc_billing']  = __( '计费模式', 'skillcraft' );
			$new['sc_price']    = __( '价格', 'skillcraft' );
			$new['sc_views']    = __( '浏览 / 热度', 'skillcraft' );
			$new['sc_featured'] = __( '镇店精选', 'skillcraft' );
		}
	}
	return $new;
}
add_filter( 'manage_sc_skill_posts_columns', 'sc_skill_admin_columns' );

function sc_skill_admin_column_values( $col, $post_id ) {
	$types = sc_product_types();
	if ( 'sc_cat' === $col ) {
		$k = get_post_meta( $post_id, '_sc_cat', true ) ?: 'workbench';
		echo esc_html( $types[ $k ]['zh'] ?? '业务工作台' );
	} elseif ( 'sc_billing' === $col ) {
		echo esc_html( get_post_meta( $post_id, '_sc_billing', true ) ?: '买断制' );
	} elseif ( 'sc_price' === $col ) {
		echo '<strong>' . esc_html( get_post_meta( $post_id, '_sc_price', true ) ?: '¥299' ) . '</strong>';
	} elseif ( 'sc_views' === $col ) {
		$v = (int) get_post_meta( $post_id, '_sc_views', true );
		$h = (int) get_post_meta( $post_id, '_sc_heat', true ) ?: 90;
		echo esc_html( number_format( $v ) . ' · 🔥' . $h );
	} elseif ( 'sc_featured' === $col ) {
		echo get_post_meta( $post_id, '_sc_featured', true ) ? '<span style="color:#1677ff;font-weight:600">★ 精选</span>' : '—';
	}
}
add_action( 'manage_sc_skill_posts_custom_column', 'sc_skill_admin_column_values', 10, 2 );

/** 访问商品详情页时自动累加浏览量 */
function sc_track_skill_views() {
	if ( is_admin() || ! function_exists( 'is_singular' ) || ! is_singular( 'sc_skill' ) ) {
		return;
	}
	$id = get_queried_object_id();
	if ( $id ) {
		$views = (int) get_post_meta( $id, '_sc_views', true );
		update_post_meta( $id, '_sc_views', $views + 1 );
	}
}
add_action( 'template_redirect', 'sc_track_skill_views', 20 );

/** 格式化浏览量（如 3240 -> 3.2k） */
function sc_format_views( $n ) {
	$n = (int) $n;
	return $n >= 1000 ? round( $n / 1000, 1 ) . 'k' : (string) $n;
}

/* ==========================================================================
   3. 激活主题时自动写入 8 个可编辑示例商品（工作台 + MCP + Skill）
   ========================================================================== */

function sc_seed_demo_skills() {
	$existing = get_posts( array(
		'post_type'   => 'sc_skill',
		'post_status' => 'any',
		'numberposts' => 1,
		'fields'      => 'ids',
	) );
	if ( ! empty( $existing ) ) {
		return;
	}

	$samples = array(
		array( 'title' => '基础设施与服务器掌管工作台', 'title_en' => 'Server & Infra Command Workbench', 'cat' => 'workbench', 'tags' => '服务器, 网站, 面板, 监控', 'billing' => '买断制', 'price' => '¥299', 'views' => 5420, 'heat' => 99, 'featured' => 1, 'desc' => '集中掌管多台云主机、网站、面板与系统负载，把 SSH 排障、部署与日常巡检交给 Agent 自动执行。', 'slug' => 'ops-server-workbench' ),
		array( 'title' => '跨境电商全链路运营工作台', 'title_en' => 'Cross-Border Commerce Workbench', 'cat' => 'workbench', 'tags' => '商品, 运营, 多店同步', 'billing' => '订阅制', 'price' => '¥199/月', 'views' => 4180, 'heat' => 97, 'featured' => 0, 'desc' => '打通选品采集、多语种详情页生成、商品上架与店铺日常运营，单人也能高效管理多站点矩阵。', 'slug' => 'ecommerce-ops-workbench' ),
		array( 'title' => '全渠道内容矩阵与知识库工作台', 'title_en' => 'Content Matrix & Knowledge Desk', 'cat' => 'workbench', 'tags' => '内容, 知识库, 提示词', 'billing' => '买断制', 'price' => '¥249', 'views' => 3690, 'heat' => 95, 'featured' => 0, 'desc' => '统一沉淀企业知识库、提示词模板与多平台内容排期，支持一键改写并分发至公众号、小红书与独立站。', 'slug' => 'content-kb-workbench' ),
		array( 'title' => '浏览器与手机群控自动化工作台', 'title_en' => 'Browser & Device Fleet Workbench', 'cat' => 'workbench', 'tags' => '浏览器, 手机, 智能体', 'billing' => '订阅制', 'price' => '¥269/月', 'views' => 3240, 'heat' => 94, 'featured' => 0, 'desc' => '可视化编排多指纹浏览器与云手机集群，结合智能体自动完成养号、表单交互与数据回传。', 'slug' => 'device-fleet-workbench' ),
		array( 'title' => '数据库与 SQL 慢查询诊断 MCP', 'title_en' => 'SQL Profiler & Tuning MCP', 'cat' => 'mcp', 'tags' => 'MySQL, EXPLAIN, MCP', 'billing' => '买断制', 'price' => '¥69', 'views' => 2870, 'heat' => 93, 'featured' => 0, 'desc' => '标准 MCP 服务，直接连接只读实例解析执行计划、锁等待与索引选择性，输出可执行 DDL 与回滚脚本。', 'slug' => 'sql-profiler-mcp' ),
		array( 'title' => '浏览器无头采集与登录态 MCP', 'title_en' => 'Headless Browser & Auth MCP', 'cat' => 'mcp', 'tags' => 'CDP, 采集, MCP', 'billing' => '买断制', 'price' => '¥89', 'views' => 2510, 'heat' => 92, 'featured' => 0, 'desc' => '为你的 Agent 提供持久化登录态、网络拦截与结构化 DOM 提取能力，开箱即用。', 'slug' => 'browser-cdp-mcp' ),
		array( 'title' => 'PR 架构与安全审查 Skill', 'title_en' => 'PR Security & Arch Guard Skill', 'cat' => 'skill', 'tags' => 'Security, Code Review', 'billing' => '订阅制', 'price' => '¥59/月', 'views' => 2190, 'heat' => 90, 'featured' => 0, 'desc' => '深度扫描鉴权绕过、越权访问、N+1 查询与并发竞态，在代码合入前给出精确到行号的修复建议。', 'slug' => 'pr-security-skill' ),
		array( 'title' => '合同条款风险与红线审阅 Skill', 'title_en' => 'Contract Redline Reviewer Skill', 'cat' => 'skill', 'tags' => '合同审阅, NDA', 'billing' => '买断制', 'price' => '¥49', 'views' => 1860, 'heat' => 88, 'featured' => 0, 'desc' => '针对 SaaS 采购、外包研发与保密协议，高亮不对等责任条款并生成温和专业的修改措辞。', 'slug' => 'contract-redline-skill' ),
	);

	foreach ( $samples as $s ) {
		wp_insert_post( array(
			'post_type'    => 'sc_skill',
			'post_status'  => 'publish',
			'post_name'    => $s['slug'],
			'post_title'   => $s['title'],
			'post_excerpt' => $s['desc'],
			'post_content' => '<p>' . esc_html( $s['desc'] ) . '</p><p>支持桌面端 / Web 独立部署或作为插件直接挂载至你的 AI Agent，购买后即刻获取完整安装包与专属授权口令。</p>',
			'meta_input'   => array(
				'_sc_cat'         => $s['cat'],
				'_sc_tags'        => $s['tags'],
				'_sc_billing'     => $s['billing'],
				'_sc_price'       => $s['price'],
				'_sc_views'       => $s['views'],
				'_sc_heat'        => $s['heat'],
				'_sc_featured'    => $s['featured'],
				'_sc_title_en'    => $s['title_en'],
				'_sc_subtitle'    => $s['desc'],
				'_sc_install_cmd' => 'npx -y @agentdesk/cli@latest install --pack ' . $s['slug'],
				'_sc_pack_slug'   => $s['slug'],
				'_sc_cycle_days'  => ( '订阅制' === $s['billing'] ) ? 30 : 0,
				'_sc_max_devices' => 2,
				'_sc_clients'     => 'workbench' === $s['cat'] ? 'Windows / macOS / Web' : 'Cursor · Claude · Codex',
			),
		) );
	}
}
