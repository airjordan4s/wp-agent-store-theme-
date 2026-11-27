<?php
/**
 * 极简吸顶头部（72px）
 *
 * @package SkillCraft
 */
?>
<!doctype html>
<html lang="<?php echo esc_attr( 'en' === sc_current_lang() ? 'en' : 'zh-CN' ); ?>">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#f6f6f7">
<?php wp_head(); ?>
</head>
<body <?php body_class( 'sc-body' ); ?>>
<?php wp_body_open(); ?>

<header class="sc-header" id="sc-header">
	<div class="sc-header__inner">
		<a class="sc-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<span class="sc-brand__mark" aria-hidden="true">
				<img src="<?php echo esc_url( SKILLCRAFT_URI . '/assets/img/mascot/mascot-head.png' ); ?>" alt="" width="40" height="24" class="sc-brand__img sc-brand__img--head">
			</span>
			<span class="sc-brand__name"><?php echo esc_html( sc_site_name() ); ?></span>
		</a>

		<nav class="sc-nav" aria-label="<?php echo esc_attr( sc_t( '主导航', 'Primary navigation' ) ); ?>">
			<?php
			if ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'primary' ) && function_exists( 'wp_nav_menu' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'items_wrap'     => '%3$s',
					'depth'          => 1,
					'fallback_cb'    => false,
				) );
			} else {
				?>
				<a class="sc-nav__link" href="<?php echo esc_url( home_url( '/skills/' ) ); ?>"><?php echo esc_html( sc_t( '作品集市', 'Catalog' ) ); ?></a>
				<a class="sc-nav__link" href="<?php echo esc_url( home_url( '/faq/' ) ); ?>"><?php echo esc_html( sc_t( '常见问题', 'FAQ' ) ); ?></a>
				<a class="sc-nav__link" href="<?php echo esc_url( home_url( '/changelog/' ) ); ?>"><?php echo esc_html( sc_t( '更新日志', 'Changelog' ) ); ?></a>
				<?php
			}
			?>

			<div class="sc-lang-drop" id="sc-langDrop">
				<button type="button" class="sc-lang-globe" id="sc-langSwitch"
				        aria-haspopup="menu" aria-expanded="false"
				        aria-label="<?php echo esc_attr( sc_t( '选择语言', 'Select language' ) ); ?>">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
						<circle cx="12" cy="12" r="9.5" stroke="currentColor" stroke-width="1.6"/>
						<path d="M2.8 12h18.4M12 2.5c2.6 2.7 4 6 4 9.5s-1.4 6.8-4 9.5c-2.6-2.7-4-6-4-9.5s1.4-6.8 4-9.5z" stroke="currentColor" stroke-width="1.5"/>
					</svg>
				</button>
				<div class="sc-lang-menu" id="sc-langMenu" role="menu" hidden>
					<button type="button" class="sc-lang-menu__item <?php echo 'zh' === sc_current_lang() ? 'is-active' : ''; ?>" role="menuitem" data-lang="zh">
						<span>简体中文</span>
						<?php if ( 'zh' === sc_current_lang() ) : ?><em>✓</em><?php endif; ?>
					</button>
					<button type="button" class="sc-lang-menu__item <?php echo 'en' === sc_current_lang() ? 'is-active' : ''; ?>" role="menuitem" data-lang="en">
						<span>English</span>
						<?php if ( 'en' === sc_current_lang() ) : ?><em>✓</em><?php endif; ?>
					</button>
				</div>
			</div>

			<?php if ( is_user_logged_in() ) :
				$u = wp_get_current_user();
				?>
				<a class="sc-user-pill" href="<?php echo esc_url( sc_account_url() ); ?>">
					<span class="sc-avatar"><?php echo esc_html( sc_user_initials( $u ) ); ?></span>
					<span><?php echo esc_html( $u->display_name ); ?></span>
				</a>
			<?php else : ?>
				<a class="sc-btn sc-btn--dark sc-header__cta" href="<?php echo esc_url( sc_login_url() ); ?>">
					<?php echo esc_html( sc_t( '登录', 'Sign in' ) ); ?>
				</a>
			<?php endif; ?>
		</nav>

		<button type="button" class="sc-burger" id="sc-burger" aria-expanded="false" aria-controls="sc-drawer" aria-label="<?php echo esc_attr( sc_t( '打开菜单', 'Toggle menu' ) ); ?>">
			<span></span><span></span>
		</button>
	</div>
</header>

<div class="sc-drawer" id="sc-drawer" hidden>
	<?php
	if ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'primary' ) && function_exists( 'wp_nav_menu' ) ) {
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'container'      => false,
			'items_wrap'     => '%3$s',
			'depth'          => 1,
			'fallback_cb'    => false,
		) );
	} else {
		?>
		<a href="<?php echo esc_url( home_url( '/skills/' ) ); ?>"><?php echo esc_html( sc_t( '作品集市', 'Catalog' ) ); ?></a>
		<a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>"><?php echo esc_html( sc_t( '常见问题', 'FAQ' ) ); ?></a>
		<a href="<?php echo esc_url( home_url( '/changelog/' ) ); ?>"><?php echo esc_html( sc_t( '更新日志', 'Changelog' ) ); ?></a>
		<?php
	}
	?>
	<div class="sc-drawer__actions">
		<?php if ( is_user_logged_in() ) : ?>
			<a class="sc-btn sc-btn--dark sc-btn--block" href="<?php echo esc_url( sc_account_url() ); ?>"><?php echo esc_html( sc_t( '进入用户中心', 'Account' ) ); ?></a>
		<?php else : ?>
			<a class="sc-btn sc-btn--dark sc-btn--block" href="<?php echo esc_url( sc_login_url() ); ?>"><?php echo esc_html( sc_t( '登录', 'Sign in' ) ); ?></a>
			<a class="sc-btn sc-btn--outline sc-btn--block" href="<?php echo esc_url( sc_signup_url() ); ?>"><?php echo esc_html( sc_t( '创建账户', 'Create account' ) ); ?></a>
		<?php endif; ?>
	</div>
</div>

<main class="sc-main" id="sc-main">
