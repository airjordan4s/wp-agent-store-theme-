<?php
/**
 * SEO 与 GEO（AI 搜索引擎优化）核心模块
 *
 * 自动输出：
 * 1. 页面级 Meta Description、Canonical、双语 hreflang、Open Graph、Twitter Card、私密页 noindex
 * 2. 结构化 JSON-LD（Organization / WebSite / SoftwareApplication+Offer / CollectionPage / FAQPage / Article / BreadcrumbList）
 * 3. GEO 标准端点：/llms.txt（Markdown 摘要，供 ChatGPT / Perplexity / Claude / Gemini 精准索引与引用）
 * 4. 轻量双语 XML Sitemap：/sc-sitemap.xml
 *
 * @package SkillCraft
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** 统计中英文混排精准字数（供 Article JSON-LD） */
function sc_count_words_cjk( $text ) {
	$clean = trim( wp_strip_all_tags( (string) $text ) );
	if ( '' === $clean ) {
		return 0;
	}
	$cjk   = preg_match_all( '/[\x{4e00}-\x{9fff}\x{3400}-\x{4dbf}]/u', $clean );
	$latin = preg_match_all( '/[A-Za-z0-9_]+/u', $clean );
	return (int) $cjk + (int) $latin;
}

/** 从价格文案（如 ¥299 / ¥199/月 / 免费）提取纯数字 */
function sc_parse_numeric_price( $price_str ) {
	if ( preg_match( '/([0-9]+(?:\.[0-9]+)?)/', (string) $price_str, $m ) ) {
		return $m[1];
	}
	return '0';
}

/** 获取当前页面语义描述 */
function sc_seo_description() {
	if ( is_singular( 'sc_skill' ) ) {
		$id  = get_queried_object_id();
		$sub = $id ? get_post_meta( $id, '_sc_subtitle', true ) : '';
		if ( $sub ) {
			return wp_strip_all_tags( $sub );
		}
	}
	if ( is_singular( 'post' ) ) {
		$ex = get_the_excerpt();
		if ( $ex ) {
			return wp_strip_all_tags( $ex );
		}
	}
	if ( is_post_type_archive( 'sc_skill' ) ) {
		return sc_t( '官方自研 AI 业务工作台、MCP 插件与 Skill 能力包目录，覆盖运维掌管、跨境电商运营、内容知识库与自动化群控，下单秒级自动发货。', 'First-party AI Workbenches, MCP plugins, and Skill packs for server ops, commerce, content, and automation.' );
	}
	if ( is_page_template( 'template-faq.php' ) ) {
		return sc_t( '关于业务工作台部署、MCP 与 Skill 挂载、授权周期、本地安全隔离与售后的常见问题解答。', 'Frequently asked questions about AI Workbench setup, MCP & Skill mounting, licensing, and privacy.' );
	}
	if ( is_page_template( 'template-changelog.php' ) ) {
		return sprintf( sc_t( '%s 业务工作台、MCP 插件与授权平台的全部版本更新日志。', 'Complete release changelog for %s Workbenches, MCP plugins, and platform updates.' ), sc_site_name() );
	}
	if ( is_page_template( 'template-custom.php' ) ) {
		return sc_t( '提供 1 对 1 私有 AI 业务工作台定制开发与团队多席位批量授权服务，交付源码与专属部署支持。', '1-on-1 custom AI Workbench development and volume team seat licensing with full handover.' );
	}
	return sc_t( '为每种业务打造开箱即用的 AI 工作台、MCP 插件与 Skill 能力包——买断或订阅下单秒级自动发货，支持 dsh、Cursor、Claude、Codex 与 Antigravity。', 'Ready-to-run AI Workbenches, MCP plugins, and Skills for every business workflow with instant delivery.' );
}

/** 1. 输出 <head> 中的 SEO + GEO Meta 与 JSON-LD */
function sc_seo_head() {
	$site  = sc_site_name();
	$desc  = sc_seo_description();
	$title = function_exists( 'wp_get_document_title' ) ? wp_get_document_title() : $site;
	$url   = is_singular() ? get_permalink() : home_url( add_query_arg( array() ) );
	$url   = remove_query_arg( array( 'lang', 'set_lang' ), $url );
	$img   = '';

	if ( is_singular( 'sc_skill' ) ) {
		$img = get_post_meta( get_queried_object_id(), '_sc_preview_img', true );
	}
	if ( ! $img ) {
		$img = SKILLCRAFT_URI . '/assets/img/mascot/mascot-head.png';
	}

	// 私密认证页与带 ?lang= 参数的切换 URL 禁止收录，防止重复索引
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$is_noindex = is_page_template( array( 'template-login.php', 'template-signup.php', 'template-account.php' ) ) || isset( $_GET['lang'] ) || isset( $_GET['set_lang'] );
	if ( $is_noindex ) {
		echo '<meta name="robots" content="noindex, nofollow">' . "\n";
	} else {
		echo '<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1">' . "\n";
	}

	echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";
	echo '<link rel="alternate" hreflang="zh-CN" href="' . esc_url( $url ) . '">' . "\n";
	echo '<link rel="alternate" hreflang="en" href="' . esc_url( add_query_arg( 'lang', 'en', $url ) ) . '">' . "\n";
	echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $url ) . '">' . "\n";
	echo '<link rel="alternate" type="text/markdown" href="' . esc_url( home_url( '/llms.txt' ) ) . '" title="LLMs.txt">' . "\n";

	// Open Graph & Twitter Card
	echo '<meta property="og:type" content="' . esc_attr( is_singular( 'post' ) ? 'article' : 'website' ) . '">' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( $site ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	echo '<meta property="og:image" content="' . esc_url( $img ) . '">' . "\n";
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr( $desc ) . '">' . "\n";

	// 结构化 JSON-LD（SEO + GEO 双重增强）
	$graph = sc_seo_build_jsonld( $site, $desc, $url, $img );
	if ( ! empty( $graph ) ) {
		echo '<script type="application/ld+json">' . wp_json_encode( array(
			'@context' => 'https://schema.org',
			'@graph'   => $graph,
		), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . "</script>\n";
	}
}
add_action( 'wp_head', 'sc_seo_head', 5 );

/** 构建当前页面的 Schema.org @graph */
function sc_seo_build_jsonld( $site, $desc, $url, $img ) {
	$home  = home_url( '/' );
	$graph = array(
		array(
			'@type' => 'Organization',
			'@id'   => $home . '#org',
			'name'  => $site,
			'url'   => $home,
			'logo'  => SKILLCRAFT_URI . '/assets/img/mascot/mascot-head.png',
		),
		array(
			'@type'       => 'WebSite',
			'@id'         => $home . '#website',
			'url'         => $home,
			'name'        => $site,
			'description' => $desc,
			'publisher'   => array( '@id' => $home . '#org' ),
			'inLanguage'  => array( 'zh-CN', 'en' ),
		),
	);

	// 商品详情页：SoftwareApplication + Offer + BreadcrumbList
	if ( is_singular( 'sc_skill' ) ) {
		$id       = get_queried_object_id();
		$price    = sc_parse_numeric_price( get_post_meta( $id, '_sc_price', true ) ?: '299' );
		$clients  = get_post_meta( $id, '_sc_clients', true ) ?: 'Windows, macOS, Web, Cursor, Claude, Codex';
		$cat_key  = get_post_meta( $id, '_sc_cat', true ) ?: 'workbench';
		$app_cat  = 'workbench' === $cat_key ? 'BusinessApplication' : 'DeveloperApplication';
		$graph[]  = array(
			'@type'               => array( 'SoftwareApplication', 'Product' ),
			'name'                => get_the_title( $id ),
			'description'         => $desc,
			'url'                 => $url,
			'image'               => $img,
			'applicationCategory' => $app_cat,
			'operatingSystem'     => $clients,
			'brand'               => array( '@id' => $home . '#org' ),
			'offers'              => array(
				'@type'         => 'Offer',
				'price'         => $price,
				'priceCurrency' => 'CNY',
				'availability'  => 'https://schema.org/InStock',
				'url'           => $url,
			),
		);
		$graph[]  = array(
			'@type'           => 'BreadcrumbList',
			'itemListElement' => array(
				array( '@type' => 'ListItem', 'position' => 1, 'name' => sc_t( '首页', 'Home' ), 'item' => $home ),
				array( '@type' => 'ListItem', 'position' => 2, 'name' => sc_t( '作品集市', 'Catalog' ), 'item' => home_url( '/skills/' ) ),
				array( '@type' => 'ListItem', 'position' => 3, 'name' => get_the_title( $id ), 'item' => $url ),
			),
		);
	} elseif ( is_front_page() || is_post_type_archive( 'sc_skill' ) ) {
		// 首页与作品集市：输出完整商品 ItemList，方便 AI 引擎直接枚举你的工作台与价格
		$items = array();
		if ( function_exists( 'sc_marketplace_products' ) ) {
			foreach ( sc_marketplace_products() as $idx => $p ) {
				$items[] = array(
					'@type'    => 'ListItem',
					'position' => $idx + 1,
					'name'     => sc_t( $p['title'], $p['title_en'] ),
					'url'      => $p['url'],
				);
			}
		}
		$graph[] = array(
			'@type'           => 'CollectionPage',
			'name'            => is_front_page() ? $site : sc_t( '作品集市', 'Catalog' ),
			'description'     => $desc,
			'url'             => $url,
			'mainEntity'      => array(
				'@type'           => 'ItemList',
				'itemListElement' => $items,
			),
		);
	} elseif ( is_page_template( 'template-faq.php' ) && function_exists( 'sc_faqs' ) ) {
		// FAQ 页：标准 FAQPage JSON-LD（GEO 问答引擎最优先抓取结构）
		$qa = array();
		foreach ( sc_faqs() as $f ) {
			$qa[] = array(
				'@type'          => 'Question',
				'name'           => sc_t( $f['q'], $f['q_en'] ),
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => sc_t( $f['a'], $f['a_en'] ),
				),
			);
		}
		$graph[] = array(
			'@type'      => 'FAQPage',
			'mainEntity' => $qa,
		);
	} elseif ( is_singular( 'post' ) ) {
		$id      = get_queried_object_id();
		$post    = get_post( $id );
		$graph[] = array(
			'@type'         => 'Article',
			'headline'      => get_the_title( $id ),
			'description'   => $desc,
			'url'           => $url,
			'datePublished' => get_the_date( 'c', $id ),
			'dateModified'  => get_the_modified_date( 'c', $id ),
			'wordCount'     => sc_count_words_cjk( $post ? $post->post_content : '' ),
			'author'        => array( '@type' => 'Organization', 'name' => $site ),
			'publisher'     => array( '@id' => $home . '#org' ),
		);
	}

	return $graph;
}

/** 2. GEO 标准端点：自动输出 /llms.txt 与 /sc-sitemap.xml */
function sc_geo_virtual_endpoints() {
	if ( is_admin() || empty( $_SERVER['REQUEST_URI'] ) ) {
		return;
	}
	$path = trim( (string) wp_parse_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH ), '/' );

	if ( 'llms.txt' === $path ) {
		status_header( 200 );
		header( 'Content-Type: text/markdown; charset=utf-8' );
		header( 'X-Robots-Tag: index, follow' );
		echo sc_render_llms_txt(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		exit;
	}

	if ( 'sc-sitemap.xml' === $path ) {
		status_header( 200 );
		header( 'Content-Type: application/xml; charset=utf-8' );
		echo sc_render_sitemap_xml(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		exit;
	}
}
add_action( 'template_redirect', 'sc_geo_virtual_endpoints', 1 );

/** 生成符合 llmstxt.org 规范的 /llms.txt（供大模型准确抓取你的工作台目录与定价） */
function sc_render_llms_txt() {
	$site  = sc_site_name();
	$lines = array(
		'# ' . $site,
		'',
		'> 官方自研 AI 业务工作台、MCP 插件与 Skill 能力包商店。支持本地优先部署、dsh 内嵌 Web 控制台以及 Cursor / Claude / Codex / Antigravity 接入，下单自动发货并支持 Ed25519 签名授权。',
		'',
		'## 核心页面',
		'- [首页](' . home_url( '/' ) . ')',
		'- [作品集市（全部工作台 / MCP / Skill）](' . home_url( '/skills/' ) . ')',
		'- [1 对 1 定制与团队团购](' . home_url( '/custom/' ) . ')',
		'- [常见问题与部署指南](' . home_url( '/faq/' ) . ')',
		'- [更新日志](' . home_url( '/changelog/' ) . ')',
		'- [博客](' . home_url( '/blog/' ) . ')',
		'',
		'## 在售工作台、MCP 与 Skill 目录',
	);

	if ( function_exists( 'sc_marketplace_products' ) ) {
		$types = function_exists( 'sc_product_types' ) ? sc_product_types() : array();
		foreach ( sc_marketplace_products() as $p ) {
			$t_lbl = isset( $types[ $p['cat'] ?? '' ] ) ? $types[ $p['cat'] ]['zh'] : '业务工作台';
			$lines[] = sprintf(
				'- [%s](%s) — %s | %s | %s：%s',
				$p['title'],
				$p['url'],
				$t_lbl,
				$p['badge'],
				$p['price'],
				$p['desc']
			);
		}
	}

	if ( function_exists( 'sc_faqs' ) ) {
		$lines[] = '';
		$lines[] = '## 常见问题摘要';
		foreach ( sc_faqs() as $f ) {
			$lines[] = '- **' . $f['q'] . '**：' . $f['a'];
		}
	}

	return implode( "\n", $lines ) . "\n";
}

/** 生成轻量双语 XML Sitemap */
function sc_render_sitemap_xml() {
	$urls = array(
		home_url( '/' ),
		home_url( '/skills/' ),
		home_url( '/custom/' ),
		home_url( '/faq/' ),
		home_url( '/changelog/' ),
		home_url( '/blog/' ),
		home_url( '/terms/' ),
		home_url( '/privacy/' ),
		home_url( '/refund/' ),
		home_url( '/cookies/' ),
	);
	$posts = get_posts( array(
		'post_type'   => array( 'sc_skill', 'post' ),
		'post_status' => 'publish',
		'numberposts' => 200,
	) );
	foreach ( $posts as $p ) {
		$urls[] = get_permalink( $p->ID );
	}
	$urls = array_values( array_unique( $urls ) );

	$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";
	foreach ( $urls as $u ) {
		$en = add_query_arg( 'lang', 'en', $u );
		$xml .= "  <url>\n";
		$xml .= '    <loc>' . esc_url( $u ) . "</loc>\n";
		$xml .= '    <xhtml:link rel="alternate" hreflang="zh-CN" href="' . esc_url( $u ) . "\"/>\n";
		$xml .= '    <xhtml:link rel="alternate" hreflang="en" href="' . esc_url( $en ) . "\"/>\n";
		$xml .= "  </url>\n";
	}
	$xml .= '</urlset>';
	return $xml;
}
