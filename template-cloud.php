<?php
/**
 * Template Name: SkillCraft 云端 Agent 部署 / Cloud Dot Box
 *
 * 专属云端 Agent 节点（Dot 盒子）一键托管部署页：
 * 3 档配置方案（实时 CNY/USD 切换）+ 4 格核心架构优势 + 节点选择与一键预约部署表单（自动入库 + 双路发信）。
 *
 * @package SkillCraft
 */

get_header();

$submitted = false;
$error_msg = '';

if ( 'POST' === ( $_SERVER['REQUEST_METHOD'] ?? '' ) && ! empty( $_POST['sc_cloud_submit'] ) ) {
	$nonce = isset( $_POST['sc_cloud_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['sc_cloud_nonce'] ) ) : '';
	if ( wp_verify_nonce( $nonce, 'sc_cloud_deploy' ) && function_exists( 'sc_submit_custom_inquiry' ) ) {
		$region = sanitize_text_field( wp_unslash( $_POST['c_region'] ?? 'US-West' ) );
		$plan   = sanitize_text_field( wp_unslash( $_POST['c_plan'] ?? 'Dot Pro' ) );
		$note   = trim( sanitize_textarea_field( wp_unslash( $_POST['c_brief'] ?? '' ) ) );
		$payload = array(
			'c_website' => $_POST['c_website'] ?? '',
			'c_name'    => $_POST['c_name'] ?? '',
			'c_email'   => $_POST['c_email'] ?? '',
			'c_type'    => 'cloud',
			'c_seats'   => $plan . ' · ' . $region,
			'c_brief'   => '【云端 Agent 部署申请】方案：' . $plan . '｜节点地区：' . $region . "\n" . ( $note ?: '标准预装开箱即用' ),
		);
		$res = sc_submit_custom_inquiry( $payload );
		if ( ! empty( $res['ok'] ) ) {
			$submitted = true;
		} else {
			$error_msg = $res['error'] ?? '';
		}
	}
}
?>

<section class="sc-band sc-subpage sc-cloud-page">
	<!-- 1. 画廊感双栏页头 + 3 格基础设施指标 -->
	<div class="sc-framed sc-mk-hero">
		<div class="sc-mk-hero__copy">
			<span class="sc-kicker">/ <?php echo esc_html( sc_t( '云端 AGENT 托管 · DOT CLOUD BOX', 'CLOUD AGENT HOSTING · DOT BOX' ) ); ?></span>
			<h1><?php echo esc_html( sc_t( '专属云端 Dot 盒子', 'Private Dot Cloud Box' ) ); ?></h1>
			<p><?php echo esc_html( sc_t( '电脑关机、断网也能 24 小时持续运行。预装业务工作台、远程 MCP 网关与独立纯净 IP，开机即用，零运维负担。', 'Run 24/7 even when your laptop is closed. Pre-configured with your Workbenches, remote MCP gateway, and dedicated static IP.' ) ); ?></p>
		</div>
		<div class="sc-mk-stats">
			<div class="sc-mk-stat">
				<strong>99.9%</strong>
				<span><?php echo esc_html( sc_t( '全年在线率', 'Uptime SLA' ) ); ?></span>
			</div>
			<div class="sc-mk-stat">
				<strong>10 min</strong>
				<span><?php echo esc_html( sc_t( '极速开机交付', 'Fast setup' ) ); ?></span>
			</div>
			<div class="sc-mk-stat">
				<strong>4 节点</strong>
				<span><?php echo esc_html( sc_t( '美/新/港/德', 'US/SG/HK/EU' ) ); ?></span>
			</div>
		</div>
	</div>

	<!-- 2. 3 档 Dot 云盒高质感悬浮方案卡 -->
	<div class="sc-framed sc-pricing-stage">
		<div class="sc-cloud-plans">
			<div class="sc-cloud-plan">
				<span class="sc-plan-eyebrow">DOT STARTER</span>
				<h2><?php echo esc_html( sc_t( 'Dot 轻量云盒', 'Dot Starter Box' ) ); ?></h2>
				<p class="sc-plan-sub"><?php echo esc_html( sc_t( '适合个人 24/7 常驻运行与远程 MCP', 'Ideal for solo 24/7 agents & remote MCP' ) ); ?></p>
				<div class="sc-plan-price">
					<strong><?php echo sc_price_html( '¥99/月' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</div>
				<ul class="sc-pd-list">
					<li><?php echo esc_html( sc_t( '2 vCPU · 4 GB 内存 · 60 GB NVMe 固态硬盘', '2 vCPU · 4 GB RAM · 60 GB NVMe SSD' ) ); ?></li>
					<li><?php echo esc_html( sc_t( '美国硅谷 / 新加坡独立静态 IP', 'Dedicated static IP (US West / Singapore)' ) ); ?></li>
					<li><?php echo esc_html( sc_t( '预装 1 套已购业务工作台 + 远程 MCP 网关', '1 pre-installed Workbench + remote MCP gateway' ) ); ?></li>
					<li><?php echo esc_html( sc_t( '自动 HTTPS 证书与 Ed25519 授权预绑定', 'Auto HTTPS & pre-bound Ed25519 license' ) ); ?></li>
				</ul>
				<a class="sc-btn sc-btn--outline sc-btn--block" href="#cloud-deploy" data-pick-plan="Dot Starter"><?php echo esc_html( sc_t( '选择轻量云盒', 'Select Starter' ) ); ?></a>
			</div>

			<div class="sc-cloud-plan sc-cloud-plan--featured" style="--sc-spot-bg:url('<?php echo esc_url( SKILLCRAFT_URI . '/assets/img/spotlight-bg.jpg' ); ?>')">
				<span class="sc-plan-eyebrow sc-plan-eyebrow--gold">★ <?php echo esc_html( sc_t( '最受欢迎 · DOT PRO', 'MOST POPULAR · DOT PRO' ) ); ?></span>
				<h2><?php echo esc_html( sc_t( 'Dot 旗舰云盒', 'Dot Pro Box' ) ); ?></h2>
				<p class="sc-plan-sub"><?php echo esc_html( sc_t( '多工作台并发 + 浏览器群控自动化首选', 'Best for multi-desk & browser automation' ) ); ?></p>
				<div class="sc-plan-price">
					<strong><?php echo sc_price_html( '¥249/月' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</div>
				<ul class="sc-pd-list sc-pd-list--dark">
					<li><?php echo esc_html( sc_t( '4 vCPU · 8 GB 内存 · 120 GB NVMe 固态硬盘', '4 vCPU · 8 GB RAM · 120 GB NVMe SSD' ) ); ?></li>
					<li><?php echo esc_html( sc_t( '美西 / 新加坡 / 香港 / 德国节点任选', 'Choice of US, SG, HK, or EU nodes' ) ); ?></li>
					<li><?php echo esc_html( sc_t( '预装全部已购工作台 + 无头浏览器持久化环境', 'All purchased Workbenches + persistent browser' ) ); ?></li>
					<li><?php echo esc_html( sc_t( '每日自动快照备份 + 远程 dsh / 桌面端直连', 'Daily snapshots + direct dsh & desktop access' ) ); ?></li>
				</ul>
				<a class="sc-btn sc-btn--light sc-btn--block" href="#cloud-deploy" data-pick-plan="Dot Pro"><?php echo esc_html( sc_t( '立即部署旗舰云盒 →', 'Deploy Dot Pro →' ) ); ?></a>
			</div>

			<div class="sc-cloud-plan">
				<span class="sc-plan-eyebrow">ENTERPRISE CLUSTER</span>
				<h2><?php echo esc_html( sc_t( '企业私有集群', 'Private Cluster' ) ); ?></h2>
				<p class="sc-plan-sub"><?php echo esc_html( sc_t( '多节点高可用集群与企业私有知识库', 'Multi-node fleet & private vector RAG' ) ); ?></p>
				<div class="sc-plan-price">
					<strong><?php echo sc_price_html( '¥699/月' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</div>
				<ul class="sc-pd-list">
					<li><?php echo esc_html( sc_t( '8 vCPU · 16 GB 内存起，按需弹性扩容', '8+ vCPU · 16+ GB RAM with elastic scaling' ) ); ?></li>
					<li><?php echo esc_html( sc_t( '独立 VPC 隔离 + 多地区出口 IP 池', 'Private VPC isolation + multi-region IP pool' ) ); ?></li>
					<li><?php echo esc_html( sc_t( '支持团队多成员 RBAC 权限与私有知识库托管', 'Team RBAC permissions & private vector KB' ) ); ?></li>
					<li><?php echo esc_html( sc_t( '7×24 专属运维巡检与 SLA 可用性保障', '24/7 managed ops & SLA uptime guarantee' ) ); ?></li>
				</ul>
				<a class="sc-btn sc-btn--outline sc-btn--block" href="#cloud-deploy" data-pick-plan="Enterprise Cluster"><?php echo esc_html( sc_t( '预约集群部署', 'Request Cluster' ) ); ?></a>
			</div>
		</div>
	</div>

	<!-- 3. 4 格核心架构优势 -->
	<div class="sc-framed sc-cloud-feats">
		<div class="sc-cstep">
			<span>01 / ALWAYS-ON</span>
			<strong><?php echo esc_html( sc_t( '24/7 永不下线值守', '24/7 Always-On Execution' ) ); ?></strong>
			<p><?php echo esc_html( sc_t( '定时巡检、电商多店同步与社媒发布在云端全天候运行，合上笔记本也不中断。', 'Scheduled checks, store syncs, and publishing keep running even while your laptop sleeps.' ) ); ?></p>
		</div>
		<div class="sc-cstep">
			<span>02 / ISOLATED</span>
			<strong><?php echo esc_html( sc_t( '独享实例，数据完全隔离', 'Single-Tenant Isolation' ) ); ?></strong>
			<p><?php echo esc_html( sc_t( '每位客户独享独立虚拟机与独立磁盘，凭据与业务数据绝不与他人混用。', 'Dedicated VM and encrypted disk per customer — zero shared credentials or state.' ) ); ?></p>
		</div>
		<div class="sc-cstep">
			<span>03 / READY-TO-USE</span>
			<strong><?php echo esc_html( sc_t( '预装工作台与远程 MCP', 'Pre-Installed Workbenches' ) ); ?></strong>
			<p><?php echo esc_html( sc_t( '免配 Docker、Nginx 与 SSL，交付即拿到专属控制台地址与远程 MCP 接入点。', 'Skip Docker, Nginx, and SSL setup — receive your live console URL and MCP endpoint.' ) ); ?></p>
		</div>
		<div class="sc-cstep">
			<span>04 / MULTI-CLIENT</span>
			<strong><?php echo esc_html( sc_t( '全桌面端随时直连操控', 'Connect From Any Client' ) ); ?></strong>
			<p><?php echo esc_html( sc_t( '支持从 dsh、Claude Desktop、Cursor、Codex 与 Antigravity 直接连接你的云端盒子。', 'Control your Cloud Box from dsh, Claude Desktop, Cursor, Codex, and Antigravity.' ) ); ?></p>
		</div>
	</div>

	<!-- 4. 一键预约部署表单（复用自动入库 + 防垃圾 + 双路发信） -->
	<div class="sc-framed sc-custom-split" id="cloud-deploy">
		<aside class="sc-custom-visual">
			<div class="sc-custom-mascot">
				<img src="<?php echo esc_url( SKILLCRAFT_URI . '/assets/img/mascot/mascot-yay.png' ); ?>" alt="" width="112" height="112" loading="lazy" decoding="async">
			</div>
			<span class="sc-kicker">/ <?php echo esc_html( sc_t( '极速开通 · 10 分钟交付', 'FAST PROVISIONING' ) ); ?></span>
			<h2><?php echo esc_html( sc_t( '开通你的专属云端 Dot 盒子', 'Provision Your Cloud Box' ) ); ?></h2>
			<p class="sc-custom-visual__desc">
				<?php echo esc_html( sc_t( '选择地区与配置提交申请，我们为你初始化独立节点、预装所需工作台并将登录信息发送至你的邮箱。', 'Pick your region and tier — we provision your isolated node, pre-install your workbenches, and email your credentials.' ) ); ?>
			</p>
			<ul class="sc-custom-perks">
				<li><strong>US</strong><span><?php echo esc_html( sc_t( '美国硅谷（圣荷西）· 跨境电商与 AI API 首选', 'US West (San Jose) · Low-latency AI & commerce' ) ); ?></span></li>
				<li><strong>SG</strong><span><?php echo esc_html( sc_t( '新加坡 / 香港 · 亚太直连低延迟', 'Singapore / HK · Fast APAC routing' ) ); ?></span></li>
				<li><strong>EU</strong><span><?php echo esc_html( sc_t( '德国法兰克福 · 欧洲站群与合规托管', 'Frankfurt (EU) · European compliance node' ) ); ?></span></li>
			</ul>
		</aside>

		<div class="sc-custom-formwrap">
			<?php if ( $submitted ) : ?>
				<div class="sc-alert sc-alert--success">
					<?php echo esc_html( sc_t( '云端部署申请已收到！确认邮件已发至你的邮箱，我们将尽快为你开通专属 Dot 节点。', 'Deployment request received! Check your inbox for confirmation — we will provision your Dot Box shortly.' ) ); ?>
				</div>
			<?php endif; ?>
			<?php if ( $error_msg ) : ?>
				<div class="sc-alert sc-alert--error"><?php echo esc_html( $error_msg ); ?></div>
			<?php endif; ?>

			<form class="sc-form" method="post" action="#cloud-deploy">
				<?php wp_nonce_field( 'sc_cloud_deploy', 'sc_cloud_nonce' ); ?>
				<input type="hidden" name="sc_cloud_submit" value="1">
				<div style="position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden" aria-hidden="true">
					<input name="c_website" type="text" tabindex="-1" autocomplete="off">
				</div>

				<div class="sc-form__row2">
					<div class="sc-field">
						<label for="sc-cl-name"><?php echo esc_html( sc_t( '你的称呼', 'Your name' ) ); ?></label>
						<input class="sc-input" id="sc-cl-name" name="c_name" type="text" required placeholder="<?php echo esc_attr( sc_t( '例如：林先生', 'Alex Lin' ) ); ?>">
					</div>
					<div class="sc-field">
						<label for="sc-cl-email"><?php echo esc_html( sc_t( '接收节点信息的邮箱', 'Delivery email' ) ); ?></label>
						<input class="sc-input" id="sc-cl-email" name="c_email" type="email" required placeholder="name@company.com">
					</div>
				</div>

				<div class="sc-form__row2">
					<div class="sc-field">
						<label for="sc-cl-plan"><?php echo esc_html( sc_t( '云盒配置方案', 'Cloud Box Tier' ) ); ?></label>
						<select class="sc-input" id="sc-cl-plan" name="c_plan">
							<option value="Dot Starter"><?php echo esc_html( sc_t( 'Dot 轻量云盒 (2C4G)', 'Dot Starter (2C4G)' ) ); ?></option>
							<option value="Dot Pro" selected><?php echo esc_html( sc_t( 'Dot 旗舰云盒 (4C8G · 推荐)', 'Dot Pro (4C8G · Popular)' ) ); ?></option>
							<option value="Enterprise Cluster"><?php echo esc_html( sc_t( '企业私有集群 (8C16G+)', 'Enterprise Cluster (8C16G+)' ) ); ?></option>
						</select>
					</div>
					<div class="sc-field">
						<label for="sc-cl-region"><?php echo esc_html( sc_t( '节点地区', 'Region' ) ); ?></label>
						<select class="sc-input" id="sc-cl-region" name="c_region">
							<option value="US-West (San Jose)"><?php echo esc_html( sc_t( '美国硅谷（圣荷西）', 'US West (San Jose)' ) ); ?></option>
							<option value="Singapore"><?php echo esc_html( sc_t( '新加坡', 'Singapore' ) ); ?></option>
							<option value="Hong Kong"><?php echo esc_html( sc_t( '中国香港', 'Hong Kong' ) ); ?></option>
							<option value="Frankfurt (EU)"><?php echo esc_html( sc_t( '德国法兰克福', 'Frankfurt (EU)' ) ); ?></option>
						</select>
					</div>
				</div>

				<div class="sc-field">
					<label for="sc-cl-brief"><?php echo esc_html( sc_t( '需要预装的工作台或补充需求', 'Workbenches to pre-install & notes' ) ); ?></label>
					<textarea class="sc-input sc-textarea" id="sc-cl-brief" name="c_brief" rows="3" required placeholder="<?php echo esc_attr( sc_t( '例如：预装服务器掌管工作台 + 跨境电商工作台，需要开启远程 MCP 接入...', 'e.g. Pre-install Server Command + Commerce Workbench with remote MCP enabled...' ) ); ?>"></textarea>
				</div>

				<div>
					<button type="submit" class="sc-btn sc-btn--dark sc-btn--lg">
						<?php echo esc_html( sc_t( '提交云端部署申请 →', 'Request Cloud Deployment →' ) ); ?>
					</button>
				</div>
			</form>
		</div>
	</div>
</section>

<?php
get_footer();
