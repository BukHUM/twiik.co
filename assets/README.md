# Chrysoberyl Theme Assets

This directory contains all CSS, JavaScript, and other assets for the Chrysoberyl WordPress theme.

## Directory Structure

```
assets/
├── css/
│   ├── custom.css          # Custom theme styles
│   ├── print.css           # Print stylesheet
│   ├── admin.css           # Admin area styles
│   └── tailwind.css        # Built Tailwind CSS (production)
├── js/
│   ├── main.js             # Main theme JavaScript
│   └── custom.js            # Custom functionality
├── images/                 # Theme images and icons
└── README.md               # This file
```

## Tailwind CSS (Local Build)

The theme uses **Tailwind CSS built locally** (no CDN). Built file: `assets/css/tailwind.css`.

### Build commands (from theme root)

```bash
# One-off build (e.g. before deploy)
npm run build:css

# Watch mode during development
npm run watch:css
```

- **Source:** `assets/css/tailwind-src.css`
- **Config:** `tailwind.config.js` (theme root)
- **Output:** `assets/css/tailwind.css` (enqueued by the theme)

Before first use or after adding new Tailwind classes in PHP/JS, run `npm run build:css`. Commit `tailwind.css` so production works without Node.

## Asset Versioning

The theme uses file modification time (`filemtime()`) for cache busting in production. This ensures browsers always load the latest version of assets when files are updated.

## Performance Optimizations

- **Lazy Loading:** Images with `loading="lazy"` attribute are automatically lazy-loaded
- **Preconnect:** Google Fonts use preconnect for faster loading
- **Print Styles:** Separate stylesheet for print media
- **Admin Styles:** Separate stylesheet for WordPress admin area

## Browser Support

- Modern browsers (Chrome, Firefox, Safari, Edge)
- IE11 support: Not guaranteed (uses modern CSS features)
- Mobile browsers: Full support

## Notes

- Always minify CSS/JS for production
- Use version control for asset files
- Test responsive design on multiple devices
- Verify print styles work correctly
