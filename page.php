<?php
/**
 * 通用页面模板
 *
 * @package SkillCraft
 */

get_header();
?>

<section class="sc-band sc-article">
	<div class="sc-framed sc-article__wrap">
		<?php while ( have_posts() ) : the_post(); ?>
			<header class="sc-article__head">
				<h1><?php the_title(); ?></h1>
			</header>
			<div class="sc-prose">
				<?php the_content(); ?>
			</div>
		<?php endwhile; ?>
	</div>
</section>

<?php
get_footer();
