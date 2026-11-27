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

/* ==========================================================================
   多币种切换（CNY / USD）：中文默认 CNY，英文默认 USD，支持顶栏手动切换
   ========================================================================== */

function sc_usd_rate() {
	if ( function_exists( 'sc_content' ) ) {
		$c = sc_content();
		if ( ! empty( $c['usd_rate'] ) && is_numeric( $c['usd_rate'] ) && (float) $c['usd_rate'] > 0 ) {
			return (float) $c['usd_rate'];
		}
	}
	return 7.2;
}

function sc_current_currency() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( isset( $_GET['currency'] ) ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$want = strtoupper( sanitize_key( wp_unslash( $_GET['currency'] ) ) );
		if ( in_array( $want, array( 'CNY', 'USD' ), true ) ) {
			return $want;
		}
	}
	if ( isset( $_COOKIE['skillcraft_currency'] ) ) {
		$c = strtoupper( sanitize_key( wp_unslash( $_COOKIE['skillcraft_currency'] ) ) );
		if ( in_array( $c, array( 'CNY', 'USD' ), true ) ) {
			return $c;
		}
	}
	// 默认规则：中文显示 CNY，英文显示 USD
	return ( 'en' === sc_current_lang() ) ? 'USD' : 'CNY';
}

/** 将中文人民币价格文案（如 ¥299 / ¥199/月 / 免费）转换为指定币种文案 */
function sc_convert_price( $raw_price, $currency = '' ) {
	$currency = $currency ? strtoupper( $currency ) : sc_current_currency();
	$str      = trim( (string) $raw_price );
	if ( '' === $str ) {
		return '';
	}
	if ( false !== stripos( $str, '免费' ) || false !== stripos( $str, 'free' ) ) {
		return ( 'USD' === $currency || 'en' === sc_current_lang() ) ? 'Free' : '免费';
	}
	$is_monthly = ( false !== strpos( $str, '/月' ) || false !== stripos( $str, '/mo' ) );
	$is_yearly  = ( false !== strpos( $str, '/年' ) || false !== stripos( $str, '/yr' ) );

	if ( ! preg_match( '/([0-9]+(?:\.[0-9]+)?)/', $str, $m ) ) {
		return $str;
	}
	$num = (float) $m[1];

	if ( 'USD' === $currency ) {
		$usd = round( $num / sc_usd_rate() );
		if ( $usd < 1 ) {
			$usd = 1;
		}
		$suffix = $is_monthly ? '/mo' : ( $is_yearly ? '/yr' : '' );
		return '$' . $usd . $suffix;
	}

	// CNY 输出
	$clean_num = ( floor( $num ) == $num ) ? (string) (int) $num : rtrim( rtrim( number_format( $num, 2, '.', '' ), '0' ), '.' );
	$suffix    = $is_monthly ? ( 'en' === sc_current_lang() ? '/mo' : '/月' ) : ( $is_yearly ? ( 'en' === sc_current_lang() ? '/yr' : '/年' ) : '' );
	return '¥' . $clean_num . $suffix;
}

/** 输出支持前端一键无刷新切换币种的价格标签 */
function sc_price_html( $raw_price ) {
	$cny = sc_convert_price( $raw_price, 'CNY' );
	$usd = sc_convert_price( $raw_price, 'USD' );
	$cur = sc_current_currency();
	$val = ( 'USD' === $cur ) ? $usd : $cny;
	return '<span class="sc-price-val" data-price-cny="' . esc_attr( $cny ) . '" data-price-usd="' . esc_attr( $usd ) . '">' . esc_html( $val ) . '</span>';
}
