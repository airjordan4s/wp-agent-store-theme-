<?php
/**
 * Template Name: SkillCraft 常见问题 / FAQ
 *
 * 瑞士建筑网格双 Tab 切换（排查指南列表 / 快速问答手风琴）。
 *
 * @package SkillCraft
 */

get_header();

$guides = sc_faq_guides();
$faqs   = sc_faqs();
?>

<section class="sc-band sc-subpage">
	<div class="sc-framed sc-subhero">
		<h1><?php echo esc_html( sc_t( '常见问题', 'Frequently Asked Questions' ) ); ?></h1>
		<p><?php echo esc_html( sc_t( '关于购买发货、Agent 客户端安装、多设备同步与售后保障的全部解答。', 'Everything you need to know about purchasing, Agent setup, updates, and support.' ) ); ?></p>
	</div>

	<!-- 50/50 双列分段 Tab 切换器 -->
	<div class="sc-framed sc-faq-tabs" role="tablist" aria-label="<?php echo esc_attr( sc_t( '问题分类', 'FAQ categories' ) ); ?>">
		<button type="button" class="sc-faq-tab is-active" role="tab" aria-selected="true" data-faq-tab="guides">
			<strong><?php echo esc_html( sc_t( '购买与安装指南', 'Setup & Guides' ) ); ?></strong>
			<span><?php echo esc_html( sc_t( '从下单发货到客户端挂载的图文步骤与排障清单', 'Step-by-step installation guides and troubleshooting' ) ); ?></span>
		</button>
		<button type="button" class="sc-faq-tab" role="tab" aria-selected="false" data-faq-tab="quick">
			<strong><?php echo esc_html( sc_t( '快速问答', 'Quick Q&A' ) ); ?></strong>
			<span><?php echo esc_html( sc_t( '关于自动发货、永久更新、多设备与开票的高频问题', 'Direct answers on delivery, updates, devices, and invoices' ) ); ?></span>
		</button>
	</div>

	<!-- Tab 1: 排查与接入指南列表 -->
	<div class="sc-framed sc-faq-panel is-active" role="tabpanel" data-faq-panel="guides">
		<?php foreach ( $guides as $g ) : ?>
			<a class="sc-guide-row" href="<?php echo esc_url( $g['url'] ); ?>">
				<div class="sc-guide-row__main">
					<span class="sc-guide-row__cat"><?php echo esc_html( sc_t( $g['cat'], $g['cat_en'] ) ); ?></span>
					<h3><?php echo esc_html( sc_t( $g['title'], $g['title_en'] ) ); ?></h3>
					<p><?php echo esc_html( sc_t( $g['summary'], $g['summary_en'] ) ); ?></p>
				</div>
				<span class="sc-guide-row__cta">
					<?php echo esc_html( sc_t( '查看详情', 'Read guide' ) ); ?>
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
						<path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</span>
			</a>
		<?php endforeach; ?>
	</div>

	<!-- Tab 2: 快速问答手风琴 -->
	<div class="sc-framed sc-faq-panel" role="tabpanel" data-faq-panel="quick" hidden>
		<?php foreach ( $faqs as $i => $f ) :
			$open = ( 0 === $i );
			?>
			<div class="sc-faq__item sc-faq__item--page <?php echo $open ? 'is-open' : ''; ?>">
				<button type="button" class="sc-faq__q" aria-expanded="<?php echo $open ? 'true' : 'false'; ?>">
					<span><?php echo esc_html( sc_t( $f['q'], $f['q_en'] ) ); ?></span>
					<span class="sc-faq__chev" aria-hidden="true">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none">
							<path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</span>
				</button>
				<div class="sc-faq__a">
					<p><?php echo esc_html( sc_t( $f['a'], $f['a_en'] ) ); ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>

<?php
get_footer();
