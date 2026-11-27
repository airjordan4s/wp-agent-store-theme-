<?php
/**
 * 商品详情页（single-sc_skill.php — 兼容业务工作台 / MCP 插件 / Skill 能力包）
 *
 * 左侧：标题与指标 + 工作台实机演示截图视窗（支持 WP 后台上传替换）+ 核心模块 + 详细介绍 + 安装/启动指令 + 评价
 * 右侧：粘性购买卡 + 官方自营保障（跟随 WP 站点标题）+ 精选推荐
 *
 * @package SkillCraft
 */

get_header();

$post_id     = function_exists( 'get_the_ID' ) ? get_the_ID() : 0;
$title_zh    = function_exists( 'get_the_title' ) ? get_the_title() : '基础设施与服务器掌管工作台';
$title_en    = $post_id ? get_post_meta( $post_id, '_sc_title_en', true ) : '';
$title       = sc_t( $title_zh, $title_en ?: 'Server & Infra Command Workbench' );
$cat_key     = $post_id ? ( get_post_meta( $post_id, '_sc_cat', true ) ?: 'workbench' ) : 'workbench';
$types       = function_exists( 'sc_product_types' ) ? sc_product_types() : array();
$type_label  = isset( $types[ $cat_key ] ) ? sc_t( $types[ $cat_key ]['zh'], $types[ $cat_key ]['en'] ) : sc_t( '业务工作台', 'Workbench' );
$billing     = $post_id ? ( get_post_meta( $post_id, '_sc_billing', true ) ?: sc_t( '买断制', 'One-time' ) ) : sc_t( '买断制', 'One-time' );
$price       = $post_id ? ( get_post_meta( $post_id, '_sc_price', true ) ?: '¥299' ) : '¥299';
$preview_img = $post_id ? get_post_meta( $post_id, '_sc_preview_img', true ) : '';
$subtitle    = ( $post_id ? get_post_meta( $post_id, '_sc_subtitle', true ) : '' ) ?: sc_t( '集中掌管多台云主机、网站、面板与系统负载，把 SSH 排障、部署与日常巡检交给 Agent 自动执行，让基础设施井然有序。', 'Manage servers, sites, panels, and system load from one desktop workbench — hand routine ops and triage to your Agent.' );
$clients     = ( $post_id ? get_post_meta( $post_id, '_sc_clients', true ) : '' ) ?: 'Windows / macOS / Web';
$install_cmd = ( $post_id ? get_post_meta( $post_id, '_sc_install_cmd', true ) : '' ) ?: 'npx -y @skiloy/cli@latest install --pack ops-server-workbench';
$raw_tags    = $post_id ? get_post_meta( $post_id, '_sc_tags', true ) : '服务器, 网站, 面板, 知识库, 智能体';
$modules     = array_values( array_filter( array_map( 'trim', explode( ',', $raw_tags ) ) ) );

$raw_hl     = $post_id ? get_post_meta( $post_id, '_sc_highlights', true ) : '';
$highlights = $raw_hl ? array_values( array_filter( array_map( 'trim', explode( "\n", $raw_hl ) ) ) ) : array(
	sc_t( '多节点服务器 CPU / 内存 / 磁盘实时看板，一键 SSH 连接与健康巡检', 'Live CPU, memory, and disk telemetry across all nodes with one-click SSH' ),
	sc_t( '统一内置网站、面板、知识库、提示词与智能体编排模块，无需来回切工具', 'Unified Sites, Panels, Knowledge Base, Prompts, and Agents in one desk' ),
	sc_t( '本地优先安全架构，SSH 密钥与服务器凭据全程加密存储在本地设备', 'Local-first architecture keeping SSH keys and credentials strictly on your device' ),
	sc_t( '支持作为独立工作台运行，同时开放标准 MCP 接口供 Cursor / Claude 调用', 'Runs standalone on Desktop/Web while exposing MCP tools for Cursor & Claude' ),
);

$votes = array(
	array( 'key' => 'top',  'label' => sc_t( '超好用', 'Super useful' ), 'count' => 26 ),
	array( 'key' => 'pro',  'label' => sc_t( '很稳定', 'Rock solid' ),   'count' => 15 ),
	array( 'key' => 'fast', 'label' => sc_t( '省时间', 'Time saver' ),   'count' => 12 ),
	array( 'key' => 'mid',  'label' => sc_t( '一般', 'Average' ),        'count' => 1 ),
);

$pd_views = $post_id ? ( (int) get_post_meta( $post_id, '_sc_views', true ) ?: 5420 ) : 5420;
$pd_heat  = $post_id ? ( (int) get_post_meta( $post_id, '_sc_heat', true ) ?: 99 ) : 99;
?>

<section class="sc-band sc-product-page">
	<!-- 顶栏面包屑 -->
	<div class="sc-framed sc-pd-crumbbar">
		<nav class="sc-crumbs" aria-label="<?php echo esc_attr( sc_t( '面包屑', 'Breadcrumb' ) ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( sc_t( '首页', 'Home' ) ); ?></a>
			<span>/</span>
			<a href="<?php echo esc_url( home_url( '/skills/' ) ); ?>"><?php echo esc_html( sc_t( '作品集市', 'Catalog' ) ); ?></a>
			<span>/</span>
			<strong><?php echo esc_html( $title ); ?></strong>
		</nav>
	</div>

	<!-- 主体双栏：左内容 + 右粘性购买卡 -->
	<div class="sc-framed sc-pd-layout">

		<!-- 左侧：商品详细介绍 -->
		<div class="sc-pd-main">
			<header class="sc-pd-head">
				<div class="sc-pd-badges">
					<span class="sc-badge sc-badge--monthly"><?php echo esc_html( $type_label ); ?></span>
					<span class="sc-badge"><?php echo esc_html( $billing ); ?></span>
					<span class="sc-pd-safe">
						<svg width="13" height="13" viewBox="0 0 24 24" fill="none" aria-hidden="true">
							<path d="M12 3l7 3v5c0 4.5-3 8.2-7 10-4-1.8-7-5.5-7-10V6l7-3z" stroke="#16a34a" stroke-width="2"/>
							<path d="M9 12l2 2 4-4" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						<?php echo esc_html( sc_t( '已通过安全审计', 'Safety verified' ) ); ?>
					</span>
					<span class="sc-mk-metric">
						<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M2 12s3.8-6.5 10-6.5S22 12 22 12s-3.8 6.5-10 6.5S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
						<?php echo esc_html( sc_format_views( $pd_views ) ); ?>
					</span>
					<span class="sc-mk-metric sc-mk-metric--hot">
						<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2c.5 4.2-3 6.2-3 10a5 5 0 0 0 10 0c0-3.2-1.8-5.6-3.5-7.2-.2 1.7-1.1 2.7-2.2 3.1.4-2.1-.1-4.1-1.3-5.9z"/></svg>
						<?php echo esc_html( $pd_heat ); ?>°
					</span>
				</div>
				<h1><?php echo esc_html( $title ); ?></h1>
				<p class="sc-pd-subtitle"><?php echo esc_html( $subtitle ); ?></p>
				<?php if ( ! empty( $modules ) ) : ?>
					<div class="sc-pd-modtags">
						<span><?php echo esc_html( sc_t( '内置模块：', 'Modules:' ) ); ?></span>
						<?php foreach ( $modules as $m ) : ?>
							<em><?php echo esc_html( $m ); ?></em>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</header>

			<!-- 工作台 / 产品实机演示截图区（后台上传真实截图自动替换） -->
			<div class="sc-pd-block sc-pd-preview">
				<div class="sc-pd-preview__head">
					<h2><?php echo esc_html( sc_t( '界面与实机截图', 'Interface' ) ); ?></h2>
					<span><?php echo esc_html( $preview_img ? sc_t( '实机截图', 'Live Screenshot' ) : sc_t( '实机截图 · 可在 WP 后台上传替换', 'Screenshot · Upload in WP Admin' ) ); ?></span>
				</div>

				<?php
				$gallery_raw  = $post_id ? (string) get_post_meta( $post_id, '_sc_gallery_imgs', true ) : '';
				$gallery_urls = array_values( array_filter( array_map( 'trim', explode( "\n", $gallery_raw ) ) ) );
				if ( $preview_img ) {
					array_unshift( $gallery_urls, $preview_img );
					$gallery_urls = array_values( array_unique( $gallery_urls ) );
				}
				if ( ! empty( $gallery_urls ) ) :
					?>
					<div class="sc-pd-shotframe">
						<div class="sc-wb-topbar sc-wb-topbar--slim">
							<div class="sc-window__dots" aria-hidden="true">
								<span style="background:#ff5f57"></span>
								<span style="background:#febc2e"></span>
								<span style="background:#28c840"></span>
							</div>
							<em class="sc-pd-shotframe__title"><?php echo esc_html( $title ); ?></em>
							<button type="button" class="sc-pd-zoombtn" id="sc-zoomMainShot" aria-label="<?php echo esc_attr( sc_t( '放大查看', 'Zoom screenshot' ) ); ?>"><?php echo esc_html( sc_t( '点击放大 ⤢', 'Zoom ⤢' ) ); ?></button>
						</div>
						<img id="sc-mainShotImg" src="<?php echo esc_url( $gallery_urls[0] ); ?>" alt="<?php echo esc_attr( $title ); ?>" data-lightbox-trigger decoding="async">
					</div>
					<?php if ( count( $gallery_urls ) > 1 ) : ?>
						<div class="sc-pd-thumbs" role="group" aria-label="<?php echo esc_attr( sc_t( '切换工作台截图', 'Switch screenshots' ) ); ?>">
							<?php foreach ( $gallery_urls as $g_i => $g_url ) : ?>
								<button type="button" class="sc-pd-thumb <?php echo 0 === $g_i ? 'is-active' : ''; ?>" data-shot-src="<?php echo esc_url( $g_url ); ?>">
									<img src="<?php echo esc_url( $g_url ); ?>" alt="" loading="lazy" decoding="async">
								</button>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				<?php else : ?>
					<div class="sc-pd-shotframe sc-pd-shotframe--mock">
						<div class="sc-wb-topbar sc-wb-topbar--slim">
							<div class="sc-window__dots" aria-hidden="true">
								<span style="background:#ff5f57"></span>
								<span style="background:#febc2e"></span>
								<span style="background:#28c840"></span>
							</div>
							<div class="sc-wb-nav">
								<span class="is-on"><?php echo esc_html( sc_t( '服务器 3', 'Servers 3' ) ); ?></span>
								<span><?php echo esc_html( sc_t( '网站', 'Sites' ) ); ?></span>
								<span><?php echo esc_html( sc_t( '内容', 'Content' ) ); ?></span>
								<span><?php echo esc_html( sc_t( '知识库', 'KB' ) ); ?></span>
								<span><?php echo esc_html( sc_t( '智能体', 'Agents' ) ); ?></span>
							</div>
						</div>
						<div class="sc-wb-body sc-wb-body--compact">
							<div class="sc-wb-header">
								<div>
									<h3><?php echo esc_html( sc_t( '掌管 Servers', 'Command Servers' ) ); ?></h3>
									<p><?php echo esc_html( sc_t( '让基础设施井然有序，把繁琐交给 Agent。', 'Keep infrastructure orderly — hand ops to your Agent.' ) ); ?></p>
								</div>
								<span class="sc-wb-pill is-on"><?php echo esc_html( sc_t( '运行正常 3', '3 Healthy' ) ); ?></span>
							</div>
							<div class="sc-wb-table">
								<div class="sc-wb-tr sc-wb-tr--head">
									<span><?php echo esc_html( sc_t( '节点名称', 'Node' ) ); ?></span>
									<span><?php echo esc_html( sc_t( '状态', 'Status' ) ); ?></span>
									<span>CPU</span>
									<span><?php echo esc_html( sc_t( '内存', 'RAM' ) ); ?></span>
									<span><?php echo esc_html( sc_t( '磁盘', 'Disk' ) ); ?></span>
								</div>
								<div class="sc-wb-tr">
									<div><strong>US-West-01</strong><small>64.118.153.164 : 22</small></div>
									<div><span class="sc-wb-ok">● <?php echo esc_html( sc_t( '正常', 'OK' ) ); ?></span></div>
									<div><strong>4%</strong><i class="sc-wb-bar"><b style="width:8%"></b></i></div>
									<div><strong>16%</strong><i class="sc-wb-bar"><b style="width:16%"></b></i></div>
									<div><strong>12%</strong><i class="sc-wb-bar"><b style="width:12%"></b></i></div>
								</div>
								<div class="sc-wb-tr">
									<div><strong>SG-Edge-02</strong><small>64.118.155.227 : 22</small></div>
									<div><span class="sc-wb-ok">● <?php echo esc_html( sc_t( '正常', 'OK' ) ); ?></span></div>
									<div><strong>11%</strong><i class="sc-wb-bar"><b style="width:14%"></b></i></div>
									<div><strong>24%</strong><i class="sc-wb-bar"><b style="width:24%"></b></i></div>
									<div><strong>18%</strong><i class="sc-wb-bar"><b style="width:18%"></b></i></div>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<div class="sc-pd-block">
				<h2><?php echo esc_html( sc_t( '核心能力与内置模块', 'Key Capabilities & Modules' ) ); ?></h2>
				<ul class="sc-pd-list">
					<?php foreach ( $highlights as $item ) : ?>
						<li><?php echo esc_html( $item ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="sc-pd-block">
				<h2><?php echo esc_html( sc_t( '详细介绍', 'Overview' ) ); ?></h2>
				<div class="sc-pd-prose">
					<?php
					if ( function_exists( 'have_posts' ) && have_posts() && ! empty( $post_id ) ) :
						while ( have_posts() ) :
							the_post();
							the_content();
						endwhile;
					else :
						?>
						<p><?php echo esc_html( sc_t( '当你需要同时维护多台海外/国内服务器、多个 WordPress 或独立站、以及日常内容和自动化任务时，频繁切换 SSH 终端和各类面板会消耗大量精力。', 'Managing multiple servers, sites, panels, and automation tasks across disconnected tabs wastes hours every week.' ) ); ?></p>
						<p><?php echo esc_html( sc_t( '这套工作台把高频基础设施能力收敛在一个极简界面内，并与 AI Agent 深度打通：日常健康巡检、日志排障、站点部署都能直接一句话交给 Agent 完成。', 'This workbench brings your operational stack into one clean interface paired with an AI Agent for health checks, log triage, and deployments.' ) ); ?></p>
					<?php endif; ?>
				</div>
			</div>

			<div class="sc-pd-block">
				<h2><?php echo esc_html( sc_t( '安装与启动方式', 'Installation & Launch' ) ); ?></h2>
				<p class="sc-pd-hint"><?php echo esc_html( sc_t( '购买后在本地终端执行一行命令即可拉取工作台 / 插件并自动绑定你的授权码：', 'After purchase, run one command locally to initialize and bind your license key:' ) ); ?></p>
				<div class="sc-pd-cmd">
					<code><?php echo esc_html( $install_cmd ); ?></code>
					<button type="button" class="sc-copy-btn" id="sc-copyCmd" data-cmd="<?php echo esc_attr( $install_cmd ); ?>">
						<span class="sc-copy-btn__tag" id="sc-copyTag"><?php echo esc_html( sc_t( '复制指令', 'Copy' ) ); ?></span>
					</button>
				</div>
			</div>

			<div class="sc-pd-block sc-pd-block--last">
				<div class="sc-pd-votes-head">
					<h2><?php echo esc_html( sc_t( '用户评价', 'Community feedback' ) ); ?></h2>
					<span id="sc-voteTotal">54</span>
				</div>
				<div class="sc-vote-bar">
					<?php foreach ( $votes as $v ) : ?>
						<button type="button" class="sc-vote-chip" data-vote-key="<?php echo esc_attr( $v['key'] ); ?>">
							<span><?php echo esc_html( $v['label'] ); ?></span>
							<strong data-vote-num><?php echo esc_html( $v['count'] ); ?></strong>
						</button>
					<?php endforeach; ?>
				</div>
			</div>
		</div>

		<!-- 右侧：粘性购买卡片 + 官方保障 + 精选推荐 -->
		<aside class="sc-pd-side">
			<div class="sc-pd-buycard">
				<div class="sc-pd-buycard__price">
					<strong><?php echo sc_price_html( $price ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					<span><?php echo esc_html( $billing ); ?></span>
				</div>
				<p class="sc-pd-buycard__note">
					<?php echo esc_html( sc_t( '下单即发专属授权码与完整安装包，买断版终身享免费版本更新与部署答疑。', 'Instant license key & package delivery with free updates and setup assistance.' ) ); ?>
				</p>

				<button type="button" class="sc-btn sc-btn--dark sc-btn--block sc-btn--lg" id="sc-buyBtn">
					<?php echo esc_html( sc_t( '立即购买', 'Buy now' ) ); ?>
				</button>

				<ul class="sc-pd-facts">
					<li><span><?php echo esc_html( sc_t( '产品形态', 'Format' ) ); ?></span><strong><?php echo esc_html( $type_label ); ?></strong></li>
					<li><span><?php echo esc_html( sc_t( '部署方式', 'Deployment' ) ); ?></span><strong><?php echo esc_html( sc_t( '本地优先 / 私有部署', 'Local-first' ) ); ?></strong></li>
					<li><span><?php echo esc_html( sc_t( '运行环境', 'Platform' ) ); ?></span><strong><?php echo esc_html( $clients ); ?></strong></li>
					<li><span><?php echo esc_html( sc_t( '售后保障', 'Support' ) ); ?></span><strong><?php echo esc_html( sc_t( '7 天无忧申诉', '7-day guarantee' ) ); ?></strong></li>
				</ul>
			</div>

			<div class="sc-pd-creator">
				<div class="sc-pd-creator__top">
					<span class="sc-avatar sc-avatar--lg"><?php echo esc_html( function_exists( 'mb_substr' ) ? mb_substr( sc_site_name(), 0, 1, 'UTF-8' ) : substr( sc_site_name(), 0, 1 ) ); ?></span>
					<div>
						<span class="sc-pd-creator__label"><?php echo esc_html( sc_t( '官方自营保障', 'First-Party Official' ) ); ?></span>
						<strong><?php echo esc_html( sc_site_name() ); ?></strong>
					</div>
				</div>
				<p><?php echo esc_html( sc_t( '本店所有业务工作台、MCP 与 Skill 均为团队自研并持续维护更新，下单即刻自动发货，并提供专属部署支持。', 'All Workbenches, MCP plugins, and Skills are built in-house with instant delivery and dedicated support.' ) ); ?></p>
			</div>

			<?php
			$all_skills = function_exists( 'sc_marketplace_products' ) ? sc_marketplace_products() : array();
			$rec_heats  = array( 97, 95, 93 );
			$rec_items  = array();
			foreach ( $all_skills as $sk ) {
				if ( $post_id && ! empty( $sk['id'] ) && (int) $sk['id'] === (int) $post_id ) {
					continue;
				}
				if ( ! empty( $sk['title'] ) && $sk['title'] === $title_zh ) {
					continue;
				}
				$rec_items[] = $sk;
				if ( count( $rec_items ) >= 3 ) {
					break;
				}
			}
			if ( ! empty( $rec_items ) ) :
				?>
				<div class="sc-pd-recs">
					<div class="sc-pd-recs__head">
						<h3><?php echo esc_html( sc_t( '精选推荐', 'Recommended' ) ); ?></h3>
						<a href="<?php echo esc_url( home_url( '/skills/' ) ); ?>"><?php echo esc_html( sc_t( '全部 →', 'All →' ) ); ?></a>
					</div>
					<div class="sc-pd-recs__list">
						<?php foreach ( $rec_items as $r_idx => $rec ) :
							$rec_title = sc_t( $rec['title'], $rec['title_en'] ?? $rec['title'] );
							$rec_desc  = sc_t( $rec['desc'], $rec['desc_en'] ?? $rec['desc'] );
							$rec_badge = sc_t( $rec['badge'], $rec['badge_en'] ?? $rec['badge'] );
							$rec_heat  = ! empty( $rec['heat'] ) ? (int) $rec['heat'] : ( $rec_heats[ $r_idx ] ?? 92 );
							$rec_url   = $rec['url'] ?? home_url( '/product/' );
							?>
							<a class="sc-pd-rec" href="<?php echo esc_url( $rec_url ); ?>">
								<div class="sc-pd-rec__top">
									<strong><?php echo esc_html( $rec_title ); ?></strong>
									<span class="sc-pd-rec__price"><?php echo sc_price_html( $rec['price'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
								</div>
								<p class="sc-pd-rec__desc"><?php echo esc_html( $rec_desc ); ?></p>
								<div class="sc-pd-rec__meta">
									<span class="sc-badge<?php echo ( '订阅制' === $rec['badge'] ) ? ' sc-badge--monthly' : ''; ?>"><?php echo esc_html( $rec_badge ); ?></span>
									<span class="sc-mk-metric sc-mk-metric--hot">
										<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2c.5 4.2-3 6.2-3 10a5 5 0 0 0 10 0c0-3.2-1.8-5.6-3.5-7.2-.2 1.7-1.1 2.7-2.2 3.1.4-2.1-.1-4.1-1.3-5.9z"/></svg>
										<?php echo esc_html( $rec_heat ); ?>°
									</span>
								</div>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</aside>

	</div>
</section>

<!-- 购买弹窗：未登录先弹出「注册 / 登录」双 Tab 门禁，已登录直接确认下单 -->
<?php
$logged_in   = is_user_logged_in();
$cur_url     = function_exists( 'get_permalink' ) && $post_id ? get_permalink( $post_id ) : home_url( '/product/' );
?>
<div class="sc-modal" id="sc-buyModal" hidden role="dialog" aria-modal="true" aria-labelledby="sc-buyModalTitle"
     data-skill-id="<?php echo esc_attr( $post_id ); ?>"
     data-skill-title="<?php echo esc_attr( $title ); ?>"
     data-skill-price="<?php echo esc_attr( $price ); ?>"
     data-install-cmd="<?php echo esc_attr( $install_cmd ); ?>">
	<div class="sc-modal__backdrop" data-close-modal></div>
	<div class="sc-modal__box">
		<?php if ( ! $logged_in ) : ?>
			<div class="sc-modal__head">
				<div class="sc-modal__title">
					<img class="sc-modal__logo" src="<?php echo esc_url( SKILLCRAFT_URI . '/assets/img/mascot/mascot-head.png' ); ?>" alt="" width="34" height="20">
					<h3 id="sc-buyModalTitle"><?php echo esc_html( sc_t( '请先注册或登录账户', 'Sign up or sign in to continue' ) ); ?></h3>
				</div>
				<button type="button" class="sc-modal__close" data-close-modal aria-label="<?php echo esc_attr( sc_t( '关闭', 'Close' ) ); ?>">×</button>
			</div>
			<p class="sc-modal__desc">
				<?php echo esc_html( sc_t( '为确保你的专属授权码、设备绑定与后续版本更新永久归档不丢失，购买前请先完成注册或登录：', 'Create a free account or sign in so your license key, device slots, and free updates stay safely linked to you:' ) ); ?>
			</p>

			<div class="sc-modal-tabs" role="tablist">
				<button type="button" class="sc-modal-tab is-active" data-auth-modal-tab="signup" role="tab" aria-selected="true"><?php echo esc_html( sc_t( '快速注册', 'Create account' ) ); ?></button>
				<button type="button" class="sc-modal-tab" data-auth-modal-tab="login" role="tab" aria-selected="false"><?php echo esc_html( sc_t( '已有账号登录', 'Sign in' ) ); ?></button>
			</div>

			<!-- Tab 1：快速注册（对接 WP 原生注册并自动返回当前商品页） -->
			<form class="sc-form" method="post" action="<?php echo esc_url( sc_signup_url() ); ?>" data-auth-modal-pane="signup">
				<?php wp_nonce_field( 'sc_auth_signup', 'sc_auth_nonce' ); ?>
				<input type="hidden" name="sc_auth_action" value="signup">
				<input type="hidden" name="redirect_to" value="<?php echo esc_url( $cur_url ); ?>">
				<div class="sc-field">
					<label for="sc-m-name"><?php echo esc_html( sc_t( '你的昵称', 'Display name' ) ); ?></label>
					<input class="sc-input" id="sc-m-name" name="sc_name" type="text" required placeholder="<?php echo esc_attr( sc_t( '例如：林致远', 'Alex Lin' ) ); ?>">
				</div>
				<div class="sc-field">
					<label for="sc-m-email"><?php echo esc_html( sc_t( '工作邮箱（用于接收授权码）', 'Email (for license delivery)' ) ); ?></label>
					<input class="sc-input" id="sc-m-email" name="sc_email" type="email" required placeholder="name@company.com">
				</div>
				<div class="sc-field">
					<label for="sc-m-pass"><?php echo esc_html( sc_t( '设置密码', 'Password' ) ); ?></label>
					<input class="sc-input" id="sc-m-pass" name="sc_password" type="password" required minlength="8" placeholder="<?php echo esc_attr( sc_t( '至少 8 位字符', 'At least 8 characters' ) ); ?>">
				</div>
				<button type="submit" class="sc-btn sc-btn--dark sc-btn--block">
					<?php echo esc_html( sc_t( '注册并继续购买 →', 'Sign up & continue →' ) ); ?>
				</button>
			</form>

			<!-- Tab 2：已有账号登录 -->
			<form class="sc-form" method="post" action="<?php echo esc_url( sc_login_url( $cur_url ) ); ?>" data-auth-modal-pane="login" hidden>
				<?php wp_nonce_field( 'sc_auth_login', 'sc_auth_nonce' ); ?>
				<input type="hidden" name="sc_auth_action" value="login">
				<input type="hidden" name="redirect_to" value="<?php echo esc_url( $cur_url ); ?>">
				<div class="sc-field">
					<label for="sc-m-login"><?php echo esc_html( sc_t( '邮箱或用户名', 'Email or username' ) ); ?></label>
					<input class="sc-input" id="sc-m-login" name="sc_login" type="text" required placeholder="name@company.com">
				</div>
				<div class="sc-field">
					<label for="sc-m-lpass"><?php echo esc_html( sc_t( '密码', 'Password' ) ); ?></label>
					<input class="sc-input" id="sc-m-lpass" name="sc_password" type="password" required placeholder="<?php echo esc_attr( sc_t( '输入你的密码', 'Enter your password' ) ); ?>">
				</div>
				<button type="submit" class="sc-btn sc-btn--dark sc-btn--block">
					<?php echo esc_html( sc_t( '登录并继续购买 →', 'Sign in & continue →' ) ); ?>
				</button>
			</form>
		<?php else : ?>
			<div class="sc-modal__head">
				<div class="sc-modal__title">
					<img class="sc-modal__logo" src="<?php echo esc_url( SKILLCRAFT_URI . '/assets/img/mascot/mascot-head.png' ); ?>" alt="" width="34" height="20">
					<h3 id="sc-buyModalTitle"><?php echo esc_html( sc_t( '确认下单并自动发货', 'Checkout & Instant Delivery' ) ); ?></h3>
				</div>
				<button type="button" class="sc-modal__close" data-close-modal aria-label="<?php echo esc_attr( sc_t( '关闭', 'Close' ) ); ?>">×</button>
			</div>
			<p class="sc-modal__desc">
				<?php echo esc_html( sprintf( sc_t( '商品：%1$s（%2$s）。确认收货邮箱后点击下单，系统将自动生成专属授权码并同步到你的会员中心：', 'Item: %1$s (%2$s). Confirm your delivery email to generate your license key and sync it to your Account:' ), $title, $billing ) ); ?>
			</p>
			<form id="sc-orderForm" class="sc-form">
				<div class="sc-field">
					<label for="sc-orderEmail"><?php echo esc_html( sc_t( '接收授权码的邮箱', 'Delivery email' ) ); ?></label>
					<input class="sc-input" id="sc-orderEmail" type="email" required placeholder="name@company.com"
					       value="<?php echo esc_attr( wp_get_current_user()->user_email ); ?>">
				</div>
				<button type="submit" class="sc-btn sc-btn--dark sc-btn--block" id="sc-orderSubmit">
					<?php echo esc_html( sc_t( '确认下单并获取授权（', 'Confirm order (' ) ); ?><?php echo sc_price_html( $price ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( sc_t( '）', ')' ) ); ?>
				</button>
			</form>
			<div id="sc-orderResult" class="sc-order-result" hidden>
				<div class="sc-alert sc-alert--success" id="sc-orderMsg" style="margin:0"></div>
				<pre class="sc-code-block" id="sc-orderCmd"></pre>
				<button type="button" class="sc-btn sc-btn--dark sc-btn--block" id="sc-copyOrderCmd">
					<?php echo esc_html( sc_t( '复制专属启动指令', 'Copy launch command' ) ); ?>
				</button>
			</div>
		<?php endif; ?>
	</div>
</div>

<!-- 全屏图片灯箱 -->
<div class="sc-lightbox" id="sc-lightbox" hidden role="dialog" aria-modal="true" aria-label="<?php echo esc_attr( sc_t( '全屏截图预览', 'Fullscreen preview' ) ); ?>">
	<button type="button" class="sc-lightbox__close" id="sc-lightboxClose" aria-label="<?php echo esc_attr( sc_t( '关闭', 'Close' ) ); ?>">×</button>
	<img id="sc-lightboxImg" src="" alt="">
</div>

<?php
get_footer();
