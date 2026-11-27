<?php
/**
 * Template Name: SkillCraft 用户中心 / Account
 *
 * 瑞士建筑网格风格会员中心：侧栏图标导航 + 4 格核心指标 + 终端一键同步卡 + 最近已购与授权口令 + 快捷入口，支持多 Tab 切换。
 *
 * @package SkillCraft
 */

get_header();

$user = is_user_logged_in() ? wp_get_current_user() : null;
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
$tab     = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'overview';
if ( ! in_array( $tab, array( 'overview', 'orders', 'profile', 'security' ), true ) ) {
	$tab = 'overview';
}
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
$welcome = ! empty( $_GET['welcome'] );
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
$saved   = isset( $_GET['saved'] ) ? sanitize_key( wp_unslash( $_GET['saved'] ) ) : '';
$notice  = sc_auth_notice();

if ( ! $user || ! $user->ID ) :
	?>
	<section class="sc-band sc-auth">
		<div class="sc-framed sc-empty-box">
			<h2><?php echo esc_html( sc_t( '请先登录', 'Sign in required' ) ); ?></h2>
			<p><?php echo esc_html( sc_t( '登录后即可查看你的已购 Skill、专属授权口令与账户设置。', 'Sign in to access your purchased Skills, license keys, and account settings.' ) ); ?></p>
			<a class="sc-btn sc-btn--dark" href="<?php echo esc_url( sc_login_url( sc_account_url() ) ); ?>"><?php echo esc_html( sc_t( '前往登录', 'Sign in' ) ); ?></a>
		</div>
	</section>
	<?php
	get_footer();
	return;
endif;

$user_lang = get_user_meta( $user->ID, 'skillcraft_lang', true );
if ( ! array_key_exists( $user_lang, sc_langs() ) ) {
	$user_lang = sc_current_lang();
}
$joined = ! empty( $user->user_registered ) ? date_i18n( 'Y-m-d', strtotime( $user->user_registered ) ) : '—';

// 读取真实订单；若在非 WP 静态预览下无订单，展示 2 条演示已购记录以便预览完整布局
$orders = function_exists( 'sc_user_orders' ) ? sc_user_orders( $user->ID ) : array();
if ( empty( $orders ) && ! defined( 'ABSPATH' ) ) {
	$orders = array(
		array(
			'order_id'      => 101,
			'order_no'      => 'ORD-20261124-8210',
			'skill_title'   => sc_t( '基础设施与服务器掌管工作台', 'Server & Infra Command Workbench' ),
			'price'         => '¥299',
			'license_key'   => 'SC-8A4F-92E1-K7P2',
			'install_cmd'   => 'npx -y @agentdesk/cli@latest install --pack ops-server-workbench',
			'expires_label' => sc_t( '永久有效', 'Lifetime' ),
			'is_expired'    => false,
			'devices_count' => 1,
			'max_devices'   => 2,
			'date'          => '2026-11-24',
		),
		array(
			'order_id'      => 102,
			'order_no'      => 'ORD-20261118-4519',
			'skill_title'   => sc_t( '跨境电商全链路运营工作台', 'Cross-Border Commerce Workbench' ),
			'price'         => '¥199/月',
			'license_key'   => 'SC-3D9C-71B4-M5X8',
			'install_cmd'   => 'npx -y @agentdesk/cli@latest install --pack ecommerce-ops-workbench',
			'expires_label' => '2026-12-18',
			'is_expired'    => false,
			'devices_count' => 1,
			'max_devices'   => 2,
			'date'          => '2026-11-18',
		),
	);
}
$order_count = count( $orders );
$sync_cmd    = 'npx @skillcraft/cli sync --account ' . $user->user_login;
?>

<section class="sc-band sc-account">
	<div class="sc-framed sc-account__grid">
		<!-- 左侧成员名片与功能导航 -->
		<aside class="sc-account__side">
			<div class="sc-account__me">
				<span class="sc-avatar sc-avatar--lg"><?php echo esc_html( sc_user_initials( $user ) ); ?></span>
				<div class="sc-account__me-info">
					<strong><?php echo esc_html( $user->display_name ); ?></strong>
					<span><?php echo esc_html( $user->user_email ); ?></span>
				</div>
			</div>

			<nav class="sc-account__nav" aria-label="<?php echo esc_attr( sc_t( '会员导航', 'Account navigation' ) ); ?>">
				<a class="<?php echo 'overview' === $tab ? 'is-active' : ''; ?>" href="<?php echo esc_url( sc_account_url( 'overview' ) ); ?>" data-acc-tab="overview">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
					<span><?php echo esc_html( sc_t( '账户概览', 'Overview' ) ); ?></span>
				</a>
				<a class="<?php echo 'orders' === $tab ? 'is-active' : ''; ?>" href="<?php echo esc_url( sc_account_url( 'orders' ) ); ?>" data-acc-tab="orders">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18M16 10a4 4 0 0 1-8 0"/></svg>
					<span><?php echo esc_html( sc_t( '我的已购', 'My Purchases' ) ); ?></span>
					<?php if ( $order_count > 0 ) : ?>
						<em class="sc-account__count"><?php echo esc_html( $order_count ); ?></em>
					<?php endif; ?>
				</a>
				<a class="<?php echo 'profile' === $tab ? 'is-active' : ''; ?>" href="<?php echo esc_url( sc_account_url( 'profile' ) ); ?>" data-acc-tab="profile">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
					<span><?php echo esc_html( sc_t( '个人资料', 'Profile' ) ); ?></span>
				</a>
				<a class="<?php echo 'security' === $tab ? 'is-active' : ''; ?>" href="<?php echo esc_url( sc_account_url( 'security' ) ); ?>" data-acc-tab="security">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 3l7 3v5c0 4.5-3 8.2-7 10-4-1.8-7-5.5-7-10V6l7-3z"/></svg>
					<span><?php echo esc_html( sc_t( '安全与密码', 'Security' ) ); ?></span>
				</a>

				<?php if ( current_user_can( 'manage_options' ) ) : ?>
					<span class="sc-account__group"><?php echo esc_html( sc_t( '店铺后台', 'STORE ADMIN' ) ); ?></span>
					<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=sc_skill' ) ); ?>">
						<span><?php echo esc_html( sc_t( '管理 Skill 商品', 'Manage Skills' ) ); ?></span>
						<em class="sc-account__ext">→</em>
					</a>
					<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=sc_order' ) ); ?>">
						<span><?php echo esc_html( sc_t( '管理订单与授权', 'Manage Orders' ) ); ?></span>
						<em class="sc-account__ext">→</em>
					</a>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=skillcraft-content' ) ); ?>">
						<span><?php echo esc_html( sc_t( '站点内容管理', 'Site Content' ) ); ?></span>
						<em class="sc-account__ext">→</em>
					</a>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=skillcraft-license-api' ) ); ?>">
						<span><?php echo esc_html( sc_t( '授权 API 与公钥', 'License API & Key' ) ); ?></span>
						<em class="sc-account__ext">→</em>
					</a>
				<?php endif; ?>

				<a class="sc-account__logout" href="<?php echo esc_url( sc_logout_url() ); ?>">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
					<span><?php echo esc_html( sc_t( '退出登录', 'Sign out' ) ); ?></span>
				</a>
			</nav>
		</aside>

		<!-- 右侧内容面板 -->
		<div class="sc-account__main">
			<?php if ( $welcome ) : ?>
				<div class="sc-alert sc-alert--success"><?php echo esc_html( sprintf( sc_t( '欢迎加入 %s！你的会员账户已就绪。', 'Welcome to %s! Your member account is ready.' ), sc_site_name() ) ); ?></div>
			<?php endif; ?>
			<?php if ( 'profile' === $saved ) : ?>
				<div class="sc-alert sc-alert--success"><?php echo esc_html( sc_t( '个人资料已保存。', 'Profile updated.' ) ); ?></div>
			<?php elseif ( 'password' === $saved ) : ?>
				<div class="sc-alert sc-alert--success"><?php echo esc_html( sc_t( '密码已更新，登录状态已保持。', 'Password updated successfully.' ) ); ?></div>
			<?php endif; ?>
			<?php if ( $notice ) : ?>
				<div class="sc-alert sc-alert--<?php echo esc_attr( $notice['type'] ); ?>"><?php echo esc_html( $notice['text'] ); ?></div>
			<?php endif; ?>

			<!-- 面板 1：账户概览 -->
			<div class="sc-acc-panel" data-acc-panel="overview" <?php echo 'overview' === $tab ? '' : 'hidden'; ?>>
				<div class="sc-account__head">
					<div>
						<span class="sc-kicker">/ <?php echo esc_html( sc_t( '会员中心 · MEMBER CONSOLE', 'MEMBER CONSOLE' ) ); ?></span>
						<h1><?php echo esc_html( sprintf( sc_t( '你好，%s', 'Hello, %s' ), $user->display_name ) ); ?></h1>
					</div>
					<a class="sc-btn sc-btn--dark" href="<?php echo esc_url( home_url( '/skills/' ) ); ?>"><?php echo esc_html( sc_t( '浏览作品集市 →', 'Browse Store →' ) ); ?></a>
				</div>

				<!-- 4 格核心数据指标 -->
				<div class="sc-account__metrics sc-account__metrics--4">
					<div class="sc-metric">
						<span><?php echo esc_html( sc_t( '已购 Skill', 'Purchased' ) ); ?></span>
						<strong><?php echo esc_html( $order_count ); ?> <small><?php echo esc_html( sc_t( '款', 'items' ) ); ?></small></strong>
					</div>
					<div class="sc-metric">
						<span><?php echo esc_html( sc_t( '有效授权码', 'Active Keys' ) ); ?></span>
						<strong><?php echo esc_html( $order_count ); ?> <small><?php echo esc_html( sc_t( '个', 'active' ) ); ?></small></strong>
					</div>
					<div class="sc-metric">
						<span><?php echo esc_html( sc_t( '账户身份', 'Role' ) ); ?></span>
						<strong><?php echo esc_html( current_user_can( 'manage_options' ) ? sc_t( '店主管理员', 'Store Admin' ) : sc_t( '官方会员', 'Member' ) ); ?></strong>
					</div>
					<div class="sc-metric">
						<span><?php echo esc_html( sc_t( '加入日期', 'Member Since' ) ); ?></span>
						<strong><?php echo esc_html( $joined ); ?></strong>
					</div>
				</div>

				<!-- 终端一键同步卡片（暗黑终端风格 + 一键复制） -->
				<div class="sc-acc-term">
					<div class="sc-acc-term__head">
						<div>
							<h3><?php echo esc_html( sc_t( '同步全部已购 Skill 到本地 Agent', 'Sync all purchased Skills to your Agent' ) ); ?></h3>
							<p><?php echo esc_html( sc_t( '在本地终端执行以下命令绑定当前账户，自动拉取并升级全部已购作品至最新版本：', 'Authenticate your local CLI to pull and auto-update all your purchased Skills:' ) ); ?></p>
						</div>
						<button type="button" class="sc-btn sc-btn--light sc-acc-copy" data-copy-text="<?php echo esc_attr( $sync_cmd ); ?>">
							<?php echo esc_html( sc_t( '复制指令', 'Copy' ) ); ?>
						</button>
					</div>
					<div class="sc-acc-term__code">
						<span class="t-dim">$</span> <code><?php echo esc_html( $sync_cmd ); ?></code>
					</div>
				</div>

				<!-- 最近已购与授权口令 -->
				<div class="sc-account__box sc-acc-recent">
					<div class="sc-acc-boxhead">
						<h3><?php echo esc_html( sc_t( '最近已购与授权口令', 'Recent Purchases & Keys' ) ); ?></h3>
						<a href="<?php echo esc_url( sc_account_url( 'orders' ) ); ?>" data-acc-tab="orders"><?php echo esc_html( sc_t( '查看全部 →', 'View all →' ) ); ?></a>
					</div>

					<?php if ( empty( $orders ) ) : ?>
						<div class="sc-acc-empty">
							<p><?php echo esc_html( sc_t( '暂未购买任何 Skill，去作品集市挑选一款开箱即用的效率工具吧。', 'No purchased Skills yet. Explore the catalog to find your first Skill.' ) ); ?></p>
							<a class="sc-btn sc-btn--dark" href="<?php echo esc_url( home_url( '/skills/' ) ); ?>"><?php echo esc_html( sc_t( '前往作品集市', 'Browse Store' ) ); ?></a>
						</div>
					<?php else : ?>
						<div class="sc-acc-orderlist">
							<?php foreach ( array_slice( $orders, 0, 2 ) as $ord ) :
								$full_cmd = $ord['install_cmd'] . ' --key ' . $ord['license_key'];
								?>
								<div class="sc-acc-orderrow">
									<div class="sc-acc-orderrow__main">
										<div class="sc-acc-orderrow__title">
											<strong><?php echo esc_html( $ord['skill_title'] ); ?></strong>
											<span class="sc-acc-pill"><?php echo esc_html( sc_t( '已发货', 'Delivered' ) ); ?></span>
										</div>
										<div class="sc-acc-orderrow__meta">
											<span><?php echo esc_html( $ord['order_no'] ); ?></span>
											<span>·</span>
											<span><?php echo esc_html( $ord['date'] ); ?></span>
											<span>·</span>
											<code><?php echo esc_html( $ord['license_key'] ); ?></code>
										</div>
									</div>
									<div class="sc-acc-orderrow__act">
										<strong class="sc-acc-price"><?php echo esc_html( $ord['price'] ); ?></strong>
										<button type="button" class="sc-copy-btn" data-copy-text="<?php echo esc_attr( $full_cmd ); ?>">
											<?php echo esc_html( sc_t( '复制口令', 'Copy cmd' ) ); ?>
										</button>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>

				<!-- 底部 3 列快捷服务入口 -->
				<div class="sc-acc-shortcuts">
					<a class="sc-acc-shortcut" href="<?php echo esc_url( home_url( '/skills/' ) ); ?>">
						<span class="sc-acc-shortcut__num">01</span>
						<strong><?php echo esc_html( sc_t( '探索官方作品集市', 'Explore Skill Store' ) ); ?></strong>
						<p><?php echo esc_html( sc_t( '浏览工程研发、设计与法务全部自营精品。', 'Browse all first-party Skills across dev, design, and ops.' ) ); ?></p>
					</a>
					<a class="sc-acc-shortcut" href="<?php echo esc_url( home_url( '/custom/' ) ); ?>">
						<span class="sc-acc-shortcut__num">02</span>
						<strong><?php echo esc_html( sc_t( '定制开发与团队团购', 'Bespoke & Team Volume' ) ); ?></strong>
						<p><?php echo esc_html( sc_t( '5 人以上享团队批量折扣，支持 1 对 1 私有定制。', 'Volume discounts for teams and 1-on-1 private Skill builds.' ) ); ?></p>
					</a>
					<a class="sc-acc-shortcut" href="<?php echo esc_url( home_url( '/faq/' ) ); ?>">
						<span class="sc-acc-shortcut__num">03</span>
						<strong><?php echo esc_html( sc_t( '安装指南与常见问题', 'Setup Guides & FAQ' ) ); ?></strong>
						<p><?php echo esc_html( sc_t( '查看 Cursor / Claude Code 挂载教程与售后保障。', 'Setup steps for Cursor, Claude Code, and support info.' ) ); ?></p>
					</a>
				</div>
			</div>

			<!-- 面板 2：我的已购 -->
			<div class="sc-acc-panel" data-acc-panel="orders" <?php echo 'orders' === $tab ? '' : 'hidden'; ?>>
				<div class="sc-account__head">
					<div>
						<span class="sc-kicker">/ <?php echo esc_html( sc_t( '我的已购 · MY PURCHASES', 'MY PURCHASES' ) ); ?></span>
						<h1><?php echo esc_html( sc_t( '已购 Skill 与授权口令', 'Purchased Skills & Licenses' ) ); ?></h1>
					</div>
					<a class="sc-btn sc-btn--dark" href="<?php echo esc_url( home_url( '/skills/' ) ); ?>"><?php echo esc_html( sc_t( '继续逛集市', 'Browse Store' ) ); ?></a>
				</div>

				<?php if ( empty( $orders ) ) : ?>
					<div class="sc-account__box sc-acc-empty">
						<p><?php echo esc_html( sc_t( '你还没有购买过 Skill，去作品集市挑一款顺手的效率工具吧。', 'You have not purchased any Skills yet.' ) ); ?></p>
						<a class="sc-btn sc-btn--dark" href="<?php echo esc_url( home_url( '/skills/' ) ); ?>"><?php echo esc_html( sc_t( '前往作品集市', 'Browse Store' ) ); ?></a>
					</div>
				<?php else : ?>
					<?php foreach ( $orders as $ord ) :
						$full_cmd  = $ord['install_cmd'] . ' --key ' . $ord['license_key'];
						$exp_lbl   = $ord['expires_label'] ?? sc_t( '永久有效', 'Lifetime' );
						$dev_cnt   = (int) ( $ord['devices_count'] ?? 0 );
						$dev_max   = (int) ( $ord['max_devices'] ?? 2 );
						$order_id  = (int) ( $ord['order_id'] ?? 0 );
						?>
						<div class="sc-account__box sc-acc-ordcard">
							<div class="sc-acc-ordcard__top">
								<div>
									<div class="sc-acc-orderrow__title">
										<strong><?php echo esc_html( $ord['skill_title'] ); ?></strong>
										<span class="sc-acc-pill"><?php echo esc_html( sc_t( '有效期：', 'Valid: ' ) . $exp_lbl ); ?></span>
									</div>
									<span class="sc-acc-ordcard__sub">
										<?php echo esc_html( $ord['order_no'] . ' · ' . $ord['date'] . ' · ' . $ord['price'] . ' · ' . sprintf( sc_t( '已绑 %1$d/%2$d 台设备', '%1$d/%2$d devices' ), $dev_cnt, $dev_max ) ); ?>
									</span>
								</div>
								<div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
									<div class="sc-acc-keychip">
										<span><?php echo esc_html( sc_t( '授权码', 'KEY' ) ); ?></span>
										<code><?php echo esc_html( $ord['license_key'] ); ?></code>
									</div>
									<?php if ( $order_id ) : ?>
										<button type="button" class="sc-copy-btn" data-unbind-order="<?php echo esc_attr( $order_id ); ?>">
											<?php echo esc_html( sc_t( '解绑设备', 'Unbind devices' ) ); ?>
										</button>
									<?php endif; ?>
								</div>
							</div>
							<div class="sc-pd-cmd">
								<code><?php echo esc_html( $full_cmd ); ?></code>
								<button type="button" class="sc-copy-btn" data-copy-text="<?php echo esc_attr( $full_cmd ); ?>">
									<?php echo esc_html( sc_t( '复制指令', 'Copy' ) ); ?>
								</button>
							</div>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>

			<!-- 面板 3：个人资料 -->
			<div class="sc-acc-panel" data-acc-panel="profile" <?php echo 'profile' === $tab ? '' : 'hidden'; ?>>
				<div class="sc-account__head">
					<div>
						<span class="sc-kicker">/ <?php echo esc_html( sc_t( '个人资料 · PROFILE', 'PROFILE' ) ); ?></span>
						<h1><?php echo esc_html( sc_t( '资料与语言偏好', 'Profile & preferences' ) ); ?></h1>
					</div>
				</div>

				<div class="sc-account__box">
					<form class="sc-form sc-form--wide" method="post" action="">
						<?php wp_nonce_field( 'sc_auth_update_profile', 'sc_auth_nonce' ); ?>
						<input type="hidden" name="sc_auth_action" value="update_profile">

						<div class="sc-form__row2">
							<div class="sc-field">
								<label for="sc-p-name"><?php echo esc_html( sc_t( '显示名称', 'Display name' ) ); ?></label>
								<input class="sc-input" id="sc-p-name" name="sc_display_name" type="text" required value="<?php echo esc_attr( $user->display_name ); ?>">
							</div>
							<div class="sc-field">
								<label for="sc-p-email"><?php echo esc_html( sc_t( '绑定邮箱', 'Email' ) ); ?></label>
								<input class="sc-input" id="sc-p-email" name="sc_email" type="email" required value="<?php echo esc_attr( $user->user_email ); ?>">
							</div>
						</div>

						<div class="sc-field">
							<label for="sc-p-lang"><?php echo esc_html( sc_t( '界面偏好语言', 'Interface language' ) ); ?></label>
							<select class="sc-input" id="sc-p-lang" name="sc_lang">
								<option value="zh" <?php selected( $user_lang, 'zh' ); ?>>简体中文</option>
								<option value="en" <?php selected( $user_lang, 'en' ); ?>>English</option>
							</select>
						</div>

						<div class="sc-field">
							<label for="sc-p-bio"><?php echo esc_html( sc_t( '个人简介', 'Bio' ) ); ?></label>
							<textarea class="sc-input sc-textarea" id="sc-p-bio" name="sc_bio" rows="3"><?php echo esc_textarea( $user->description ); ?></textarea>
						</div>

						<div>
							<button type="submit" class="sc-btn sc-btn--dark"><?php echo esc_html( sc_t( '保存更改', 'Save changes' ) ); ?></button>
						</div>
					</form>
				</div>
			</div>

			<!-- 面板 4：安全与密码 -->
			<div class="sc-acc-panel" data-acc-panel="security" <?php echo 'security' === $tab ? '' : 'hidden'; ?>>
				<div class="sc-account__head">
					<div>
						<span class="sc-kicker">/ <?php echo esc_html( sc_t( '安全设置 · SECURITY', 'SECURITY' ) ); ?></span>
						<h1><?php echo esc_html( sc_t( '修改登录密码', 'Change password' ) ); ?></h1>
					</div>
				</div>

				<div class="sc-account__box">
					<form class="sc-form sc-form--wide" method="post" action="">
						<?php wp_nonce_field( 'sc_auth_update_password', 'sc_auth_nonce' ); ?>
						<input type="hidden" name="sc_auth_action" value="update_password">

						<div class="sc-field">
							<label for="sc-cur-p"><?php echo esc_html( sc_t( '当前密码', 'Current password' ) ); ?></label>
							<input class="sc-input" id="sc-cur-p" name="sc_current_password" type="password" required>
						</div>

						<div class="sc-form__row2">
							<div class="sc-field">
								<label for="sc-new-p"><?php echo esc_html( sc_t( '新密码', 'New password' ) ); ?></label>
								<input class="sc-input" id="sc-new-p" name="sc_new_password" type="password" required minlength="8">
							</div>
							<div class="sc-field">
								<label for="sc-cfm-p"><?php echo esc_html( sc_t( '确认新密码', 'Confirm new password' ) ); ?></label>
								<input class="sc-input" id="sc-cfm-p" name="sc_confirm_password" type="password" required minlength="8">
							</div>
						</div>

						<div>
							<button type="submit" class="sc-btn sc-btn--dark"><?php echo esc_html( sc_t( '更新密码', 'Update password' ) ); ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
