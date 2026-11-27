# AI Workbench / MCP / Skill Store — WordPress Theme

Bilingual (ZH / EN) Swiss-architectural WordPress theme for selling first-party **AI Business Workbenches**, **MCP Plugins**, and **Skill Packs** — zero third-party plugin dependencies.

## Features
- **First-Party Storefront**: Hero with interactive Agent Office SVG + 4-row marquee, minimalist macOS workbench switcher, 4-column catalog grid, interactive dark onboarding section, supported Agents marquee, security pillars, FAQ accordion, tactile dossier envelope.
- **Full WordPress Admin CMS**:
  - `工作台与商品` (`sc_skill` CPT): product format (`workbench` / `mcp` / `skill`), Media Library screenshot uploader, built-in module tags, views & heat metrics, protected payload & download URL, license cycle days, max devices.
  - `商品订单` (`sc_order` CPT): automated license key generation (`SC-XXXX-XXXX-XXXX`), expiry calculation, device binding list, `wp_mail()` automated delivery, `sc_fulfill_order()` payment webhook hook.
  - `更新日志` (`sc_changelog` CPT): publish new releases directly to `/changelog/`.
  - `SkillCraft 内容`: toggle homepage modules, edit bilingual copy, manage FAQs & guides, toggle blog comments & default card/list layout.
- **Anti-AI-Bypass License REST API (`/wp-json/skillcraft/v1/license/*`)**:
  - Auto-generated **Ed25519** asymmetric keypair (private key stays on WP; clients only embed public key).
  - Nonce challenge-response + 24h short-lived signed lease tokens + IP rate limiting.
  - Endpoints: `/activate`, `/verify`, `/payload`, `/deactivate`.
- **SEO & GEO Ready**: Auto-generated `/llms.txt`, `/sc-sitemap.xml`, dynamic Schema.org JSON-LD (`Organization`, `WebSite`, `SoftwareApplication + Offer`, `CollectionPage`, `FAQPage`, `Article`), bilingual `hreflang`, and `noindex` on auth pages.
- **Compliance Pages**: Auto-created `/terms/`, `/privacy/`, `/refund/`, `/cookies/` + minimalist Cookie consent banner.

## Installation
1. Copy this folder into `wp-content/themes/skillcraft/` (or upload as a `.zip` under **Appearance → Themes → Add New**).
2. Activate the theme — core pages and 8 starter products are created automatically.
3. Set your brand name in **Settings → General → Site Title** (the entire theme syncs automatically).
4. Copy your Ed25519 Public Key from **SkillCraft 内容 → 授权 API 与公钥** into your `dsh` / desktop / MCP client.

## Local Static Preview (No WordPress Required)
```bash
php tools/preview.php
php -S 127.0.0.1:8789 -t preview
```
