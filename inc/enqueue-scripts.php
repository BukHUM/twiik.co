<?php
/**
 * Enqueue scripts and styles
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue theme scripts and styles
 */
function chrysoberyl_enqueue_assets()
{
    $version = chrysoberyl_get_theme_version();

    // Google Fonts: Google Sans (400,500,700) + Noto Sans Thai (300–700) — ตรง mockup
    wp_enqueue_style(
        'chrysoberyl-google-fonts',
        'https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;700&family=Noto+Sans+Thai:wght@300;400;500;600;700&display=swap',
        array(),
        null
    );

    // Add preconnect for Google Fonts
    add_filter('style_loader_tag', 'chrysoberyl_add_preconnect_for_fonts', 10, 2);

    // Font Awesome
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        array(),
        '6.4.0'
    );

    // Tailwind CSS (local build – no CDN)
    $tailwind_css = get_template_directory() . '/assets/css/tailwind.css';
    if (file_exists($tailwind_css)) {
        wp_enqueue_style(
            'chrysoberyl-tailwind',
            get_template_directory_uri() . '/assets/css/tailwind.css',
            array(),
            filemtime($tailwind_css)
        );
    }

    // Theme stylesheet (style.css in theme root)
    $style_uri = get_stylesheet_uri();
    $style_deps = array('chrysoberyl-google-fonts', 'font-awesome');
    if (file_exists($tailwind_css)) {
        $style_deps[] = 'chrysoberyl-tailwind';
    }
    wp_enqueue_style(
        'chrysoberyl-style',
        $style_uri,
        $style_deps,
        $version
    );

    // Custom CSS
    $custom_css_file = get_template_directory() . '/assets/css/custom.css';
    if (file_exists($custom_css_file)) {
        wp_enqueue_style(
            'chrysoberyl-custom',
            get_template_directory_uri() . '/assets/css/custom.css',
            array('chrysoberyl-style'),
            filemtime($custom_css_file)
        );
    }

    // Prism.js – code snippet syntax highlighting (light theme, editor-like) on single/post/page
    if (is_singular()) {
        wp_enqueue_style(
            'prism-coy',
            'https://cdn.jsdelivr.net/npm/prismjs@1.29.0/themes/prism-coy.min.css',
            array(),
            '1.29.0'
        );
        wp_enqueue_script(
            'prism-core',
            'https://cdn.jsdelivr.net/npm/prismjs@1.29.0/prism.min.js',
            array(),
            '1.29.0',
            true
        );
        wp_enqueue_script(
            'prism-markup',
            'https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-markup.min.js',
            array('prism-core'),
            '1.29.0',
            true
        );
        wp_enqueue_script(
            'prism-css',
            'https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-css.min.js',
            array('prism-core'),
            '1.29.0',
            true
        );
        wp_enqueue_script(
            'prism-clike',
            'https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-clike.min.js',
            array('prism-core'),
            '1.29.0',
            true
        );
        wp_enqueue_script(
            'prism-javascript',
            'https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-javascript.min.js',
            array('prism-clike'),
            '1.29.0',
            true
        );
        wp_enqueue_script(
            'prism-php',
            'https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-php.min.js',
            array('prism-markup'),
            '1.29.0',
            true
        );
        wp_add_inline_script(
            'prism-php',
            "document.addEventListener('DOMContentLoaded',function(){var c=document.querySelector('.chrysoberyl-article-content,.prose');if(window.Prism&&c){Prism.highlightAllUnder(c);}else if(window.Prism){Prism.highlightAll();}var pres=document.querySelectorAll('.chrysoberyl-article-content pre[class*=\"language-\"], .prose pre[class*=\"language-\"]');pres.forEach(function(pre){var m=pre.className.match(/language-([a-z0-9]+)/i);if(m){pre.setAttribute('data-lang',m[1].toLowerCase());}});});",
            'after'
        );
    }

    // Print styles
    $print_css_file = get_template_directory() . '/assets/css/print.css';
    if (file_exists($print_css_file)) {
        wp_enqueue_style(
            'chrysoberyl-print',
            get_template_directory_uri() . '/assets/css/print.css',
            array('chrysoberyl-style'),
            filemtime($print_css_file),
            'print'
        );
    }

    // Main JavaScript
    $main_js_file = get_template_directory() . '/assets/js/main.js';
    if (file_exists($main_js_file)) {
        wp_enqueue_script(
            'chrysoberyl-main',
            get_template_directory_uri() . '/assets/js/main.js',
            array('jquery'),
            filemtime($main_js_file),
            true
        );

        // Custom JavaScript
        $custom_js_file = get_template_directory() . '/assets/js/custom.js';
        if (file_exists($custom_js_file)) {
            wp_enqueue_script(
                'chrysoberyl-custom',
                get_template_directory_uri() . '/assets/js/custom.js',
                array('chrysoberyl-main'),
                filemtime($custom_js_file),
                true
            );
        }
    }

    // Add defer attribute for non-critical scripts
    add_filter('script_loader_tag', 'chrysoberyl_add_defer_to_scripts', 10, 2);

    // Localize script for AJAX
    $search_enabled = get_option('chrysoberyl_search_enabled', '1');
    $search_suggestions_enabled = get_option('chrysoberyl_search_suggestions_enabled', '1');
    $search_live_enabled = get_option('chrysoberyl_search_live_enabled', '1');
    $search_debounce = get_option('chrysoberyl_search_debounce', 300);
    $search_min_length = get_option('chrysoberyl_search_min_length', 2);
    $search_suggestions_style = get_option('chrysoberyl_search_suggestions_style', 'dropdown');
    $search_placeholder = get_option('chrysoberyl_search_placeholder', __('พิมพ์คำค้นหา...', 'chrysoberyl'));

    wp_localize_script('chrysoberyl-main', 'chrysoberylAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('chrysoberyl-nonce'),
        'searchUrl' => home_url('/?s='),
        'search' => array(
            'enabled' => $search_enabled === '1',
            'suggestions_enabled' => $search_suggestions_enabled === '1',
            'live_enabled' => $search_live_enabled === '1',
            'debounce' => absint($search_debounce),
            'min_length' => absint($search_min_length),
            'style' => $search_suggestions_style,
            'placeholder' => $search_placeholder,
        ),
    ));

    // Comment reply script (only on single posts with comments)
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // Conditional script loading: Only load custom.js on pages that need it
    $load_custom_js = true; // Default: load on all pages

    // Check if we're on a page that needs custom.js
    if (is_front_page() || is_home() || is_archive() || is_search() || is_single() || is_page()) {
        $load_custom_js = true;
    } else {
        // Don't load on pages that don't need it
        $load_custom_js = false;
    }

    // Store flag for later use
    if (!$load_custom_js) {
        // Remove custom.js if it was enqueued
        add_action('wp_print_scripts', function () {
            wp_dequeue_script('chrysoberyl-custom');
        }, 100);
    }

    // Lazy loading support
    wp_add_inline_script('chrysoberyl-main', '
        if ("loading" in HTMLImageElement.prototype) {
            const images = document.querySelectorAll("img[loading=\'lazy\']");
            images.forEach(img => {
                img.src = img.dataset.src || img.src;
            });
        }
    ', 'before');
}
add_action('wp_enqueue_scripts', 'chrysoberyl_enqueue_assets');

/**
 * Output early resource hints (preconnect/dns-prefetch) so the browser can connect to third-party origins before CSS/JS requests.
 * Reduces latency for Google Fonts and Font Awesome CDN – improves PageSpeed "Reduce initial server response time" and LCP.
 */
function chrysoberyl_early_resource_hints()
{
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">' . "\n";
}
add_action('wp_head', 'chrysoberyl_early_resource_hints', 0);

/**
 * Preload LCP image (hero first slide) on pages that show hero – improves LCP on mobile.
 */
function chrysoberyl_preload_lcp_image()
{
    if (!is_front_page() && !is_home() && !is_archive()) {
        return;
    }
    if (!function_exists('chrysoberyl_get_first_hero_image_url')) {
        return;
    }
    $url = chrysoberyl_get_first_hero_image_url();
    if (empty($url)) {
        return;
    }
    echo '<link rel="preload" as="image" href="' . esc_url($url) . '">' . "\n";
}
add_action('wp_head', 'chrysoberyl_preload_lcp_image', 1);

/**
 * Add defer attribute to non-critical scripts
 *
 * @param string $tag Script tag.
 * @param string $handle Script handle.
 * @return string Modified script tag.
 */
function chrysoberyl_add_defer_to_scripts($tag, $handle)
{
    $defer_scripts = array('chrysoberyl-main', 'chrysoberyl-custom');

    if (in_array($handle, $defer_scripts, true)) {
        // Add defer if not already present
        if (strpos($tag, ' defer') === false && strpos($tag, 'defer') === false) {
            $tag = str_replace(' src', ' defer src', $tag);
        }
    }

    return $tag;
}

/**
 * Add preconnect for Google Fonts (when not loading async).
 *
 * @param string $tag Link tag.
 * @param string $handle Style handle.
 * @return string Modified link tag.
 */
function chrysoberyl_add_preconnect_for_fonts($tag, $handle)
{
    // Google Fonts: preconnect is added in chrysoberyl_google_fonts_async (priority 5).
    if ('chrysoberyl-google-fonts' === $handle) {
        return $tag;
    }
    return $tag;
}

/**
 * Load Google Fonts CSS asynchronously to reduce render-blocking (~1,200 ms).
 * Uses font-display: swap (in URL) so text shows immediately with fallback font.
 *
 * @param string $tag   Link tag.
 * @param string $handle Style handle.
 * @param string $href  Style URL.
 * @param string $media Media attribute.
 * @return string Modified link tag.
 */
function chrysoberyl_google_fonts_async($tag, $handle, $href, $media)
{
    if ('chrysoberyl-google-fonts' !== $handle) {
        return $tag;
    }
    $preconnect = '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    $preconnect .= '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    $async_tag = '<link rel="preload" as="style" href="' . esc_url($href) . '" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
    $async_tag .= '<noscript><link rel="stylesheet" href="' . esc_url($href) . '" media="' . esc_attr($media ?: 'all') . '"></noscript>';
    return $preconnect . $async_tag;
}
add_filter('style_loader_tag', 'chrysoberyl_google_fonts_async', 5, 4);

/**
 * Load Font Awesome asynchronously so it doesn't block FCP (improves mobile PageSpeed).
 *
 * @param string $tag   Link tag.
 * @param string $handle Style handle.
 * @param string $href  Style URL.
 * @param string $media Media attribute.
 * @return string Modified link tag.
 */
function chrysoberyl_font_awesome_async($tag, $handle, $href, $media)
{
    if ('font-awesome' !== $handle) {
        return $tag;
    }
    $tag = '<link rel="preload" as="style" href="' . esc_url($href) . '" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
    $tag .= '<noscript><link rel="stylesheet" href="' . esc_url($href) . '" media="' . esc_attr($media ?: 'all') . '"></noscript>';
    return $tag;
}
add_filter('style_loader_tag', 'chrysoberyl_font_awesome_async', 10, 4);

/**
 * Enqueue admin styles and scripts
 */
function chrysoberyl_enqueue_admin_styles($hook)
{
    // Load on post list and edit screens
    if (in_array($hook, array('post.php', 'post-new.php', 'edit.php'))) {
        wp_enqueue_style(
            'chrysoberyl-admin',
            get_template_directory_uri() . '/assets/css/admin.css',
            array(),
            chrysoberyl_get_theme_version()
        );

        // Add inline CSS for post list table column widths
        if ('edit.php' === $hook) {
            $custom_css = '
            #posts-filter .wp-list-table,
            #posts-filter .widefat {
                table-layout: auto !important;
            }
            #posts-filter .wp-list-table th.column-title,
            #posts-filter .wp-list-table td.column-title {
                width: 50% !important;
                min-width: 400px !important;
                max-width: none !important;
            }
            .wp-list-table th.column-title,
            .wp-list-table td.column-title {
                width: 50% !important;
                min-width: 400px !important;
                max-width: none !important;
            }
            ';
            wp_add_inline_style('chrysoberyl-admin', $custom_css);
        }
    }

    // Load on theme settings page
    if (strpos($hook, 'chrysoberyl-settings') !== false) {
        wp_enqueue_style(
            'chrysoberyl-admin',
            get_template_directory_uri() . '/assets/css/admin.css',
            array(),
            chrysoberyl_get_theme_version()
        );

        // Enqueue WordPress media uploader with proper dependencies
        wp_enqueue_media();

        // Enqueue jQuery and jQuery UI Sortable for widget order
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-sortable');

        // Localize script for AJAX (must be array)
        wp_localize_script('jquery', 'chrysoberylAdmin', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('chrysoberyl_settings_nonce'),
        ));
    }
}
add_action('admin_enqueue_scripts', 'chrysoberyl_enqueue_admin_styles');

