<?php
/**
 * SkillCraft 主题入口
 *
 * @package SkillCraft
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SKILLCRAFT_VERSION', '1.0.0' );
define( 'SKILLCRAFT_DIR', get_template_directory() );
define( 'SKILLCRAFT_URI', get_template_directory_uri() );

require_once SKILLCRAFT_DIR . '/inc/i18n.php';
require_once SKILLCRAFT_DIR . '/inc/data.php';
require_once SKILLCRAFT_DIR . '/inc/auth.php';
require_once SKILLCRAFT_DIR . '/inc/admin.php';
require_once SKILLCRAFT_DIR . '/inc/skills-admin.php';
require_once SKILLCRAFT_DIR . '/inc/orders.php';
require_once SKILLCRAFT_DIR . '/inc/license-api.php';
require_once SKILLCRAFT_DIR . '/inc/changelog-cpt.php';
require_once SKILLCRAFT_DIR . '/inc/seo.php';

function skillcraft_setup() {
	load_theme_textdomain( 'skillcraft', SKILLCRAFT_DIR . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-logo', array(
		'height'      => 48,
		'width'       => 180,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script',
	) );

	register_nav_menus( array(
		'primary' => __( '主导航 / Primary', 'skillcraft' ),
		'footer'  => __( '页脚链接 / Footer', 'skillcraft' ),
	) );
}
add_action( 'after_setup_theme', 'skillcraft_setup' );

function skillcraft_assets() {
	wp_enqueue_style(
		'skillcraft-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&family=Noto+Sans+SC:wght@400;500;600;700&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'skillcraft-main',
		SKILLCRAFT_URI . '/assets/css/main.css',
		array( 'skillcraft-fonts' ),
		SKILLCRAFT_VERSION
	);

	wp_enqueue_script(
		'skillcraft-main',
		SKILLCRAFT_URI . '/assets/js/main.js',
		array(),
		SKILLCRAFT_VERSION,
		true
	);

	wp_localize_script( 'skillcraft-main', 'SkillCraft', array(
		'lang'       => sc_current_lang(),
		'homeUrl'    => trailingslashit( home_url( '/' ) ),
		'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
		'orderNonce' => wp_create_nonce( 'sc_order_nonce' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'skillcraft_assets' );

/** 注册自定义文章类型：Skill 商品（后台可直接发布） */
function skillcraft_register_post_types() {
	register_post_type( 'sc_skill', array(
		'labels' => array(
			'name'          => __( '工作台与商品', 'skillcraft' ),
			'singular_name' => __( '工作台商品', 'skillcraft' ),
			'add_new_item'  => __( '上架新工作台 / 插件', 'skillcraft' ),
		),
		'public'        => true,
		'has_archive'   => true,
		'menu_icon'     => 'dashicons-store',
		'supports'      => array( 'title', 'editor', 'excerpt', 'custom-fields' ),
		'rewrite'       => array( 'slug' => 'skills' ),
		'show_in_rest'  => true,
	) );
}
add_action( 'init', 'skillcraft_register_post_types' );

/** 激活主题时自动创建核心页面 */
function skillcraft_after_switch() {
	$pages = array(
		array( 'slug' => 'home',      'title' => '首页',     'tpl' => '' ),
		array( 'slug' => 'blog',      'title' => '博客',     'tpl' => '' ),
		array( 'slug' => 'faq',       'title' => '常见问题',   'tpl' => 'template-faq.php' ),
		array( 'slug' => 'changelog', 'title' => '更新日志',   'tpl' => 'template-changelog.php' ),
		array( 'slug' => 'custom',    'title' => '定制与团购', 'tpl' => 'template-custom.php' ),
		array( 'slug' => 'terms',     'title' => '服务条款',   'tpl' => 'template-legal.php' ),
		array( 'slug' => 'privacy',   'title' => '隐私政策',   'tpl' => 'template-legal.php' ),
		array( 'slug' => 'refund',    'title' => '退款政策',   'tpl' => 'template-legal.php' ),
		array( 'slug' => 'cookies',   'title' => 'Cookie 政策', 'tpl' => 'template-legal.php' ),
		array( 'slug' => 'login',     'title' => '登录',     'tpl' => 'template-login.php' ),
		array( 'slug' => 'signup',    'title' => '创建账户', 'tpl' => 'template-signup.php' ),
		array( 'slug' => 'account',   'title' => '用户中心', 'tpl' => 'template-account.php' ),
	);

	foreach ( $pages as $p ) {
		if ( get_page_by_path( $p['slug'] ) ) {
			continue;
		}
		$id = wp_insert_post( array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'post_name'   => $p['slug'],
			'post_title'  => $p['title'],
		) );
		if ( $id && ! is_wp_error( $id ) && $p['tpl'] ) {
			update_post_meta( $id, '_wp_page_template', $p['tpl'] );
		}
	}

	$front = get_page_by_path( 'home' );
	if ( $front ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front->ID );
	}
	$blog = get_page_by_path( 'blog' );
	if ( $blog ) {
		update_option( 'page_for_posts', $blog->ID );
	}
	if ( '' === get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
		flush_rewrite_rules();
	}
	sc_seed_demo_skills();
}
add_action( 'after_switch_theme', 'skillcraft_after_switch' );

/** 站点名 */
function sc_site_name() {
	$name = get_bloginfo( 'name', 'display' );
	return $name ? $name : 'SkillCraft';
}

/** 文章浏览量自动统计与读取 */
function sc_track_post_views() {
	if ( is_admin() || ! function_exists( 'is_singular' ) || ! is_singular( 'post' ) ) {
		return;
	}
	$id = get_queried_object_id();
	if ( $id ) {
		$v = (int) get_post_meta( $id, '_sc_post_views', true );
		update_post_meta( $id, '_sc_post_views', $v + 1 );
	}
}
add_action( 'template_redirect', 'sc_track_post_views', 20 );

function sc_get_post_views( $post_id = 0, $fallback = 1840 ) {
	if ( $post_id && function_exists( 'get_post_meta' ) ) {
		$v = (int) get_post_meta( $post_id, '_sc_post_views', true );
		if ( $v > 0 ) {
			return $v;
		}
	}
	return (int) $fallback;
}
