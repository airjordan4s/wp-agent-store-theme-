<?php
/**
 * 作品集市内页（archive-sc_skill.php → /skills/）
 *
 * 面向「AI 业务工作台 + MCP 插件 + Skill 能力包」的高级瑞士建筑画廊目录页。
 *
 * @package SkillCraft
 */

get_header();

$products = sc_marketplace_products();
$types    = function_exists( 'sc_product_types' ) ? sc_product_types() : array(
	'workbench' => array( 'zh' => '业务工作台', 'en' => 'Workbench',  'tint' => '#eef5ff', 'color' => '#1677ff', 'icon' => 'desktop' ),
	'mcp'       => array( 'zh' => 'MCP 插件',   'en' => 'MCP Plugin', 'tint' => '#f5f3ff', 'color' => '#7c3aed', 'icon' => 'plug' ),
	'skill'     => array( 'zh' => 'Skill 能力包', 'en' => 'Skill Pack', 'tint' => '#ecfdf5', 'color' => '#059669', 'icon' => 'spark' ),
);

if ( ! function_exists( 'sc_market_icon' ) ) {
	function sc_market_icon( $name ) {
		$icons = array(
			'desktop' => '<rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/>',
			'plug'    => '<path d="M12 22v-5M9 8V2M15 8V2M6 8h12v3a6 6 0 0 1-12 0V8z"/>',
			'spark'   => '<path d="M12 3l2.2 6.8L21 12l-6.8 2.2L12 21l-2.2-6.8L3 12l6.8-2.2L12 3z"/>',
		);
		$path = $icons[ $name ] ?? $icons['desktop'];
		return '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $path . '</svg>';
	}
}
?>

<section class="sc-band sc-subpage sc-market-page">
	<!-- 1. 画廊感页头 + 3 格信任微指标 -->
	<div class="sc-framed sc-mk-hero">
		<div class="sc-mk-hero__copy">
			<span class="sc-kicker">/ <?php echo esc_html( sc_t( '官方自营目录 · WORKBENCHES · MCP · SKILLS', 'FIRST-PARTY CATALOG · 2026' ) ); ?></span>
			<h1><?php echo esc_html( sc_t( '作品集市', 'Workbenches & Plugins' ) ); ?></h1>
			<p><?php echo esc_html( sc_t( '按业务场景打造开箱即用的 AI 工作台、标准 MCP 插件与 Skill 能力包。支持本地部署与 Agent 挂载，下单秒级自动发货。', 'Production-ready AI Workbenches, MCP plugins, and Skill packs built for real business workflows. Instant automated delivery.' ) ); ?></p>
		</div>

		<div class="sc-mk-stats">
			<div class="sc-mk-stat">
				<strong><?php echo esc_html( count( $products ) ); ?>+</strong>
				<span><?php echo esc_html( sc_t( '工作台与插件', 'Workbenches & Packs' ) ); ?></span>
			</div>
			<div class="sc-mk-stat">
				<strong>24/7</strong>
				<span><?php echo esc_html( sc_t( '下单秒级发货', 'Instant delivery' ) ); ?></span>
			</div>
			<div class="sc-mk-stat">
				<strong>FREE</strong>
				<span><?php echo esc_html( sc_t( '买断终身更新', 'Lifetime updates' ) ); ?></span>
			</div>
		</div>
	</div>

	<!-- 2. 暗黑高反差「镇店精选」Spotlight 卡片（带工作台实机缩略界面） -->
	<div class="sc-framed sc-mk-spotlight">
		<div class="sc-mk-spot__left">
			<div class="sc-mk-spot__eyebrow">
				<span class="sc-mk-spot__dot"></span>
				<span><?php echo esc_html( sc_t( '本月镇店精选 · 业务工作台', 'EDITOR’S PICK · WORKBENCH' ) ); ?></span>
			</div>
			<h2><?php echo esc_html( sc_t( '基础设施与服务器掌管工作台', 'Server & Infra Command Workbench' ) ); ?></h2>
			<p><?php echo esc_html( sc_t( '把多台服务器、网站、面板、知识库与智能体收进同一个桌面工作台，实时监控 CPU / 内存 / 磁盘，异常直接交给 Agent 接管排查。', 'Unify servers, sites, panels, knowledge base, and agents inside one desktop workbench with live metrics and automated triage.' ) ); ?></p>
			<div class="sc-mk-spot__actions">
				<a class="sc-btn sc-btn--light" href="<?php echo esc_url( home_url( '/product/' ) ); ?>">
					<?php echo esc_html( sc_t( '查看工作台预览 · ', 'Preview Workbench · ' ) ); ?><?php echo sc_price_html( '¥299' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> →
				</a>
				<a class="sc-mk-spot__link" href="<?php echo esc_url( home_url( '/custom/' ) ); ?>">
					<?php echo esc_html( sc_t( '按业务定制工作台', 'Custom build' ) ); ?>
				</a>
			</div>
		</div>

		<div class="sc-mk-spot__right" aria-hidden="true">
			<div class="sc-mk-term sc-mk-deskprev">
				<div class="sc-mk-term__bar">
					<span></span><span></span><span></span>
					<em><?php echo esc_html( sc_t( '服务器 3 · 网站 · 内容 · 知识库 · 智能体', 'Servers 3 · Sites · Content · KB · Agents' ) ); ?></em>
				</div>
				<div class="sc-mk-deskprev__body">
					<div class="sc-mk-deskprev__row">
						<strong>US-West-01</strong>
						<span class="t-ok">● <?php echo esc_html( sc_t( '正常', 'OK' ) ); ?></span>
						<span>CPU 4%</span>
						<span>RAM 16%</span>
					</div>
					<div class="sc-mk-deskprev__row">
						<strong>SG-Edge-02</strong>
						<span class="t-ok">● <?php echo esc_html( sc_t( '正常', 'OK' ) ); ?></span>
						<span>CPU 11%</span>
						<span>RAM 24%</span>
					</div>
					<div class="sc-mk-deskprev__row">
						<strong>US-Core-03</strong>
						<span class="t-ok">● <?php echo esc_html( sc_t( '正常', 'OK' ) ); ?></span>
						<span>CPU 6%</span>
						<span>RAM 46%</span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- 3. 多维筛选（产品形态 + 计费模式 + 实时搜索） -->
	<div class="sc-framed sc-market-bar">
		<div class="sc-market-filters" role="group" aria-label="<?php echo esc_attr( sc_t( '按形态筛选', 'Filter by format' ) ); ?>">
			<button type="button" class="sc-filter-btn is-active" data-cat-filter="all">
				<?php echo esc_html( sc_t( '全部作品', 'All' ) ); ?>
				<span><?php echo esc_html( count( $products ) ); ?></span>
			</button>
			<button type="button" class="sc-filter-btn" data-cat-filter="workbench"><?php echo esc_html( sc_t( '业务工作台', 'Workbenches' ) ); ?></button>
			<button type="button" class="sc-filter-btn" data-cat-filter="mcp"><?php echo esc_html( sc_t( 'MCP 插件', 'MCP Plugins' ) ); ?></button>
			<button type="button" class="sc-filter-btn" data-cat-filter="skill"><?php echo esc_html( sc_t( 'Skill 能力包', 'Skill Packs' ) ); ?></button>
		</div>

		<div class="sc-market-controls">
			<div class="sc-billing-seg" role="group" aria-label="<?php echo esc_attr( sc_t( '按计费筛选', 'Filter by billing' ) ); ?>">
				<button type="button" class="sc-billing-seg__btn is-active" data-billing-filter="all"><?php echo esc_html( sc_t( '全部', 'All' ) ); ?></button>
				<button type="button" class="sc-billing-seg__btn" data-billing-filter="one-time"><?php echo esc_html( sc_t( '买断', 'One-time' ) ); ?></button>
				<button type="button" class="sc-billing-seg__btn" data-billing-filter="monthly"><?php echo esc_html( sc_t( '订阅', 'Monthly' ) ); ?></button>
			</div>

			<div class="sc-market-search">
				<svg class="sc-market-search__icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
				<input type="search" id="sc-marketSearch" class="sc-input sc-market-search__input" placeholder="<?php echo esc_attr( sc_t( '搜索工作台、MCP 或 Skill...', 'Search workbenches, MCP, Skills...' ) ); ?>" aria-label="<?php echo esc_attr( sc_t( '搜索作品', 'Search catalog' ) ); ?>">
			</div>
		</div>
	</div>

	<!-- 4. 精品建筑网格（4 列） -->
	<div class="sc-framed sc-products__grid" id="sc-marketGrid">
		<?php foreach ( $products as $idx => $p ) :
			$cat_key = ! empty( $p['cat'] ) && isset( $types[ $p['cat'] ] ) ? $p['cat'] : 'workbench';
			$t_info  = $types[ $cat_key ];
			$billing = ( '订阅制' === $p['badge'] || 'Monthly' === ( $p['badge_en'] ?? '' ) ) ? 'monthly' : 'one-time';
			$title   = sc_t( $p['title'], $p['title_en'] );
			$desc    = sc_t( $p['desc'], $p['desc_en'] );
			$cat_lbl = sc_t( $t_info['zh'], $t_info['en'] );
			$tags    = ! empty( $p['tags'] ) && is_array( $p['tags'] ) ? $p['tags'] : array();
			$views   = ! empty( $p['views'] ) ? (int) $p['views'] : 1820;
			$heat    = ! empty( $p['heat'] ) ? (int) $p['heat'] : 92;
			$p_img   = ! empty( $p['preview_img'] ) ? $p['preview_img'] : '';
			$num     = str_pad( (string) ( $idx + 1 ), 2, '0', STR_PAD_LEFT );
			?>
			<a class="sc-product-card sc-mk-card"
			   href="<?php echo esc_url( $p['url'] ); ?>"
			   data-cat="<?php echo esc_attr( $cat_key ); ?>"
			   data-billing="<?php echo esc_attr( $billing ); ?>"
			   data-search="<?php echo esc_attr( strtolower( $title . ' ' . $desc . ' ' . implode( ' ', $tags ) . ' ' . $cat_lbl ) ); ?>">

				<?php if ( $p_img ) : ?>
					<div class="sc-mk-card__shot">
						<img src="<?php echo esc_url( $p_img ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy">
					</div>
				<?php endif; ?>

				<div class="sc-mk-card__top">
					<span class="sc-mk-icon" style="background:<?php echo esc_attr( $t_info['tint'] ); ?>;color:<?php echo esc_attr( $t_info['color'] ); ?>">
						<?php echo sc_market_icon( $t_info['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
					<span class="sc-badge<?php echo 'monthly' === $billing ? ' sc-badge--monthly' : ''; ?>"><?php echo esc_html( sc_t( $p['badge'], $p['badge_en'] ) ); ?></span>
				</div>

				<div class="sc-mk-card__body">
					<span class="sc-mk-card__cat"><?php echo esc_html( $num . ' / ' . $cat_lbl ); ?></span>
					<h3 class="sc-mk-card__title"><?php echo esc_html( $title ); ?></h3>
					<p class="sc-product-card__desc"><?php echo esc_html( $desc ); ?></p>
				</div>

				<?php if ( ! empty( $tags ) ) : ?>
					<div class="sc-mk-card__tags">
						<?php foreach ( array_slice( $tags, 0, 3 ) as $tg ) : ?>
							<span><?php echo esc_html( $tg ); ?></span>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<div class="sc-mk-card__metrics">
					<span class="sc-mk-metric" title="<?php echo esc_attr( sc_t( '浏览量', 'Views' ) ); ?>">
						<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M2 12s3.8-6.5 10-6.5S22 12 22 12s-3.8 6.5-10 6.5S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
						<?php echo esc_html( sc_format_views( $views ) ); ?>
					</span>
					<span class="sc-mk-metric sc-mk-metric--hot" title="<?php echo esc_attr( sc_t( '热度指数', 'Heat' ) ); ?>">
						<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2c.5 4.2-3 6.2-3 10a5 5 0 0 0 10 0c0-3.2-1.8-5.6-3.5-7.2-.2 1.7-1.1 2.7-2.2 3.1.4-2.1-.1-4.1-1.3-5.9z"/></svg>
						<?php echo esc_html( $heat ); ?>°
					</span>
				</div>

				<div class="sc-product-card__foot sc-mk-card__foot">
					<span class="sc-mk-card__go"><?php echo esc_html( sc_t( '查看详情 →', 'Inspect →' ) ); ?></span>
					<span class="sc-product-card__price"><?php echo sc_price_html( $p['price'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</div>
			</a>
		<?php endforeach; ?>
	</div>

	<div class="sc-framed sc-market-empty" id="sc-marketEmpty" hidden>
		<p><?php echo esc_html( sc_t( '没有找到匹配的工作台或插件，试试切换筛选条件。', 'No matching items found. Try another filter.' ) ); ?></p>
	</div>

	<!-- 5. 底部定制与团购引导横幅 -->
	<div class="sc-framed sc-mk-custom">
		<div>
			<h3><?php echo esc_html( sc_t( '需要贴合你业务流程的专属工作台或 MCP？', 'Need a custom Workbench or MCP for your workflow?' ) ); ?></h3>
			<p><?php echo esc_html( sc_t( '我们提供 1 对 1 私有业务工作台定制开发与团队批量授权服务，交付源码与部署支持。', 'We offer 1-on-1 private workbench development and volume team licenses with full handover.' ) ); ?></p>
		</div>
		<a class="sc-btn sc-btn--outline" href="<?php echo esc_url( home_url( '/custom/' ) ); ?>">
			<?php echo esc_html( sc_t( '了解定制与团购 →', 'Custom & Team Plans →' ) ); ?>
		</a>
	</div>
</section>

<?php
get_footer();
