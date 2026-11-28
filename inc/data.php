<?php
/**
 * 首页与全站结构化原创示例数据（面向 AI 业务工作台 / MCP 插件 / Skill 能力包商店）。
 *
 * 品牌名全部通过 sc_site_name() 自动读取 WordPress「设置 → 常规 → 站点标题」。
 *
 * @package SkillCraft
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Hero 4 行错位滚动的工作台 / MCP / Skill 标签流 */
function sc_skill_streams() {
	return array(
		array(
			array( 'name' => '服务器掌管工作台', 'name_en' => 'Server Command Desk', 'price' => '¥299', 'desc' => '多节点监控、网站管理、面板接管与 SSH 巡检一站掌控', 'desc_en' => 'Unified server metrics, sites, panels, and automated SSH ops' ),
			array( 'name' => '跨境电商运营工作台', 'name_en' => 'Commerce Ops Desk', 'price' => '¥199/月', 'desc' => '选品、商品上架、多店同步与内容增长全链路闭环', 'desc_en' => 'End-to-end sourcing, catalog publishing, and multi-store ops' ),
			array( 'name' => 'SQL 慢查询诊断 MCP', 'name_en' => 'SQL Profiler MCP', 'price' => '¥69', 'desc' => '直连只读库解析 EXPLAIN 与锁等待，输出可执行索引 DDL', 'desc_en' => 'Inspect execution plans and lock waits with ready-to-run DDL' ),
			array( 'name' => '知识库与提示词工作台', 'name_en' => 'Knowledge & Prompt Desk', 'price' => '¥249', 'desc' => '沉淀团队文档、提示词模板与私有检索问答工作流', 'desc_en' => 'Centralize team docs, prompts, and private RAG workflows' ),
			array( 'name' => '合同风险审阅 Skill', 'name_en' => 'Contract Redline Skill', 'price' => '¥49', 'desc' => '逐条标注单方免责、违约金与知识产权归属风险点', 'desc_en' => 'Highlight liability traps, indemnity clauses, and IP risks' ),
		),
		array(
			array( 'name' => '浏览器与手机群控台', 'name_en' => 'Browser & Phone Fleet', 'price' => '¥269/月', 'desc' => '多指纹浏览器与云手机集群可视化调度，Agent 自动值守', 'desc_en' => 'Orchestrate browser profiles and mobile fleets with AI agents' ),
			array( 'name' => 'PR 安全审查 Skill', 'name_en' => 'PR Security Skill', 'price' => '¥59/月', 'desc' => '在合并前拦截权限越界、SQL 拼接与竞态条件隐患', 'desc_en' => 'Catch auth bypasses, injection sinks, and race conditions pre-merge' ),
			array( 'name' => '全渠道内容矩阵工作台', 'name_en' => 'Content Matrix Desk', 'price' => '¥249', 'desc' => '一次创作自动适配公众号、小红书、独立站与社媒排期', 'desc_en' => 'Draft once and publish tailored variants across every channel' ),
			array( 'name' => '无头浏览器采集 MCP', 'name_en' => 'Headless CDP MCP', 'price' => '¥89', 'desc' => '持久化登录态、网络拦截与结构化 DOM 提取开箱即用', 'desc_en' => 'Persistent sessions, request interception, and structured DOM capture' ),
			array( 'name' => '多站点 SEO 监控台', 'name_en' => 'Multi-Site SEO Desk', 'price' => '¥189', 'desc' => '批量巡检收录、死链、Schema 与核心着陆页转化表现', 'desc_en' => 'Monitor indexing, broken links, schema, and landing conversions' ),
		),
		array(
			array( 'name' => '客服与工单中枢工作台', 'name_en' => 'Support Desk Hub', 'price' => '¥229', 'desc' => '接入邮件、IM 与知识库，自动草拟回复并追踪售后进度', 'desc_en' => 'Unify email, chat, and KB with agent-drafted ticket responses' ),
			array( 'name' => '社媒评论与私信 MCP', 'name_en' => 'Social Inbox MCP', 'price' => '¥79', 'desc' => '统一拉取多平台评论线索，自动分类高意向客户并提醒', 'desc_en' => 'Aggregate social comments and flag high-intent buyer leads' ),
			array( 'name' => '财务三表核验 Skill', 'name_en' => 'Financial Audit Skill', 'price' => '¥99/月', 'desc' => '自动抽取三表核心指标，校验勾稽关系并定位异常附注', 'desc_en' => 'Cross-verify financial statements and trace anomalies to footnotes' ),
			array( 'name' => '地图与本地商机工作台', 'name_en' => 'Local Leads Map Desk', 'price' => '¥199', 'desc' => '按区域批量检索商户、清洗联系方式并沉淀 CRM 线索池', 'desc_en' => 'Discover regional prospects on maps and sync clean CRM leads' ),
			array( 'name' => 'UI 走查与无障碍 Skill', 'name_en' => 'UI & A11y QA Skill', 'price' => '¥39', 'desc' => '检查间距阶、对比度与响应式溢出，直接输出 CSS 补丁', 'desc_en' => 'Verify spacing, contrast, and overflow with drop-in CSS patches' ),
		),
		array(
			array( 'name' => '智能体编排中控台', 'name_en' => 'Agent Orchestrator', 'price' => '¥349', 'desc' => '多 Agent 角色分工、定时巡检任务与执行日志全景看板', 'desc_en' => 'Multi-agent role routing, cron schedules, and live execution logs' ),
			array( 'name' => '数据库备份与迁移 MCP', 'name_en' => 'DB Backup & Sync MCP', 'price' => '¥75', 'desc' => '一键快照、跨机安全迁移与定时健康校验，零手工失误', 'desc_en' => 'One-click snapshots, safe migrations, and automated integrity checks' ),
			array( 'name' => 'PRD 边界拆解 Skill', 'name_en' => 'PRD State Skill', 'price' => '¥29.9', 'desc' => '从需求描述反向推导异常分支、幂等策略与 QA 验收用例', 'desc_en' => 'Derive edge cases, idempotency rules, and QA checklists from specs' ),
			array( 'name' => '域名与证书托管工作台', 'name_en' => 'Domain & SSL Desk', 'price' => '¥159', 'desc' => '集中管理 DNS 解析、SSL 续期告警与边缘节点配置同步', 'desc_en' => 'Manage DNS records, SSL renewal alerts, and edge configs in one view' ),
			array( 'name' => 'Prompt 评估回归 Skill', 'name_en' => 'Prompt Eval Skill', 'price' => '¥75', 'desc' => '自动构建回归测试集，量化每次模型与提示词改动准确率', 'desc_en' => 'Build regression datasets and quantify accuracy across prompt updates' ),
		),
	);
}

/** 已购用户真实反馈长廊 */
function sc_developer_cases() {
	return array(
		array(
			'title'    => '「服务器掌管工作台把 12 台机器和站点收进一个界面，省心太多」',
			'title_en' => '"The Server Workbench unified all 12 nodes and sites into one clean desk"',
			'desc'     => '以前每天要在 SSH、宝塔和监控面板之间来回切，现在打开工作台一眼看清 CPU、内存和磁盘，异常直接让 Agent 查日志处理。',
			'desc_en'  => 'No more juggling SSH tabs and panels. CPU, memory, and disk health sit in one place with an Agent ready to triage logs.',
			'author'   => '林先生 · 独立站技术负责人',
			'author_en'=> 'M. Lin · Infra Lead',
		),
		array(
			'title'    => '「电商运营工作台帮我们一个人管 6 个站点，上架效率翻了 4 倍」',
			'title_en' => '"One operator now manages 6 storefronts with 4x faster catalog launches"',
			'desc'     => '选品、商品详情改写、多语种同步和日常巡检都在同一个工作台里完成，买断后团队直接复用，回本极快。',
			'desc_en'  => 'Sourcing, localization, publishing, and daily checks all run inside one workbench—paid for itself in week one.',
			'author'   => '周女士 · 跨境电商品牌主理人',
			'author_en'=> 'Y. Zhou · E-Commerce Founder',
		),
		array(
			'title'    => '「把无头采集 MCP 挂进 Cursor 后，原本半天的竞品调研 5 分钟跑完」',
			'title_en' => '"Mounting the Headless Browser MCP turned half-day research into 5 minutes"',
			'desc'     => '持久化登录态和结构化提取非常稳，配合工作台里的知识库模块直接沉淀成周报，真正做到开箱即用。',
			'desc_en'  => 'Persistent sessions and clean DOM extraction work out of the box and feed straight into our knowledge base.',
			'author'   => '陈工 · 自动化增长工程师',
			'author_en'=> 'S. Chen · Growth Engineer',
		),
		array(
			'title'    => '「内容矩阵工作台 + 提示词库，让团队输出风格彻底统一」',
			'title_en' => '"The Content Matrix Workbench aligned our entire editorial voice"',
			'desc'     => '知识库、提示词模板和分发排期全在一起，新人第一天上手就能产出符合品牌调性的多平台内容。',
			'desc_en'  => 'Knowledge base, prompt templates, and publishing schedules live together so new hires ship on-brand copy on day one.',
			'author'   => '苏女士 · 新媒体运营总监',
			'author_en'=> 'Y. Su · Content Director',
		),
	);
}

/** 4 列建筑网格商品陈列（优先读后台 sc_skill，否则用官方工作台 / MCP / Skill 示例） */
function sc_marketplace_products() {
	if ( function_exists( 'get_posts' ) ) {
		$posts = get_posts( array(
			'post_type'   => 'sc_skill',
			'post_status' => 'publish',
			'numberposts' => 12,
		) );
		if ( ! empty( $posts ) ) {
			$out = array();
			foreach ( $posts as $post ) {
				$title_en = get_post_meta( $post->ID, '_sc_title_en', true );
				$subtitle = get_post_meta( $post->ID, '_sc_subtitle', true );
				$desc     = $subtitle ?: wp_strip_all_tags( $post->post_excerpt ?: wp_trim_words( $post->post_content, 24 ) );
				$billing  = get_post_meta( $post->ID, '_sc_billing', true ) ?: sc_t( '买断制', 'One-time' );
				$raw_tags = get_post_meta( $post->ID, '_sc_tags', true );
				$tags     = $raw_tags ? array_slice( array_values( array_filter( array_map( 'trim', explode( ',', $raw_tags ) ) ) ), 0, 4 ) : array();
				$cat      = get_post_meta( $post->ID, '_sc_cat', true ) ?: 'workbench';
				if ( ! in_array( $cat, array( 'workbench', 'mcp', 'skill' ), true ) ) {
					$cat = 'workbench';
				}
				$out[] = array(
					'id'          => $post->ID,
					'title'       => get_the_title( $post->ID ),
					'title_en'    => $title_en ?: get_the_title( $post->ID ),
					'cat'         => $cat,
					'tags'        => $tags,
					'preview_img' => get_post_meta( $post->ID, '_sc_preview_img', true ),
					'featured'    => (int) get_post_meta( $post->ID, '_sc_featured', true ),
					'badge'       => $billing,
					'badge_en'    => '订阅制' === $billing ? 'Monthly' : ( '免费' === $billing ? 'Free' : 'One-time' ),
					'desc'        => $desc,
					'desc_en'     => $desc,
					'price'       => get_post_meta( $post->ID, '_sc_price', true ) ?: '¥299',
					'views'       => (int) get_post_meta( $post->ID, '_sc_views', true ) ?: 1280,
					'heat'        => (int) get_post_meta( $post->ID, '_sc_heat', true ) ?: 92,
					'url'         => get_permalink( $post->ID ),
				);
			}
			return $out;
		}
	}

	return array(
		array(
			'title'    => '基础设施与服务器掌管工作台',
			'title_en' => 'Server & Infra Command Workbench',
			'cat'      => 'workbench',
			'tags'     => array( '服务器', '网站', '面板', '监控' ),
			'badge'    => '买断制', 'badge_en' => 'One-time',
			'desc'     => '集中掌管多台云主机、网站、面板与系统负载，把 SSH 排障、部署与日常巡检交给 Agent 自动执行。',
			'desc_en'  => 'Unified server, site, panel, and load management with automated Agent triage and deployments.',
			'price'    => '¥299',
			'views'    => 5420, 'heat' => 99,
			'url'      => home_url( '/skills/' ),
		),
		array(
			'title'    => '跨境电商全链路运营工作台',
			'title_en' => 'Cross-Border Commerce Workbench',
			'cat'      => 'workbench',
			'tags'     => array( '商品', '运营', '多店同步' ),
			'badge'    => '订阅制', 'badge_en' => 'Monthly',
			'desc'     => '打通选品采集、多语种详情页生成、商品上架与店铺日常运营，单人也能高效管理多站点矩阵。',
			'desc_en'  => 'End-to-end product sourcing, localized listings, and multi-store operations in one desk.',
			'price'    => '¥199/月',
			'views'    => 4180, 'heat' => 97,
			'url'      => home_url( '/skills/' ),
		),
		array(
			'title'    => '全渠道内容矩阵与知识库工作台',
			'title_en' => 'Content Matrix & Knowledge Desk',
			'cat'      => 'workbench',
			'tags'     => array( '内容', '知识库', '提示词' ),
			'badge'    => '买断制', 'badge_en' => 'One-time',
			'desc'     => '统一沉淀企业知识库、提示词模板与多平台内容排期，支持一键改写并分发至公众号、小红书与独立站。',
			'desc_en'  => 'Centralize knowledge bases, prompt libraries, and multi-channel content publishing.',
			'price'    => '¥249',
			'views'    => 3690, 'heat' => 95,
			'url'      => home_url( '/skills/' ),
		),
		array(
			'title'    => '浏览器与手机群控自动化工作台',
			'title_en' => 'Browser & Device Fleet Workbench',
			'cat'      => 'workbench',
			'tags'     => array( '浏览器', '手机', '智能体' ),
			'badge'    => '订阅制', 'badge_en' => 'Monthly',
			'desc'     => '可视化编排多指纹浏览器与云手机集群，结合智能体自动完成养号、表单交互与数据回传。',
			'desc_en'  => 'Orchestrate multi-profile browsers and mobile device fleets with autonomous agents.',
			'price'    => '¥269/月',
			'views'    => 3240, 'heat' => 94,
			'url'      => home_url( '/skills/' ),
		),
		array(
			'title'    => '数据库与 SQL 慢查询诊断 MCP',
			'title_en' => 'SQL Profiler & Tuning MCP',
			'cat'      => 'mcp',
			'tags'     => array( 'MySQL', 'EXPLAIN', 'MCP' ),
			'badge'    => '买断制', 'badge_en' => 'One-time',
			'desc'     => '标准 MCP 服务，直接连接只读实例解析执行计划、锁等待与索引选择性，输出可执行 DDL 与回滚脚本。',
			'desc_en'  => 'Standard MCP server analyzing EXPLAIN plans and locks with ready-to-run DDL rewrites.',
			'price'    => '¥69',
			'views'    => 2870, 'heat' => 93,
			'url'      => home_url( '/skills/' ),
		),
		array(
			'title'    => '浏览器无头采集与登录态 MCP',
			'title_en' => 'Headless Browser & Auth MCP',
			'cat'      => 'mcp',
			'tags'     => array( 'CDP', '采集', 'MCP' ),
			'badge'    => '买断制', 'badge_en' => 'One-time',
			'desc'     => '为你的 Agent 提供持久化登录态、网络拦截与结构化 DOM 提取能力，开箱即用。',
			'desc_en'  => 'Give your Agent persistent browser sessions, network hooks, and structured DOM capture.',
			'price'    => '¥89',
			'views'    => 2510, 'heat' => 92,
			'url'      => home_url( '/skills/' ),
		),
		array(
			'title'    => 'PR 架构与安全审查 Skill',
			'title_en' => 'PR Security & Arch Guard Skill',
			'cat'      => 'skill',
			'tags'     => array( 'Security', 'Code Review' ),
			'badge'    => '订阅制', 'badge_en' => 'Monthly',
			'desc'     => '深度扫描鉴权绕过、越权访问、N+1 查询与并发竞态，在代码合入前给出精确到行号的修复建议。',
			'desc_en'  => 'Catch auth bypasses, IDOR, N+1 queries, and race conditions before merge.',
			'price'    => '¥59/月',
			'views'    => 2190, 'heat' => 90,
			'url'      => home_url( '/skills/' ),
		),
		array(
			'title'    => '合同条款风险与红线审阅 Skill',
			'title_en' => 'Contract Redline Reviewer Skill',
			'cat'      => 'skill',
			'tags'     => array( '合同审阅', 'NDA' ),
			'badge'    => '买断制', 'badge_en' => 'One-time',
			'desc'     => '针对 SaaS 采购、外包研发与保密协议，高亮不对等责任条款并生成温和专业的修改措辞。',
			'desc_en'  => 'Flag asymmetric liability clauses in SaaS, MSA, and NDA drafts with balanced redlines.',
			'price'    => '¥49',
			'views'    => 1860, 'heat' => 88,
			'url'      => home_url( '/skills/' ),
		),
	);
}

/** 暗色 3 步购买与上手交互区 */
function sc_onboarding_steps() {
	return array(
		array(
			'num'      => '01',
			'title'    => '按业务挑选工作台或插件',
			'title_en' => 'Pick your Workbench, MCP, or Skill',
			'desc'     => '覆盖服务器运维、电商运营、内容知识库与群控自动化等场景，先看实机界面，再按需买断或订阅。',
			'desc_en'  => 'Choose from vertical workbenches, MCP servers, and Skill packs with live previews and flexible licensing.',
		),
		array(
			'num'      => '02',
			'title'    => '下单自动发货安装包与授权码',
			'title_en' => 'Instant delivery & license key',
			'desc'     => '完成下单后秒级发放专属授权码、工作台安装包与一行启动指令，随时在「会员中心」查看和重装。',
			'desc_en'  => 'Receive your personal license key, installer, and setup command immediately in your member console.',
		),
		array(
			'num'      => '03',
			'title'    => '本地启动工作台或挂载进 Agent',
			'title_en' => 'Launch locally or mount to your Agent',
			'desc'     => '独立桌面/Web 工作台开箱即用，MCP 与 Skill 支持一键接入 Cursor、Claude Code、Codex 与 Antigravity。',
			'desc_en'  => 'Run standalone desktop/web workbenches or mount MCP & Skills into Cursor, Claude Code, and Antigravity.',
		),
	);
}

/** 支持的 Agent 生态（纵向滚动） */
function sc_supported_agents() {
	return array(
		array( 'name' => 'Cursor',      'color' => '#111111', 'mark' => 'Cu', 'logo' => 'cursor.svg' ),
		array( 'name' => 'Claude Code', 'color' => '#d97757', 'mark' => 'Cc', 'logo' => 'claude.svg' ),
		array( 'name' => 'Codex CLI',   'color' => '#10a37f', 'mark' => 'Cx', 'logo' => 'codex.svg' ),
		array( 'name' => 'Windsurf',    'color' => '#09b6a2', 'mark' => 'Ws', 'logo' => 'windsurf.svg' ),
		array( 'name' => 'Antigravity', 'color' => '#1677ff', 'mark' => 'Ag', 'logo' => 'gemini.svg' ),
		array( 'name' => 'Desktop App', 'color' => '#6366f1', 'mark' => 'Dk', 'logo' => 'desktop.svg' ),
	);
}

/** 自营安全与售后三大保障 */
function sc_security_pillars() {
	return array(
		array(
			'index'    => '01 / PRODUCTION-READY',
			'title'    => '全栈自研工作台，真实业务反复验证',
			'title_en' => 'Built & battle-tested in production',
			'desc'     => '每一套工作台、MCP 与 Skill 均源自我们团队自用的真实业务工具，不是拼凑的玩具，买回就能跑通流程。',
			'desc_en'  => 'Every workbench, MCP plugin, and Skill comes from our own production tooling—ready for real workloads on day one.',
		),
		array(
			'index'    => '02 / LOCAL-FIRST PRIVACY',
			'title'    => '本地优先架构，核心数据不离你的机器',
			'title_en' => 'Local-first privacy & isolation',
			'desc'     => '服务器密钥、店铺凭证与私有知识库全部保存在你自己的本地环境或私有服务器，严格最小权限隔离。',
			'desc_en'  => 'Credentials, store tokens, and private knowledge bases stay on your own machine or private server.',
		),
		array(
			'index'    => '03 / INSTANT DELIVERY',
			'title'    => '24 小时自动发货 & 持续迭代售后',
			'title_en' => 'Instant delivery & ongoing updates',
			'desc'     => '下单即发专属授权码与安装口令，买断版终身享免费版本更新；部署或二开定制均有专属客服一对一协助。',
			'desc_en'  => 'Immediate license delivery with free version updates and dedicated 1-on-1 deployment support.',
		),
	);
}

/** 常见问题 FAQ（优先读后台保存项，全部为买家视角） */
function sc_faqs() {
	$saved = get_option( 'skillcraft_faqs', array() );
	if ( is_array( $saved ) && ! empty( $saved ) ) {
		return $saved;
	}
	return array(
		array(
			'q'    => '工作台、MCP 插件和 Skill 能力包有什么区别？',
			'q_en' => 'What is the difference between Workbenches, MCP plugins, and Skills?',
			'a'    => '「业务工作台」是带完整可视化界面的独立系统（如服务器掌管、电商运营、内容知识库），开箱即用；「MCP 插件」是为 Agent 扩展外部工具能力的标准服务；「Skill 能力包」则是专注特定任务的轻量专家 SOP，三者可单独购买也可组合使用。',
			'a_en' => 'Workbenches are complete visual apps for vertical workflows; MCP plugins give your Agent external tool capabilities; Skill packs are lightweight expert SOPs. Buy individually or combine freely.',
		),
		array(
			'q'    => '购买后怎么发货？需要等人工处理吗？',
			'q_en' => 'How is my purchase delivered? Is it automatic?',
			'a'    => '全自动秒级发货。下单完成后页面会立刻展示你的专属授权码、工作台安装包与一行启动指令，同时自动归档到「会员中心」，随时可以找回重装。',
			'a_en' => 'Delivery is 100% instant and automated. Your license key, package link, and setup command appear right after checkout and stay saved in your Account.',
		),
		array(
			'q'    => '支持哪些系统和 AI Agent 客户端？',
			'q_en' => 'Which platforms and Agent clients are supported?',
			'a'    => '业务工作台支持 Windows / macOS 桌面端与自托管 Web；MCP 插件与 Skill 能力包兼容所有标准协议客户端，包括 Cursor、Claude Code、Codex CLI、Windsurf、Antigravity 等。',
			'a_en' => 'Workbenches run on Windows, macOS, and self-hosted Web; MCP & Skills work with Cursor, Claude Code, Codex CLI, Windsurf, and Antigravity.',
		),
		array(
			'q'    => '买断制和订阅制有什么区别？后续更新怎么算？',
			'q_en' => 'How do one-time purchases and monthly subscriptions differ?',
			'a'    => '买断制一次付费终身使用，包含后续所有小版本迭代与 Bug 修复，无隐藏续费；订阅制适用于需要持续跟随外部平台接口适配的工作台或插件，随时可在会员中心管理续期。',
			'a_en' => 'One-time items grant lifetime access with free updates; monthly items cover continuous external API maintenance and can be managed anytime.',
		),
		array(
			'q'    => '可以按我们自己的业务流程定制专属工作台吗？',
			'q_en' => 'Can you build a custom workbench for our workflow?',
			'a'    => '完全可以。我们在「定制与团购」页面提供 1 对 1 私有工作台定制开发与团队批量授权服务，可按你的服务器、内部系统或电商流程量身打造。',
			'a_en' => 'Yes! Visit our Custom & Team page for 1-on-1 private workbench development and volume licensing.',
		),
	);
}

/** FAQ 独立页「安装与使用指南」条目（优先读后台保存项） */
function sc_faq_guides() {
	$saved = get_option( 'skillcraft_guides', array() );
	if ( is_array( $saved ) && ! empty( $saved ) ) {
		return $saved;
	}
	return array(
		array(
			'cat'        => '工作台部署', 'cat_en' => 'Workbench Setup',
			'title'      => '业务工作台本地安装与授权激活指南（Windows / macOS / Docker）',
			'title_en'   => 'Installing & activating Workbenches on Windows, macOS & Docker',
			'summary'    => '3 分钟完成工作台初始化、输入专属授权码并连接你的第一台服务器或业务账号。',
			'summary_en' => 'Initialize your workbench, apply your license key, and connect your first workspace in 3 minutes.',
			'url'        => home_url( '/skills/' ),
		),
		array(
			'cat'        => 'MCP 与 Skill', 'cat_en' => 'MCP & Skills',
			'title'      => '在 Cursor / Claude Code / Antigravity 中一行命令挂载 MCP 与 Skill',
			'title_en'   => 'Mounting MCP plugins & Skills in Cursor, Claude Code & Antigravity',
			'summary'    => '下单获取专属口令后，复制到终端即可自动完成配置写入、依赖检查与沙箱试跑。',
			'summary_en' => 'Paste your post-purchase install command to configure, verify, and run your first test.',
			'url'        => home_url( '/skills/' ),
		),
		array(
			'cat'        => '安全与隐私', 'cat_en' => 'Security & Privacy',
			'title'      => '本地凭据加密存储与最小权限隔离说明',
			'title_en'   => 'Local credential encryption & least-privilege isolation',
			'summary'    => '了解工作台如何将 SSH 密钥、API Token 与业务数据严格保存在你的本地设备。',
			'summary_en' => 'See how SSH keys, API tokens, and business records stay strictly on your own machine.',
			'url'        => home_url( '/faq/' ),
		),
		array(
			'cat'        => '团队与定制', 'cat_en' => 'Teams & Custom',
			'title'      => '团队多席位授权分配与私有工作台定制交付流程',
			'title_en'   => 'Team seat licensing & private custom workbench delivery',
			'summary'    => '支持 5 人以上团队批量折扣，以及按企业专属业务流程 1 对 1 定制交付源码。',
			'summary_en' => 'Volume discounts for 5+ seats and 1-on-1 custom workbench delivery with source handover.',
			'url'        => home_url( '/custom/' ),
		),
	);
}

/** 更新日志独立页时间线（优先读后台「更新日志」CPT 发布项，其次读后台选项，最后回退示例） */
function sc_changelog_entries() {
	if ( function_exists( 'sc_changelog_from_cpt' ) ) {
		$cpt = sc_changelog_from_cpt();
		if ( ! empty( $cpt ) ) {
			return $cpt;
		}
	}
	$saved = get_option( 'skillcraft_changelog', array() );
	if ( is_array( $saved ) && ! empty( $saved ) ) {
		return $saved;
	}
	return array(
		array(
			'version'    => 'v2.4.0',
			'date'       => '2026-11-26',
			'type'       => 'feature',
			'badge'      => '新特性', 'badge_en' => 'New Feature',
			'title'      => '正式上线「业务工作台 + MCP + Skill」三大形态矩阵',
			'title_en'   => 'Launched Workbenches, MCP Plugins & Skills Catalog',
			'summary'    => '覆盖服务器掌管、跨境电商运营、内容知识库与设备群控 4 套核心工作台，详情页支持实机截图展示。',
			'summary_en' => 'Released 4 flagship business workbenches with live interface previews and WP Media Library screenshot support.',
			'items'      => array(
				array( 'zh' => '正式上架基础设施掌管、跨境电商运营、内容知识库与设备群控 4 套核心业务工作台', 'en' => 'Released 4 flagship business workbenches covering Ops, Commerce, Content, and Device Fleets' ),
				array( 'zh' => 'WP 后台商品编辑支持直接上传工作台实机截图与自定义内置模块标签', 'en' => 'Added media-library screenshot uploader and module tag editor in WP Admin' ),
				array( 'zh' => '全站品牌名自动跟随 WordPress 站点标题配置，无需改代码即可换牌', 'en' => 'Site branding now syncs automatically with WordPress Site Title settings' ),
			),
		),
		array(
			'version'    => 'v2.3.0',
			'date'       => '2026-11-12',
			'type'       => 'improve',
			'badge'      => '体验优化', 'badge_en' => 'Improvement',
			'title'      => '会员中心、定制团购页与热度指标全面升级',
			'title_en'   => 'Member Console, Custom Page & Live Metrics Upgrade',
			'summary'    => '会员中心新增终端一键同步卡与已购授权口令快捷复制，作品集市支持多维筛选。',
			'summary_en' => 'Upgraded Member Console with dark CLI sync card, one-click key copy, and multi-axis catalog filters.',
			'items'      => array(
				array( 'zh' => '上线独立「定制与团购」页面，支持在线提交 1 对 1 私有工作台定制需求', 'en' => 'Launched dedicated Custom & Team Volume page with native inquiry form' ),
				array( 'zh' => '商品卡片与详情页支持展示真实浏览量与热度指数', 'en' => 'Added live view counts and heat indicators across catalog and product pages' ),
			),
		),
	);
}

/** 首页可编辑文案（优先读 WP 后台选项，为空回退默认双语文案，品牌名自动跟随 WP 设置） */
function sc_copy( $key, $default_zh, $default_en ) {
	$opt = get_option( 'skillcraft_copy', array() );
	if ( ! is_array( $opt ) ) {
		$opt = array();
	}
	$lang = sc_current_lang();
	$k    = 'en' === $lang ? $key . '_en' : $key;
	if ( ! empty( $opt[ $k ] ) ) {
		return $opt[ $k ];
	}
	if ( 'en' === $lang && ! empty( $opt[ $key ] ) ) {
		return $opt[ $key ];
	}
	return 'en' === $lang ? $default_en : $default_zh;
}

