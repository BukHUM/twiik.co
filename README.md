# Chrysoberyl WordPress Theme

Modern news and blog theme for WordPress.

## Description

Chrysoberyl is a clean, responsive WordPress theme designed for news and blog websites. Built with Tailwind CSS, featuring comprehensive Theme Settings, and full translation support (Thai / English).

**Demo sites:** [twiik.co](https://twiik.co) | [chrysoberyl.me](https://chrysoberyl.me)

## Features

### Core Features
- **Responsive design** — Mobile-first layout optimized for all devices
- **Theme Settings** — Comprehensive admin panel for customization
- **Translation ready** — Thai default with English translation included
- **SEO friendly** — Clean markup, fast loading, Rank Math compatible

### Theme Settings (Admin Panel)
- **General** — Logo, pagination type, homepage layout, sidebar options
- **Footer** — Newsletter toggle, tags section, 4 customizable columns, copyright text
- **Social Sharing** — Configurable buttons, positions (above/below post, floating), icon styles
- **Search** — Live search, suggestions, debounce timing, minimum character length
- **Table of Contents** — Position (top/sidebar/floating), heading levels, scroll spy, sticky, collapsible
- **Widgets** — Enable/disable and reorder sidebar widgets

### Content Features
- **Custom post types** — Video News, Photo Gallery, Featured Story
- **Category colors** — Assign custom colors to categories
- **Classic Editor support** — Insert Code button with syntax highlighting
- **Demo data import** — Quick setup with sample content, categories, pages, and menus

### Menu Locations
- Primary Menu (header navigation with mega menu support)
- Footer Menu
- Footer Copyright Menu

## Requirements

- WordPress 6.0+
- PHP 7.4+
- MySQL 5.6+ or MariaDB

## Installation

1. Upload the `chrysoberyl` folder to `/wp-content/themes/`
2. Activate the theme via **Appearance → Themes**
3. Configure options under **Chrysoberyl → Theme Settings**
4. (Optional) Import demo data via **Chrysoberyl → Import Demo Data**

## Demo Data Import

The theme includes a demo data importer to quickly set up your site:

1. Go to **Chrysoberyl → Import Demo Data** in WordPress admin
2. Select what to import: Categories, Posts, Pages, or Menus
3. Click **Import** to add sample content

**Note:** Demo posts include a source link to twiik.co. You can edit or remove these after import.

## Rank Math HTML Sitemap

The theme includes a custom template for Rank Math HTML sitemap:

1. Create a page for your sitemap
2. Set **Page Attributes → Template** to **RankMath**
3. Configure in **Rank Math SEO → Sitemap Settings → HTML Sitemap**

## Translation

- Text domain: `chrysoberyl`
- English translations: `languages/chrysoberyl-en_US.po`
- Set **Settings → General → Site Language** to English (United States) to use English

To add or update translations, edit the `.po` file and compile to `.mo` using Poedit or similar tools.

## File Structure

```
chrysoberyl/
├── assets/
│   ├── css/          # Tailwind CSS and custom styles
│   └── js/           # JavaScript files
├── inc/
│   ├── admin/        # Admin menu and dashboard
│   ├── custom-post-types.php
│   ├── demo-data-import.php
│   ├── theme-setup.php
│   └── ...
├── languages/        # Translation files
├── template-parts/   # Reusable template components
├── widgets/          # Custom widget classes
├── functions.php
├── style.css
└── README.md
```

## Version

1.0.0

## Credits

- **Forked from:** [Trend Today theme](https://gawao.com)
- **Development team:** [Tonkla IT](https://tonkla.co)
- **Demo sites:** [chrysoberyl.me](https://chrysoberyl.me), [twiik.co](https://twiik.co)

## License

GNU General Public License v2 or later  
[http://www.gnu.org/licenses/gpl-2.0.html](http://www.gnu.org/licenses/gpl-2.0.html)
