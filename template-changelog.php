<?php
/**
 * Template Name: SkillCraft 更新日志 / Changelog
 *
 * 瑞士建筑网格双栏时间线（左栏版本号+日期 / 右栏状态胶囊+标题+摘要+破折号明细）。
 *
 * @package SkillCraft
 */

get_header();

$entries = sc_changelog_entries();
?>

<section class="sc-band sc-subpage">
	<div class="sc-framed sc-subhero">
		<h1><?php echo esc_html( sc_t( '更新日志', 'Changelog' ) ); ?></h1>
		<p><?php echo esc_html( sprintf( sc_t( '记录 %s 业务工作台、MCP 插件与商店系统的每一次迭代。', 'Tracking every release across %s workbenches, MCP plugins, and platform tools.' ), sc_site_name() ) ); ?></p>
	</div>

	<div class="sc-framed sc-changelog">
		<?php foreach ( $entries as $entry ) : ?>
			<article class="sc-release">
				<div class="sc-release__meta">
					<strong class="sc-release__ver"><?php echo esc_html( $entry['version'] ); ?></strong>
					<time class="sc-release__date"><?php echo esc_html( $entry['date'] ); ?></time>
				</div>

				<div class="sc-release__body">
					<span class="sc-release__badge sc-release__badge--<?php echo esc_attr( $entry['type'] ); ?>">
						<?php echo esc_html( sc_t( $entry['badge'], $entry['badge_en'] ) ); ?>
					</span>
					<h2><?php echo esc_html( sc_t( $entry['title'], $entry['title_en'] ) ); ?></h2>
					<p class="sc-release__summary"><?php echo esc_html( sc_t( $entry['summary'], $entry['summary_en'] ) ); ?></p>
					<ul class="sc-release__list">
						<?php foreach ( $entry['items'] as $it ) : ?>
							<li><?php echo esc_html( sc_t( $it['zh'], $it['en'] ) ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
</section>

<?php
get_footer();
