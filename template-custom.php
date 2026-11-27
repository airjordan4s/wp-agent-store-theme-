<?php
/**
 * Template Name: SkillCraft 定制与团购 / Custom & Team
 *
 * 1 对 1 私有 Skill 定制开发 + 团队批量授权独立页面（内置原生表单收单）。
 *
 * @package SkillCraft
 */

get_header();

$submitted = false;
$error_msg = '';

if ( 'POST' === ( $_SERVER['REQUEST_METHOD'] ?? '' ) && ! empty( $_POST['sc_custom_submit'] ) ) {
	$nonce = isset( $_POST['sc_custom_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['sc_custom_nonce'] ) ) : '';
	if ( wp_verify_nonce( $nonce, 'sc_custom_inquiry' ) && function_exists( 'sc_submit_custom_inquiry' ) ) {
		$res = sc_submit_custom_inquiry( $_POST );
		if ( ! empty( $res['ok'] ) ) {
			$submitted = true;
		} else {
			$error_msg = $res['error'] ?? '';
		}
	}
}
?>

<section class="sc-band sc-subpage">
	<!-- 画廊感双栏页头 + 3 格交付指标 -->
	<div class="sc-framed sc-mk-hero">
		<div class="sc-mk-hero__copy">
			<span class="sc-kicker">/ <?php echo esc_html( sc_t( '私有定制与团队团购 · BESPOKE', 'CUSTOM & TEAM LICENSING' ) ); ?></span>
			<h1><?php echo esc_html( sc_t( '为你的团队定制专属 AI 工作台', 'Bespoke AI Workbenches for Teams' ) ); ?></h1>
			<p><?php echo esc_html( sc_t( '无论是 5 人以上团队批量采购，还是把你们内部的业务流程封装成专属工作台与 MCP，我们提供一站式私有交付。', 'From volume seat discounts to 1-on-1 private workbench & MCP builds tailored to your exact workflows.' ) ); ?></p>
		</div>
		<div class="sc-mk-stats">
			<div class="sc-mk-stat">
				<strong>24h</strong>
				<span><?php echo esc_html( sc_t( '评估与报价', 'Scoping SLA' ) ); ?></span>
			</div>
			<div class="sc-mk-stat">
				<strong>3–7d</strong>
				<span><?php echo esc_html( sc_t( '极速打样交付', 'Turnaround' ) ); ?></span>
			</div>
			<div class="sc-mk-stat">
				<strong>100%</strong>
				<span><?php echo esc_html( sc_t( '私有源码归属', 'IP Ownership' ) ); ?></span>
			</div>
		</div>
	</div>

	<!-- 两档方案悬浮质感卡片 -->
	<div class="sc-framed sc-pricing-stage">
		<div class="sc-custom-plans">
			<div class="sc-cloud-plan">
				<span class="sc-plan-eyebrow">01 / TEAM BUNDLE</span>
				<h2><?php echo esc_html( sc_t( '团队批量授权团购', 'Team Volume License' ) ); ?></h2>
				<p class="sc-plan-sub"><?php echo esc_html( sc_t( '5 个席位起购 · 统一授权管理与电子发票', '5+ seats · Unified team keys & corporate invoicing' ) ); ?></p>
				<div class="sc-plan-price">
					<strong><?php echo esc_html( sc_t( '7 折起', '30% Off' ) ); ?></strong>
				</div>
				<ul class="sc-pd-list">
					<li><?php echo esc_html( sc_t( '全店在售工作台、MCP 与 Skill 任意组合打包，支持一键分发给团队成员', 'Mix and match any Workbenches, MCP plugins, or Skills with team-wide distribution' ) ); ?></li>
					<li><?php echo esc_html( sc_t( '免费获得后续版本升级、设备解绑与新模型适配优化', 'Free ongoing updates, seat transfers, and new model optimizations' ) ); ?></li>
					<li><?php echo esc_html( sc_t( '提供正规企业增值税电子发票与专属技术支持群', 'Formal tax invoices and a dedicated support channel' ) ); ?></li>
				</ul>
			</div>

			<div class="sc-cloud-plan sc-cloud-plan--featured" style="--sc-spot-bg:url('<?php echo esc_url( SKILLCRAFT_URI . '/assets/img/spotlight-bg.jpg' ); ?>')">
				<span class="sc-plan-eyebrow sc-plan-eyebrow--gold">★ 02 / BESPOKE ENGINEERING</span>
				<h2><?php echo esc_html( sc_t( '1 对 1 私有工作台定制', '1-on-1 Bespoke Workbench' ) ); ?></h2>
				<p class="sc-plan-sub"><?php echo esc_html( sc_t( '3–7 个工作日交付 · 支持源码与私有内网交付', '3–7 day turnaround · Private hosting & source handover' ) ); ?></p>
				<div class="sc-plan-price">
					<strong><?php echo esc_html( sc_t( '按需报价', 'Custom' ) ); ?></strong>
				</div>
				<ul class="sc-pd-list sc-pd-list--dark">
					<li><?php echo esc_html( sc_t( '深度贴合你们的内部业务系统、数据库结构、电商流程或审批 SOP', 'Tailored to your exact internal systems, schemas, or operational SOPs' ) ); ?></li>
					<li><?php echo esc_html( sc_t( '支持内嵌 dsh、独立桌面端/Web 或远程 MCP 形态交付', 'Delivered as dsh plugin, standalone desktop/web desk, or remote MCP' ) ); ?></li>
					<li><?php echo esc_html( sc_t( '支持完全私有内网部署，知识产权 100% 归属贵司', 'Supports air-gapped private hosting with full IP ownership' ) ); ?></li>
				</ul>
			</div>
		</div>
	</div>

	<!-- 3 步合作流程 -->
	<div class="sc-framed sc-custom-steps">
		<div class="sc-cstep">
			<span>STEP 01</span>
			<strong><?php echo esc_html( sc_t( '提交场景需求', 'Share your workflow' ) ); ?></strong>
			<p><?php echo esc_html( sc_t( '填写下方表单或扫码联系客服，说明期望解决的痛点与团队规模。', 'Outline your target workflow, client stack, and team size below.' ) ); ?></p>
		</div>
		<div class="sc-cstep">
			<span>STEP 02</span>
			<strong><?php echo esc_html( sc_t( '方案评估与打样', 'Scope & prototype' ) ); ?></strong>
			<p><?php echo esc_html( sc_t( '24 小时内给出明确报价、交付周期与可交互沙箱 Demo。', 'Receive a fixed quote, timeline, and sandbox demo within 24h.' ) ); ?></p>
		</div>
		<div class="sc-cstep">
			<span>STEP 03</span>
			<strong><?php echo esc_html( sc_t( '正式交付与售后', 'Deliver & support' ) ); ?></strong>
			<p><?php echo esc_html( sc_t( '提供一键安装指令、使用文档与 30 天免费调优维护。', 'Delivered with one-line install, docs, and 30-day tuning support.' ) ); ?></p>
		</div>
	</div>

	<!-- 在线需求表单（高级双栏对称布局：左栏抱爱心吉祥物背书卡 + 右栏表单） -->
	<div class="sc-framed sc-custom-split">
		<aside class="sc-custom-visual">
			<div class="sc-custom-mascot">
				<img src="<?php echo esc_url( SKILLCRAFT_URI . '/assets/img/mascot/mascot-heart.png' ); ?>" alt="" width="118" height="150">
			</div>
			<span class="sc-kicker">/ <?php echo esc_html( sc_t( '核心团队直连', 'DIRECT TO ENGINEERS' ) ); ?></span>
			<h2><?php echo esc_html( sc_t( '提交定制或团购咨询', 'Start an inquiry' ) ); ?></h2>
			<p class="sc-custom-visual__desc">
				<?php echo esc_html( sc_t( '没有冗长的销售流程，你的需求将直接由一线研发与架构师评估，1 个工作日内通过邮件给出清晰方案与报价。', 'No sales middlemen — our engineers review every brief directly and reply within one business day.' ) ); ?>
			</p>

			<ul class="sc-custom-perks">
				<li>
					<strong>24h</strong>
					<span><?php echo esc_html( sc_t( '工作日内响应并给出评估方案', 'Business-day response & scoping' ) ); ?></span>
				</li>
				<li>
					<strong>NDA</strong>
					<span><?php echo esc_html( sc_t( '支持签署企业保密协议与私有交付', 'NDA & private air-gapped delivery' ) ); ?></span>
				</li>
				<li>
					<strong>30d</strong>
					<span><?php echo esc_html( sc_t( '交付后含 30 天免费调优与维护', '30-day complimentary tuning support' ) ); ?></span>
				</li>
			</ul>
		</aside>

		<div class="sc-custom-formwrap">
			<?php if ( $submitted ) : ?>
				<div class="sc-alert sc-alert--success">
					<?php echo esc_html( sc_t( '需求已收到！我们会尽快通过邮箱联系你确认方案细节。', 'Inquiry received! We will reach out by email shortly.' ) ); ?>
				</div>
			<?php endif; ?>
			<?php if ( $error_msg ) : ?>
				<div class="sc-alert sc-alert--error"><?php echo esc_html( $error_msg ); ?></div>
			<?php endif; ?>

			<form class="sc-form" method="post" action="">
				<?php wp_nonce_field( 'sc_custom_inquiry', 'sc_custom_nonce' ); ?>
				<input type="hidden" name="sc_custom_submit" value="1">
				<div style="position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden" aria-hidden="true">
					<label for="sc-c-web">Website</label>
					<input id="sc-c-web" name="c_website" type="text" tabindex="-1" autocomplete="off">
				</div>

				<div class="sc-form__row2">
					<div class="sc-field">
						<label for="c_name"><?php echo esc_html( sc_t( '你的称呼', 'Your name' ) ); ?></label>
						<input class="sc-input" id="c_name" name="c_name" type="text" required placeholder="<?php echo esc_attr( sc_t( '例如：陈先生', 'Alex Chen' ) ); ?>">
					</div>
					<div class="sc-field">
						<label for="c_email"><?php echo esc_html( sc_t( '联系邮箱', 'Work email' ) ); ?></label>
						<input class="sc-input" id="c_email" name="c_email" type="email" required placeholder="name@company.com">
					</div>
				</div>

				<div class="sc-form__row2">
					<div class="sc-field">
						<label for="c_type"><?php echo esc_html( sc_t( '咨询类型', 'Inquiry type' ) ); ?></label>
						<select class="sc-input" id="c_type" name="c_type">
							<option value="custom"><?php echo esc_html( sc_t( '1 对 1 私有 Skill 定制开发', '1-on-1 Bespoke Skill' ) ); ?></option>
							<option value="team"><?php echo esc_html( sc_t( '团队批量团购授权', 'Team Volume License' ) ); ?></option>
						</select>
					</div>
					<div class="sc-field">
						<label for="c_seats"><?php echo esc_html( sc_t( '团队人数 / 预期预算（选填）', 'Team size / budget (optional)' ) ); ?></label>
						<input class="sc-input" id="c_seats" name="c_seats" type="text" placeholder="<?php echo esc_attr( sc_t( '例如：15 人研发团队', 'e.g. 15 engineers' ) ); ?>">
					</div>
				</div>

				<div class="sc-field">
					<label for="c_brief"><?php echo esc_html( sc_t( '需求说明', 'Project details' ) ); ?></label>
					<textarea class="sc-input sc-textarea" id="c_brief" name="c_brief" rows="5" required placeholder="<?php echo esc_attr( sc_t( '简述你们希望用 Skill 解决的具体工作流、使用的 Agent 客户端（如 Cursor / Claude Code）等…', 'Describe the workflow you want to automate and which Agent clients your team uses…' ) ); ?>"></textarea>
				</div>

				<div>
					<button type="submit" class="sc-btn sc-btn--dark sc-btn--block sc-btn--lg">
						<?php echo esc_html( sc_t( '提交咨询需求 →', 'Submit inquiry →' ) ); ?>
					</button>
				</div>
			</form>
		</div>
	</div>
</section>

<?php
get_footer();
