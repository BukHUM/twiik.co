# twiik.co — Chrysoberyl WordPress Theme

Twiik - Tweak tech knowledge project base on WordPress CMS and Chrysoberyl theme.

---

# Chrysoberyl WordPress Theme

Minimal and clean blog style

## Description

Chrysoberyl is a clean, responsive WordPress theme for news and blog content with modern UI/UX. Built with Tailwind CSS, Theme Settings, and translation support (Thai / English).

## Features

- **Responsive design** — Mobile-first layout
- **Theme Settings** — Logo, pagination type, homepage columns, login page style, footer, social sharing, search, table of contents, widgets (Chrysoberyl menu in admin)
- **Custom post types** — Video News, Photo Gallery, Featured Story
- **Footer** — Newsletter/tags toggles, Footer1–Footer4 columns (sidebar/menu/social), copyright text and menu
- **Social sharing** — Configurable buttons and positions (above/below post, floating)
- **Search** — Live search, suggestions, post type and field options
- **Table of Contents** — TOC for single posts (style, position, mobile)
- **Widgets** — Popular Posts, Recent Posts, Trending Tags, Related Posts, Newsletter, Social Follow, and more
- **Translation ready** — Thai default; English via `languages/en_US.mo`. See `languages/README.md` for regenerating .pot/.po/.mo
- **Custom menu locations** — Primary, Footer, Footer Copyright
- **Featured images, post formats** — Standard WordPress support

## Requirements

- WordPress 6.0+
- PHP 8.0+
- MySQL 5.6+, MariaDB

## Installation

1. Upload the `chrysoberyl` folder to `/wp-content/themes/`
2. Activate the theme via **Appearance → Themes**
3. Configure options under **Chrysoberyl → Theme Settings** (General, Footer, Social Sharing, Search, Table of Contents, Widgets)

## Rank Math HTML Sitemap

The theme includes a **RankMath** page template for the HTML sitemap (collapsible sections, multi-column links, clearer spacing). To use:

1. Create or edit the page used as sitemap in **Rank Math SEO → Sitemap Settings → HTML Sitemap** (Display format: **Page**).
2. In the page editor, set **Page Attributes → Template** to **RankMath**.
3. For multiple collapsible sections (Posts, Pages, Categories), in **Rank Math SEO → Sitemap Settings** enable **Include in HTML Sitemap** for each post type and taxonomy you want.

## Translation

The theme uses the text domain `chrysoberyl`. English strings are in `languages/chrysoberyl-en_US.po` and compiled to `languages/en_US.mo`. Set **Settings → General → Site Language** to English to use them. To update or add languages, see **languages/README.md**.

## Version

1.0.0

## Credits

(ตรงกับหน้า **Chrysoberyl → Dashboard** ใน Theme Settings)

- **ทีมผู้พัฒนา:** [ต้นกล้าไอที](https://tonkla.co)
- **เว็บตัวอย่าง:** [กาเหว่า](https://gawao.com)
- **เว็บตัวอย่าง:** [Twiik](https://twiik.co)

## License

GNU General Public License v2 or later
