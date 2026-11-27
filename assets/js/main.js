/* SkillCraft 交互层：撕贴 CTA、macOS 演示窗、暗色步骤切换、跟随鼠标眨眼、FAQ、档案袋开合、语言切换 */
(function () {
	'use strict';

	var cfg = window.SkillCraft || { lang: 'zh' };

	/* 1. 语言切换（点击地球弹出精致下拉菜单） */
	var langDrop = document.getElementById('sc-langDrop');
	var langBtn = document.getElementById('sc-langSwitch');
	var langMenu = document.getElementById('sc-langMenu');
	if (langDrop && langBtn && langMenu) {
		function setLangMenu(open) {
			langMenu.hidden = !open;
			langBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
		}
		langBtn.addEventListener('click', function (e) {
			e.stopPropagation();
			setLangMenu(langMenu.hidden);
		});
		document.addEventListener('click', function (e) {
			if (!langDrop.contains(e.target)) setLangMenu(false);
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape') setLangMenu(false);
		});
		langMenu.querySelectorAll('[data-lang]').forEach(function (item) {
			item.addEventListener('click', function () {
				var want = item.getAttribute('data-lang');
				setLangMenu(false);
				if (want === cfg.lang) return;
				try {
					var defCcy = (want === 'en') ? 'USD' : 'CNY';
					document.cookie = 'skillcraft_lang=' + want + '; path=/; max-age=' + (60 * 60 * 24 * 365) + '; SameSite=Lax';
					document.cookie = 'skillcraft_currency=' + defCcy + '; path=/; max-age=' + (60 * 60 * 24 * 365) + '; SameSite=Lax';
				} catch (e) {}
				if (/\.html$/i.test(location.pathname)) {
					if (want === 'en' && !/-en\.html$/i.test(location.pathname)) {
						location.href = location.pathname.replace(/\.html$/i, '-en.html');
						return;
					}
					if (want === 'zh' && /-en\.html$/i.test(location.pathname)) {
						location.href = location.pathname.replace(/-en\.html$/i, '.html');
						return;
					}
				}
				try {
					var url = new URL(location.href);
					url.searchParams.delete('lang');
					location.href = url.toString();
				} catch (e) {
					location.reload();
				}
			});
		});
	}

	/* 2. 移动端抽屉 */
	var burger = document.getElementById('sc-burger');
	var drawer = document.getElementById('sc-drawer');
	if (burger && drawer) {
		burger.addEventListener('click', function () {
			var open = drawer.hidden;
			drawer.hidden = !open;
			burger.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	}

	/* 3. Hero 撕贴纸 CTA */
	var peel = document.getElementById('sc-peelCta');
	if (peel) {
		var topBtn = peel.querySelector('.sc-peel__top');
		if (topBtn) {
			topBtn.addEventListener('click', function () {
				peel.classList.add('is-peeled');
			});
		}
	}

	/* 4. macOS 交互演示窗口（点击黑色发送圆按钮体验流程） */
	var demoForm = document.getElementById('sc-demoForm');
	var demoInput = document.getElementById('sc-demoInput');
	var demoLog = document.getElementById('sc-demoLog');
	var demoIntro = document.getElementById('sc-demoIntro');
	function runDemoPrompt(text) {
		if (!demoLog || !text) return;
		if (demoIntro) demoIntro.style.display = 'none';
		demoLog.innerHTML = '';

		var u = document.createElement('div');
		u.className = 'sc-msg sc-msg--user';
		u.textContent = text;
		demoLog.appendChild(u);

		var steps = cfg.lang === 'en'
			? [
				{ badge: '1/3 · Verified Registry', text: 'Resolved Skill "Full-Stack SQL Profiler" (v1.2.0) · Security scan passed · Price ¥49.90 (One-time).' },
				{ badge: '2/3 · Checkout Confirmed', text: 'Authorization confirmed by user · Encrypted runtime token issued for current workspace.' },
				{ badge: '3/3 · Installed & Ready', text: 'SKILL.md mounted successfully. You can now invoke SQL profiling directly inside your Agent.' }
			]
			: [
				{ badge: '1/3 · 校验官方来源', text: '已解析商品「SQL 慢查询全链路诊断」(v1.2.0) · 安全扫描通过 · 买断价 ¥49.90。' },
				{ badge: '2/3 · 支付与授权确认', text: '用户已确认授权 · 正在为当前工作区签发加密运行凭据并下载能力包…' },
				{ badge: '3/3 · 安装完成', text: 'SKILL.md 已挂载并校验通过，现在可以直接在对话中调用该专业 Skill。' }
			];

		steps.forEach(function (st, idx) {
			setTimeout(function () {
				var a = document.createElement('div');
				a.className = 'sc-msg sc-msg--agent';
				var badge = document.createElement('span');
				badge.className = 'sc-msg__badge';
				badge.textContent = st.badge;
				var p = document.createElement('p');
				p.textContent = st.text;
				a.appendChild(badge);
				a.appendChild(p);
				demoLog.appendChild(a);
			}, 320 * (idx + 1));
		});
	}
	if (demoForm && demoInput) {
		demoForm.addEventListener('submit', function (e) {
			e.preventDefault();
			var v = demoInput.value.trim();
			if (!v) return;
			runDemoPrompt(v);
		});
	}

	/* 5. 一键复制指令 */
	var copyBtn = document.getElementById('sc-copyCmd');
	var copyTag = document.getElementById('sc-copyTag');
	if (copyBtn && copyTag) {
		copyBtn.addEventListener('click', function () {
			var cmd = copyBtn.getAttribute('data-cmd') || '';
			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(cmd).catch(function () {});
			}
			var prev = copyTag.textContent;
			copyTag.textContent = cfg.lang === 'en' ? 'Copied ✓' : '已复制 ✓';
			setTimeout(function () { copyTag.textContent = prev; }, 1800);
		});
	}

	/* 6. 暗色 3 步入驻交互切换 */
	var stepTabs = document.querySelectorAll('[data-step-tab]');
	var stepPanes = document.querySelectorAll('[data-step-pane]');
	stepTabs.forEach(function (tab) {
		tab.addEventListener('click', function () {
			var idx = tab.getAttribute('data-step-tab');
			stepTabs.forEach(function (t) {
				var active = t === tab;
				t.classList.toggle('is-active', active);
				t.setAttribute('aria-selected', active ? 'true' : 'false');
			});
			stepPanes.forEach(function (p) {
				p.classList.toggle('is-active', p.getAttribute('data-step-pane') === idx);
			});
		});
	});

	/* 7. 暗色区趴着的小狗：全宽 100% 聚光灯 + 金元宝光标 + 五官跟随 + 靠近全屏爆金光星星眼 */
	var darkBand = document.getElementById('start');
	var darkHead = document.getElementById('sc-darkHead');
	var peekDog = document.getElementById('sc-peekDog');
	var dogFace = document.getElementById('sc-dogFace');
	var ingotCursor = document.getElementById('sc-ingotCursor');
	if (darkBand && darkHead && peekDog && dogFace) {
		darkHead.addEventListener('mouseenter', function () {
			darkBand.classList.add('is-hover');
		});
		darkHead.addEventListener('mouseleave', function () {
			darkBand.classList.remove('is-hover', 'is-gold');
			dogFace.style.transform = '';
		});
		darkHead.addEventListener('mousemove', function (e) {
			var box = darkBand.getBoundingClientRect();
			var x = e.clientX - box.left;
			var y = e.clientY - box.top;
			darkBand.style.setProperty('--mx', x + 'px');
			darkBand.style.setProperty('--my', y + 'px');
			if (ingotCursor) {
				ingotCursor.style.left = x + 'px';
				ingotCursor.style.top = y + 'px';
			}

			var dbox = peekDog.getBoundingClientRect();
			var cx = dbox.left + dbox.width / 2;
			var cy = dbox.top + dbox.height / 2;
			var dx = e.clientX - cx;
			var dy = e.clientY - cy;
			var dist = Math.hypot(dx, dy);

			var tx = Math.max(-7, Math.min(7, dx / 26));
			var ty = Math.max(-5, Math.min(5, dy / 26));
			dogFace.style.transform = 'translate(' + tx.toFixed(1) + 'px,' + ty.toFixed(1) + 'px)';

			// 鼠标（金元宝）靠近小狗 150px 内 → 整个暗色区块全宽爆耀眼金光 + 星星眼
			darkBand.classList.toggle('is-gold', dist < 150);
		});
	}

	/* 8. FAQ 手风琴 & 独立页双 Tab 切换 */
	document.querySelectorAll('.sc-faq__q').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var item = btn.closest('.sc-faq__item');
			var open = item.classList.toggle('is-open');
			btn.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	});

	var faqTabs = document.querySelectorAll('[data-faq-tab]');
	var faqPanels = document.querySelectorAll('[data-faq-panel]');
	faqTabs.forEach(function (tab) {
		tab.addEventListener('click', function () {
			var key = tab.getAttribute('data-faq-tab');
			faqTabs.forEach(function (t) {
				var active = t === tab;
				t.classList.toggle('is-active', active);
				t.setAttribute('aria-selected', active ? 'true' : 'false');
			});
			faqPanels.forEach(function (p) {
				var show = p.getAttribute('data-faq-panel') === key;
				p.classList.toggle('is-active', show);
				p.hidden = !show;
			});
		});
	});

	/* 9. 拟物米白宣纸档案袋开合（点击整个档案袋或按回车均可触发） */
	var env = document.getElementById('sc-envelope');
	var envHint = document.getElementById('sc-envelopeBtn');
	if (env) {
		function toggleEnv(e) {
			if (e.target && e.target.closest && e.target.closest('a')) return;
			var open = env.classList.toggle('is-open');
			env.setAttribute('aria-expanded', open ? 'true' : 'false');
			if (envHint) {
				envHint.textContent = open
					? (cfg.lang === 'en' ? 'Click to close dossier' : '点击合上档案袋')
					: (cfg.lang === 'en' ? 'Click to unwind dossier' : '点击解开档案袋');
			}
		}
		env.addEventListener('click', toggleEnv);
		env.addEventListener('keydown', function (e) {
			if (e.key === 'Enter' || e.key === ' ') {
				e.preventDefault();
				toggleEnv(e);
			}
		});
	}

	/* 10. 悬浮技术支持微件 */
	var floatToggle = document.getElementById('sc-floatToggle');
	var floatCard = document.getElementById('sc-floatCard');
	var floatClose = document.getElementById('sc-floatClose');
	if (floatToggle && floatCard) {
		floatToggle.addEventListener('click', function () {
			var open = floatCard.hidden;
			floatCard.hidden = !open;
			floatToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	}
	if (floatClose && floatCard && floatToggle) {
		floatClose.addEventListener('click', function () {
			floatCard.hidden = true;
			floatToggle.setAttribute('aria-expanded', 'false');
		});
	}

	/* 11. 密码显隐 */
	document.querySelectorAll('[data-toggle-pass]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var inp = document.getElementById(btn.getAttribute('data-toggle-pass'));
			if (!inp) return;
			var show = inp.type === 'password';
			inp.type = show ? 'text' : 'password';
			btn.textContent = cfg.lang === 'en' ? (show ? 'Hide' : 'Show') : (show ? '隐藏' : '显示');
		});
	});

	/* 12. 商品页：展示模式/阅读模式切换 + 使用说明 + 评价投票 + 购买弹窗 */
	var prodStage = document.getElementById('sc-prodStage');
	var modeBtns = document.querySelectorAll('[data-prod-mode]');
	if (prodStage && modeBtns.length) {
		modeBtns.forEach(function (btn) {
			btn.addEventListener('click', function () {
				var mode = btn.getAttribute('data-prod-mode');
				prodStage.setAttribute('data-mode', mode);
				modeBtns.forEach(function (b) {
					var on = b === btn;
					b.classList.toggle('is-active', on);
					b.setAttribute('aria-pressed', on ? 'true' : 'false');
				});
			});
		});
	}

	var guideToggle = document.getElementById('sc-guideToggle');
	var usageBox = document.getElementById('sc-usageBox');
	if (guideToggle && usageBox) {
		guideToggle.addEventListener('click', function () {
			usageBox.hidden = !usageBox.hidden;
		});
	}

	var voteTotal = document.getElementById('sc-voteTotal');
	document.querySelectorAll('[data-vote-key]').forEach(function (chip) {
		chip.addEventListener('click', function () {
			var numEl = chip.querySelector('[data-vote-num]');
			if (!numEl) return;
			var voted = chip.classList.toggle('is-voted');
			var cur = parseInt(numEl.textContent, 10) || 0;
			numEl.textContent = cur + (voted ? 1 : -1);
			if (voteTotal) {
				voteTotal.textContent = (parseInt(voteTotal.textContent, 10) || 0) + (voted ? 1 : -1);
			}
		});
	});

	var buyBtn = document.getElementById('sc-buyBtn');
	var buyModal = document.getElementById('sc-buyModal');
	if (buyBtn && buyModal) {
		var orderForm = document.getElementById('sc-orderForm');
		var orderResult = document.getElementById('sc-orderResult');
		var orderMsg = document.getElementById('sc-orderMsg');
		var orderCmd = document.getElementById('sc-orderCmd');
		var copyOrderBtn = document.getElementById('sc-copyOrderCmd');
		var orderSubmit = document.getElementById('sc-orderSubmit');

		buyBtn.addEventListener('click', function () {
			buyModal.hidden = false;
		});
		buyModal.querySelectorAll('[data-close-modal]').forEach(function (el) {
			el.addEventListener('click', function () { buyModal.hidden = true; });
		});

		function showDelivery(orderNo, key, cmd) {
			if (orderForm) orderForm.hidden = true;
			if (orderResult) orderResult.hidden = false;
			if (orderMsg) {
				orderMsg.textContent = (cfg.lang === 'en')
					? ('Order ' + orderNo + ' confirmed · License: ' + key)
					: ('订单 ' + orderNo + ' 已生效 · 专属授权码：' + key);
			}
			if (orderCmd) orderCmd.textContent = cmd;
			if (copyOrderBtn) copyOrderBtn.setAttribute('data-cmd', cmd);
		}

		if (orderForm) {
			orderForm.addEventListener('submit', function (e) {
				e.preventDefault();
				var emailEl = document.getElementById('sc-orderEmail');
				var email = emailEl ? emailEl.value.trim() : '';
				var baseCmd = buyModal.getAttribute('data-install-cmd') || '';

				if (cfg.ajaxUrl && cfg.orderNonce && !/\.html$/i.test(location.pathname)) {
					if (orderSubmit) orderSubmit.disabled = true;
					var fd = new FormData();
					fd.append('action', 'sc_create_order');
					fd.append('nonce', cfg.orderNonce);
					fd.append('skill_id', buyModal.getAttribute('data-skill-id') || '0');
					fd.append('skill_title', buyModal.getAttribute('data-skill-title') || '');
					fd.append('price', buyModal.getAttribute('data-skill-price') || '');
					fd.append('install_cmd', baseCmd);
					fd.append('email', email);

					fetch(cfg.ajaxUrl, { method: 'POST', body: fd, credentials: 'same-origin' })
						.then(function (r) { return r.json(); })
						.then(function (res) {
							if (res && res.success && res.data) {
								showDelivery(res.data.order_no, res.data.license_key, res.data.install_cmd);
							}
						})
						.finally(function () {
							if (orderSubmit) orderSubmit.disabled = false;
						});
				} else {
					var demoKey = 'SC-DEMO-' + Math.floor(1000 + Math.random() * 9000);
					showDelivery('ORD-PREVIEW', demoKey, baseCmd + ' --key ' + demoKey);
				}
			});
		}

		if (copyOrderBtn) {
			copyOrderBtn.addEventListener('click', function () {
				var cmd = copyOrderBtn.getAttribute('data-cmd') || '';
				if (navigator.clipboard && navigator.clipboard.writeText) {
					navigator.clipboard.writeText(cmd).catch(function () {});
				}
				var prev = copyOrderBtn.textContent;
				copyOrderBtn.textContent = cfg.lang === 'en' ? 'Copied ✓' : '已复制安装指令 ✓';
				setTimeout(function () { copyOrderBtn.textContent = prev; }, 1600);
			});
		}
	}

	/* 13. 作品集市内页：分类 + 计费 + 实时搜索联合筛选 */
	var marketGrid = document.getElementById('sc-marketGrid');
	var marketSearch = document.getElementById('sc-marketSearch');
	var marketEmpty = document.getElementById('sc-marketEmpty');
	var catBtns = document.querySelectorAll('[data-cat-filter]');
	var filterBtns = document.querySelectorAll('[data-billing-filter]');
	if (marketGrid) {
		var curCat = 'all';
		var curBilling = 'all';
		function applyMarketFilter() {
			var q = marketSearch ? marketSearch.value.trim().toLowerCase() : '';
			var shown = 0;
			marketGrid.querySelectorAll('.sc-product-card').forEach(function (card) {
				var okC = (curCat === 'all') || (card.getAttribute('data-cat') === curCat);
				var okB = (curBilling === 'all') || (card.getAttribute('data-billing') === curBilling);
				var okQ = !q || ((card.getAttribute('data-search') || '').indexOf(q) !== -1);
				var show = okC && okB && okQ;
				card.style.display = show ? '' : 'none';
				if (show) shown++;
			});
			if (marketEmpty) marketEmpty.hidden = shown > 0;
		}
		catBtns.forEach(function (btn) {
			btn.addEventListener('click', function () {
				curCat = btn.getAttribute('data-cat-filter') || 'all';
				catBtns.forEach(function (b) { b.classList.toggle('is-active', b === btn); });
				applyMarketFilter();
			});
		});
		filterBtns.forEach(function (btn) {
			btn.addEventListener('click', function () {
				curBilling = btn.getAttribute('data-billing-filter') || 'all';
				filterBtns.forEach(function (b) { b.classList.toggle('is-active', b === btn); });
				applyMarketFilter();
			});
		});
		if (marketSearch) {
			marketSearch.addEventListener('input', applyMarketFilter);
		}
	}

	/* 13.4 首页极简 macOS 演示窗：工作台场景切换 */
	var wbTabs = document.querySelectorAll('[data-wb-switch]');
	var wbTag = document.getElementById('sc-wbTag');
	var wbTitle = document.getElementById('sc-wbTitle');
	var wbChips = document.getElementById('sc-wbChips');
	var wbInput = document.getElementById('sc-demoInput');
	if (wbTabs.length && wbTitle) {
		var isEnWb = window.SkillCraft && window.SkillCraft.lang === 'en';
		var wbPresets = {
			ops: {
				tag: isEnWb ? 'OPS WORKBENCH · LIVE DEMO' : '运维工作台 · 实时演示',
				title: isEnWb ? 'Click send below to watch your Agent handle server ops.' : '点击右下角发送，体验 Agent 接管服务器运维。',
				chips: isEnWb ? ['3 Nodes Online', 'Sites & Panels', 'Auto SSH Triage'] : ['服务器 3 节点在线', '网站与面板巡检', 'SSH 异常自动排查'],
				prompt: isEnWb ? 'Inspect all online nodes, find the highest memory instance, and draft a cleanup plan.' : '巡检全部在线节点负载，定位内存占用最高的实例并自动生成清理建议。'
			},
			ecom: {
				tag: isEnWb ? 'COMMERCE WORKBENCH · LIVE DEMO' : '电商工作台 · 实时演示',
				title: isEnWb ? 'Click send below to sync SKUs across all storefronts.' : '点击右下角发送，体验多店商品上架与运营同步。',
				chips: isEnWb ? ['6 Active Stores', 'Localized Listings', 'Inventory Sync'] : ['6 个独立站在线', '多语种详情生成', '库存与价格同步'],
				prompt: isEnWb ? 'Sync 12 new SKUs to US & EU stores with localized titles and SEO metadata.' : '将选品池最新 12 款商品自动生成英文详情页并同步上架到欧美独立站。'
			},
			kb: {
				tag: isEnWb ? 'KNOWLEDGE DESK · LIVE DEMO' : '知识库工作台 · 实时演示',
				title: isEnWb ? 'Click send below to turn docs into multi-channel posts.' : '点击右下角发送，体验知识库驱动的全渠道内容分发。',
				chips: isEnWb ? ['46 Indexed Docs', 'Prompt Library', 'Multi-Channel'] : ['46 篇私有知识库', '提示词模板资产', '多平台定时分发'],
				prompt: isEnWb ? 'Draft a WeChat & Xiaohongshu launch post from our v2.4 release notes.' : '根据知识库里的 v2.4 更新文档，生成公众号长文与小红书图文卡片并排期。'
			}
		};

		wbTabs.forEach(function (tab) {
			tab.addEventListener('click', function () {
				var key = tab.getAttribute('data-wb-switch');
				var d = wbPresets[key];
				if (!d) return;
				wbTabs.forEach(function (t) {
					var on = t === tab;
					t.classList.toggle('is-active', on);
					t.setAttribute('aria-selected', on ? 'true' : 'false');
				});
				if (wbTag) wbTag.textContent = d.tag;
				wbTitle.textContent = d.title;
				if (wbChips) {
					wbChips.innerHTML = d.chips.map(function (c) { return '<span>' + c + '</span>'; }).join('');
				}
				if (wbInput) wbInput.value = d.prompt;
				var log = document.getElementById('sc-demoLog');
				if (log) log.innerHTML = '';
				var intro = document.getElementById('sc-demoIntro');
				if (intro) intro.style.display = '';
			});
		});
	}

	/* 13.5 会员中心 Tab 无刷新切换 + 通用一键复制 */
	var accPanels = document.querySelectorAll('[data-acc-panel]');
	if (accPanels.length) {
		var accTriggers = document.querySelectorAll('[data-acc-tab]');
		accTriggers.forEach(function (link) {
			link.addEventListener('click', function (e) {
				var target = link.getAttribute('data-acc-tab');
				if (!target) return;
				e.preventDefault();
				accPanels.forEach(function (p) {
					p.hidden = p.getAttribute('data-acc-panel') !== target;
				});
				document.querySelectorAll('.sc-account__nav [data-acc-tab]').forEach(function (a) {
					a.classList.toggle('is-active', a.getAttribute('data-acc-tab') === target);
				});
			});
		});
	}

	document.querySelectorAll('[data-copy-text]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var txt = btn.getAttribute('data-copy-text') || '';
			if (!txt) return;
			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(txt).catch(function () {});
			}
			var prev = btn.textContent;
			btn.textContent = (window.SkillCraft && window.SkillCraft.lang === 'en') ? 'Copied ✓' : '已复制 ✓';
			setTimeout(function () { btn.textContent = prev; }, 1600);
		});
	});

	document.querySelectorAll('[data-unbind-order]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var oid = btn.getAttribute('data-unbind-order');
			var isEnU = window.SkillCraft && window.SkillCraft.lang === 'en';
			if (!oid) return;
			btn.disabled = true;
			if (window.SkillCraft && window.SkillCraft.ajaxUrl && window.SkillCraft.orderNonce) {
				var fd = new FormData();
				fd.append('action', 'sc_unbind_devices');
				fd.append('nonce', window.SkillCraft.orderNonce);
				fd.append('order_id', oid);
				fetch(window.SkillCraft.ajaxUrl, { method: 'POST', body: fd, credentials: 'same-origin' })
					.then(function (r) { return r.json(); })
					.then(function (res) {
						btn.textContent = (res && res.success) ? (isEnU ? 'Unbound ✓' : '已解绑 ✓') : (isEnU ? 'Failed' : '操作失败');
					})
					.catch(function () {
						btn.textContent = isEnU ? 'Network error' : '网络错误';
					});
			} else {
				btn.textContent = isEnU ? 'Unbound (Demo) ✓' : '已解绑 (演示) ✓';
			}
		});
	});

	/* 13.6 Cookie 偏好横幅 */
	var cookieBar = document.getElementById('sc-cookieBar');
	var openCookieBtn = document.getElementById('sc-openCookiePrefs');
	if (cookieBar) {
		var hasConsent = document.cookie.indexOf('sc_cookie_consent=') !== -1;
		if (!hasConsent) {
			cookieBar.hidden = false;
		}
		cookieBar.querySelectorAll('[data-cookie-choice]').forEach(function (btn) {
			btn.addEventListener('click', function () {
				var choice = btn.getAttribute('data-cookie-choice') || 'essential';
				document.cookie = 'sc_cookie_consent=' + choice + '; path=/; max-age=' + (60 * 60 * 24 * 365) + '; SameSite=Lax';
				cookieBar.hidden = true;
			});
		});
		if (openCookieBtn) {
			openCookieBtn.addEventListener('click', function () {
				cookieBar.hidden = false;
			});
		}
	}

	/* 13.7 博客视图切换（卡片画廊 ⇄ 极简列表）+ 分类筛选 + 文章分享复制 */
	var blogFeed = document.getElementById('sc-blogFeed');
	if (blogFeed) {
		var viewBtns = document.querySelectorAll('[data-blog-view]');
		var catBtnsB = document.querySelectorAll('[data-blog-cat]');
		var savedView = '';
		try { savedView = localStorage.getItem('sc_blog_view') || ''; } catch (e) {}
		if (savedView === 'grid' || savedView === 'list') {
			blogFeed.setAttribute('data-layout', savedView);
			viewBtns.forEach(function (b) { b.classList.toggle('is-active', b.getAttribute('data-blog-view') === savedView); });
		}
		viewBtns.forEach(function (btn) {
			btn.addEventListener('click', function () {
				var v = btn.getAttribute('data-blog-view') || 'grid';
				blogFeed.setAttribute('data-layout', v);
				viewBtns.forEach(function (b) { b.classList.toggle('is-active', b === btn); });
				try { localStorage.setItem('sc_blog_view', v); } catch (e) {}
			});
		});
		catBtnsB.forEach(function (btn) {
			btn.addEventListener('click', function () {
				var cat = btn.getAttribute('data-blog-cat') || 'all';
				catBtnsB.forEach(function (b) { b.classList.toggle('is-active', b === btn); });
				blogFeed.querySelectorAll('.sc-bcard').forEach(function (card) {
					var c = card.getAttribute('data-post-cat') || '';
					card.hidden = !(cat === 'all' || c.toLowerCase() === cat.toLowerCase());
				});
			});
		});
	}

	document.querySelectorAll('[data-share-copy]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var u = window.location.href || btn.getAttribute('data-share-copy') || '';
			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(u).catch(function () {});
			}
			var sp = btn.querySelector('span');
			if (sp) {
				var old = sp.textContent;
				sp.textContent = (window.SkillCraft && window.SkillCraft.lang === 'en') ? 'Link copied ✓' : '链接已复制 ✓';
				setTimeout(function () { sp.textContent = old; }, 1600);
			}
		});
	});

	/* 13.8 商品详情页多图画廊切换 + 全屏灯箱 */
	var mainShot = document.getElementById('sc-mainShotImg');
	var lb = document.getElementById('sc-lightbox');
	var lbImg = document.getElementById('sc-lightboxImg');
	var lbClose = document.getElementById('sc-lightboxClose');
	if (mainShot) {
		document.querySelectorAll('[data-shot-src]').forEach(function (t) {
			t.addEventListener('click', function () {
				var src = t.getAttribute('data-shot-src');
				if (!src) return;
				mainShot.src = src;
				document.querySelectorAll('[data-shot-src]').forEach(function (b) { b.classList.toggle('is-active', b === t); });
			});
		});
		var openLb = function () {
			if (!lb || !lbImg) return;
			lbImg.src = mainShot.src;
			lb.hidden = false;
		};
		mainShot.addEventListener('click', openLb);
		var zoomBtn = document.getElementById('sc-zoomMainShot');
		if (zoomBtn) zoomBtn.addEventListener('click', openLb);
		if (lbClose) lbClose.addEventListener('click', function () { lb.hidden = true; });
		if (lb) {
			lb.addEventListener('click', function (e) { if (e.target === lb) lb.hidden = true; });
			document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !lb.hidden) lb.hidden = true; });
		}
	}

	/* 13.9 货币无刷新实时切换（CNY ⇄ USD） */
	var currBtn = document.getElementById('sc-currSwitch');
	var currMenu = document.getElementById('sc-currMenu');
	var currLabel = document.getElementById('sc-currLabel');
	var applyCurrency = function (ccy) {
		var code = (ccy === 'USD') ? 'USD' : 'CNY';
		if (currBtn) currBtn.setAttribute('data-currency', code);
		if (currLabel) currLabel.textContent = (code === 'USD') ? '$ USD' : '¥ CNY';
		document.querySelectorAll('[data-price-cny][data-price-usd]').forEach(function (el) {
			el.textContent = (code === 'USD') ? el.getAttribute('data-price-usd') : el.getAttribute('data-price-cny');
		});
		if (currMenu) {
			currMenu.querySelectorAll('[data-set-currency]').forEach(function (b) {
				var on = b.getAttribute('data-set-currency') === code;
				b.classList.toggle('is-active', on);
				var chk = b.querySelector('[data-curr-check]');
				if (chk) chk.hidden = !on;
			});
		}
	};
	if (currBtn && currMenu) {
		// 若用户有手动选择的 Cookie，页面加载时立刻同步
		var mCcy = document.cookie.match(/(?:^|;\s*)skillcraft_currency=(CNY|USD)/i);
		if (mCcy && mCcy[1]) {
			applyCurrency(mCcy[1].toUpperCase());
		}
		currBtn.addEventListener('click', function (e) {
			e.stopPropagation();
			var open = currMenu.hidden;
			currMenu.hidden = !open;
			currBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
		currMenu.querySelectorAll('[data-set-currency]').forEach(function (item) {
			item.addEventListener('click', function () {
				var want = item.getAttribute('data-set-currency') || 'CNY';
				document.cookie = 'skillcraft_currency=' + want + '; path=/; max-age=' + (60 * 60 * 24 * 365) + '; SameSite=Lax';
				applyCurrency(want);
				currMenu.hidden = true;
				currBtn.setAttribute('aria-expanded', 'false');
			});
		});
		document.addEventListener('click', function (e) {
			if (!currMenu.hidden && !currBtn.contains(e.target) && !currMenu.contains(e.target)) {
				currMenu.hidden = true;
				currBtn.setAttribute('aria-expanded', 'false');
			}
		});
	}

	/* 14. 滚动显现 */
	var reveals = document.querySelectorAll('[data-reveal]');
	if ('IntersectionObserver' in window) {
		var ro = new IntersectionObserver(function (entries) {
			entries.forEach(function (en) {
				if (!en.isIntersecting) return;
				en.target.classList.add('is-in');
				ro.unobserve(en.target);
			});
		}, { threshold: 0.1 });
		reveals.forEach(function (el) { ro.observe(el); });
	} else {
		reveals.forEach(function (el) { el.classList.add('is-in'); });
	}
})();
