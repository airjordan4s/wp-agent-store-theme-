<?php
/**
 * 轻量双语切换：?lang= > Cookie > 用户资料 > 默认 zh。
 *
 * @package SkillCraft
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sc_langs() {
	return array(
		'zh' => '简体中文',
		'en' => 'English',
	);
}

function sc_current_lang() {
	$langs = array_keys( sc_langs() );

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( isset( $_GET['lang'] ) ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$want = sanitize_key( wp_unslash( $_GET['lang'] ) );
		if ( in_array( $want, $langs, true ) ) {
			return $want;
		}
	}

	if ( isset( $_COOKIE['skillcraft_lang'] ) ) {
		$c = sanitize_key( wp_unslash( $_COOKIE['skillcraft_lang'] ) );
		if ( in_array( $c, $langs, true ) ) {
			return $c;
		}
	}

	if ( is_user_logged_in() ) {
		$u = get_user_meta( get_current_user_id(), 'skillcraft_lang', true );
		if ( $u && in_array( $u, $langs, true ) ) {
			return $u;
		}
	}

	return 'zh';
}

function sc_maybe_set_lang_cookie() {
	if ( is_admin() || headers_sent() ) {
		return;
	}
	$want = sc_current_lang();
	if ( ! isset( $_COOKIE['skillcraft_lang'] ) || $_COOKIE['skillcraft_lang'] !== $want ) {
		setcookie( 'skillcraft_lang', $want, time() + YEAR_IN_SECONDS, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), false );
	}
}
add_action( 'init', 'sc_maybe_set_lang_cookie', 2 );

function sc_t( $zh, $en = '' ) {
	if ( '' === $en ) {
		return $zh;
	}
	return ( 'en' === sc_current_lang() ) ? $en : $zh;
}
