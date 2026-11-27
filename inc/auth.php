<?php
/**
 * 认证与用户中心（原生 WordPress 认证，无插件依赖）。
 *
 * @package SkillCraft
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sc_page_url( $slug, $template = '' ) {
	static $cache = array();
	$key = $slug . '|' . $template;
	if ( isset( $cache[ $key ] ) ) {
		return $cache[ $key ];
	}

	$url = '';
	if ( $template && function_exists( 'get_posts' ) ) {
		$ids = get_posts( array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'numberposts' => 1,
			'fields'      => 'ids',
			'meta_key'    => '_wp_page_template',
			'meta_value'  => $template,
		) );
		if ( ! empty( $ids ) ) {
			$url = get_permalink( $ids[0] );
		}
	}
	if ( ! $url && function_exists( 'get_page_by_path' ) ) {
		$page = get_page_by_path( $slug );
		if ( $page && ! empty( $page->ID ) ) {
			$url = get_permalink( $page->ID );
		}
	}
	if ( ! $url ) {
		$url = home_url( '/' . trim( $slug, '/' ) . '/' );
	}
	$cache[ $key ] = $url;
	return $url;
}

function sc_login_url( $redirect = '' ) {
	$url = sc_page_url( 'login', 'template-login.php' );
	if ( $redirect ) {
		$url = add_query_arg( 'redirect_to', $redirect, $url );
	}
	return $url;
}

function sc_signup_url() {
	return sc_page_url( 'signup', 'template-signup.php' );
}

function sc_account_url( $tab = '' ) {
	$url = sc_page_url( 'account', 'template-account.php' );
	if ( $tab && 'overview' !== $tab ) {
		$url = add_query_arg( 'tab', $tab, $url );
	}
	return $url;
}

function sc_logout_url() {
	return function_exists( 'wp_logout_url' ) ? wp_logout_url( home_url( '/' ) ) : home_url( '/' );
}

function sc_user_initials( $user ) {
	$name = '';
	if ( is_object( $user ) ) {
		$name = ! empty( $user->display_name ) ? trim( (string) $user->display_name ) : trim( (string) ( $user->user_login ?? '' ) );
	}
	if ( '' === $name ) {
		return 'U';
	}
	return function_exists( 'mb_substr' ) ? mb_strtoupper( mb_substr( $name, 0, 1, 'UTF-8' ), 'UTF-8' ) : strtoupper( substr( $name, 0, 1 ) );
}

function sc_auth_notice( $set = null ) {
	static $notice = null;
	if ( null !== $set ) {
		$notice = $set;
	}
	return $notice;
}

function sc_handle_auth_requests() {
	if ( is_admin() ) {
		return;
	}

	if ( function_exists( 'is_page_template' ) ) {
		if ( is_page_template( 'template-account.php' ) && ! is_user_logged_in() ) {
			wp_safe_redirect( sc_login_url( sc_account_url() ) );
			exit;
		}
		if ( ( is_page_template( 'template-login.php' ) || is_page_template( 'template-signup.php' ) ) && is_user_logged_in() ) {
			wp_safe_redirect( sc_account_url() );
			exit;
		}
	}

	if ( 'POST' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) || empty( $_POST['sc_auth_action'] ) ) {
		return;
	}

	$action = sanitize_key( wp_unslash( $_POST['sc_auth_action'] ) );
	$nonce  = isset( $_POST['sc_auth_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['sc_auth_nonce'] ) ) : '';

	if ( ! wp_verify_nonce( $nonce, 'sc_auth_' . $action ) ) {
		sc_auth_notice( array(
			'type' => 'error',
			'text' => sc_t( '安全校验已过期，请刷新页面后重试。', 'Security check expired. Please refresh and try again.' ),
		) );
		return;
	}

	if ( 'login' === $action ) {
		$login    = trim( sanitize_text_field( wp_unslash( $_POST['sc_login'] ?? '' ) ) );
		$password = (string) wp_unslash( $_POST['sc_password'] ?? '' );
		$remember = ! empty( $_POST['sc_remember'] );
		$redirect = esc_url_raw( wp_unslash( $_POST['redirect_to'] ?? '' ) );

		if ( '' === $login || '' === $password ) {
			sc_auth_notice( array( 'type' => 'error', 'text' => sc_t( '请输入账号和密码。', 'Please enter your credentials.' ) ) );
			return;
		}
		if ( is_email( $login ) ) {
			$u = get_user_by( 'email', $login );
			if ( $u ) {
				$login = $u->user_login;
			}
		}
		$user = wp_signon( array( 'user_login' => $login, 'user_password' => $password, 'remember' => $remember ), is_ssl() );
		if ( is_wp_error( $user ) ) {
			sc_auth_notice( array( 'type' => 'error', 'text' => sc_t( '账号或密码不正确，请检查后重试。', 'Invalid email or password.' ) ) );
			return;
		}
		wp_safe_redirect( $redirect ? wp_validate_redirect( $redirect, sc_account_url() ) : sc_account_url() );
		exit;
	}

	if ( 'signup' === $action ) {
		if ( ! get_option( 'users_can_register' ) ) {
			sc_auth_notice( array( 'type' => 'error', 'text' => sc_t( '站点暂未开放自助注册，请联系管理员。', 'Registration is currently disabled.' ) ) );
			return;
		}
		$name     = trim( sanitize_text_field( wp_unslash( $_POST['sc_name'] ?? '' ) ) );
		$email    = trim( sanitize_email( wp_unslash( $_POST['sc_email'] ?? '' ) ) );
		$password = (string) wp_unslash( $_POST['sc_password'] ?? '' );

		if ( '' === $name || ! is_email( $email ) || strlen( $password ) < 8 ) {
			sc_auth_notice( array( 'type' => 'error', 'text' => sc_t( '请填写有效昵称、邮箱，并设置至少 8 位密码。', 'Provide a valid name, email, and an 8+ character password.' ) ) );
			return;
		}
		if ( email_exists( $email ) ) {
			sc_auth_notice( array( 'type' => 'error', 'text' => sc_t( '该邮箱已被注册，请直接登录。', 'Email already registered. Please sign in.' ) ) );
			return;
		}
		$base = sanitize_user( current( explode( '@', $email ) ), true ) ?: 'creator';
		$uname = $base;
		$i = 1;
		while ( username_exists( $uname ) ) {
			$uname = $base . $i++;
		}
		$uid = wp_create_user( $uname, $password, $email );
		if ( is_wp_error( $uid ) ) {
			sc_auth_notice( array( 'type' => 'error', 'text' => sc_t( '注册失败，请稍后再试。', 'Registration failed. Please try again.' ) ) );
			return;
		}
		wp_update_user( array( 'ID' => $uid, 'display_name' => $name, 'nickname' => $name ) );
		update_user_meta( $uid, 'skillcraft_lang', sc_current_lang() );
		wp_set_current_user( $uid );
		wp_set_auth_cookie( $uid, true, is_ssl() );
		wp_safe_redirect( add_query_arg( 'welcome', '1', sc_account_url() ) );
		exit;
	}

	if ( 'update_profile' === $action && is_user_logged_in() ) {
		$user  = wp_get_current_user();
		$name  = trim( sanitize_text_field( wp_unslash( $_POST['sc_display_name'] ?? '' ) ) );
		$email = trim( sanitize_email( wp_unslash( $_POST['sc_email'] ?? '' ) ) );
		$bio   = trim( sanitize_textarea_field( wp_unslash( $_POST['sc_bio'] ?? '' ) ) );
		$lang  = sanitize_key( wp_unslash( $_POST['sc_lang'] ?? 'zh' ) );

		if ( '' === $name || ! is_email( $email ) ) {
			sc_auth_notice( array( 'type' => 'error', 'text' => sc_t( '昵称与有效邮箱为必填项。', 'Name and valid email are required.' ) ) );
			return;
		}
		$exists = email_exists( $email );
		if ( $exists && (int) $exists !== (int) $user->ID ) {
			sc_auth_notice( array( 'type' => 'error', 'text' => sc_t( '该邮箱已被其他账户使用。', 'Email is already in use.' ) ) );
			return;
		}
		wp_update_user( array( 'ID' => $user->ID, 'display_name' => $name, 'user_email' => $email, 'description' => $bio ) );
		if ( array_key_exists( $lang, sc_langs() ) ) {
			update_user_meta( $user->ID, 'skillcraft_lang', $lang );
			if ( ! headers_sent() ) {
				setcookie( 'skillcraft_lang', $lang, time() + YEAR_IN_SECONDS, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), false );
			}
		}
		wp_safe_redirect( add_query_arg( 'saved', 'profile', sc_account_url( 'profile' ) ) );
		exit;
	}

	if ( 'update_password' === $action && is_user_logged_in() ) {
		$user = wp_get_current_user();
		$cur  = (string) wp_unslash( $_POST['sc_current_password'] ?? '' );
		$new  = (string) wp_unslash( $_POST['sc_new_password'] ?? '' );
		$cfm  = (string) wp_unslash( $_POST['sc_confirm_password'] ?? '' );

		if ( ! wp_check_password( $cur, $user->user_pass, $user->ID ) ) {
			sc_auth_notice( array( 'type' => 'error', 'text' => sc_t( '当前密码不正确。', 'Current password is incorrect.' ) ) );
			return;
		}
		if ( strlen( $new ) < 8 || $new !== $cfm ) {
			sc_auth_notice( array( 'type' => 'error', 'text' => sc_t( '新密码需至少 8 位且两次输入一致。', 'New password must be 8+ characters and match.' ) ) );
			return;
		}
		wp_set_password( $new, $user->ID );
		wp_set_current_user( $user->ID );
		wp_set_auth_cookie( $user->ID, true, is_ssl() );
		wp_safe_redirect( add_query_arg( 'saved', 'password', sc_account_url( 'security' ) ) );
		exit;
	}
}
add_action( 'template_redirect', 'sc_handle_auth_requests', 5 );
