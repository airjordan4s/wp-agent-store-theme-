<?php
/**
 * 首页：瑞士建筑网格 10 大完整视觉区块
 *
 * @package SkillCraft
 */

get_header();

$streams  = sc_skill_streams();
$cases    = sc_developer_cases();
$products = sc_marketplace_products();
$steps    = sc_onboarding_steps();
$agents   = sc_supported_agents();
$security = sc_security_pillars();
$faqs     = sc_faqs();
?>

<!-- 1. HERO + 撕贴交互 CTA + 4 行错位横向流 -->
<?php if ( sc_section_enabled( 'hero' ) ) : ?>
<section class="sc-band sc-hero">
	<div class="sc-framed sc-hero__top" data-reveal>
		<div class="sc-hero__copy">
			<h1 class="sc-hero__title">
				<?php echo sc_nl_title( 'hero_title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</h1>

			<div class="sc-peel" id="sc-peelCta">
				<a class="sc-peel__under" href="<?php echo esc_url( home_url( '/skills/' ) ); ?>">
					<span><?php echo esc_html( sc_txt( 'hero_cta_under' ) ); ?></span>
					<span class="sc-peel__arrow" aria-hidden="true">
						<svg width="56" height="14" viewBox="0 0 56 14" fill="none"><path d="M0 7h54M48 1.5L54.5 7 48 12.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</span>
				</a>
				<button type="button" class="sc-peel__top" aria-label="<?php echo esc_attr( sc_t( '揭开贴纸浏览作品', 'Peel sticker to explore' ) ); ?>">
					<span class="sc-peel__label"><?php echo esc_html( sc_txt( 'hero_cta_top' ) ); ?></span>
					<span class="sc-peel__arrow" aria-hidden="true">
						<svg width="56" height="14" viewBox="0 0 56 14" fill="none"><path d="M0 7h54M48 1.5L54.5 7 48 12.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</span>
					<span class="sc-peel__curl" aria-hidden="true"></span>
				</button>
			</div>
		</div>

		<!-- 极简线条款 Agent 数字员工办公室微动画 -->
		<div class="sc-office" aria-hidden="true">
			<svg viewBox="0 0 460 300" width="460" height="300" fill="none" xmlns="http://www.w3.org/2000/svg">
				<!-- 极简建筑地板网格 -->
				<g stroke="rgba(0,0,0,0.055)" stroke-width="1">
					<path d="M24 52H436M24 104H436M24 156H436M24 208H436M24 260H436"/>
					<path d="M84 24V276M168 24V276M252 24V276M336 24V276M420 24V276"/>
				</g>

				<!-- 左上角状态小牌 -->
				<g>
					<rect x="28" y="22" width="122" height="24" rx="4" fill="#ffffff" stroke="rgba(0,0,0,0.16)" stroke-width="1.2"/>
					<circle cx="40" cy="34" r="3.2" fill="#1677ff" class="sc-office__pulse"/>
					<text x="50" y="38" font-family="JetBrains Mono, monospace" font-size="10" font-weight="600" fill="#111111">4 AGENTS ACTIVE</text>
				</g>

				<!-- ===== 工位 1（左上）：正在敲代码的 Agent ===== -->
				<g class="sc-desk sc-desk--1">
					<!-- 办公桌 -->
					<rect x="34" y="96" width="144" height="10" rx="2" fill="#ffffff" stroke="#111111" stroke-width="1.5"/>
					<path d="M44 106V138M168 106V138" stroke="#111111" stroke-width="1.4" stroke-linecap="round"/>
					<!-- 显示器 -->
					<rect x="64" y="64" width="52" height="32" rx="3" fill="#ffffff" stroke="#111111" stroke-width="1.5"/>
					<line x1="72" y1="74" x2="102" y2="74" stroke="#1677ff" stroke-width="1.6" stroke-linecap="round" class="sc-line-1"/>
					<line x1="72" y1="81" x2="108" y2="81" stroke="#111111" stroke-width="1.4" stroke-linecap="round" class="sc-line-2"/>
					<line x1="72" y1="88" x2="94" y2="88" stroke="rgba(0,0,0,0.4)" stroke-width="1.4" stroke-linecap="round" class="sc-line-3"/>
					<!-- 工位上的 Agent #1（点头 + 敲键盘） -->
					<g class="sc-agent-sit sc-agent-sit--1">
						<!-- 身体与椅背 -->
						<path d="M126 116C126 106 134 100 144 100C154 100 162 106 162 116V134H126V116Z" fill="#ffffff" stroke="#111111" stroke-width="1.5"/>
						<!-- 小圆头（带可爱双点眼） -->
						<g class="sc-agent-head-1">
							<rect x="131" y="74" width="26" height="22" rx="9" fill="#ffffff" stroke="#111111" stroke-width="1.5"/>
							<circle cx="139" cy="84" r="1.6" fill="#111111"/>
							<circle cx="147" cy="84" r="1.6" fill="#111111"/>
						</g>
						<!-- 敲键盘的小手 -->
						<circle cx="122" cy="95" r="3" fill="#ffffff" stroke="#111111" stroke-width="1.4" class="sc-hand-l"/>
						<circle cx="129" cy="95" r="3" fill="#ffffff" stroke="#111111" stroke-width="1.4" class="sc-hand-r"/>
					</g>
					<!-- 气泡 SQL ✓ -->
					<g class="sc-bubble sc-bubble--1">
						<rect x="132" y="42" width="52" height="22" rx="11" fill="#ffffff" stroke="#111111" stroke-width="1.2"/>
						<text x="142" y="57" font-family="JetBrains Mono, monospace" font-size="10" font-weight="600" fill="#1677ff">SQL ✓</text>
					</g>
				</g>

				<!-- ===== 工位 2（右上）：正在做代码审查的 Agent ===== -->
				<g class="sc-desk sc-desk--2">
					<!-- 办公桌 -->
					<rect x="266" y="96" width="148" height="10" rx="2" fill="#ffffff" stroke="#111111" stroke-width="1.5"/>
					<path d="M276 106V138M404 106V138" stroke="#111111" stroke-width="1.4" stroke-linecap="round"/>
					<!-- 双屏显示器 -->
					<rect x="332" y="64" width="54" height="32" rx="3" fill="#ffffff" stroke="#111111" stroke-width="1.5"/>
					<line x1="340" y1="74" x2="376" y2="74" stroke="#111111" stroke-width="1.5" stroke-linecap="round" class="sc-line-2"/>
					<line x1="340" y1="82" x2="366" y2="82" stroke="#1677ff" stroke-width="1.7" stroke-linecap="round" class="sc-line-4"/>
					<rect x="372" y="79" width="4" height="7" fill="#1677ff" class="sc-cursor"/>
					<!-- 工位上的 Agent #2 -->
					<g class="sc-agent-sit sc-agent-sit--2">
						<path d="M282 116C282 106 290 100 300 100C310 100 318 106 318 116V134H282V116Z" fill="#ffffff" stroke="#111111" stroke-width="1.5"/>
						<g class="sc-agent-head-2">
							<rect x="287" y="74" width="26" height="22" rx="9" fill="#ffffff" stroke="#111111" stroke-width="1.5"/>
							<circle cx="297" cy="84" r="1.6" fill="#111111"/>
							<circle cx="305" cy="84" r="1.6" fill="#111111"/>
						</g>
						<circle cx="322" cy="95" r="3" fill="#ffffff" stroke="#111111" stroke-width="1.4" class="sc-hand-r"/>
					</g>
					<!-- 气泡 PR OK -->
					<g class="sc-bubble sc-bubble--2">
						<rect x="266" y="42" width="56" height="22" rx="11" fill="#ffffff" stroke="#111111" stroke-width="1.2"/>
						<text x="276" y="57" font-family="JetBrains Mono, monospace" font-size="10" font-weight="600" fill="#111111">PR OK</text>
					</g>
				</g>

				<!-- ===== 走廊：来回走动派单/交付的 Agent #3 ===== -->
				<g class="sc-walker-path">
					<g class="sc-walker-bob">
						<!-- 小脚（交替迈步） -->
						<line x1="16" y1="42" x2="13" y2="52" stroke="#111111" stroke-width="1.6" stroke-linecap="round" class="sc-leg-l"/>
						<line x1="26" y1="42" x2="29" y2="52" stroke="#111111" stroke-width="1.6" stroke-linecap="round" class="sc-leg-r"/>
						<!-- 身体 -->
						<rect x="8" y="20" width="26" height="23" rx="10" fill="#ffffff" stroke="#111111" stroke-width="1.5"/>
						<!-- 头部 -->
						<rect x="9" y="2" width="24" height="20" rx="8" fill="#ffffff" stroke="#111111" stroke-width="1.5"/>
						<circle cx="17" cy="11" r="1.5" fill="#111111"/>
						<circle cx="25" cy="11" r="1.5" fill="#111111"/>
						<!-- 手里捧着的小文件/Skill包 -->
						<rect x="28" y="23" width="14" height="11" rx="2" fill="#eef5ff" stroke="#1677ff" stroke-width="1.3"/>
						<line x1="31" y1="28" x2="39" y2="28" stroke="#1677ff" stroke-width="1.2" stroke-linecap="round"/>
					</g>
				</g>

				<!-- ===== 下排左侧：看板白板 + 站在白板前汇报的 Agent #4 ===== -->
				<g class="sc-whiteboard">
					<!-- 支架白板 -->
					<rect x="44" y="200" width="96" height="58" rx="4" fill="#ffffff" stroke="#111111" stroke-width="1.5"/>
					<path d="M62 258L56 274M122 258L128 274" stroke="#111111" stroke-width="1.4" stroke-linecap="round"/>
					<!-- 白板上的流程图节点 -->
					<circle cx="66" cy="222" r="5" stroke="#111111" stroke-width="1.3"/>
					<circle cx="94" cy="236" r="5" stroke="#1677ff" stroke-width="1.4" fill="#eef5ff"/>
					<circle cx="122" cy="218" r="5" stroke="#111111" stroke-width="1.3"/>
					<path d="M71 224L89 234M99 233L117 221" stroke="#111111" stroke-width="1.2" stroke-dasharray="2 2"/>
				</g>

				<!-- ===== 下排右侧：咖啡吧台 + 端咖啡巡检的 Agent #4 ===== -->
				<g class="sc-coffee-zone">
					<!-- 吧台 -->
					<rect x="296" y="234" width="118" height="34" rx="3" fill="#ffffff" stroke="#111111" stroke-width="1.5"/>
					<!-- 咖啡机 -->
					<rect x="368" y="208" width="28" height="26" rx="3" fill="#ffffff" stroke="#111111" stroke-width="1.4"/>
					<rect x="374" y="220" width="10" height="10" rx="1.5" stroke="#111111" stroke-width="1.2"/>
					<!-- 热气 -->
					<path d="M352 214C350 209 354 205 352 200" stroke="rgba(0,0,0,0.4)" stroke-width="1.2" stroke-linecap="round" class="sc-steam sc-steam--1"/>
					<!-- 站立的 Agent #4 -->
					<g class="sc-agent-stand">
						<rect x="312" y="204" width="24" height="30" rx="9" fill="#ffffff" stroke="#111111" stroke-width="1.5"/>
						<rect x="312" y="184" width="24" height="20" rx="8" fill="#ffffff" stroke="#111111" stroke-width="1.5"/>
						<circle cx="320" cy="193" r="1.5" fill="#111111"/>
						<circle cx="328" cy="193" r="1.5" fill="#111111"/>
						<!-- 端着的小咖啡杯 -->
						<rect x="342" y="218" width="12" height="12" rx="2" fill="#ffffff" stroke="#111111" stroke-width="1.3"/>
					</g>
					<!-- 成交小气泡 +¥49.9 -->
					<g class="sc-office__toast">
						<rect x="336" y="156" width="84" height="24" rx="12" fill="#111111"/>
						<circle cx="348" cy="168" r="3" fill="#34d399"/>
						<text x="357" y="172" font-family="JetBrains Mono, monospace" font-size="10.5" font-weight="600" fill="#ffffff">+¥49.9</text>
					</g>
				</g>

				<!-- 底部地平基线 -->
				<line x1="20" y1="274" x2="440" y2="274" stroke="#111111" stroke-width="1.5" stroke-linecap="round"/>
			</svg>
		</div>
	</div>

	<div class="sc-streams" aria-label="<?php echo esc_attr( sc_t( '精选 Skill 实时流', 'Featured Skill streams' ) ); ?>">
		<?php foreach ( $streams as $row_idx => $row ) :
			$dir_cls = ( 1 === $row_idx % 2 ) ? 'sc-stream--rev' : '';
			?>
			<div class="sc-stream <?php echo esc_attr( $dir_cls ); ?>">
				<div class="sc-stream__track">
					<?php for ( $dup = 0; $dup < 2; $dup++ ) : ?>
						<?php foreach ( $row as $pill ) : ?>
							<a class="sc-pill" href="<?php echo esc_url( home_url( '/product/' ) ); ?>">
								<div class="sc-pill__head">
									<span class="sc-pill__name"><?php echo esc_html( sc_t( $pill['name'], $pill['name_en'] ) ); ?></span>
									<span class="sc-pill__price"><?php echo sc_price_html( $pill['price'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
								</div>
								<p class="sc-pill__desc"><?php echo esc_html( sc_t( $pill['desc'], $pill['desc_en'] ) ); ?></p>
							</a>
						<?php endforeach; ?>
					<?php endfor; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>

<div class="sc-spacer"><div class="sc-framed"></div></div>
<?php endif; ?>

<!-- 2. macOS 风格极简交互演示窗口（顶边带可摸头奔跑小狗 + 支持切换不同业务工作台） -->
<?php if ( sc_section_enabled( 'demo' ) ) : ?>
<section class="sc-band sc-demo" id="demo">
	<div class="sc-framed sc-demo__wrap" data-reveal>
		<!-- 窗顶小狗跑道：平时在顶边撒欢跑，鼠标移上去可停下来被摸狗头 -->
		<div class="sc-roof-track" id="sc-roofTrack" aria-label="<?php echo esc_attr( sc_t( '窗顶奔跑小狗，鼠标移上去可以摸摸狗头', 'Roof puppy — hover to pet its head' ) ); ?>">
			<div class="sc-roof-dog" id="sc-roofDog" role="button" tabindex="0" title="<?php echo esc_attr( sc_t( '摸摸狗头 ♥', 'Pet the puppy ♥' ) ); ?>">
				<span class="sc-roof-dog__bubble" id="sc-roofBubble" aria-hidden="true"></span>
				<div class="sc-roof-dog__hearts" id="sc-roofHearts" aria-hidden="true"></div>
				<svg class="sc-roof-dog__svg" viewBox="0 0 84 62" width="68" height="50" fill="none" xmlns="http://www.w3.org/2000/svg">
					<!-- 尾巴（摇啊摇） -->
					<path class="sc-rd-tail" d="M18 36C10 31 8 22 14 19" stroke="#111111" stroke-width="3.2" stroke-linecap="round"/>
					<!-- 后腿 -->
					<path class="sc-rd-leg-b1" d="M25 46L21 57" stroke="#111111" stroke-width="3.4" stroke-linecap="round"/>
					<path class="sc-rd-leg-b2" d="M32 46L35 57" stroke="#111111" stroke-width="3.4" stroke-linecap="round"/>
					<!-- 身体 -->
					<rect x="18" y="30" width="36" height="20" rx="10" fill="#ffffff" stroke="#111111" stroke-width="2.6"/>
					<!-- 前腿 -->
					<path class="sc-rd-leg-f1" d="M45 46L42 57" stroke="#111111" stroke-width="3.4" stroke-linecap="round"/>
					<path class="sc-rd-leg-f2" d="M52 46L56 57" stroke="#111111" stroke-width="3.4" stroke-linecap="round"/>
					<!-- 狗头（带耷拉耳朵） -->
					<g class="sc-rd-head">
						<!-- 后耳 -->
						<path class="sc-rd-ear" d="M46 16C41 18 39 26 42 31C44 33 48 31 49 27" fill="#ffffff" stroke="#111111" stroke-width="2.4" stroke-linejoin="round"/>
						<!-- 圆圆狗头 -->
						<rect x="44" y="10" width="30" height="25" rx="12" fill="#ffffff" stroke="#111111" stroke-width="2.6"/>
						<!-- 前耷拉耳 -->
						<path class="sc-rd-ear" d="M52 17C48 21 47 29 51 32C54 33 57 30 57 25" fill="#ffffff" stroke="#111111" stroke-width="2.4" stroke-linejoin="round"/>
						<!-- 腮红 -->
						<ellipse class="sc-rd-blush" cx="56" cy="27" rx="3" ry="1.8" fill="#fda4af" opacity="0.75"/>
						<ellipse class="sc-rd-blush" cx="70" cy="27" rx="2.6" ry="1.6" fill="#fda4af" opacity="0.75"/>
						<!-- 正常圆眼睛 -->
						<g class="sc-rd-eyes-normal">
							<circle cx="59" cy="22" r="2.1" fill="#111111"/>
							<circle cx="68" cy="22" r="2.1" fill="#111111"/>
						</g>
						<!-- 被摸头时眯成弯弯笑眼 ^ ^ -->
						<g class="sc-rd-eyes-happy">
							<path d="M56.5 23C57.8 20.5 60.2 20.5 61.5 23" stroke="#111111" stroke-width="2.2" stroke-linecap="round"/>
							<path d="M65.5 23C66.8 20.5 69.2 20.5 70.5 23" stroke="#111111" stroke-width="2.2" stroke-linecap="round"/>
						</g>
						<!-- 小鼻子与微笑嘴 -->
						<ellipse cx="64" cy="25.2" rx="1.8" ry="1.3" fill="#111111"/>
						<path d="M61.5 27.6C62.4 29 63.6 29 64 27.8C64.4 29 65.6 29 66.5 27.6" stroke="#111111" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
					</g>
				</svg>
			</div>
		</div>

		<div class="sc-window">
			<div class="sc-window__chrome sc-window__chrome--tabs">
				<div class="sc-window__dots" aria-hidden="true">
					<span style="background:#ff5f57"></span>
					<span style="background:#febc2e"></span>
					<span style="background:#28c840"></span>
				</div>
				<div class="sc-win-seg" role="tablist" aria-label="<?php echo esc_attr( sc_t( '切换演示工作台', 'Switch demo workbench' ) ); ?>">
					<button type="button" class="sc-win-seg__btn is-active" data-wb-switch="ops" role="tab" aria-selected="true"><?php echo esc_html( sc_t( '运维工作台', 'Ops Desk' ) ); ?></button>
					<button type="button" class="sc-win-seg__btn" data-wb-switch="ecom" role="tab" aria-selected="false"><?php echo esc_html( sc_t( '电商工作台', 'Commerce' ) ); ?></button>
					<button type="button" class="sc-win-seg__btn" data-wb-switch="kb" role="tab" aria-selected="false"><?php echo esc_html( sc_t( '知识库工作台', 'Knowledge' ) ); ?></button>
				</div>
				<a class="sc-win-chrome-link" href="<?php echo esc_url( home_url( '/product/' ) ); ?>"><?php echo esc_html( sc_t( '查看详情 →', 'Inspect →' ) ); ?></a>
			</div>

			<div class="sc-window__shell">
				<aside class="sc-window__rail" aria-hidden="true">
					<div class="sc-window__rail-top">
						<span></span><span></span><span></span><span></span>
					</div>
					<span class="sc-window__rail-dot"></span>
				</aside>

				<div class="sc-window__canvas">
					<div class="sc-window__stage" id="sc-demoFeed">
						<div class="sc-demo__intro" id="sc-demoIntro">
							<span class="sc-demo__tag" id="sc-wbTag"><?php echo esc_html( sc_t( '业务工作台 · 实时演示', 'WORKBENCH · LIVE DEMO' ) ); ?></span>
							<h2 id="sc-wbTitle"><?php echo esc_html( sc_t( '点击右下角发送，体验 Agent 接管服务器运维。', 'Click send below to watch your Agent handle server ops.' ) ); ?></h2>
							<div class="sc-demo__chips" id="sc-wbChips">
								<span><?php echo esc_html( sc_t( '服务器 3 节点在线', '3 Nodes Online' ) ); ?></span>
								<span><?php echo esc_html( sc_t( '网站与面板巡检', 'Sites & Panels' ) ); ?></span>
								<span><?php echo esc_html( sc_t( 'SSH 异常自动排查', 'Auto SSH Triage' ) ); ?></span>
							</div>
						</div>
						<div class="sc-demo__log" id="sc-demoLog" aria-live="polite"></div>
					</div>

					<form class="sc-window__composer" id="sc-demoForm">
						<textarea class="sc-window__textarea" id="sc-demoInput" rows="4"><?php echo esc_textarea( sc_txt( 'demo_prompt' ) ); ?></textarea>
						<button type="submit" class="sc-send-circle" aria-label="<?php echo esc_attr( sc_t( '发送演示指令', 'Send demo prompt' ) ); ?>">
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none">
								<path d="M12 19V5M5.5 11.5L12 5l6.5 6.5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</button>
					</form>
				</div>
			</div>
		</div>

		<div class="sc-demo__copybar">
			<span><?php echo esc_html( sc_t( '想在本地直接启动这套工作台？', 'Want to launch this workbench locally?' ) ); ?></span>
			<button type="button" class="sc-copy-btn" id="sc-copyCmd" data-cmd="<?php echo esc_attr( sc_txt( 'demo_copy_cmd' ) ); ?>">
				<span class="sc-copy-btn__tag" id="sc-copyTag"><?php echo esc_html( sc_t( '点击复制指令', 'Copy command' ) ); ?></span>
			</button>
		</div>
	</div>
</section>

<div class="sc-spacer"><div class="sc-framed"></div></div>
<?php endif; ?>

<!-- 3. 开发者优秀案例长廊 -->
<?php if ( sc_section_enabled( 'creators' ) ) : ?>
<section class="sc-band sc-creators">
	<div class="sc-framed sc-section-head" data-reveal>
		<h2><?php echo sc_nl_title( 'creators_title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
	</div>

	<div class="sc-framed sc-creators__lane">
		<div class="sc-creators__track">
			<?php for ( $dup = 0; $dup < 2; $dup++ ) : ?>
				<?php foreach ( $cases as $idx => $c ) : ?>
					<article class="sc-creator-card sc-creator-card--offset-<?php echo esc_attr( $idx % 2 ); ?>">
						<div class="sc-creator-card__top">
							<span>/ <?php echo esc_html( sc_t( '真实反馈', 'Review' ) ); ?></span>
							<span><?php echo esc_html( sc_t( '已购用户', 'Verified Buyer' ) ); ?></span>
						</div>
						<h3 class="sc-creator-card__title"><?php echo esc_html( sc_t( $c['title'], $c['title_en'] ) ); ?></h3>
						<div class="sc-creator-card__bottom">
							<p><?php echo esc_html( sc_t( $c['desc'], $c['desc_en'] ) ); ?></p>
							<span class="sc-creator-card__author"><?php echo esc_html( sc_t( $c['author'], $c['author_en'] ) ); ?></span>
						</div>
					</article>
				<?php endforeach; ?>
			<?php endfor; ?>
		</div>
	</div>
</section>

<div class="sc-spacer"><div class="sc-framed"></div></div>
<?php endif; ?>

<!-- 4. 4 列零间隙建筑网格商品陈列 -->
<?php if ( sc_section_enabled( 'products' ) ) : ?>
<section class="sc-band sc-products" id="products">
	<div class="sc-framed sc-section-head" data-reveal>
		<h2><?php echo sc_nl_title( 'products_title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
	</div>

	<div class="sc-framed sc-products__grid">
		<?php foreach ( $products as $p ) : ?>
			<a class="sc-product-card" href="<?php echo esc_url( $p['url'] ); ?>">
				<div class="sc-product-card__head">
					<h3><?php echo esc_html( sc_t( $p['title'], $p['title_en'] ) ); ?></h3>
					<span class="sc-badge"><?php echo esc_html( sc_t( $p['badge'], $p['badge_en'] ) ); ?></span>
				</div>
				<p class="sc-product-card__desc"><?php echo esc_html( sc_t( $p['desc'], $p['desc_en'] ) ); ?></p>
				<div class="sc-product-card__foot">
					<span class="sc-product-card__action"><?php echo esc_html( sc_t( '查看详情 →', 'Inspect →' ) ); ?></span>
					<span class="sc-product-card__price">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
							<path d="M20.6 13.4l-7.2 7.2a2 2 0 0 1-2.8 0L3 13V4h9l8.6 8.6a2 2 0 0 1 0 .8z" stroke="currentColor" stroke-width="1.6"/>
							<circle cx="7.5" cy="8.5" r="1.2" fill="currentColor"/>
						</svg>
						<strong><?php echo sc_price_html( $p['price'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					</span>
				</div>
			</a>
		<?php endforeach; ?>
	</div>
</section>

<div class="sc-spacer"><div class="sc-framed"></div></div>
<?php endif; ?>

<!-- 5. 纯黑高反差 3 步入驻交互区 -->
<?php if ( sc_section_enabled( 'start' ) ) : ?>
<section class="sc-dark-band" id="start">
	<!-- 鼠标跟随的发光金元宝光标（全宽区域生效） -->
	<div class="sc-ingot-cursor" id="sc-ingotCursor" aria-hidden="true">
		<svg width="36" height="28" viewBox="0 0 44 34" fill="none">
			<path d="M14 14C14 8.5 17.6 5 22 5C26.4 5 30 8.5 30 14" fill="#fde047" stroke="#111111" stroke-width="2.6" stroke-linecap="round"/>
			<path d="M4 13C4 11.5 6 10.5 8 11.2L13.5 13.5H30.5L36 11.2C38 10.5 40 11.5 40 13C40 22 33 28 22 28C11 28 4 22 4 13Z" fill="#facc15" stroke="#111111" stroke-width="2.6" stroke-linejoin="round"/>
		</svg>
	</div>

	<div class="sc-start__head" id="sc-darkHead" data-reveal>

		<!-- 趴着摇屁股摇尾巴的垂耳小白狗（眼睛跟随金元宝 + 靠近变金色星星眼 + 加速摇屁股 + 全屏爆金光） -->
		<div class="sc-mascot sc-mascot--peek" id="sc-peekDog" aria-hidden="true">
			<svg width="168" height="104" viewBox="0 0 180 112" fill="none" xmlns="http://www.w3.org/2000/svg">
				<!-- 背后摇动的小屁股与小尾巴 -->
				<g class="sc-dog-butt">
					<path d="M128 66C144 58 156 44 152 34" stroke="#ffffff" stroke-width="11" stroke-linecap="round"/>
					<path d="M128 66C144 58 156 44 152 34" stroke="#111111" stroke-width="4.2" stroke-linecap="round" class="sc-dog-tail"/>
					<ellipse cx="116" cy="76" rx="28" ry="20" fill="#ffffff" stroke="#111111" stroke-width="4"/>
				</g>
				<!-- 垂耳小白狗本体 -->
				<g class="sc-dog-headwrap">
					<!-- 圆滚滚狗头 -->
					<path d="M44 94V58C44 30 59 16 82 16C105 16 120 30 120 58V94H44Z" fill="#ffffff" stroke="#111111" stroke-width="4" stroke-linejoin="round"/>
					<!-- 两只软萌向下耷拉的长垂耳（画在头部前层，自然垂在脸颊两侧） -->
					<path d="M52 28C36 28 24 44 24 66C24 78 32 82 39 76C44 71 46 58 49 46" fill="#ffffff" stroke="#111111" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" class="sc-dog-ear-l"/>
					<path d="M112 28C128 28 140 44 140 66C140 78 132 82 125 76C120 71 118 58 115 46" fill="#ffffff" stroke="#111111" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" class="sc-dog-ear-r"/>
					<!-- 趴着的两只前爪 -->
					<path d="M26 96C26 86 34 82 44 82C52 82 57 87 57 96H26Z" fill="#ffffff" stroke="#111111" stroke-width="4" stroke-linejoin="round"/>
					<path d="M107 96C107 87 112 82 120 82C130 82 138 86 138 96H107Z" fill="#ffffff" stroke="#111111" stroke-width="4" stroke-linejoin="round"/>
					<!-- 可跟随鼠标移动的五官组（同款粉腮红 + 黑豆眼 + 星星眼） -->
					<g id="sc-dogFace">
						<ellipse cx="58" cy="65" rx="6.5" ry="3.8" fill="#fca5a5" opacity="0.72"/>
						<ellipse cx="106" cy="65" rx="6.5" ry="3.8" fill="#fca5a5" opacity="0.72"/>
						<g class="sc-dog-eyes-normal">
							<circle cx="68" cy="54" r="4.2" fill="#111111"/>
							<circle cx="96" cy="54" r="4.2" fill="#111111"/>
						</g>
						<g class="sc-dog-eyes-star">
							<path d="M68 45L70.4 51.6L77 54L70.4 56.4L68 63L65.6 56.4L59 54L65.6 51.6Z" fill="#f59e0b"/>
							<path d="M96 45L98.4 51.6L105 54L98.4 56.4L96 63L93.6 56.4L87 54L93.6 51.6Z" fill="#f59e0b"/>
						</g>
						<ellipse cx="82" cy="61" rx="4.2" ry="3" fill="#111111"/>
						<path d="M72 67.5C74.5 71 79.5 70 82 65.5C84.5 70 89.5 71 92 67.5" stroke="#111111" stroke-width="3.6" stroke-linecap="round" stroke-linejoin="round"/>
					</g>
				</g>
			</svg>
		</div>
		<h2><?php echo esc_html( sc_txt( 'start_title' ) ); ?></h2>
	</div>

	<div class="sc-framed sc-framed--dark sc-start__grid">
		<div class="sc-start__tabs" role="tablist">
			<?php foreach ( $steps as $i => $s ) : ?>
				<button type="button" class="sc-step-tab <?php echo 0 === $i ? 'is-active' : ''; ?>"
				        role="tab" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"
				        data-step-tab="<?php echo esc_attr( $i ); ?>">
					<span class="sc-step-tab__num"><?php echo esc_html( $s['num'] ); ?></span>
					<div class="sc-step-tab__body">
						<strong><?php echo esc_html( sc_t( $s['title'], $s['title_en'] ) ); ?></strong>
						<p><?php echo esc_html( sc_t( $s['desc'], $s['desc_en'] ) ); ?></p>
					</div>
				</button>
			<?php endforeach; ?>
			<div class="sc-start__footnote">
				<?php echo esc_html( sc_t( '兼容开放 SKILL.md 与 MCP 协议标准 · 零平台锁定', 'Built on open SKILL.md & MCP standards · Zero vendor lock-in' ) ); ?>
			</div>
		</div>

		<div class="sc-start__stage">
			<!-- Stage 0: 挑选作品 -->
			<div class="sc-stage-pane is-active" data-step-pane="0">
				<div class="sc-mock-card">
					<div class="sc-mock-card__head">
						<span>SQL 慢查询全链路诊断 · v1.2.0</span>
						<span class="sc-mock-pill sc-mock-pill--ok"><?php echo esc_html( sc_t( '官方自营', 'First-party' ) ); ?></span>
					</div>
					<pre class="sc-mock-code">skill: sql-query-profiler
license: lifetime_one_time
price: ¥49.9
updates: free_forever
clients: [Cursor, Claude, Codex]</pre>
					<div class="sc-mock-foot">
						<span><?php echo esc_html( sc_t( '买断一次永久可用，含免费版本更新', 'Lifetime access with free updates' ) ); ?></span>
						<span class="sc-mock-btn"><?php echo esc_html( sc_t( '立即购买', 'Buy now' ) ); ?></span>
					</div>
				</div>
			</div>

			<!-- Stage 1: 自动发货专属口令 -->
			<div class="sc-stage-pane" data-step-pane="1">
				<div class="sc-mock-card">
					<div class="sc-mock-card__head">
						<span><?php echo esc_html( sc_t( '支付成功 · 自动发货', 'Order confirmed · Instant delivery' ) ); ?></span>
						<span class="sc-mock-pill sc-mock-pill--ok">Ready</span>
					</div>
					<div class="sc-mock-share">
						<strong><?php echo esc_html( sc_t( '基础设施与服务器掌管工作台', 'Server & Infra Command Workbench' ) ); ?></strong>
						<code>npx -y @agentdesk/cli@latest install --pack ops-server-workbench</code>
						<div class="sc-mock-tags">
							<span>Windows / macOS</span><span>Web</span><span>Cursor</span><span>Claude Code</span>
						</div>
					</div>
					<div class="sc-mock-foot">
						<span><?php echo esc_html( sc_t( '订单与专属授权码已自动同步至你的会员中心', 'Order & license key saved automatically in your Account' ) ); ?></span>
					</div>
				</div>
			</div>

			<!-- Stage 2: 启动工作台或挂载开跑 -->
			<div class="sc-stage-pane" data-step-pane="2">
				<div class="sc-mock-card">
					<div class="sc-mock-card__head">
						<span><?php echo esc_html( sc_t( '工作台运行状态', 'Workbench runtime status' ) ); ?></span>
						<span class="sc-mock-pill sc-mock-pill--ok"><?php echo esc_html( sc_t( '已就绪', 'Active' ) ); ?></span>
					</div>
					<div class="sc-mock-toast">
						<span class="sc-mock-toast__dot"></span>
						<div>
							<strong><?php echo esc_html( sc_t( '工作台与 MCP 工具已就绪', 'Workbench & MCP tools ready' ) ); ?></strong>
							<span><?php echo esc_html( sc_t( '基础设施掌管工作台 · 已连接 3 个在线节点', 'Server Command Workbench · 3 nodes connected' ) ); ?></span>
						</div>
					</div>
					<div class="sc-mock-toast sc-mock-toast--muted">
						<span class="sc-mock-toast__dot"></span>
						<div>
							<strong><?php echo esc_html( sc_t( '后续版本更新自动同步', 'Free updates enabled' ) ); ?></strong>
							<span><?php echo esc_html( sc_t( 'v2.4.0 → 发现新模块时一键升级', 'v2.4.0 → One-command upgrade anytime' ) ); ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<div class="sc-spacer"><div class="sc-framed"></div></div>
<?php endif; ?>

<!-- 6. 支持的 Agents 双栏生态墙 -->
<?php if ( sc_section_enabled( 'agents' ) ) : ?>
<section class="sc-band sc-agents" id="agents">
	<div class="sc-framed sc-agents__grid">
		<div class="sc-agents__marquee">
			<div class="sc-agents__track">
				<?php for ( $dup = 0; $dup < 2; $dup++ ) : ?>
					<?php foreach ( $agents as $ag ) : ?>
						<div class="sc-agent-item">
							<span class="sc-agent-item__icon" style="background:<?php echo esc_attr( $ag['color'] ); ?>"><?php echo esc_html( $ag['mark'] ); ?></span>
							<strong><?php echo esc_html( $ag['name'] ); ?></strong>
						</div>
					<?php endforeach; ?>
				<?php endfor; ?>
			</div>
		</div>

		<div class="sc-agents__title" data-reveal>
			<h2><?php echo sc_nl_title( 'agents_title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
			<p><?php echo esc_html( sc_txt( 'agents_desc' ) ); ?></p>
		</div>
	</div>

	<div class="sc-framed sc-agents__notice">
		<span><?php echo esc_html( sprintf( sc_t( '提示：所有通过 %s 安装的工作台、MCP 与 Skill 均遵循本地优先与最小权限原则，凭据不离你的设备。', 'Note: All Workbenches, MCP plugins, and Skills from %s follow local-first & least-privilege isolation.' ), sc_site_name() ) ); ?></span>
	</div>
</section>

<div class="sc-spacer"><div class="sc-framed"></div></div>
<?php endif; ?>

<!-- 7. 安全三大支柱 50/50 建筑分栏 -->
<?php if ( sc_section_enabled( 'security' ) ) : ?>
<section class="sc-band sc-security" id="security">
	<div class="sc-framed sc-split">
		<div class="sc-split__title" data-reveal>
			<h2><?php echo sc_nl_title( 'security_title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
		</div>

		<div class="sc-split__cells">
			<?php foreach ( $security as $sec ) : ?>
				<div class="sc-sec-cell" data-reveal>
					<span class="sc-sec-cell__idx"><?php echo esc_html( $sec['index'] ); ?></span>
					<h3><?php echo esc_html( sc_t( $sec['title'], $sec['title_en'] ) ); ?></h3>
					<p><?php echo esc_html( sc_t( $sec['desc'], $sec['desc_en'] ) ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<div class="sc-spacer"><div class="sc-framed"></div></div>
<?php endif; ?>

<!-- 8. FAQ 手风琴 50/50 反向分栏 -->
<?php if ( sc_section_enabled( 'faq' ) ) : ?>
<section class="sc-band sc-faq" id="faq">
	<div class="sc-framed sc-split sc-split--rev">
		<div class="sc-faq__list">
			<?php foreach ( $faqs as $i => $f ) :
				$open = ( 0 === $i );
				?>
				<div class="sc-faq__item <?php echo $open ? 'is-open' : ''; ?>">
					<button type="button" class="sc-faq__q" aria-expanded="<?php echo $open ? 'true' : 'false'; ?>">
						<span><?php echo esc_html( sc_t( $f['q'], $f['q_en'] ) ); ?></span>
						<span class="sc-faq__icon" aria-hidden="true"></span>
					</button>
					<div class="sc-faq__a">
						<p><?php echo esc_html( sc_t( $f['a'], $f['a_en'] ) ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="sc-split__title sc-split__title--right" data-reveal>
			<h2><?php echo esc_html( sc_txt( 'faq_title' ) ); ?></h2>
		</div>
	</div>
</section>

<div class="sc-spacer"><div class="sc-framed"></div></div>
<?php endif; ?>

<!-- 9. 可点击解开的拟物米白宣纸档案袋（真实三层物理叠层：后翻盖 → 袋内信纸 → 前袋身） -->
<?php if ( sc_section_enabled( 'envelope' ) ) : ?>
<section class="sc-band sc-about" id="about">
	<div class="sc-framed sc-about__wrap" data-reveal>
		<div class="sc-envelope" id="sc-envelope" role="button" tabindex="0" aria-expanded="false"
		     aria-label="<?php echo esc_attr( sc_t( '点击解开档案袋', 'Unwind and open dossier' ) ); ?>">

			<!-- 内衬背板（翻盖掀开后露出的信封内壁） -->
			<div class="sc-envelope__back" aria-hidden="true"></div>

			<!-- 翻盖（合上时盖在最前，打开时向上翻转 180° 退到信纸后方） -->
			<div class="sc-envelope__flap" aria-hidden="true">
				<svg class="sc-envelope__flap-svg" viewBox="0 0 740 210" preserveAspectRatio="none">
					<defs>
						<filter id="flapShadow" x="-4%" y="-4%" width="108%" height="124%">
							<feDropShadow dx="0" dy="6" stdDeviation="6" flood-color="#000000" flood-opacity="0.08"/>
						</filter>
					</defs>
					<path d="M0 0 H740 L674 156 C664 180 644 192 618 192 H122 C96 192 76 180 66 156 L0 0 Z"
					      fill="#f5f2eb" filter="url(#flapShadow)"/>
				</svg>
				<span class="sc-clasp sc-clasp--top"><i></i></span>
			</div>

			<!-- 中间层：真正藏在袋内、从袋口滑出一半的压纹宣纸信笺 -->
			<div class="sc-envelope__letter" aria-hidden="true">
				<p class="sc-envelope__quote-zh"><?php echo esc_html( sc_content()['envelope_zh'] ); ?></p>
				<p class="sc-envelope__quote-en"><?php echo esc_html( sc_content()['envelope_en'] ); ?></p>
			</div>

			<!-- 最前层：前袋身（永远遮住信纸下半截，带下圆扣与绕线） -->
			<div class="sc-envelope__front">
				<svg class="sc-string" viewBox="0 0 60 150" width="60" height="150" fill="none" aria-hidden="true">
					<path class="sc-string__path" d="M26 14 L26 116 M34 14 L34 116 M28 18 L21 88" stroke="#1677ff" stroke-width="1.8" stroke-linecap="round"/>
				</svg>
				<span class="sc-clasp sc-clasp--bot" aria-hidden="true"><i></i></span>
				<span class="sc-envelope__hint" id="sc-envelopeBtn">
					<?php echo esc_html( sc_t( '点击解开档案袋', 'Click to unwind dossier' ) ); ?>
				</span>
			</div>

		</div>
	</div>
</section>
<?php endif; ?>

<?php
get_footer();
