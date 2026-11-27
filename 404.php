<?php
/**
 * 404 页面
 *
 * @package SkillCraft
 */

get_header();
?>

<section class="sc-band sc-auth">
	<div class="sc-framed sc-empty-box">
		<span class="sc-kicker">/ 404</span>
		<h1><?php echo esc_html( sc_t( '页面不存在', 'Page not found' ) ); ?></h1>
		<p><?php echo esc_html( sc_t( '你访问的地址可能已移动或删除。', 'The page you are looking for may have moved or been removed.' ) ); ?></p>
		<a class="sc-btn sc-btn--dark" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( sc_t( '返回首页', 'Back home' ) ); ?></a>
	</div>
</section>

<?php
get_footer();
