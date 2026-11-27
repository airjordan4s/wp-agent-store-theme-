<?php
/**
 * Template Name: SkillCraft 登录 / Sign In
 *
 * @package SkillCraft
 */

get_header();

$notice      = sc_auth_notice();
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
$redirect_to = isset( $_GET['redirect_to'] ) ? esc_url_raw( wp_unslash( $_GET['redirect_to'] ) ) : '';
// phpcs:ignore WordPress.Security.NonceVerification.Missing
$posted_user = isset( $_POST['sc_login'] ) ? sanitize_text_field( wp_unslash( $_POST['sc_login'] ) ) : '';
?>

<section class="sc-band sc-auth">
	<div class="sc-framed sc-auth__grid">
		<div class="sc-auth__aside sc-auth__aside--visual">
			<div class="sc-auth__mascot" aria-hidden="true">
				<img src="<?php echo esc_url( SKILLCRAFT_URI . '/assets/img/mascot/mascot-yay.png' ); ?>" alt="" width="116" height="116">
			</div>
			<span class="sc-kicker">/ <?php echo esc_html( sc_t( '会员中心 · MEMBER CONSOLE', 'MEMBER CONSOLE' ) ); ?></span>
			<h1><?php echo wp_kses( sprintf( sc_t( '欢迎回到 %s', 'Welcome back to %s' ), esc_html( sc_site_name() ) ), array( 'br' => array() ) ); ?></h1>
			<p><?php echo esc_html( sc_t( '登录后即可查看已购工作台、MCP 与 Skill 授权码，随时复制启动指令并获取版本更新。', 'Sign in to access your purchased Workbenches, MCP plugins, and Skills with instant license keys.' ) ); ?></p>
			<ul class="sc-custom-perks sc-auth__perks">
				<li><strong>24/7</strong><span><?php echo esc_html( sc_t( '已购授权码与安装包随时找回重装', 'Access license keys & installers anytime' ) ); ?></span></li>
				<li><strong>SYNC</strong><span><?php echo esc_html( sc_t( '终端一行命令同步全部已购工作台', 'One-line CLI sync across devices' ) ); ?></span></li>
				<li><strong>FREE</strong><span><?php echo esc_html( sc_t( '买断版永久享官方后续版本免费更新', 'Free lifetime updates on one-time packs' ) ); ?></span></li>
			</ul>
		</div>

		<div class="sc-auth__panel">
			<h2><?php echo esc_html( sc_t( '登录账户', 'Sign in' ) ); ?></h2>
			<p class="sc-auth__sub"><?php echo esc_html( sc_t( '使用工作邮箱或用户名登录。', 'Continue with your email or username.' ) ); ?></p>

			<?php if ( $notice ) : ?>
				<div class="sc-alert sc-alert--<?php echo esc_attr( $notice['type'] ); ?>" role="alert">
					<?php echo esc_html( $notice['text'] ); ?>
				</div>
			<?php endif; ?>

			<form class="sc-form" method="post" action="">
				<?php wp_nonce_field( 'sc_auth_login', 'sc_auth_nonce' ); ?>
				<input type="hidden" name="sc_auth_action" value="login">
				<?php if ( $redirect_to ) : ?>
					<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>">
				<?php endif; ?>

				<div class="sc-field">
					<label for="sc-login"><?php echo esc_html( sc_t( '邮箱或用户名', 'Email or username' ) ); ?></label>
					<input class="sc-input" id="sc-login" name="sc_login" type="text" required autocomplete="username" placeholder="name@studio.dev" value="<?php echo esc_attr( $posted_user ); ?>">
				</div>

				<div class="sc-field">
					<label for="sc-pass"><?php echo esc_html( sc_t( '密码', 'Password' ) ); ?></label>
					<div class="sc-input-wrap">
						<input class="sc-input" id="sc-pass" name="sc_password" type="password" required autocomplete="current-password" placeholder="<?php echo esc_attr( sc_t( '输入你的密码', 'Enter your password' ) ); ?>">
						<button type="button" class="sc-eye" data-toggle-pass="sc-pass"><?php echo esc_html( sc_t( '显示', 'Show' ) ); ?></button>
					</div>
				</div>

				<label class="sc-check">
					<input type="checkbox" name="sc_remember" value="1" checked>
					<span><?php echo esc_html( sc_t( '保持登录状态 14 天', 'Keep me signed in for 14 days' ) ); ?></span>
				</label>

				<button type="submit" class="sc-btn sc-btn--dark sc-btn--block sc-btn--lg">
					<?php echo esc_html( sc_t( '登录', 'Sign in' ) ); ?>
				</button>
			</form>

			<p class="sc-auth__foot">
				<?php echo esc_html( sc_t( '还没有账号？', "Don't have an account?" ) ); ?>
				<a href="<?php echo esc_url( sc_signup_url() ); ?>"><?php echo esc_html( sc_t( '免费创建账户 →', 'Create one free →' ) ); ?></a>
			</p>
		</div>
	</div>
</section>

<?php
get_footer();
