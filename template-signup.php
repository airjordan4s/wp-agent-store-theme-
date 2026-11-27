<?php
/**
 * Template Name: SkillCraft 注册 / Sign Up
 *
 * @package SkillCraft
 */

get_header();

$notice       = sc_auth_notice();
$can_register = (bool) get_option( 'users_can_register', 1 );
// phpcs:ignore WordPress.Security.NonceVerification.Missing
$posted_name  = isset( $_POST['sc_name'] ) ? sanitize_text_field( wp_unslash( $_POST['sc_name'] ) ) : '';
// phpcs:ignore WordPress.Security.NonceVerification.Missing
$posted_email = isset( $_POST['sc_email'] ) ? sanitize_email( wp_unslash( $_POST['sc_email'] ) ) : '';
?>

<section class="sc-band sc-auth">
	<div class="sc-framed sc-auth__grid">
		<div class="sc-auth__aside sc-auth__aside--visual">
			<div class="sc-auth__mascot" aria-hidden="true">
				<img src="<?php echo esc_url( SKILLCRAFT_URI . '/assets/img/mascot/mascot-heart.png' ); ?>" alt="" width="116" height="116">
			</div>
			<span class="sc-kicker">/ <?php echo esc_html( sc_t( '免费注册会员 · JOIN US', 'CREATE ACCOUNT' ) ); ?></span>
			<h1><?php echo esc_html( sc_t( '创建你的专属工作台账户', 'Create your Workbench account' ) ); ?></h1>
			<p><?php echo esc_html( sc_t( '注册后永久保存你的已购工作台、MCP 授权码与免费版本更新，换电脑也能一键同步。', 'Keep all your purchased Workbenches, license keys, and free updates synced across every device.' ) ); ?></p>
			<ul class="sc-custom-perks sc-auth__perks">
				<li><strong>01</strong><span><?php echo esc_html( sc_t( '订单与授权码自动归档，永不丢失', 'Orders & license keys saved permanently' ) ); ?></span></li>
				<li><strong>02</strong><span><?php echo esc_html( sc_t( '支持工作台、MCP 与 Skill 统一管理', 'Unified library for Workbenches, MCP & Skills' ) ); ?></span></li>
				<li><strong>03</strong><span><?php echo esc_html( sc_t( '享官方新品抢先体验与专属售后支持', 'Early access to new releases & support' ) ); ?></span></li>
			</ul>
		</div>

		<div class="sc-auth__panel">
			<h2><?php echo esc_html( sc_t( '创建会员账户', 'Create your account' ) ); ?></h2>
			<p class="sc-auth__sub"><?php echo esc_html( sc_t( '填写邮箱与密码，立即开启高效工作流。', 'Sign up in seconds to manage your Skill library.' ) ); ?></p>

			<?php if ( $notice ) : ?>
				<div class="sc-alert sc-alert--<?php echo esc_attr( $notice['type'] ); ?>" role="alert">
					<?php echo esc_html( $notice['text'] ); ?>
				</div>
			<?php endif; ?>

			<form class="sc-form" method="post" action="">
				<?php wp_nonce_field( 'sc_auth_signup', 'sc_auth_nonce' ); ?>
				<input type="hidden" name="sc_auth_action" value="signup">

				<div class="sc-field">
					<label for="sc-name"><?php echo esc_html( sc_t( '你的昵称 / 品牌名', 'Display name' ) ); ?></label>
					<input class="sc-input" id="sc-name" name="sc_name" type="text" required autocomplete="name" placeholder="<?php echo esc_attr( sc_t( '例如：林致远', 'Alex Lin' ) ); ?>" value="<?php echo esc_attr( $posted_name ); ?>">
				</div>

				<div class="sc-field">
					<label for="sc-email"><?php echo esc_html( sc_t( '工作邮箱', 'Work email' ) ); ?></label>
					<input class="sc-input" id="sc-email" name="sc_email" type="email" required autocomplete="email" placeholder="name@studio.dev" value="<?php echo esc_attr( $posted_email ); ?>">
				</div>

				<div class="sc-field">
					<label for="sc-signup-pass"><?php echo esc_html( sc_t( '设置密码', 'Password' ) ); ?></label>
					<div class="sc-input-wrap">
						<input class="sc-input" id="sc-signup-pass" name="sc_password" type="password" required minlength="8" autocomplete="new-password" placeholder="<?php echo esc_attr( sc_t( '至少 8 位字符', 'At least 8 characters' ) ); ?>">
						<button type="button" class="sc-eye" data-toggle-pass="sc-signup-pass"><?php echo esc_html( sc_t( '显示', 'Show' ) ); ?></button>
					</div>
				</div>

				<button type="submit" class="sc-btn sc-btn--dark sc-btn--block sc-btn--lg" <?php disabled( ! $can_register ); ?>>
					<?php echo esc_html( sc_t( '创建免费账户', 'Create free account' ) ); ?>
				</button>
			</form>

			<p class="sc-auth__foot">
				<?php echo esc_html( sc_t( '已有账户？', 'Already have an account?' ) ); ?>
				<a href="<?php echo esc_url( sc_login_url() ); ?>"><?php echo esc_html( sc_t( '立即登录 →', 'Sign in →' ) ); ?></a>
			</p>
		</div>
	</div>
</section>

<?php
get_footer();
