<?php
/**
 * 紧凑瑞士网格页脚 + 右下角悬浮技术支持微件
 *
 * @package SkillCraft
 */
?>
</main>

<footer class="sc-footer">
	<div class="sc-framed sc-footer__top">
		<div class="sc-footer__brand">
			<a class="sc-brand sc-brand--sm" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<span class="sc-brand__mark" aria-hidden="true">
					<img src="<?php echo esc_url( SKILLCRAFT_URI . '/assets/img/mascot/mascot-head.png' ); ?>" alt="" width="34" height="20" class="sc-brand__img sc-brand__img--head">
				</span>
				<span class="sc-brand__name"><?php echo esc_html( sc_site_name() ); ?></span>
			</a>
			<p class="sc-footer__slogan">
				<?php echo esc_html( sc_txt( 'footer_slogan' ) ); ?>
			</p>
			<div class="sc-footer__meta">
				<?php echo esc_html( sc_txt( 'footer_contact' ) ); ?>
			</div>
		</div>

		<div class="sc-footer__col">
			<strong><?php echo esc_html( sc_t( '产品与服务', 'Products' ) ); ?></strong>
			<a href="<?php echo esc_url( home_url( '/skills/' ) ); ?>"><?php echo esc_html( sc_t( '作品集市', 'Catalog' ) ); ?></a>
			<a href="<?php echo esc_url( home_url( '/cloud/' ) ); ?>"><?php echo esc_html( sc_t( '云端 Agent 部署', 'Cloud Dot Box' ) ); ?></a>
			<a href="<?php echo esc_url( home_url( '/custom/' ) ); ?>"><?php echo esc_html( sc_t( '定制与团购', 'Custom & Teams' ) ); ?></a>
			<a href="<?php echo esc_url( sc_account_url() ); ?>"><?php echo esc_html( sc_t( '会员中心', 'Member Console' ) ); ?></a>
		</div>

		<div class="sc-footer__col">
			<strong><?php echo esc_html( sc_t( '文档与动态', 'Resources' ) ); ?></strong>
			<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php echo esc_html( sc_t( '博客专栏', 'Blog' ) ); ?></a>
			<a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>"><?php echo esc_html( sc_t( '常见问题', 'FAQ & Guides' ) ); ?></a>
			<a href="<?php echo esc_url( home_url( '/changelog/' ) ); ?>"><?php echo esc_html( sc_t( '更新日志', 'Changelog' ) ); ?></a>
		</div>

		<div class="sc-footer__col">
			<strong><?php echo esc_html( sc_t( '政策与合规', 'Legal & Policy' ) ); ?></strong>
			<a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>"><?php echo esc_html( sc_t( '服务条款', 'Terms of Service' ) ); ?></a>
			<a href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>"><?php echo esc_html( sc_t( '隐私政策', 'Privacy Policy' ) ); ?></a>
			<a href="<?php echo esc_url( home_url( '/refund/' ) ); ?>"><?php echo esc_html( sc_t( '退款政策', 'Refund Policy' ) ); ?></a>
			<a href="<?php echo esc_url( home_url( '/cookies/' ) ); ?>"><?php echo esc_html( sc_t( 'Cookie 政策', 'Cookie Policy' ) ); ?></a>
		</div>
	</div>

	<div class="sc-framed sc-footer__bottom">
		<span>Copyright © <?php echo esc_html( gmdate( 'Y' ) . ' ' . sc_site_name() ); ?>. All rights reserved.</span>
		<div class="sc-footer__legal">
			<button type="button" class="sc-footer__cookiebtn" id="sc-openCookiePrefs"><?php echo esc_html( sc_t( 'Cookie 设置', 'Cookie Settings' ) ); ?></button>
			<a href="<?php echo esc_url( home_url( '/llms.txt' ) ); ?>">llms.txt</a>
			<a href="<?php echo esc_url( home_url( '/sc-sitemap.xml' ) ); ?>">Sitemap</a>
		</div>
	</div>
</footer>

<!-- 极简瑞士风格 Cookie 偏好横幅（首次访问显示，点击后持久化） -->
<div class="sc-cookie-bar" id="sc-cookieBar" role="dialog" aria-label="<?php echo esc_attr( sc_t( 'Cookie 偏好设置', 'Cookie preferences' ) ); ?>" hidden>
	<div class="sc-cookie-bar__copy">
		<strong><?php echo esc_html( sc_t( 'Cookie 与隐私偏好', 'Cookie Preferences' ) ); ?></strong>
		<p>
			<?php echo esc_html( sc_t( '我们使用必要 Cookie 维持登录态与语言选择，可选统计 Cookie 仅在您同意后启用。', 'We use essential cookies for sign-in and language settings. Optional analytics run only with your consent.' ) ); ?>
			<a href="<?php echo esc_url( home_url( '/cookies/' ) ); ?>"><?php echo esc_html( sc_t( '查看 Cookie 政策 →', 'Cookie Policy →' ) ); ?></a>
		</p>
	</div>
	<div class="sc-cookie-bar__actions">
		<button type="button" class="sc-btn sc-btn--outline sc-btn--sm" data-cookie-choice="essential"><?php echo esc_html( sc_t( '仅必要 Cookie', 'Essential only' ) ); ?></button>
		<button type="button" class="sc-btn sc-btn--dark sc-btn--sm" data-cookie-choice="all"><?php echo esc_html( sc_t( '全部接受', 'Accept all' ) ); ?></button>
	</div>
</div>

<!-- 右下角悬浮技术支持卡片 -->
<div class="sc-float" id="sc-floatWidget">
	<img class="sc-float__mascot" src="<?php echo esc_url( SKILLCRAFT_URI . '/assets/img/mascot/mascot-thumb.png' ); ?>" alt="" width="54" height="60" aria-hidden="true">
	<button type="button" class="sc-float__pill" id="sc-floatToggle" aria-expanded="false">
		<span><?php echo esc_html( sc_t( '技术支持', 'Support' ) ); ?></span>
	</button>
	<div class="sc-float__card" id="sc-floatCard" hidden>
		<div class="sc-float__card-head">
			<strong><?php echo esc_html( sc_txt( 'support_title' ) ); ?></strong>
			<button type="button" class="sc-float__close" id="sc-floatClose" aria-label="<?php echo esc_attr( sc_t( '关闭', 'Close' ) ); ?>">×</button>
		</div>
		<div class="sc-float__qr" aria-hidden="true">
			<?php
			$qr_url = sc_content()['support_qr_url'] ?? '';
			if ( $qr_url ) :
				?>
				<img src="<?php echo esc_url( $qr_url ); ?>" alt="" width="120" height="120" style="border-radius:8px;display:block;margin:0 auto">
			<?php else : ?>
				<svg viewBox="0 0 120 120" width="120" height="120" fill="none">
					<rect width="120" height="120" rx="8" fill="#f6f6f7"/>
					<rect x="14" y="14" width="30" height="30" rx="4" stroke="#111" stroke-width="4"/>
					<rect x="23" y="23" width="12" height="12" rx="1.5" fill="#111"/>
					<rect x="76" y="14" width="30" height="30" rx="4" stroke="#111" stroke-width="4"/>
					<rect x="85" y="23" width="12" height="12" rx="1.5" fill="#111"/>
					<rect x="14" y="76" width="30" height="30" rx="4" stroke="#111" stroke-width="4"/>
					<rect x="23" y="85" width="12" height="12" rx="1.5" fill="#111"/>
					<path d="M54 20h12v12H54zM54 44h12v24H54zM20 56h24v10H20zM76 56h12v12H76zM94 56h12v22H94zM56 78h16v12H56zM78 78h10v28H78zM94 88h12v18H94z" fill="#111"/>
					<circle cx="60" cy="60" r="10" fill="#1677ff"/>
					<path d="M56.5 60.5l2.5 2.5 5-5.5" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			<?php endif; ?>
		</div>
		<p class="sc-float__desc"><?php echo esc_html( sc_txt( 'support_desc' ) ); ?></p>
	</div>
</div>

<?php wp_footer(); ?>
</body>
</html>
