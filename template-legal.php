<?php
/**
 * Template Name: SkillCraft 政策与条款页 / Legal & Policy
 *
 * 瑞士建筑网格双栏长文模板（左栏粘性锚点目录 / 右栏清晰条款），支持：
 * 服务条款（/terms/）、隐私政策（/privacy/）、退款政策（/refund/）、Cookie 政策（/cookies/）。
 * 优先显示 WordPress 后台页面正文，若后台未填写正文则自动展示对应双语规范骨架。
 *
 * @package SkillCraft
 */

get_header();

$slug = '';
if ( function_exists( 'get_post_field' ) && function_exists( 'get_the_ID' ) && get_the_ID() ) {
	$slug = (string) get_post_field( 'post_name', get_the_ID() );
}
if ( isset( $GLOBALS['__legal_slug'] ) ) {
	$slug = (string) $GLOBALS['__legal_slug'];
}
if ( ! in_array( $slug, array( 'terms', 'privacy', 'refund', 'cookies' ), true ) ) {
	$slug = 'terms';
}

$site = sc_site_name();

$docs = array(
	'terms' => array(
		'kicker'   => sc_t( '政策中心 · TERMS OF SERVICE', 'POLICY CENTER · TERMS' ),
		'title'    => sc_t( '服务条款', 'Terms of Service' ),
		'updated'  => '2026-11-27',
		'summary'  => sprintf( sc_t( '使用 %s 商店购买或运行业务工作台、MCP 插件及 Skill 能力包前，请阅读以下授权与使用约定。', 'Please review these licensing and usage terms before purchasing or running Workbenches, MCP plugins, or Skills from %s.' ), $site ),
		'sections' => array(
			array(
				'id'    => 'scope',
				'title' => sc_t( '一、授权范围与交付形态', '1. License Scope & Delivery' ),
				'body'  => sc_t( '您在本站购买的业务工作台、MCP 插件与 Skill 能力包均授予非独占、不可转让的使用许可。买断制商品一经购买永久有效并含后续常规更新；订阅制商品在对应计费周期内有效。', 'Each Workbench, MCP plugin, or Skill grants a non-exclusive, non-transferable license. One-time purchases grant lifetime access with regular updates; monthly subscriptions remain active during the paid billing period.' ),
			),
			array(
				'id'    => 'devices',
				'title' => sc_t( '二、设备绑定与合理使用', '2. Device Binding & Fair Use' ),
				'body'  => sc_t( '每个个人授权码默认支持在限定台数设备（通常为 2 台）上激活，更换设备时可在会员中心自助解绑。禁止将授权码、安装包或受保护云端配置公开分发、转售、共享给未授权第三方，或对鉴权机制进行逆向与绕过。', 'Each personal license supports activation up to its stated device limit (typically 2 devices) with self-service unbinding in your Account. Reselling, redistributing, sharing keys, or bypassing license verification is prohibited.' ),
			),
			array(
				'id'    => 'updates',
				'title' => sc_t( '三、版本更新与服务可用性', '3. Updates & Availability' ),
				'body'  => sc_t( '我们持续维护官方自研产品并适配主流客户端（dsh、Cursor、Claude、Codex、Antigravity 等）。因第三方平台接口变动导致的兼容性调整，我们将在合理时间内发布更新补丁。', 'We actively maintain our first-party products for dsh, Cursor, Claude, Codex, and Antigravity, and issue compatibility updates promptly when upstream client APIs change.' ),
			),
		),
	),
	'privacy' => array(
		'kicker'   => sc_t( '政策中心 · PRIVACY POLICY', 'POLICY CENTER · PRIVACY' ),
		'title'    => sc_t( '隐私政策', 'Privacy Policy' ),
		'updated'  => '2026-11-27',
		'summary'  => sprintf( sc_t( '%s 坚持「本地优先」与最小数据收集原则，严格保护您的账户与业务隐私。', '%s follows a local-first, minimal-collection approach to protect your account and operational data.' ), $site ),
		'sections' => array(
			array(
				'id'    => 'collect',
				'title' => sc_t( '一、我们收集哪些信息', '1. Information We Collect' ),
				'body'  => sc_t( '我们仅收集完成订单发货与授权校验所必需的最少信息：注册/收货邮箱、订单记录以及经单向哈希脱敏处理后的设备指纹（machine_hash）。我们不会收集您的服务器 SSH 密钥、数据库密码、业务代码或私有知识库内容。', 'We collect only the minimum data needed for delivery and license verification: your email, order history, and a one-way hashed device fingerprint. We never collect your SSH keys, database credentials, source code, or private knowledge bases.' ),
			),
			array(
				'id'    => 'usage',
				'title' => sc_t( '二、信息用途与本地优先原则', '2. How We Use Information' ),
				'body'  => sc_t( '您的邮箱仅用于发送自动发货授权码、找回订单与重要版本安全通知；脱敏设备指纹仅用于防止授权码被异常盗用。所有工作台业务数据均存储在您自己的本地设备或私有服务器中。', 'Your email is used solely for license delivery, order recovery, and critical security updates. Operational workbench data stays on your own machine or private server.' ),
			),
			array(
				'id'    => 'rights',
				'title' => sc_t( '三、数据查询与删除权利', '3. Your Data Rights' ),
				'body'  => sc_t( '您随时可在会员中心修改个人资料、解绑设备，或通过页脚联系邮箱申请导出或注销您的账户数据。', 'You can update your profile, unbind devices anytime in your Account, or email us to request data export or account deletion.' ),
			),
		),
	),
	'refund' => array(
		'kicker'   => sc_t( '政策中心 · REFUND POLICY', 'POLICY CENTER · REFUND' ),
		'title'    => sc_t( '退款与售后政策', 'Refund & Support Policy' ),
		'updated'  => '2026-11-27',
		'summary'  => sc_t( '清晰的虚拟数字商品退款与 7 天部署无忧保障说明。', 'Clear refund eligibility and 7-day deployment support terms for digital software products.' ),
		'sections' => array(
			array(
				'id'    => 'eligibility',
				'title' => sc_t( '一、7 天无忧申诉退款范围', '1. 7-Day Refund Eligibility' ),
				'body'  => sc_t( '自下单起 7 天内，若出现以下情形之一，可联系官方客服申请全额退款：1）授权码尚未激活使用；2）在官方声明支持的系统/客户端环境下经客服协助仍无法正常部署运行；3）因重复支付导致的异常订单。', 'Within 7 days of purchase, you may request a full refund if: (1) the license has not been activated, (2) the product cannot run on a supported environment despite support assistance, or (3) a duplicate payment occurred.' ),
			),
			array(
				'id'    => 'revocation',
				'title' => sc_t( '二、退款后的授权回收机制', '2. License Revocation Upon Refund' ),
				'body'  => sc_t( '退款审核通过后，原路退回款项，系统将同步把该订单状态标记为「已退款」并立即收回对应授权码与云端载荷访问权限。', 'Once a refund is processed, funds are returned to the original payment method and the associated license key and protected payload access are automatically revoked.' ),
			),
			array(
				'id'    => 'custom',
				'title' => sc_t( '三、1 对 1 定制开发说明', '3. Custom Development Orders' ),
				'body'  => sc_t( '私有定制开发项目按双方确认的里程碑验收结算，启动开发前可随时无责取消，交付验收后不支持无理由退款。', 'Custom 1-on-1 builds follow agreed milestone sign-off terms and may be canceled prior to kickoff.' ),
			),
		),
	),
	'cookies' => array(
		'kicker'   => sc_t( '政策中心 · COOKIE POLICY', 'POLICY CENTER · COOKIES' ),
		'title'    => sc_t( 'Cookie 政策', 'Cookie Policy' ),
		'updated'  => '2026-11-27',
		'summary'  => sc_t( '说明本站如何使用必要 Cookie 来维持登录状态与双语偏好，以及如何管理您的 Cookie 选择。', 'How we use essential cookies for authentication and language preferences, and how to manage your choices.' ),
		'sections' => array(
			array(
				'id'    => 'essential',
				'title' => sc_t( '一、必要功能 Cookie（始终开启）', '1. Strictly Necessary Cookies' ),
				'body'  => sc_t( '用于保障网站核心功能正常运转，包括 WordPress 登录会话 Cookie、CSRF 安全校验令牌、界面语言偏好（skillcraft_lang）以及您的 Cookie 同意记录（sc_cookie_consent）。此类 Cookie 不涉及跨站广告追踪。', 'Required for core site functionality: WordPress login sessions, CSRF nonces, interface language preference (skillcraft_lang), and your consent choice (sc_cookie_consent). No cross-site ad tracking is used.' ),
			),
			array(
				'id'    => 'analytics',
				'title' => sc_t( '二、可选体验与统计 Cookie', '2. Optional Analytics Cookies' ),
				'body'  => sc_t( '仅在您点击「全部接受」后启用，用于了解最受欢迎的工作台分类与页面加载表现，帮助我们持续优化产品文档与交互体验。', 'Enabled only if you click "Accept all", helping us understand catalog demand and page performance.' ),
			),
			array(
				'id'    => 'manage',
				'title' => sc_t( '三、随时修改您的偏好', '3. Manage Your Preferences' ),
				'body'  => sc_t( '您随时可以点击网站底部页脚的「Cookie 设置」按钮重新选择「仅必要 Cookie」或「全部接受」，或在浏览器设置中清除本地 Cookie。', 'You can change your choice anytime via the "Cookie Settings" button in the footer or clear cookies in your browser.' ),
			),
		),
	),
);

$doc = $docs[ $slug ];
?>

<section class="sc-band sc-subpage sc-legal-page">
	<div class="sc-framed sc-subhero">
		<span class="sc-kicker">/ <?php echo esc_html( $doc['kicker'] ); ?></span>
		<h1><?php echo esc_html( $doc['title'] ); ?></h1>
		<p><?php echo esc_html( $doc['summary'] ); ?></p>
		<div class="sc-legal-meta">
			<span><?php echo esc_html( sc_t( '最近更新：', 'Last updated: ' ) . $doc['updated'] ); ?></span>
			<nav class="sc-legal-pills" aria-label="<?php echo esc_attr( sc_t( '政策导航', 'Policy navigation' ) ); ?>">
				<a class="<?php echo 'terms' === $slug ? 'is-active' : ''; ?>" href="<?php echo esc_url( home_url( '/terms/' ) ); ?>"><?php echo esc_html( sc_t( '服务条款', 'Terms' ) ); ?></a>
				<a class="<?php echo 'privacy' === $slug ? 'is-active' : ''; ?>" href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>"><?php echo esc_html( sc_t( '隐私政策', 'Privacy' ) ); ?></a>
				<a class="<?php echo 'refund' === $slug ? 'is-active' : ''; ?>" href="<?php echo esc_url( home_url( '/refund/' ) ); ?>"><?php echo esc_html( sc_t( '退款政策', 'Refund' ) ); ?></a>
				<a class="<?php echo 'cookies' === $slug ? 'is-active' : ''; ?>" href="<?php echo esc_url( home_url( '/cookies/' ) ); ?>"><?php echo esc_html( sc_t( 'Cookie 政策', 'Cookies' ) ); ?></a>
			</nav>
		</div>
	</div>

	<div class="sc-framed sc-legal-grid">
		<aside class="sc-legal-toc">
			<strong><?php echo esc_html( sc_t( '条款目录', 'On this page' ) ); ?></strong>
			<ul>
				<?php foreach ( $doc['sections'] as $sec ) : ?>
					<li><a href="#<?php echo esc_attr( $sec['id'] ); ?>"><?php echo esc_html( $sec['title'] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</aside>

		<article class="sc-legal-body">
			<?php
			$has_custom_content = false;
			if ( function_exists( 'have_posts' ) && have_posts() && function_exists( 'get_the_ID' ) && get_the_ID() ) {
				$raw_post = get_post( get_the_ID() );
				if ( $raw_post && '' !== trim( wp_strip_all_tags( $raw_post->post_content ) ) ) {
					$has_custom_content = true;
					while ( have_posts() ) {
						the_post();
						the_content();
					}
				}
			}
			if ( ! $has_custom_content ) :
				foreach ( $doc['sections'] as $sec ) :
					?>
					<section class="sc-legal-sec" id="<?php echo esc_attr( $sec['id'] ); ?>">
						<h2><?php echo esc_html( $sec['title'] ); ?></h2>
						<p><?php echo esc_html( $sec['body'] ); ?></p>
					</section>
					<?php
				endforeach;
			endif;
			?>
		</article>
	</div>
</section>

<?php
get_footer();
