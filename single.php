<?php
/**
 * 文章详情页（浏览次数 + 一键分享 + 上一篇/下一篇导航 + 后台可控评论区）
 *
 * @package SkillCraft
 */

get_header();
?>

<section class="sc-band sc-article">
	<div class="sc-framed sc-article__wrap">
		<?php while ( have_posts() ) : the_post();
			$pid       = function_exists( 'get_the_ID' ) ? get_the_ID() : 0;
			$views     = sc_get_post_views( $pid, 2480 );
			$post_url  = function_exists( 'get_permalink' ) ? get_permalink() : home_url( '/blog/' );
			$post_title = function_exists( 'get_the_title' ) ? get_the_title() : '';
			$prev_post = function_exists( 'get_previous_post' ) ? get_previous_post() : null;
			$next_post = function_exists( 'get_next_post' ) ? get_next_post() : null;
			?>
			<header class="sc-article__head">
				<a class="sc-kicker" href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">← <?php echo esc_html( sc_t( '返回博客', 'Back to Blog' ) ); ?></a>
				<h1><?php the_title(); ?></h1>
				<div class="sc-article__meta">
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'Y-m-d' ) ); ?></time>
					<span>·</span>
					<span><?php echo esc_html( get_the_author() ); ?></span>
					<span>·</span>
					<span class="sc-bcard__views">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M2 12s3.8-6.5 10-6.5S22 12 22 12s-3.8 6.5-10 6.5S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
						<?php echo esc_html( sc_format_views( $views ) . ' ' . sc_t( '次浏览', 'views' ) ); ?>
					</span>
				</div>
			</header>

			<div class="sc-prose">
				<?php the_content(); ?>
			</div>

			<!-- 一键分享条 -->
			<div class="sc-share-bar">
				<span class="sc-share-bar__label"><?php echo esc_html( sc_t( '分享本文', 'Share this post' ) ); ?></span>
				<div class="sc-share-bar__btns">
					<button type="button" class="sc-share-btn" data-share-copy="<?php echo esc_attr( $post_url ); ?>">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
						<span><?php echo esc_html( sc_t( '复制链接', 'Copy link' ) ); ?></span>
					</button>
					<a class="sc-share-btn" target="_blank" rel="noopener noreferrer" href="<?php echo esc_url( 'https://twitter.com/intent/tweet?text=' . rawurlencode( $post_title ) . '&url=' . rawurlencode( $post_url ) ); ?>">
						<span>X / Twitter</span>
					</a>
					<a class="sc-share-btn" target="_blank" rel="noopener noreferrer" href="<?php echo esc_url( 'https://service.weibo.com/share/share.php?title=' . rawurlencode( $post_title ) . '&url=' . rawurlencode( $post_url ) ); ?>">
						<span><?php echo esc_html( sc_t( '微博', 'Weibo' ) ); ?></span>
					</a>
				</div>
			</div>

			<!-- 上一篇 / 下一篇导航 -->
			<nav class="sc-post-nav" aria-label="<?php echo esc_attr( sc_t( '文章导航', 'Post navigation' ) ); ?>">
				<?php if ( $prev_post ) : ?>
					<a class="sc-post-nav__card" href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>">
						<span>← <?php echo esc_html( sc_t( '上一篇', 'Previous' ) ); ?></span>
						<strong><?php echo esc_html( get_the_title( $prev_post ) ); ?></strong>
					</a>
				<?php elseif ( ! function_exists( 'get_previous_post' ) ) : ?>
					<a class="sc-post-nav__card" href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">
						<span>← <?php echo esc_html( sc_t( '上一篇', 'Previous' ) ); ?></span>
						<strong><?php echo esc_html( sc_t( '用 Ed25519 非对称签名打造防 AI 绕过的软件授权体系', 'Building Anti-Bypass Software Licensing with Ed25519' ) ); ?></strong>
					</a>
				<?php else : ?>
					<div class="sc-post-nav__card sc-post-nav__card--empty">
						<span>← <?php echo esc_html( sc_t( '上一篇', 'Previous' ) ); ?></span>
						<strong><?php echo esc_html( sc_t( '已经是第一篇了', 'Start of archive' ) ); ?></strong>
					</div>
				<?php endif; ?>

				<?php if ( $next_post ) : ?>
					<a class="sc-post-nav__card sc-post-nav__card--next" href="<?php echo esc_url( get_permalink( $next_post ) ); ?>">
						<span><?php echo esc_html( sc_t( '下一篇', 'Next' ) ); ?> →</span>
						<strong><?php echo esc_html( get_the_title( $next_post ) ); ?></strong>
					</a>
				<?php elseif ( ! function_exists( 'get_next_post' ) ) : ?>
					<a class="sc-post-nav__card sc-post-nav__card--next" href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">
						<span><?php echo esc_html( sc_t( '下一篇', 'Next' ) ); ?> →</span>
						<strong><?php echo esc_html( sc_t( '1 个人如何用 AI 工作台同时掌管 6 个跨境独立站', 'How One Operator Runs 6 Storefronts with AI Workbenches' ) ); ?></strong>
					</a>
				<?php else : ?>
					<div class="sc-post-nav__card sc-post-nav__card--next sc-post-nav__card--empty">
						<span><?php echo esc_html( sc_t( '下一篇', 'Next' ) ); ?> →</span>
						<strong><?php echo esc_html( sc_t( '已经是最新一篇', 'Latest post' ) ); ?></strong>
					</div>
				<?php endif; ?>
			</nav>

			<!-- 后台可控评论区（在「SkillCraft 内容」一键开关） -->
			<?php if ( sc_section_enabled( 'blog_comments' ) ) : ?>
				<div class="sc-comments-wrap" id="comments">
					<?php
					if ( function_exists( 'comments_template' ) && defined( 'ABSPATH' ) ) :
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
					else :
						?>
						<h3><?php echo esc_html( sc_t( '评论讨论', 'Comments' ) ); ?></h3>
						<p class="sc-comments__hint"><?php echo esc_html( sc_t( '评论区可在 WordPress 后台「SkillCraft 内容 → 博客文章开启评论区」一键打开或关闭。', 'Comments can be toggled on or off anytime in WordPress Admin.' ) ); ?></p>
						<form class="sc-form sc-form--wide" onsubmit="event.preventDefault();this.querySelector('button').textContent='<?php echo esc_attr( sc_t( '已提交（WP 环境下自动入库）✓', 'Submitted ✓' ) ); ?>'">
							<div class="sc-field">
								<label for="sc-demo-comment"><?php echo esc_html( sc_t( '发表你的看法', 'Leave a comment' ) ); ?></label>
								<textarea class="sc-input sc-textarea" id="sc-demo-comment" rows="3" placeholder="<?php echo esc_attr( sc_t( '写下你的使用心得或问题...', 'Share your thoughts or questions...' ) ); ?>"></textarea>
							</div>
							<div>
								<button type="submit" class="sc-btn sc-btn--dark"><?php echo esc_html( sc_t( '发表评论', 'Post comment' ) ); ?></button>
							</div>
						</form>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		<?php endwhile; ?>
	</div>
</section>

<?php
get_footer();
