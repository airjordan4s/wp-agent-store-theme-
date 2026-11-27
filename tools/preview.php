<?php
/**
 * SkillCraft 静态预览导出工具
 *
 * 用法：php tools/preview.php
 */

error_reporting( E_ALL & ~E_DEPRECATED );
ini_set( 'display_errors', '1' );

$ROOT = dirname( __DIR__ );
$OUT  = $ROOT . '/preview';

@mkdir( $OUT . '/assets/css', 0777, true );
@mkdir( $OUT . '/assets/js', 0777, true );
@mkdir( $OUT . '/assets/img/mascot', 0777, true );

define( 'ABSPATH', $ROOT . '/' );
define( 'YEAR_IN_SECONDS', 31536000 );
define( 'COOKIEPATH', '/' );
define( 'COOKIE_DOMAIN', '' );

$GLOBALS['__root']      = $ROOT;
$GLOBALS['__lang']      = 'zh';
$GLOBALS['__logged_in'] = false;
$GLOBALS['__q']         = 'front';
$GLOBALS['__loop_i']    = 0;

$GLOBALS['__posts'] = array(
	array(
		'title_zh'   => '如何把一线工程经验封装成可复用的付费 Skill',
		'title_en'   => 'Packaging production engineering playbooks into sellable Skills',
		'excerpt_zh' => '从确定性边界、输入校验到沙箱隔离，拆解一份高复购率 Skill 的结构标准。',
		'excerpt_en' => 'From deterministic boundaries to sandbox isolation — the anatomy of a high-retention Skill.',
		'date'       => '2026-11-20',
	),
	array(
		'title_zh'   => '为什么托管执行比明文下发更适合商业化 Skill',
		'title_en'   => 'Why hosted execution beats plain-text distribution for commercial Skills',
		'excerpt_zh' => '兼顾创作者知识产权保护与毫秒级响应延迟的混合执行架构实践。',
		'excerpt_en' => 'Balancing creator IP protection with low-latency execution in a hybrid runtime.',
		'date'       => '2026-11-12',
	),
	array(
		'title_zh'   => 'MCP 与 SKILL.md 协议互通指南',
		'title_en'   => 'Interoperability guide for MCP and SKILL.md',
		'excerpt_zh' => '一次声明即可同时运行在 Cursor、Claude Code、Codex CLI 与 Antigravity 中。',
		'excerpt_en' => 'Declare once and run seamlessly across Cursor, Claude Code, Codex CLI, and Antigravity.',
		'date'       => '2026-11-04',
	),
);

/* ---- WP Stubs ---- */
function add_action() {}
function add_filter() {}
function load_theme_textdomain() { return true; }
function add_theme_support() {}
function register_nav_menus() {}
function register_post_type() {}
function add_menu_page() {}
function add_meta_box() {}
function checked( $a, $b = true, $e = true ) { $r = ( $a == $b ) ? 'checked="checked"' : ''; if ( $e ) echo $r; return $r; }
function wp_enqueue_style() {}
function wp_enqueue_script() {}
function wp_localize_script() {}
function wp_nonce_field() {}
function wp_verify_nonce() { return true; }
function wp_create_nonce() { return 'preview'; }
function __( $s ) { return $s; }
function esc_html( $s ) { return htmlspecialchars( (string) $s, ENT_QUOTES, 'UTF-8' ); }
function esc_attr( $s ) { return htmlspecialchars( (string) $s, ENT_QUOTES, 'UTF-8' ); }
function esc_textarea( $s ) { return htmlspecialchars( (string) $s, ENT_QUOTES, 'UTF-8' ); }
function esc_url( $s ) { return (string) $s; }
function esc_url_raw( $s ) { return (string) $s; }
function wp_kses( $s ) { return strip_tags( (string) $s, '<br><a><strong><em>' ); }
function wp_strip_all_tags( $s ) { return trim( strip_tags( (string) $s ) ); }
function wp_trim_words( $s ) { return $s; }
function wp_unslash( $s ) { return $s; }
function sanitize_key( $s ) { return preg_replace( '/[^a-z0-9_\-]/', '', strtolower( (string) $s ) ); }
function sanitize_text_field( $s ) { return trim( strip_tags( (string) $s ) ); }
function sanitize_email( $s ) { return trim( (string) $s ); }
function sanitize_textarea_field( $s ) { return trim( strip_tags( (string) $s ) ); }
function get_template_directory() { return $GLOBALS['__root']; }
function get_template_directory_uri() { return '.'; }
function trailingslashit( $s ) { return rtrim( $s, '/' ) . '/'; }
function is_admin() { return false; }
function is_ssl() { return false; }
function is_user_logged_in() { return ! empty( $GLOBALS['__logged_in'] ); }
function current_user_can() { return true; }
function get_current_user_id() { return 1; }
function get_user_meta() { return $GLOBALS['__lang']; }
function get_option( $k, $d = false ) { return 'users_can_register' === $k ? 1 : $d; }
function get_posts() { return array(); }
function get_page_by_path() { return null; }
function get_bloginfo( $k = '' ) { return 'charset' === $k ? 'UTF-8' : 'SkillCraft'; }
function language_attributes() { echo 'lang="' . ( 'en' === $GLOBALS['__lang'] ? 'en' : 'zh-CN' ) . '"'; }
function body_class( $c = '' ) { echo 'class="' . esc_attr( $c ) . '"'; }
function wp_body_open() {}
function bloginfo( $k = '' ) { echo esc_html( get_bloginfo( $k ) ); }
function selected( $a, $b, $e = true ) { $r = ( $a == $b ) ? 'selected="selected"' : ''; if ( $e ) echo $r; return $r; }
function disabled( $a, $b = true, $e = true ) { $r = ( $a == $b ) ? 'disabled="disabled"' : ''; if ( $e ) echo $r; return $r; }
function date_i18n( $fmt, $ts = false ) { return date( $fmt, $ts ?: time() ); }
function admin_url( $p = '' ) { return './' . $p; }
function wp_logout_url() { return './index' . ( 'en' === $GLOBALS['__lang'] ? '-en' : '' ) . '.html'; }
function add_query_arg( $k, $v, $u ) { return $u; }
function home_url( $p = '' ) {
	$en = ( 'en' === $GLOBALS['__lang'] ) ? '-en' : '';
	$p  = (string) $p;
	if ( '' === $p || '/' === $p ) {
		return './index' . $en . '.html';
	}
	if ( 0 === strpos( $p, '/#' ) ) {
		return './index' . $en . '.html' . substr( $p, 1 );
	}
	$clean = trim( $p, '/' );
	$map   = array(
		'blog'      => './blog' . $en . '.html',
		'faq'       => './faq' . $en . '.html',
		'changelog' => './changelog' . $en . '.html',
		'custom'    => './custom' . $en . '.html',
		'terms'     => './terms' . $en . '.html',
		'privacy'   => './privacy' . $en . '.html',
		'refund'    => './refund' . $en . '.html',
		'cookies'   => './cookies' . $en . '.html',
		'skills'    => './skills' . $en . '.html',
		'product'   => './product' . $en . '.html',
		'login'     => './login' . $en . '.html',
		'signup'    => './signup' . $en . '.html',
		'account'   => './account' . $en . '.html',
	);
	return $map[ $clean ] ?? './' . ltrim( $p, '/' );
}
function wp_get_current_user() {
	$u = new stdClass();
	$u->ID              = 1;
	$u->user_login      = 'linzhiyuan';
	$u->display_name    = 'en' === $GLOBALS['__lang'] ? 'Alex Lin' : '林致远';
	$u->user_email      = 'alex@skillcraft.dev';
	$u->description     = 'en' === $GLOBALS['__lang'] ? 'Staff DBA & Skill Builder' : '数据库架构师 / Skill 创作者';
	$u->user_registered = '2026-08-12 09:00:00';
	return $u;
}
function have_posts() {
	if ( 'blog' === $GLOBALS['__q'] ) {
		return $GLOBALS['__loop_i'] < count( $GLOBALS['__posts'] );
	}
	return 0 === $GLOBALS['__loop_i'];
}
function the_post() {
	$GLOBALS['__cur'] = $GLOBALS['__posts'][ $GLOBALS['__loop_i'] ] ?? $GLOBALS['__posts'][0];
	$GLOBALS['__loop_i']++;
}
function post_class( $c = '' ) { echo 'class="' . esc_attr( $c ) . '"'; }
function the_permalink() { echo './article' . ( 'en' === $GLOBALS['__lang'] ? '-en' : '' ) . '.html'; }
function the_title() {
	$p = $GLOBALS['__cur'] ?? $GLOBALS['__posts'][0];
	echo esc_html( 'en' === $GLOBALS['__lang'] ? $p['title_en'] : $p['title_zh'] );
}
function get_the_excerpt() {
	$p = $GLOBALS['__cur'] ?? $GLOBALS['__posts'][0];
	return 'en' === $GLOBALS['__lang'] ? $p['excerpt_en'] : $p['excerpt_zh'];
}
function get_the_date( $fmt = 'Y/m/d' ) {
	$p = $GLOBALS['__cur'] ?? $GLOBALS['__posts'][0];
	return str_replace( '-', '/', $p['date'] );
}
function get_the_author() { return 'en' === $GLOBALS['__lang'] ? 'SkillCraft Editorial' : 'SkillCraft 编辑部'; }
function the_content() {
	if ( 'en' === $GLOBALS['__lang'] ) {
		echo '<p>Great engineering expertise often stays trapped inside private wikis or individual terminals. Packaging it as a versioned Skill turns tacit knowledge into a repeatable, monetizable product.</p><h2>1. Define strict input & output contracts</h2><p>Every high-retention Skill states its required context up front and produces structured diffs, checklists, or verified scripts rather than generic prose.</p><h2>2. Protect core IP with hosted execution</h2><p>By keeping proprietary rubrics and reference corpora on the server side, builders distribute capability without exposing raw source text.</p>';
	} else {
		echo '<p>许多真正有价值的工程经验，长期沉淀在个人笔记与私有脚本里。将它封装为标准化、可版本化的 Skill，就能让专业能力被成百上千个团队按需调用。</p><h2>一、明确输入边界与确定性产出</h2><p>高复购率的 Skill 从不输出泛泛而谈的建议，而是直接给出可执行的 SQL 改写、精确到行号的安全补丁与回归清单。</p><h2>二、用托管执行守住创作者知识产权</h2><p>通过云端加密托管与按会话签发短期凭证，创作者既能开放能力调用，又不必担心核心资产被明文搬运。</p>';
	}
}
function wp_head() {
	echo '<title>SkillCraft — ' . ( 'en' === $GLOBALS['__lang'] ? 'Monetize Your Valuable Skills' : '让你的有价值的 Skill 为你创造价值' ) . "</title>\n";
	echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&family=Noto+Sans+SC:wght@400;500;600;700&display=swap">' . "\n";
	echo '<link rel="stylesheet" href="./assets/css/main.css">' . "\n";
	echo '<script>window.SkillCraft={lang:"' . $GLOBALS['__lang'] . '"};</script>' . "\n";
}
function wp_footer() {
	echo '<script src="./assets/js/main.js"></script>' . "\n";
}
function get_header() { include $GLOBALS['__root'] . '/header.php'; }
function get_footer() { include $GLOBALS['__root'] . '/footer.php'; }

require $ROOT . '/functions.php';

copy( $ROOT . '/assets/css/main.css', $OUT . '/assets/css/main.css' );
copy( $ROOT . '/assets/js/main.js', $OUT . '/assets/js/main.js' );
foreach ( glob( $ROOT . '/assets/img/mascot/*.png' ) ?: array() as $img_f ) {
	copy( $img_f, $OUT . '/assets/img/mascot/' . basename( $img_f ) );
}
foreach ( glob( $ROOT . '/assets/img/*.{png,jpg,jpeg,webp}', GLOB_BRACE ) ?: array() as $img_f ) {
	copy( $img_f, $OUT . '/assets/img/' . basename( $img_f ) );
}

function export_page( $file, $lang, $q, $tpl ) {
	global $ROOT, $OUT;
	$GLOBALS['__lang']      = $lang;
	$_GET['lang']           = $lang;
	$GLOBALS['__q']         = $q;
	$GLOBALS['__loop_i']    = 0;
	$GLOBALS['__logged_in'] = ( 'account' === $q );

	ob_start();
	include $ROOT . '/' . $tpl;
	$html = ob_get_clean();
	file_put_contents( $OUT . '/' . $file, $html );
	printf( "  %-18s %5.1f KB  lang=%s\n", $file, strlen( $html ) / 1024, $lang );
}

echo "== 导出 SkillCraft 静态预览 ==\n";
export_page( 'index.html',      'zh', 'front',   'front-page.php' );
export_page( 'index-en.html',   'en', 'front',   'front-page.php' );
export_page( 'login.html',      'zh', 'login',   'template-login.php' );
export_page( 'login-en.html',   'en', 'login',   'template-login.php' );
export_page( 'signup.html',     'zh', 'signup',  'template-signup.php' );
export_page( 'signup-en.html',  'en', 'signup',  'template-signup.php' );
export_page( 'account.html',    'zh', 'account', 'template-account.php' );
export_page( 'account-en.html', 'en', 'account', 'template-account.php' );
export_page( 'blog.html',         'zh', 'blog',      'home.php' );
export_page( 'blog-en.html',      'en', 'blog',      'home.php' );
export_page( 'faq.html',          'zh', 'faq',       'template-faq.php' );
export_page( 'faq-en.html',       'en', 'faq',       'template-faq.php' );
export_page( 'changelog.html',    'zh', 'changelog', 'template-changelog.php' );
export_page( 'changelog-en.html', 'en', 'changelog', 'template-changelog.php' );
export_page( 'custom.html',       'zh', 'custom',    'template-custom.php' );
export_page( 'custom-en.html',    'en', 'custom',    'template-custom.php' );
export_page( 'skills.html',       'zh', 'skills',    'archive-sc_skill.php' );
export_page( 'skills-en.html',    'en', 'skills',    'archive-sc_skill.php' );
export_page( 'product.html',      'zh', 'product',   'single-sc_skill.php' );
export_page( 'product-en.html',   'en', 'product',   'single-sc_skill.php' );
export_page( 'article.html',      'zh', 'single',    'single.php' );
export_page( 'article-en.html',   'en', 'single',    'single.php' );
foreach ( array( 'terms', 'privacy', 'refund', 'cookies' ) as $lslug ) {
	$GLOBALS['__legal_slug'] = $lslug;
	export_page( $lslug . '.html',    'zh', 'legal', 'template-legal.php' );
	export_page( $lslug . '-en.html', 'en', 'legal', 'template-legal.php' );
}
echo "== 完成 ==\n";
