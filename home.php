<?php
/**
 * 博客列表页（支持「3 列杂志封面卡片」⇄「极简架构列表」一键切换 + 分类筛选 + 浏览量）
 *
 * @package SkillCraft
 */

get_header();

$default_layout = sc_content()['blog_default_layout'] ?? 'grid';
if ( ! in_array( $default_layout, array( 'grid', 'list' ), true ) ) {
	$default_layout = 'grid';
}

// 莫兰迪高级几何封面色板（当文章未设置特色图时自动生成精致视觉封面）
$cover_palettes = array(
	array( 'bg' => '#53b2a1', 'pat' => 'circles', 'cat' => 'Product',    'cat_zh' => '产品发布', 'short' => 'AI Workbench 2.4', 'short_zh' => '业务工作台 2.4' ),
	array( 'bg' => '#4a9488', 'pat' => 'grid',    'cat' => 'Technology', 'cat_zh' => '技术架构', 'short' => 'Ed25519 License',  'short_zh' => '防绕过签名授权' ),
	array( 'bg' => '#c9be82', 'pat' => 'dots',    'cat' => 'Case',       'cat_zh' => '实战案例', 'short' => '1 Person, 6 Stores', 'short_zh' => '1 人掌管 6 站点' ),
	array( 'bg' => '#7a879e', 'pat' => 'grid',    'cat' => 'Feature',    'cat_zh' => '功能指南', 'short' => 'Remote MCP Hub',   'short_zh' => '远程 MCP 托管' ),
);
$demo_views = array( 2480, 1920, 3150, 1460 );
?>

<section class="sc-band sc-subpage sc-blog-page">
	<div class="sc-framed sc-subhero sc-blog-hero">
		<h1><?php echo esc_html( sc_t( '博客', 'Blog' ) ); ?></h1>
		<p><?php echo esc_html( sc_t( '团队笔记、工作台架构拆解、实战提效案例与版本发布动态。', 'Team notes, architecture breakdowns, real-world stories, and release announcements.' ) ); ?></p>

		<div class="sc-blog-toolbar">
			<div class="sc-blog-cats" role="group" aria-label="<?php echo esc_attr( sc_t( '按分类筛选文章', 'Filter posts by category' ) ); ?>">
				<button type="button" class="sc-blog-cat is-active" data-blog-cat="all"><?php echo esc_html( sc_t( '全部', 'All' ) ); ?></button>
				<button type="button" class="sc-blog-cat" data-blog-cat="Technology"><?php echo esc_html( sc_t( '技术架构', 'Technology' ) ); ?></button>
				<button type="button" class="sc-blog-cat" data-blog-cat="Product"><?php echo esc_html( sc_t( '产品发布', 'Product' ) ); ?></button>
				<button type="button" class="sc-blog-cat" data-blog-cat="Case"><?php echo esc_html( sc_t( '实战案例', 'Case' ) ); ?></button>
			</div>

			<div class="sc-view-switch" role="group" aria-label="<?php echo esc_attr( sc_t( '切换展示样式', 'Switch layout' ) ); ?>">
				<button type="button" class="sc-view-btn <?php echo 'grid' === $default_layout ? 'is-active' : ''; ?>" data-blog-view="grid" title="<?php echo esc_attr( sc_t( '卡片画廊视图', 'Card grid view' ) ); ?>">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
					<span><?php echo esc_html( sc_t( '卡片', 'Cards' ) ); ?></span>
				</button>
				<button type="button" class="sc-view-btn <?php echo 'list' === $default_layout ? 'is-active' : ''; ?>" data-blog-view="list" title="<?php echo esc_attr( sc_t( '极简列表视图', 'List view' ) ); ?>">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
					<span><?php echo esc_html( sc_t( '列表', 'List' ) ); ?></span>
				</button>
			</div>
		</div>
	</div>

	<div class="sc-framed sc-blog-wrap">
		<?php if ( have_posts() ) : ?>
			<div class="sc-blog-feed" id="sc-blogFeed" data-layout="<?php echo esc_attr( $default_layout ); ?>">
				<?php
				$idx = 0;
				while ( have_posts() ) :
					the_post();
					$pid      = function_exists( 'get_the_ID' ) ? get_the_ID() : 0;
					$pal      = $cover_palettes[ $idx % count( $cover_palettes ) ];
					$cat_key  = $pal['cat'];
					$cat_disp = sc_t( $pal['cat_zh'], $pal['cat'] );
					if ( $pid && function_exists( 'get_the_category' ) ) {
						$cats = get_the_category( $pid );
						if ( ! empty( $cats ) ) {
							$cat_disp = $cats[0]->name;
							$cat_key  = $cats[0]->slug ?: $pal['cat'];
						}
					}
					$thumb_url = ( $pid && function_exists( 'get_the_post_thumbnail_url' ) ) ? get_the_post_thumbnail_url( $pid, 'large' ) : '';
					$views     = sc_get_post_views( $pid, $demo_views[ $idx % count( $demo_views ) ] );
					$idx++;
					?>
					<a <?php post_class( 'sc-bcard' ); ?> href="<?php the_permalink(); ?>" data-post-cat="<?php echo esc_attr( $cat_key ); ?>">
						<div class="sc-bcard__cover sc-bcard__cover--<?php echo esc_attr( $pal['pat'] ); ?>" style="background-color:<?php echo esc_attr( $pal['bg'] ); ?>">
							<?php if ( $thumb_url ) : ?>
								<img src="<?php echo esc_url( $thumb_url ); ?>" alt="" loading="lazy">
							<?php else : ?>
								<div class="sc-bcard__cover-text">
									<span><?php echo esc_html( sc_t( $pal['short_zh'], $pal['short'] ) ); ?></span>
								</div>
							<?php endif; ?>
						</div>

						<div class="sc-bcard__body">
							<span class="sc-bcard__tag"># <?php echo esc_html( $cat_disp ); ?></span>
							<h2 class="sc-bcard__title"><?php the_title(); ?></h2>
							<p class="sc-bcard__excerpt"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
							<div class="sc-bcard__meta">
								<span><?php echo esc_html( get_the_author() ); ?></span>
								<span>·</span>
								<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'Y/m/d' ) ); ?></time>
								<span>·</span>
								<span class="sc-bcard__views">
									<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M2 12s3.8-6.5 10-6.5S22 12 22 12s-3.8 6.5-10 6.5S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
									<?php echo esc_html( sc_format_views( $views ) ); ?>
								</span>
							</div>
						</div>
					</a>
				<?php endwhile; ?>
			</div>
		<?php else : ?>
			<div class="sc-empty-box">
				<p><?php echo esc_html( sc_t( '还没有发布文章，去 WordPress 后台写第一篇吧。', 'No posts published yet.' ) ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
