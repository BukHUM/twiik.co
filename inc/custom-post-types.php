<?php
/**
 * Custom Post Types
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create main admin menu for Chrysoberyl - Theme theme
 */
function chrysoberyl_add_admin_menu()
{
    // Main menu page
    add_menu_page(
        __('Chrysoberyl - Theme', 'chrysoberyl'),
        __('Chrysoberyl - Theme', 'chrysoberyl'),
        'edit_posts',
        'chrysoberyl',
        'chrysoberyl_admin_page',
        'dashicons-admin-site-alt3',
        5
    );

    // Dashboard submenu (default page)
    add_submenu_page(
        'chrysoberyl',
        __('Dashboard', 'chrysoberyl'),
        __('Dashboard', 'chrysoberyl'),
        'edit_posts',
        'chrysoberyl',
        'chrysoberyl_admin_page'
    );

    // Theme Settings submenu
    add_submenu_page(
        'chrysoberyl',
        __('Theme Settings', 'chrysoberyl'),
        __('Theme Settings', 'chrysoberyl'),
        'manage_options',
        'chrysoberyl-settings',
        'chrysoberyl_settings_page'
    );

    // Import Demo Data submenu
    add_submenu_page(
        'chrysoberyl',
        __('Import Demo Data', 'chrysoberyl'),
        __('Import Demo Data', 'chrysoberyl'),
        'manage_options',
        'chrysoberyl-import-demo',
        'chrysoberyl_import_demo_page'
    );

    // Gemini API submenu
    add_submenu_page(
        'chrysoberyl',
        __('Gemini API', 'chrysoberyl'),
        __('Gemini API', 'chrysoberyl'),
        'manage_options',
        'chrysoberyl-gemini-api',
        'chrysoberyl_gemini_api_page'
    );
}
add_action('admin_menu', 'chrysoberyl_add_admin_menu', 9);

/**
 * Admin page callback for Chrysoberyl - Theme menu
 */
function chrysoberyl_admin_page()
{
    // Get statistics
    $post_counts = wp_count_posts('post');
    $page_counts = wp_count_posts('page');
    $comment_count = wp_count_comments();
    $user_count = count_users();

    // Get recent posts
    $recent_posts = get_posts(array(
        'post_type' => 'post',
        'posts_per_page' => 5,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
    ));

    // Get theme version
    $theme = wp_get_theme();
    $theme_version = $theme->get('Version');

    // Get WordPress version
    global $wp_version;

    // Get PHP version
    $php_version = PHP_VERSION;

    // Get active plugins count
    $active_plugins = get_option('active_plugins', array());
    $active_plugins_count = count($active_plugins);

    // Get memory limit
    $memory_limit = ini_get('memory_limit');

    // Get upload directory info
    $upload_dir = wp_upload_dir();
    $upload_size = 0;
    if (isset($upload_dir['basedir']) && is_dir($upload_dir['basedir'])) {
        $upload_size = chrysoberyl_get_directory_size($upload_dir['basedir']);
    }
    ?>
    <div class="wrap chrysoberyl-dashboard-wrap">
        <h1 class="chrysoberyl-dashboard-title">
            <span class="dashicons dashicons-admin-site-alt3"></span>
            <?php echo esc_html(get_admin_page_title()); ?>
        </h1>

        <div class="chrysoberyl-dashboard">
            <!-- About Theme (top) -->
            <div class="chrysoberyl-dashboard-widgets chrysoberyl-developer-credit">
                <div class="chrysoberyl-widget chrysoberyl-widget-developer">
                    <div class="chrysoberyl-widget-header">
                        <h3>
                            <span class="dashicons dashicons-groups"></span>
                            <?php _e('เกี่ยวกับธีม', 'chrysoberyl'); ?>
                        </h3>
                    </div>
                    <div class="chrysoberyl-widget-content">
                        <p class="chrysoberyl-credit-intro">
                            <?php _e('ธีม Chrysoberyl - Theme พัฒนาและดูแลโดย', 'chrysoberyl'); ?>
                        </p>
                        <ul class="chrysoberyl-credit-list">
                            <li>
                                <span class="chrysoberyl-credit-label"><?php _e('ฟอร์กมาจาก:', 'chrysoberyl'); ?></span>
                                <a href="https://gawao.com" target="_blank" rel="noopener noreferrer"><?php _e('ธีม Trend Today', 'chrysoberyl'); ?></a>
                            </li>
                            <li>
                                <span class="chrysoberyl-credit-label"><?php _e('ทีมผู้พัฒนา:', 'chrysoberyl'); ?></span>
                                <a href="https://tonkla.co" target="_blank"
                                    rel="noopener noreferrer"><?php echo esc_html(__('ต้นกล้าไอที', 'chrysoberyl')); ?></a>
                            </li>
                            <li>
                                <span
                                    class="chrysoberyl-credit-label"><?php _e('เว็บที่ใช้งานจริง:', 'chrysoberyl'); ?></span>
                                <a href="https://chrysoberyl.me" target="_blank" rel="noopener noreferrer">chrysoberyl.me</a>,
                                <a href="https://twiik.co" target="_blank" rel="noopener noreferrer">twiik.co</a>
                            </li>
                        </ul>
                        <p class="chrysoberyl-credit-license">
                            <span class="dashicons dashicons-yes-alt"></span>
                            <?php _e('ธีมนี้ใช้งานได้ฟรี ตามเงื่อนไขสัญญาอนุญาตของ WordPress (GPL)', 'chrysoberyl'); ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Statistics Grid -->
            <div class="chrysoberyl-dashboard-widgets">
                <!-- Content Statistics -->
                <div class="chrysoberyl-widget">
                    <div class="chrysoberyl-widget-header">
                        <h3>
                            <span class="dashicons dashicons-chart-bar"></span>
                            <?php _e('Content Statistics', 'chrysoberyl'); ?>
                        </h3>
                    </div>
                    <div class="chrysoberyl-widget-content">
                        <div class="chrysoberyl-stats-grid">
                            <div class="chrysoberyl-stat-item">
                                <div class="stat-icon" style="background: #2271b1;">
                                    <span class="dashicons dashicons-admin-post"></span>
                                </div>
                                <div class="stat-info">
                                    <div class="stat-number"><?php echo number_format_i18n($post_counts->publish); ?>
                                    </div>
                                    <div class="stat-label"><?php _e('บทความ', 'chrysoberyl'); ?></div>
                                </div>
                            </div>
                            <div class="chrysoberyl-stat-item">
                                <div class="stat-icon" style="background: #00a32a;">
                                    <span class="dashicons dashicons-admin-page"></span>
                                </div>
                                <div class="stat-info">
                                    <div class="stat-number"><?php echo number_format_i18n($page_counts->publish); ?>
                                    </div>
                                    <div class="stat-label"><?php _e('หน้า', 'chrysoberyl'); ?></div>
                                </div>
                            </div>
                            <div class="chrysoberyl-stat-item">
                                <div class="stat-icon" style="background: #d63638;">
                                    <span class="dashicons dashicons-admin-comments"></span>
                                </div>
                                <div class="stat-info">
                                    <div class="stat-number"><?php echo number_format_i18n($comment_count->approved); ?>
                                    </div>
                                    <div class="stat-label"><?php _e('ความคิดเห็น', 'chrysoberyl'); ?></div>
                                </div>
                            </div>
                            <div class="chrysoberyl-stat-item">
                                <div class="stat-icon" style="background: #f0b849;">
                                    <span class="dashicons dashicons-admin-users"></span>
                                </div>
                                <div class="stat-info">
                                    <div class="stat-number"><?php echo number_format_i18n($user_count['total_users']); ?>
                                    </div>
                                    <div class="stat-label"><?php _e('ผู้ใช้', 'chrysoberyl'); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Recent Posts & System Info -->
            <div class="chrysoberyl-dashboard-widgets">
                <!-- Recent Posts -->
                <div class="chrysoberyl-widget">
                    <div class="chrysoberyl-widget-header">
                        <h3>
                            <span class="dashicons dashicons-clock"></span>
                            <?php _e('Recent Posts', 'chrysoberyl'); ?>
                        </h3>
                    </div>
                    <div class="chrysoberyl-widget-content">
                        <?php if (!empty($recent_posts)): ?>
                            <ul class="chrysoberyl-recent-posts">
                                <?php foreach ($recent_posts as $post):
                                    setup_postdata($post); ?>
                                    <li>
                                        <a href="<?php echo get_edit_post_link($post->ID); ?>">
                                            <strong><?php echo esc_html(get_the_title($post->ID)); ?></strong>
                                            <span class="post-date">
                                                <?php echo human_time_diff(get_the_time('U', $post->ID), current_time('timestamp')) . ' ' . __('ago', 'chrysoberyl'); ?>
                                            </span>
                                        </a>
                                    </li>
                                <?php endforeach;
                                wp_reset_postdata(); ?>
                            </ul>
                        <?php else: ?>
                            <p><?php _e('ยังไม่มีบทความ', 'chrysoberyl'); ?></p>
                        <?php endif; ?>
                        <p style="margin-top: 15px;">
                            <a href="<?php echo admin_url('edit.php'); ?>" class="button button-small">
                                <?php _e('ดูบทความทั้งหมด', 'chrysoberyl'); ?>
                            </a>
                            <a href="<?php echo admin_url('post-new.php'); ?>" class="button button-small">
                                <?php _e('เขียนบทความใหม่', 'chrysoberyl'); ?>
                            </a>
                        </p>
                    </div>
                </div>

                <!-- System Information -->
                <div class="chrysoberyl-widget">
                    <div class="chrysoberyl-widget-header">
                        <h3>
                            <span class="dashicons dashicons-info"></span>
                            <?php _e('System Information', 'chrysoberyl'); ?>
                        </h3>
                    </div>
                    <div class="chrysoberyl-widget-content">
                        <ul class="chrysoberyl-system-info">
                            <li>
                                <strong><?php _e('Theme Version:', 'chrysoberyl'); ?></strong>
                                <span><?php echo esc_html($theme_version); ?></span>
                            </li>
                            <li>
                                <strong><?php _e('WordPress Version:', 'chrysoberyl'); ?></strong>
                                <span><?php echo esc_html($wp_version); ?></span>
                            </li>
                            <li>
                                <strong><?php _e('PHP Version:', 'chrysoberyl'); ?></strong>
                                <span><?php echo esc_html($php_version); ?></span>
                            </li>
                            <li>
                                <strong><?php _e('Memory Limit:', 'chrysoberyl'); ?></strong>
                                <span><?php echo esc_html($memory_limit); ?></span>
                            </li>
                            <li>
                                <strong><?php _e('Active Plugins:', 'chrysoberyl'); ?></strong>
                                <span><?php echo number_format_i18n($active_plugins_count); ?></span>
                            </li>
                            <li>
                                <strong><?php _e('Upload Directory Size:', 'chrysoberyl'); ?></strong>
                                <span><?php echo size_format($upload_size, 2); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .chrysoberyl-dashboard-wrap {
            max-width: 1400px;
        }

        .chrysoberyl-dashboard-title {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chrysoberyl-dashboard-title .dashicons {
            font-size: 32px;
            width: 32px;
            height: 32px;
        }

        .chrysoberyl-dashboard-widgets {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .chrysoberyl-widget {
            background: #fff;
            border: 1px solid #ccd0d4;
            box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
            border-radius: 4px;
            overflow: hidden;
        }

        .chrysoberyl-widget-header {
            background: #f6f7f7;
            padding: 15px 20px;
            border-bottom: 1px solid #ccd0d4;
        }

        .chrysoberyl-widget-header h2,
        .chrysoberyl-widget-header h3 {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
        }

        .chrysoberyl-widget-header .dashicons {
            font-size: 20px;
            width: 20px;
            height: 20px;
        }

        .chrysoberyl-widget-content {
            padding: 20px;
        }

        .chrysoberyl-stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .chrysoberyl-stat-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 6px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            flex-shrink: 0;
        }

        .stat-icon .dashicons {
            font-size: 24px;
            width: 24px;
            height: 24px;
        }

        .stat-info {
            flex: 1;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #1d2327;
            line-height: 1.2;
        }

        .stat-label {
            font-size: 13px;
            color: #646970;
            margin-top: 2px;
        }

        .chrysoberyl-stats-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .chrysoberyl-stats-list li {
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f1;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chrysoberyl-stats-list li:last-child {
            border-bottom: none;
        }

        .chrysoberyl-stats-list li a {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            text-decoration: none;
            color: inherit;
        }

        .chrysoberyl-stats-list li a:hover {
            color: #2271b1;
        }

        .chrysoberyl-stats-list .dashicons {
            color: #646970;
            font-size: 18px;
            width: 18px;
            height: 18px;
        }

        .chrysoberyl-stats-list .stat-value {
            margin-left: auto;
            font-weight: bold;
            color: #2271b1;
        }

        .chrysoberyl-recent-posts {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .chrysoberyl-recent-posts li {
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f1;
        }

        .chrysoberyl-recent-posts li:last-child {
            border-bottom: none;
        }

        .chrysoberyl-recent-posts li a {
            display: block;
            text-decoration: none;
            color: inherit;
        }

        .chrysoberyl-recent-posts li a:hover {
            color: #2271b1;
        }

        .chrysoberyl-recent-posts .post-date {
            display: block;
            font-size: 12px;
            color: #646970;
            margin-top: 4px;
        }

        .chrysoberyl-system-info {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .chrysoberyl-system-info li {
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chrysoberyl-system-info li:last-child {
            border-bottom: none;
        }

        .chrysoberyl-system-info strong {
            color: #1d2327;
        }

        .chrysoberyl-system-info span {
            color: #646970;
        }

        .chrysoberyl-developer-credit {
            margin-top: 20px;
        }

        .chrysoberyl-widget-developer .chrysoberyl-credit-intro {
            margin: 0 0 12px 0;
            color: #646970;
            font-size: 14px;
        }

        .chrysoberyl-credit-list {
            list-style: none;
            margin: 0 0 15px 0;
            padding: 0;
        }

        .chrysoberyl-credit-list li {
            padding: 6px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .chrysoberyl-credit-label {
            color: #1d2327;
            font-weight: 600;
            min-width: 100px;
        }

        .chrysoberyl-credit-list a {
            color: #2271b1;
            text-decoration: none;
        }

        .chrysoberyl-credit-list a:hover {
            text-decoration: underline;
        }

        .chrysoberyl-credit-license {
            margin: 0;
            padding: 12px 15px;
            background: #f0f6fc;
            border-left: 4px solid #2271b1;
            color: #1d2327;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .chrysoberyl-credit-license .dashicons {
            font-size: 18px;
            width: 18px;
            height: 18px;
            color: #00a32a;
        }
    </style>
    <?php
}

/**
 * Get directory size recursively
 *
 * @param string $directory Directory path.
 * @return int Size in bytes.
 */
function chrysoberyl_get_directory_size($directory)
{
    $size = 0;
    if (is_dir($directory)) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
        foreach ($files as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }
    }
    return $size;
}

/**
 * Import Demo Data page callback
 * (Moved to inc/demo-data-import.php)
 */

/**
 * Gemini API page callback (placeholder)
 */
function chrysoberyl_gemini_api_page()
{
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(__('Gemini API', 'chrysoberyl')); ?></h1>
        <p><?php esc_html_e('Content coming soon.', 'chrysoberyl'); ?></p>
    </div>
    <?php
}

/**
 * Theme Settings page callback
 */
function chrysoberyl_settings_page()
{
    // Save settings
    if (isset($_POST['chrysoberyl_save_settings']) && check_admin_referer('chrysoberyl_settings_nonce')) {
        if (current_user_can('manage_options')) {
            // Save logo
            if (isset($_POST['chrysoberyl_logo'])) {
                update_option('chrysoberyl_logo', sanitize_text_field($_POST['chrysoberyl_logo']));
            }
            // Save site name style when no logo (gray vs colorful)
            if (isset($_POST['chrysoberyl_site_name_style'])) {
                $style = sanitize_text_field($_POST['chrysoberyl_site_name_style']);
                if (in_array($style, array('gray', 'google_colors'), true)) {
                    update_option('chrysoberyl_site_name_style', $style);
                }
            }
            // Save login page style (theme style vs default WordPress)
            $login_use_theme_style = (isset($_POST['chrysoberyl_login_use_theme_style']) && $_POST['chrysoberyl_login_use_theme_style'] === '1') ? '1' : '0';
            update_option('chrysoberyl_login_use_theme_style', $login_use_theme_style);

            // Save pagination type
            if (isset($_POST['chrysoberyl_pagination_type'])) {
                $pagination_type = sanitize_text_field($_POST['chrysoberyl_pagination_type']);
                if (in_array($pagination_type, array('pagination', 'load_more'), true)) {
                    update_option('chrysoberyl_pagination_type', $pagination_type);
                }
            }
            // Save homepage news grid columns (1–4)
            if (isset($_POST['chrysoberyl_home_news_columns'])) {
                $cols = absint($_POST['chrysoberyl_home_news_columns']);
                if ($cols >= 1 && $cols <= 4) {
                    update_option('chrysoberyl_home_news_columns', $cols);
                }
            }

            // Save social sharing settings
            // Enable/Disable
            $social_sharing_enabled = isset($_POST['chrysoberyl_social_sharing_enabled']) ? '1' : '0';
            update_option('chrysoberyl_social_sharing_enabled', $social_sharing_enabled);

            // Show share on Post / Page
            $social_show_on_post = isset($_POST['chrysoberyl_social_show_on_post']) ? '1' : '0';
            update_option('chrysoberyl_social_show_on_post', $social_show_on_post);
            $social_show_on_page = isset($_POST['chrysoberyl_social_show_on_page']) ? '1' : '0';
            update_option('chrysoberyl_social_show_on_page', $social_show_on_page);

            // Selected platforms
            $available_platforms = array('facebook', 'twitter', 'line', 'linkedin', 'whatsapp', 'telegram', 'copy_link');
            $selected_platforms = isset($_POST['chrysoberyl_social_platforms']) && is_array($_POST['chrysoberyl_social_platforms'])
                ? array_intersect($_POST['chrysoberyl_social_platforms'], $available_platforms)
                : array();
            update_option('chrysoberyl_social_platforms', $selected_platforms);

            // Display positions
            $display_positions = array();
            if (isset($_POST['chrysoberyl_social_display_single_top'])) {
                $display_positions[] = 'single_top';
            }
            if (isset($_POST['chrysoberyl_social_display_single_bottom'])) {
                $display_positions[] = 'single_bottom';
            }
            if (isset($_POST['chrysoberyl_social_display_floating'])) {
                $display_positions[] = 'floating';
            }
            update_option('chrysoberyl_social_display_positions', $display_positions);

            // Button style
            if (isset($_POST['chrysoberyl_social_button_style'])) {
                $button_style = sanitize_text_field($_POST['chrysoberyl_social_button_style']);
                if (in_array($button_style, array('icon_only', 'icon_text', 'button'), true)) {
                    update_option('chrysoberyl_social_button_style', $button_style);
                }
            }

            // Button size
            if (isset($_POST['chrysoberyl_social_button_size'])) {
                $button_size = sanitize_text_field($_POST['chrysoberyl_social_button_size']);
                if (in_array($button_size, array('small', 'medium', 'large'), true)) {
                    update_option('chrysoberyl_social_button_size', $button_size);
                }
            }

            // Icon style (branded vs mockup: เทา วงกลม hover)
            if (isset($_POST['chrysoberyl_social_icon_style'])) {
                $icon_style = sanitize_text_field($_POST['chrysoberyl_social_icon_style']);
                if (in_array($icon_style, array('branded', 'mockup'), true)) {
                    update_option('chrysoberyl_social_icon_style', $icon_style);
                }
            }

            // Save search settings
            // Enable/Disable
            $search_enabled = isset($_POST['chrysoberyl_search_enabled']) ? '1' : '0';
            update_option('chrysoberyl_search_enabled', $search_enabled);

            $search_suggestions_enabled = isset($_POST['chrysoberyl_search_suggestions_enabled']) ? '1' : '0';
            update_option('chrysoberyl_search_suggestions_enabled', $search_suggestions_enabled);

            $search_live_enabled = isset($_POST['chrysoberyl_search_live_enabled']) ? '1' : '0';
            update_option('chrysoberyl_search_live_enabled', $search_live_enabled);

            // Search behavior
            if (isset($_POST['chrysoberyl_search_suggestions_count'])) {
                $suggestions_count = absint($_POST['chrysoberyl_search_suggestions_count']);
                if ($suggestions_count > 0 && $suggestions_count <= 20) {
                    update_option('chrysoberyl_search_suggestions_count', $suggestions_count);
                }
            }

            if (isset($_POST['chrysoberyl_search_debounce'])) {
                $debounce = absint($_POST['chrysoberyl_search_debounce']);
                if ($debounce >= 0 && $debounce <= 2000) {
                    update_option('chrysoberyl_search_debounce', $debounce);
                }
            }

            if (isset($_POST['chrysoberyl_search_min_length'])) {
                $min_length = absint($_POST['chrysoberyl_search_min_length']);
                if ($min_length >= 1 && $min_length <= 10) {
                    update_option('chrysoberyl_search_min_length', $min_length);
                }
            }

            // Post types to search (post and page only; CPT removed)
            $available_post_types = array('post', 'page');
            $search_post_types = isset($_POST['chrysoberyl_search_post_types']) && is_array($_POST['chrysoberyl_search_post_types'])
                ? array_intersect($_POST['chrysoberyl_search_post_types'], $available_post_types)
                : array('post');
            update_option('chrysoberyl_search_post_types', $search_post_types);

            // Search fields
            $search_fields = array();
            if (isset($_POST['chrysoberyl_search_field_title'])) {
                $search_fields[] = 'title';
            }
            if (isset($_POST['chrysoberyl_search_field_content'])) {
                $search_fields[] = 'content';
            }
            if (isset($_POST['chrysoberyl_search_field_excerpt'])) {
                $search_fields[] = 'excerpt';
            }
            if (isset($_POST['chrysoberyl_search_field_categories'])) {
                $search_fields[] = 'categories';
            }
            if (isset($_POST['chrysoberyl_search_field_tags'])) {
                $search_fields[] = 'tags';
            }
            if (empty($search_fields)) {
                $search_fields = array('title', 'content'); // Default
            }
            update_option('chrysoberyl_search_fields', $search_fields);

            // Search display
            if (isset($_POST['chrysoberyl_search_suggestions_style'])) {
                $suggestions_style = sanitize_text_field($_POST['chrysoberyl_search_suggestions_style']);
                if (in_array($suggestions_style, array('dropdown', 'modal', 'fullpage'), true)) {
                    update_option('chrysoberyl_search_suggestions_style', $suggestions_style);
                }
            }

            $suggestions_display = array();
            if (isset($_POST['chrysoberyl_search_show_image'])) {
                $suggestions_display[] = 'image';
            }
            if (isset($_POST['chrysoberyl_search_show_excerpt'])) {
                $suggestions_display[] = 'excerpt';
            }

            // Save widget visibility settings
            $available_widgets = array(
                'popular_posts',
                'recent_posts',
                'trending_tags',
                'related_posts',
                'categories',
                'search',
                'social_follow',
                'most_commented',
                'archive',
                'custom_html',
            );
            if (isset($_POST['chrysoberyl_enabled_widgets']) && is_array($_POST['chrysoberyl_enabled_widgets'])) {
                $enabled_widgets = array_intersect($_POST['chrysoberyl_enabled_widgets'], $available_widgets);
            } else {
                // If no checkboxes are selected, save empty array
                $enabled_widgets = array();
            }
            update_option('chrysoberyl_enabled_widgets', $enabled_widgets);
            // Widget display order (comma-separated from hidden input)
            if (isset($_POST['chrysoberyl_widgets_order']) && is_string($_POST['chrysoberyl_widgets_order'])) {
                $order_raw = sanitize_text_field($_POST['chrysoberyl_widgets_order']);
                $order_keys = array_filter(array_map('trim', explode(',', $order_raw)));
                $allowed = array('popular_posts', 'recent_posts', 'trending_tags', 'related_posts', 'categories', 'search', 'social_follow', 'most_commented', 'archive', 'custom_html');
                $order_keys = array_values(array_intersect($order_keys, $allowed));
                if (!empty($order_keys)) {
                    update_option('chrysoberyl_widgets_order', $order_keys);
                }
            }

            // Sidebar on single post
            $sidebar_single_post_enabled = isset($_POST['chrysoberyl_sidebar_single_post_enabled']) ? '1' : '0';
            update_option('chrysoberyl_sidebar_single_post_enabled', $sidebar_single_post_enabled);
            // Author box on single post (below content, above related posts)
            $author_box_single_enabled = isset($_POST['chrysoberyl_author_box_single_enabled']) ? '1' : '0';
            update_option('chrysoberyl_author_box_single_enabled', $author_box_single_enabled);
            // Sidebar on page (static pages)
            $sidebar_single_page_enabled = isset($_POST['chrysoberyl_sidebar_single_page_enabled']) ? '1' : '0';
            update_option('chrysoberyl_sidebar_single_page_enabled', $sidebar_single_page_enabled);
            // Sidebar on homepage (hidden=0 when unchecked; when checked both sent, last wins or use array)
            $sidebar_home_raw = isset($_POST['chrysoberyl_sidebar_home_enabled']) ? $_POST['chrysoberyl_sidebar_home_enabled'] : '0';
            $is_home_enabled = ($sidebar_home_raw === '1') || (is_array($sidebar_home_raw) && in_array('1', $sidebar_home_raw, true));
            update_option('chrysoberyl_sidebar_home_enabled', $is_home_enabled ? '1' : '0');
            // Sidebar on archive (category, tag, author, date archives)
            $sidebar_archive_raw = isset($_POST['chrysoberyl_sidebar_archive_enabled']) ? $_POST['chrysoberyl_sidebar_archive_enabled'] : '0';
            $is_archive_enabled = ($sidebar_archive_raw === '1') || (is_array($sidebar_archive_raw) && in_array('1', $sidebar_archive_raw, true));
            update_option('chrysoberyl_sidebar_archive_enabled', $is_archive_enabled ? '1' : '0');

            // Floating Left Ad (skyscraper 120x600 or 160x600)
            $floating_left_ad_enabled = isset($_POST['chrysoberyl_floating_left_ad_enabled']) ? '1' : '0';
            update_option('chrysoberyl_floating_left_ad_enabled', $floating_left_ad_enabled);
            if (isset($_POST['chrysoberyl_floating_left_ad_size'])) {
                $size = sanitize_text_field($_POST['chrysoberyl_floating_left_ad_size']);
                if (in_array($size, array('120x600', '160x600'), true)) {
                    update_option('chrysoberyl_floating_left_ad_size', $size);
                }
            }
            if (isset($_POST['chrysoberyl_floating_left_ad_content'])) {
                update_option('chrysoberyl_floating_left_ad_content', wp_kses_post($_POST['chrysoberyl_floating_left_ad_content']));
            }

            // Save Breadcrumb settings
            $breadcrumb_enabled = isset($_POST['chrysoberyl_breadcrumb_enabled']) ? '1' : '0';
            update_option('chrysoberyl_breadcrumb_enabled', $breadcrumb_enabled);

            $breadcrumb_show_on_home = isset($_POST['chrysoberyl_breadcrumb_show_on_home']) ? '1' : '0';
            update_option('chrysoberyl_breadcrumb_show_on_home', $breadcrumb_show_on_home);

            $breadcrumb_show_on_single = isset($_POST['chrysoberyl_breadcrumb_show_on_single']) ? '1' : '0';
            update_option('chrysoberyl_breadcrumb_show_on_single', $breadcrumb_show_on_single);

            $breadcrumb_show_on_page = isset($_POST['chrysoberyl_breadcrumb_show_on_page']) ? '1' : '0';
            update_option('chrysoberyl_breadcrumb_show_on_page', $breadcrumb_show_on_page);

            $breadcrumb_show_on_category = isset($_POST['chrysoberyl_breadcrumb_show_on_category']) ? '1' : '0';
            update_option('chrysoberyl_breadcrumb_show_on_category', $breadcrumb_show_on_category);

            $breadcrumb_show_on_tag = isset($_POST['chrysoberyl_breadcrumb_show_on_tag']) ? '1' : '0';
            update_option('chrysoberyl_breadcrumb_show_on_tag', $breadcrumb_show_on_tag);

            $breadcrumb_show_on_author = isset($_POST['chrysoberyl_breadcrumb_show_on_author']) ? '1' : '0';
            update_option('chrysoberyl_breadcrumb_show_on_author', $breadcrumb_show_on_author);

            $breadcrumb_show_on_date = isset($_POST['chrysoberyl_breadcrumb_show_on_date']) ? '1' : '0';
            update_option('chrysoberyl_breadcrumb_show_on_date', $breadcrumb_show_on_date);

            $breadcrumb_show_on_search = isset($_POST['chrysoberyl_breadcrumb_show_on_search']) ? '1' : '0';
            update_option('chrysoberyl_breadcrumb_show_on_search', $breadcrumb_show_on_search);

            $breadcrumb_show_on_404 = isset($_POST['chrysoberyl_breadcrumb_show_on_404']) ? '1' : '0';
            update_option('chrysoberyl_breadcrumb_show_on_404', $breadcrumb_show_on_404);

            if (isset($_POST['chrysoberyl_search_show_date'])) {
                $suggestions_display[] = 'date';
            }
            if (isset($_POST['chrysoberyl_search_show_category'])) {
                $suggestions_display[] = 'category';
            }
            update_option('chrysoberyl_search_suggestions_display', $suggestions_display);

            // Search results page
            if (isset($_POST['chrysoberyl_search_results_layout'])) {
                $results_layout = sanitize_text_field($_POST['chrysoberyl_search_results_layout']);
                if (in_array($results_layout, array('list', 'grid', 'mixed'), true)) {
                    update_option('chrysoberyl_search_results_layout', $results_layout);
                }
            }

            if (isset($_POST['chrysoberyl_search_results_sort'])) {
                $results_sort = sanitize_text_field($_POST['chrysoberyl_search_results_sort']);
                if (in_array($results_sort, array('relevance', 'date_desc', 'date_asc', 'title_asc', 'title_desc'), true)) {
                    update_option('chrysoberyl_search_results_sort', $results_sort);
                }
            }

            // Search placeholder
            if (isset($_POST['chrysoberyl_search_placeholder'])) {
                update_option('chrysoberyl_search_placeholder', sanitize_text_field($_POST['chrysoberyl_search_placeholder']));
            }

            // Exclude categories
            $exclude_categories = isset($_POST['chrysoberyl_search_exclude_categories']) && is_array($_POST['chrysoberyl_search_exclude_categories'])
                ? array_map('absint', $_POST['chrysoberyl_search_exclude_categories'])
                : array();
            update_option('chrysoberyl_search_exclude_categories', $exclude_categories);

            // Save Footer settings
            $footer_menu_section = (isset($_POST['chrysoberyl_footer_menu_section_enabled']) && $_POST['chrysoberyl_footer_menu_section_enabled'] === '1') ? '1' : '0';
            update_option('chrysoberyl_footer_menu_section_enabled', $footer_menu_section);
            $footer_legal_section = (isset($_POST['chrysoberyl_footer_legal_section_enabled']) && $_POST['chrysoberyl_footer_legal_section_enabled'] === '1') ? '1' : '0';
            update_option('chrysoberyl_footer_legal_section_enabled', $footer_legal_section);
            if (isset($_POST['chrysoberyl_facebook_url'])) {
                update_option('chrysoberyl_facebook_url', esc_url_raw($_POST['chrysoberyl_facebook_url']));
            }
            if (isset($_POST['chrysoberyl_twitter_url'])) {
                update_option('chrysoberyl_twitter_url', esc_url_raw($_POST['chrysoberyl_twitter_url']));
            }
            if (isset($_POST['chrysoberyl_instagram_url'])) {
                update_option('chrysoberyl_instagram_url', esc_url_raw($_POST['chrysoberyl_instagram_url']));
            }
            $footer_newsletter = (isset($_POST['chrysoberyl_footer_newsletter_enabled']) && $_POST['chrysoberyl_footer_newsletter_enabled'] === '1') ? '1' : '0';
            update_option('chrysoberyl_footer_newsletter_enabled', $footer_newsletter);
            $footer_tags = (isset($_POST['chrysoberyl_footer_tags_enabled']) && $_POST['chrysoberyl_footer_tags_enabled'] === '1') ? '1' : '0';
            update_option('chrysoberyl_footer_tags_enabled', $footer_tags);
            if (isset($_POST['chrysoberyl_footer_copyright_text'])) {
                update_option('chrysoberyl_footer_copyright_text', sanitize_textarea_field($_POST['chrysoberyl_footer_copyright_text']));
            }
            $footer_column_types = array('sidebar', 'menu', 'social');
            for ($i = 1; $i <= 4; $i++) {
                $key = 'chrysoberyl_footer' . $i . '_type';
                if (isset($_POST[$key]) && in_array($_POST[$key], $footer_column_types, true)) {
                    update_option($key, sanitize_text_field($_POST[$key]));
                }
                $menu_key = 'chrysoberyl_footer' . $i . '_menu';
                if (isset($_POST[$menu_key])) {
                    update_option($menu_key, absint($_POST[$menu_key]));
                }
                $show_heading_key = 'chrysoberyl_footer' . $i . '_show_heading';
                $show_heading = (isset($_POST[$show_heading_key]) && $_POST[$show_heading_key] === '1') ? '1' : '0';
                update_option($show_heading_key, $show_heading);
                $heading_text_key = 'chrysoberyl_footer' . $i . '_heading_text';
                if (isset($_POST[$heading_text_key])) {
                    update_option($heading_text_key, sanitize_text_field($_POST[$heading_text_key]));
                }
                $heading_icon_key = 'chrysoberyl_footer' . $i . '_heading_icon';
                $allowed_icons = array('', 'folder-open', 'info-circle', 'share-alt', 'link', 'list', 'sitemap', 'address-book', 'envelope', 'phone', 'map-marker-alt', 'th-large', 'bars', 'chevron-right', 'star', 'heart', 'bookmark');
                if (isset($_POST[$heading_icon_key]) && in_array($_POST[$heading_icon_key], $allowed_icons, true)) {
                    update_option($heading_icon_key, $_POST[$heading_icon_key]);
                }
            }

            // Save TOC settings
            // Enable/Disable
            $toc_enabled = isset($_POST['chrysoberyl_toc_enabled']) ? '1' : '0';
            update_option('chrysoberyl_toc_enabled', $toc_enabled);

            // Display on (Post single, Page single)
            $toc_show_on_single_post = isset($_POST['chrysoberyl_toc_show_on_single_post']) ? '1' : '0';
            update_option('chrysoberyl_toc_show_on_single_post', $toc_show_on_single_post);
            $toc_show_on_single_page = isset($_POST['chrysoberyl_toc_show_on_single_page']) ? '1' : '0';
            update_option('chrysoberyl_toc_show_on_single_page', $toc_show_on_single_page);

            $toc_mobile_enabled = isset($_POST['chrysoberyl_toc_mobile_enabled']) ? '1' : '0';
            update_option('chrysoberyl_toc_mobile_enabled', $toc_mobile_enabled);

            // Position
            if (isset($_POST['chrysoberyl_toc_position'])) {
                $toc_position = sanitize_text_field($_POST['chrysoberyl_toc_position']);
                if (in_array($toc_position, array('top', 'sidebar', 'floating'), true)) {
                    update_option('chrysoberyl_toc_position', $toc_position);
                }
            }

            // Mobile position
            if (isset($_POST['chrysoberyl_toc_mobile_position'])) {
                $toc_mobile_position = sanitize_text_field($_POST['chrysoberyl_toc_mobile_position']);
                if (in_array($toc_mobile_position, array('top', 'bottom', 'floating', 'collapsible'), true)) {
                    update_option('chrysoberyl_toc_mobile_position', $toc_mobile_position);
                }
            }

            // Heading levels
            $toc_headings = array();
            if (isset($_POST['chrysoberyl_toc_heading_h2'])) {
                $toc_headings[] = 'h2';
            }
            if (isset($_POST['chrysoberyl_toc_heading_h3'])) {
                $toc_headings[] = 'h3';
            }
            if (isset($_POST['chrysoberyl_toc_heading_h4'])) {
                $toc_headings[] = 'h4';
            }
            if (isset($_POST['chrysoberyl_toc_heading_h5'])) {
                $toc_headings[] = 'h5';
            }
            if (isset($_POST['chrysoberyl_toc_heading_h6'])) {
                $toc_headings[] = 'h6';
            }
            if (empty($toc_headings)) {
                $toc_headings = array('h2', 'h3', 'h4'); // Default
            }
            update_option('chrysoberyl_toc_headings', $toc_headings);

            // Style
            if (isset($_POST['chrysoberyl_toc_style'])) {
                $toc_style = sanitize_text_field($_POST['chrysoberyl_toc_style']);
                if (in_array($toc_style, array('simple', 'numbered', 'nested'), true)) {
                    update_option('chrysoberyl_toc_style', $toc_style);
                }
            }

            // Features
            $toc_smooth_scroll = isset($_POST['chrysoberyl_toc_smooth_scroll']) ? '1' : '0';
            update_option('chrysoberyl_toc_smooth_scroll', $toc_smooth_scroll);

            $toc_scroll_spy = isset($_POST['chrysoberyl_toc_scroll_spy']) ? '1' : '0';
            update_option('chrysoberyl_toc_scroll_spy', $toc_scroll_spy);

            $toc_collapsible = isset($_POST['chrysoberyl_toc_collapsible']) ? '1' : '0';
            update_option('chrysoberyl_toc_collapsible', $toc_collapsible);

            $toc_sticky = isset($_POST['chrysoberyl_toc_sticky']) ? '1' : '0';
            update_option('chrysoberyl_toc_sticky', $toc_sticky);

            $toc_auto_collapse_mobile = isset($_POST['chrysoberyl_toc_auto_collapse_mobile']) ? '1' : '0';
            update_option('chrysoberyl_toc_auto_collapse_mobile', $toc_auto_collapse_mobile);

            // Minimum headings count
            if (isset($_POST['chrysoberyl_toc_min_headings'])) {
                $min_headings = absint($_POST['chrysoberyl_toc_min_headings']);
                if ($min_headings >= 0 && $min_headings <= 20) {
                    update_option('chrysoberyl_toc_min_headings', $min_headings);
                }
            }

            // Custom title
            if (isset($_POST['chrysoberyl_toc_title'])) {
                update_option('chrysoberyl_toc_title', sanitize_text_field($_POST['chrysoberyl_toc_title']));
            }

            echo '<div class="notice notice-success is-dismissible"><p>' . __('Settings saved successfully!', 'chrysoberyl') . '</p></div>';
        }
    }

    // Get active tab from POST, GET parameter, or default to general
    // JavaScript will handle hash-based navigation, but we can also check for tab parameter
    $active_tab = 'general';
    if (isset($_POST['chrysoberyl_active_tab'])) {
        $active_tab = sanitize_text_field($_POST['chrysoberyl_active_tab']);
    } elseif (isset($_GET['tab'])) {
        $active_tab = sanitize_text_field($_GET['tab']);
    }

    if (!in_array($active_tab, array('general', 'social-sharing', 'search', 'toc', 'widgets', 'footer'), true)) {
        $active_tab = 'general';
    }

    $logo_id = get_option('chrysoberyl_logo', '');
    $logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, 'full') : '';
    $site_name_style = get_option('chrysoberyl_site_name_style', 'gray');
    $pagination_type = get_option('chrysoberyl_pagination_type', 'load_more'); // Default to load_more
    $home_news_columns = (int) get_option('chrysoberyl_home_news_columns', '2');
    $home_news_columns = max(1, min(4, $home_news_columns));

    // Get social sharing settings
    $social_sharing_enabled = get_option('chrysoberyl_social_sharing_enabled', '1');
    $social_show_on_post = get_option('chrysoberyl_social_show_on_post', '1');
    $social_show_on_page = get_option('chrysoberyl_social_show_on_page', '1');
    $selected_platforms = get_option('chrysoberyl_social_platforms', array('facebook', 'twitter', 'line'));
    $display_positions = get_option('chrysoberyl_social_display_positions', array('single_bottom'));
    $button_style = get_option('chrysoberyl_social_button_style', 'icon_only');
    $button_size = get_option('chrysoberyl_social_button_size', 'medium');
    $social_icon_style = get_option('chrysoberyl_social_icon_style', 'branded');

    $available_platforms = array(
        'facebook' => __('Facebook', 'chrysoberyl'),
        'twitter' => __('Twitter/X', 'chrysoberyl'),
        'line' => __('Line', 'chrysoberyl'),
        'linkedin' => __('LinkedIn', 'chrysoberyl'),
        'whatsapp' => __('WhatsApp', 'chrysoberyl'),
        'telegram' => __('Telegram', 'chrysoberyl'),
        'copy_link' => __('Copy Link', 'chrysoberyl'),
    );

    // Get search settings
    $search_enabled = get_option('chrysoberyl_search_enabled', '1');
    $search_suggestions_enabled = get_option('chrysoberyl_search_suggestions_enabled', '1');
    $search_live_enabled = get_option('chrysoberyl_search_live_enabled', '1');
    $search_suggestions_count = get_option('chrysoberyl_search_suggestions_count', 5);
    $search_debounce = get_option('chrysoberyl_search_debounce', 300);
    $search_min_length = get_option('chrysoberyl_search_min_length', 2);
    $search_post_types = get_option('chrysoberyl_search_post_types', array('post'));
    $search_fields = get_option('chrysoberyl_search_fields', array('title', 'content'));
    $search_suggestions_style = get_option('chrysoberyl_search_suggestions_style', 'dropdown');
    $search_suggestions_display = get_option('chrysoberyl_search_suggestions_display', array('image', 'excerpt'));
    $search_results_layout = get_option('chrysoberyl_search_results_layout', 'list');
    $search_results_sort = get_option('chrysoberyl_search_results_sort', 'relevance');
    $search_placeholder = get_option('chrysoberyl_search_placeholder', __('พิมพ์คำค้นหา...', 'chrysoberyl'));
    $search_exclude_categories = get_option('chrysoberyl_search_exclude_categories', array());

    $available_post_types = array(
        'post' => __('Posts', 'chrysoberyl'),
        'page' => __('Pages', 'chrysoberyl'),
    );

    // Get all categories for exclude list
    $all_categories = get_categories(array('hide_empty' => false));

    // Get Breadcrumb settings
    $breadcrumb_enabled = get_option('chrysoberyl_breadcrumb_enabled', '1');
    $breadcrumb_show_on_home = get_option('chrysoberyl_breadcrumb_show_on_home', '0');
    $breadcrumb_show_on_single = get_option('chrysoberyl_breadcrumb_show_on_single', '1');
    $breadcrumb_show_on_page = get_option('chrysoberyl_breadcrumb_show_on_page', '1');
    $breadcrumb_show_on_category = get_option('chrysoberyl_breadcrumb_show_on_category', '1');
    $breadcrumb_show_on_tag = get_option('chrysoberyl_breadcrumb_show_on_tag', '1');
    $breadcrumb_show_on_author = get_option('chrysoberyl_breadcrumb_show_on_author', '1');
    $breadcrumb_show_on_date = get_option('chrysoberyl_breadcrumb_show_on_date', '1');
    $breadcrumb_show_on_search = get_option('chrysoberyl_breadcrumb_show_on_search', '1');
    $breadcrumb_show_on_404 = get_option('chrysoberyl_breadcrumb_show_on_404', '1');

    // Get TOC settings
    $toc_enabled = get_option('chrysoberyl_toc_enabled', '1');
    $toc_mobile_enabled = get_option('chrysoberyl_toc_mobile_enabled', '1');
    $toc_position = get_option('chrysoberyl_toc_position', 'top');
    $toc_show_on_single_post = get_option('chrysoberyl_toc_show_on_single_post', '1');
    $toc_show_on_single_page = get_option('chrysoberyl_toc_show_on_single_page', '0');
    $toc_mobile_position = get_option('chrysoberyl_toc_mobile_position', 'floating');
    $toc_headings = get_option('chrysoberyl_toc_headings', array('h2', 'h3', 'h4'));
    $toc_style = get_option('chrysoberyl_toc_style', 'nested');
    $toc_smooth_scroll = get_option('chrysoberyl_toc_smooth_scroll', '1');
    $toc_scroll_spy = get_option('chrysoberyl_toc_scroll_spy', '1');
    $toc_collapsible = get_option('chrysoberyl_toc_collapsible', '1');
    $toc_sticky = get_option('chrysoberyl_toc_sticky', '0');
    $toc_auto_collapse_mobile = get_option('chrysoberyl_toc_auto_collapse_mobile', '1');
    $toc_min_headings = get_option('chrysoberyl_toc_min_headings', 2);
    $toc_title = get_option('chrysoberyl_toc_title', __('สารบัญ', 'chrysoberyl'));

    // Get Widget visibility settings
    $available_widgets = array(
        'popular_posts' => __('Popular Posts Widget', 'chrysoberyl'),
        'recent_posts' => __('Recent Posts Widget', 'chrysoberyl'),
        'trending_tags' => __('Trending Tags Widget', 'chrysoberyl'),
        'related_posts' => __('Related Posts Widget', 'chrysoberyl'),
        'categories' => __('Categories Widget', 'chrysoberyl'),
        'search' => __('Search Widget', 'chrysoberyl'),
        'social_follow' => __('Social Follow Widget', 'chrysoberyl'),
        'most_commented' => __('Most Commented Widget', 'chrysoberyl'),
        'archive' => __('Archive Widget', 'chrysoberyl'),
        'custom_html' => __('Custom HTML / Ad Widget', 'chrysoberyl'),
    );
    // Sidebar on single post / page / homepage / archive (default: show all)
    $sidebar_single_post_enabled = get_option('chrysoberyl_sidebar_single_post_enabled', '1');
    $author_box_single_enabled = get_option('chrysoberyl_author_box_single_enabled', '1');
    $sidebar_single_page_enabled = get_option('chrysoberyl_sidebar_single_page_enabled', '1');
    $sidebar_home_enabled = get_option('chrysoberyl_sidebar_home_enabled', '1');
    $sidebar_archive_enabled = get_option('chrysoberyl_sidebar_archive_enabled', '1');

    // Get enabled widgets - default to all enabled only if option doesn't exist
    $saved_widgets = get_option('chrysoberyl_enabled_widgets');
    if ($saved_widgets === false) {
        // First time - default to all enabled
        $enabled_widgets = array_keys($available_widgets);
    } else {
        // Use saved value (can be empty array if all unchecked)
        $enabled_widgets = is_array($saved_widgets) ? $saved_widgets : array();
    }

    ?>
    <div class="wrap chrysoberyl-settings-wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

        <form method="post" action="" id="chrysoberyl-settings-form">
            <?php wp_nonce_field('chrysoberyl_settings_nonce'); ?>

            <!-- Hidden input to store active tab -->
            <input type="hidden" name="chrysoberyl_active_tab" id="chrysoberyl_active_tab"
                value="<?php echo esc_attr(isset($_POST['chrysoberyl_active_tab']) ? sanitize_text_field($_POST['chrysoberyl_active_tab']) : 'general'); ?>" />

            <!-- Tabs Navigation -->
            <nav class="nav-tab-wrapper chrysoberyl-nav-tabs">
                <a href="#general" class="nav-tab <?php echo $active_tab === 'general' ? 'nav-tab-active' : ''; ?>"
                    data-tab="general">
                    <span class="dashicons dashicons-admin-settings"></span> <?php _e('General', 'chrysoberyl'); ?>
                </a>
                <a href="#footer" class="nav-tab <?php echo $active_tab === 'footer' ? 'nav-tab-active' : ''; ?>"
                    data-tab="footer">
                    <span class="dashicons dashicons-editor-kitchensink"></span> <?php _e('Footer', 'chrysoberyl'); ?>
                </a>
                <a href="#social-sharing"
                    class="nav-tab <?php echo $active_tab === 'social-sharing' ? 'nav-tab-active' : ''; ?>"
                    data-tab="social-sharing">
                    <span class="dashicons dashicons-share"></span> <?php _e('Social Sharing', 'chrysoberyl'); ?>
                </a>
                <a href="#search" class="nav-tab <?php echo $active_tab === 'search' ? 'nav-tab-active' : ''; ?>"
                    data-tab="search">
                    <span class="dashicons dashicons-search"></span> <?php _e('Search', 'chrysoberyl'); ?>
                </a>
                <a href="#toc" class="nav-tab <?php echo $active_tab === 'toc' ? 'nav-tab-active' : ''; ?>" data-tab="toc">
                    <span class="dashicons dashicons-list-view"></span> <?php _e('Table of Contents', 'chrysoberyl'); ?>
                </a>
                <a href="#widgets" class="nav-tab <?php echo $active_tab === 'widgets' ? 'nav-tab-active' : ''; ?>"
                    data-tab="widgets">
                    <span class="dashicons dashicons-welcome-widgets-menus"></span> <?php _e('Widgets', 'chrysoberyl'); ?>
                </a>
            </nav>

            <!-- General Settings Tab -->
            <div id="general-tab" class="chrysoberyl-tab-content <?php echo $active_tab === 'general' ? 'active' : ''; ?>">
                <div class="chrysoberyl-settings-section">
                    <h2 class="chrysoberyl-section-title">
                        <span class="dashicons dashicons-admin-customizer"></span>
                        <?php _e('General Settings', 'chrysoberyl'); ?>
                    </h2>

                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="chrysoberyl_logo"><?php _e('Website Logo', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <div class="chrysoberyl-logo-upload">
                                    <input type="hidden" id="chrysoberyl_logo" name="chrysoberyl_logo"
                                        value="<?php echo esc_attr($logo_id); ?>" />
                                    <div id="chrysoberyl_logo_preview" class="chrysoberyl-logo-preview">
                                        <?php if ($logo_url): ?>
                                            <img src="<?php echo esc_url($logo_url); ?>"
                                                alt="<?php _e('Logo Preview', 'chrysoberyl'); ?>" />
                                        <?php else: ?>
                                            <div class="chrysoberyl-logo-placeholder">
                                                <span class="dashicons dashicons-format-image"></span>
                                                <p><?php _e('No logo uploaded', 'chrysoberyl'); ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="chrysoberyl-logo-actions">
                                        <button type="button" class="button button-primary"
                                            id="chrysoberyl_upload_logo_btn">
                                            <span class="dashicons dashicons-upload"></span>
                                            <?php echo $logo_id ? __('Change Logo', 'chrysoberyl') : __('Upload Logo', 'chrysoberyl'); ?>
                                        </button>
                                        <?php if ($logo_id): ?>
                                            <button type="button" class="button" id="chrysoberyl_remove_logo_btn">
                                                <span class="dashicons dashicons-trash"></span>
                                                <?php _e('Remove', 'chrysoberyl'); ?>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                    <p class="description">
                                        <?php _e('Upload a logo for your website. Recommended size: 200x60 pixels or larger.', 'chrysoberyl'); ?>
                                    </p>
                                </div>
                                <p class="description" style="margin-top:10px;">
                                    <?php _e('เมื่อไม่มีโลโก้ แสดงชื่อเว็บใน header แบบใด:', 'chrysoberyl'); ?>
                                </p>
                                <fieldset class="chrysoberyl-radio-group" style="margin-top:8px;">
                                    <label class="chrysoberyl-radio-option">
                                        <input type="radio" name="chrysoberyl_site_name_style" value="gray" <?php checked($site_name_style, 'gray'); ?> />
                                        <span class="radio-label"><?php _e('สีเทา (ค่าเริ่มต้น)', 'chrysoberyl'); ?></span>
                                    </label>
                                    <label class="chrysoberyl-radio-option">
                                        <input type="radio" name="chrysoberyl_site_name_style" value="google_colors" <?php checked($site_name_style, 'google_colors'); ?> />
                                        <span
                                            class="radio-label"><?php _e('มีสีสัน (แบบโลโก้ Google)', 'chrysoberyl'); ?></span>
                                    </label>
                                </fieldset>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label
                                    for="chrysoberyl_pagination_type"><?php _e('Pagination Type', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <fieldset class="chrysoberyl-radio-group">
                                    <label class="chrysoberyl-radio-option">
                                        <input type="radio" name="chrysoberyl_pagination_type" value="pagination" <?php checked($pagination_type, 'pagination'); ?> />
                                        <span class="radio-label">
                                            <strong><?php _e('Pagination', 'chrysoberyl'); ?></strong>
                                            <small><?php _e('แสดงหมายเลขหน้า', 'chrysoberyl'); ?></small>
                                        </span>
                                    </label>
                                    <label class="chrysoberyl-radio-option">
                                        <input type="radio" name="chrysoberyl_pagination_type" value="load_more" <?php checked($pagination_type, 'load_more'); ?> />
                                        <span class="radio-label">
                                            <strong><?php _e('Load More', 'chrysoberyl'); ?></strong>
                                            <small><?php _e('โหลดข่าวเพิ่มเติม', 'chrysoberyl'); ?></small>
                                        </span>
                                    </label>
                                </fieldset>
                                <p class="description">
                                    <?php _e('เลือกวิธีการแสดงผลบทความในหน้าแรก: แสดง pagination หรือปุ่มโหลดข่าวเพิ่มเติม', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label
                                    for="chrysoberyl_home_news_columns"><?php _e('จำนวนคอลัมน์ข่าวล่าสุด (หน้าแรก)', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <select name="chrysoberyl_home_news_columns" id="chrysoberyl_home_news_columns">
                                    <?php for ($n = 1; $n <= 4; $n++): ?>
                                        <option value="<?php echo (int) $n; ?>" <?php selected($home_news_columns, $n); ?>>
                                            <?php echo (int) $n; ?>         <?php _e('คอลัมน์', 'chrysoberyl'); ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                                <p class="description">
                                    <?php _e('เลือกว่าจะให้กริดข่าวล่าสุดในหน้าแรกและหน้าอาร์คิฟแสดงกี่คอลัมน์ (จอขนาดกลางขึ้นไป)', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('หน้าเข้าสู่ระบบ (Login)', 'chrysoberyl'); ?></th>
                            <td>
                                <?php $login_use_theme_style = get_option('chrysoberyl_login_use_theme_style', '1'); ?>
                                <label class="chrysoberyl-toggle">
                                    <input type="hidden" name="chrysoberyl_login_use_theme_style" value="0" />
                                    <input type="checkbox" name="chrysoberyl_login_use_theme_style" value="1" <?php checked($login_use_theme_style, '1'); ?> />
                                    <span class="toggle-slider"></span>
                                    <span
                                        class="toggle-label"><?php _e('ใช้สไตล์ธีม (ปรับแต่ง wp-login ให้เข้ากับธีม Chrysoberyl - Theme)', 'chrysoberyl'); ?></span>
                                </label>
                                <p class="description" style="margin-top: 8px;">
                                    <?php _e('ปิด = แสดงหน้าเข้าสู่ระบบแบบดั้งเดิมของ WordPress', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Breadcrumb Settings Section -->
                <div class="chrysoberyl-settings-section" style="margin-top: 30px;">
                    <h2 class="chrysoberyl-section-title">
                        <span class="dashicons dashicons-admin-links"></span>
                        <?php _e('Breadcrumb Settings', 'chrysoberyl'); ?>
                    </h2>

                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e('เปิดใช้งาน Breadcrumbs', 'chrysoberyl'); ?></th>
                            <td>
                                <label class="chrysoberyl-toggle">
                                    <input type="hidden" name="chrysoberyl_breadcrumb_enabled" value="0" />
                                    <input type="checkbox" name="chrysoberyl_breadcrumb_enabled" value="1" <?php checked($breadcrumb_enabled, '1'); ?> />
                                    <span class="toggle-slider"></span>
                                    <span
                                        class="toggle-label"><?php _e('แสดง Breadcrumbs (เส้นทางนำทาง)', 'chrysoberyl'); ?></span>
                                </label>
                                <p class="description" style="margin-top: 8px;">
                                    <?php _e('เปิด/ปิดการแสดง breadcrumbs ทั้งหมดในเว็บไซต์', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('แสดง Breadcrumbs ที่', 'chrysoberyl'); ?></th>
                            <td>
                                <fieldset>
                                    <legend class="screen-reader-text">
                                        <span><?php _e('เลือกหน้าที่ต้องการแสดง Breadcrumbs', 'chrysoberyl'); ?></span>
                                    </legend>
                                    <p class="description" style="margin-bottom: 12px;">
                                        <?php _e('เลือกประเภทหน้าที่ต้องการแสดง breadcrumbs:', 'chrysoberyl'); ?>
                                    </p>
                                    <div
                                        style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 8px;">
                                        <label>
                                            <input type="checkbox" name="chrysoberyl_breadcrumb_show_on_home" value="1"
                                                <?php checked($breadcrumb_show_on_home, '1'); ?> />
                                            <?php _e('หน้าแรก (Homepage)', 'chrysoberyl'); ?>
                                        </label>
                                        <label>
                                            <input type="checkbox" name="chrysoberyl_breadcrumb_show_on_single" value="1"
                                                <?php checked($breadcrumb_show_on_single, '1'); ?> />
                                            <?php _e('บทความ (Single Post)', 'chrysoberyl'); ?>
                                        </label>
                                        <label>
                                            <input type="checkbox" name="chrysoberyl_breadcrumb_show_on_page" value="1"
                                                <?php checked($breadcrumb_show_on_page, '1'); ?> />
                                            <?php _e('หน้าเพจ (Page)', 'chrysoberyl'); ?>
                                        </label>
                                        <label>
                                            <input type="checkbox" name="chrysoberyl_breadcrumb_show_on_category" value="1"
                                                <?php checked($breadcrumb_show_on_category, '1'); ?> />
                                            <?php _e('หมวดหมู่ (Category Archive)', 'chrysoberyl'); ?>
                                        </label>
                                        <label>
                                            <input type="checkbox" name="chrysoberyl_breadcrumb_show_on_tag" value="1" <?php checked($breadcrumb_show_on_tag, '1'); ?> />
                                            <?php _e('แท็ก (Tag Archive)', 'chrysoberyl'); ?>
                                        </label>
                                        <label>
                                            <input type="checkbox" name="chrysoberyl_breadcrumb_show_on_author" value="1"
                                                <?php checked($breadcrumb_show_on_author, '1'); ?> />
                                            <?php _e('ผู้เขียน (Author Archive)', 'chrysoberyl'); ?>
                                        </label>
                                        <label>
                                            <input type="checkbox" name="chrysoberyl_breadcrumb_show_on_date" value="1"
                                                <?php checked($breadcrumb_show_on_date, '1'); ?> />
                                            <?php _e('วันที่ (Date Archive)', 'chrysoberyl'); ?>
                                        </label>
                                        <label>
                                            <input type="checkbox" name="chrysoberyl_breadcrumb_show_on_search" value="1"
                                                <?php checked($breadcrumb_show_on_search, '1'); ?> />
                                            <?php _e('ผลการค้นหา (Search Results)', 'chrysoberyl'); ?>
                                        </label>
                                        <label>
                                            <input type="checkbox" name="chrysoberyl_breadcrumb_show_on_404" value="1" <?php checked($breadcrumb_show_on_404, '1'); ?> />
                                            <?php _e('หน้าไม่พบ (404 Page)', 'chrysoberyl'); ?>
                                        </label>
                                    </div>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Social Sharing Settings Tab -->
            <div id="social-sharing-tab"
                class="chrysoberyl-tab-content <?php echo $active_tab === 'social-sharing' ? 'active' : ''; ?>">
                <div class="chrysoberyl-settings-section">
                    <h2 class="chrysoberyl-section-title">
                        <span class="dashicons dashicons-share"></span>
                        <?php _e('Social Sharing Settings', 'chrysoberyl'); ?>
                    </h2>
                    <p class="chrysoberyl-section-description">
                        <?php _e('ตั้งค่าการแชร์เนื้อหาไปยัง Social Media platforms ต่างๆ', 'chrysoberyl'); ?>
                    </p>

                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <?php _e('Enable Social Sharing', 'chrysoberyl'); ?>
                            </th>
                            <td>
                                <label class="chrysoberyl-toggle">
                                    <input type="checkbox" name="chrysoberyl_social_sharing_enabled" value="1" <?php checked($social_sharing_enabled, '1'); ?> />
                                    <span class="toggle-slider"></span>
                                    <span
                                        class="toggle-label"><?php _e('เปิดใช้งานการแชร์เนื้อหาไปยัง Social Media', 'chrysoberyl'); ?></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <?php _e('แสดงปุ่มแชร์ในหน้าบทความ', 'chrysoberyl'); ?>
                            </th>
                            <td>
                                <label class="chrysoberyl-toggle">
                                    <input type="checkbox" name="chrysoberyl_social_show_on_post" value="1" <?php checked($social_show_on_post, '1'); ?> />
                                    <span class="toggle-slider"></span>
                                    <span
                                        class="toggle-label"><?php _e('แสดงปุ่มแชร์ในหน้าบทความ (Single Post)', 'chrysoberyl'); ?></span>
                                </label>
                                <p class="description" style="margin-top: 8px;">
                                    <?php _e('ปิดใช้ถ้าไม่ต้องการให้มีปุ่มแชร์ในหน้าบทความ', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <?php _e('แสดงปุ่มแชร์ในหน้าคงที่', 'chrysoberyl'); ?>
                            </th>
                            <td>
                                <label class="chrysoberyl-toggle">
                                    <input type="checkbox" name="chrysoberyl_social_show_on_page" value="1" <?php checked($social_show_on_page, '1'); ?> />
                                    <span class="toggle-slider"></span>
                                    <span
                                        class="toggle-label"><?php _e('แสดงปุ่มแชร์ในหน้าคงที่ (Page เช่น Privacy Policy)', 'chrysoberyl'); ?></span>
                                </label>
                                <p class="description" style="margin-top: 8px;">
                                    <?php _e('ปิดใช้ถ้าไม่ต้องการให้มีปุ่มแชร์ในหน้าคงที่', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label><?php _e('Social Platforms', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <div class="chrysoberyl-platforms-grid">
                                    <?php
                                    $platform_icons = array(
                                        'facebook' => 'fab fa-facebook-f',
                                        'twitter' => 'fab fa-twitter',
                                        'line' => 'fab fa-line',
                                        'linkedin' => 'fab fa-linkedin-in',
                                        'whatsapp' => 'fab fa-whatsapp',
                                        'telegram' => 'fab fa-telegram-plane',
                                        'copy_link' => 'fas fa-link',
                                    );
                                    foreach ($available_platforms as $platform_key => $platform_name):
                                        $icon = isset($platform_icons[$platform_key]) ? $platform_icons[$platform_key] : 'fas fa-share-alt';
                                        ?>
                                        <label class="chrysoberyl-platform-item">
                                            <input type="checkbox" name="chrysoberyl_social_platforms[]"
                                                value="<?php echo esc_attr($platform_key); ?>" <?php checked(in_array($platform_key, $selected_platforms), true); ?> />
                                            <span class="platform-icon">
                                                <i class="<?php echo esc_attr($icon); ?>"></i>
                                            </span>
                                            <span class="platform-name"><?php echo esc_html($platform_name); ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                                <p class="description">
                                    <?php _e('เลือก Social Media platforms ที่ต้องการแสดงปุ่มแชร์', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label><?php _e('Display Positions', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <div class="chrysoberyl-positions-grid">
                                    <label class="chrysoberyl-position-item">
                                        <input type="checkbox" name="chrysoberyl_social_display_single_top" value="1" <?php checked(in_array('single_top', $display_positions), true); ?> />
                                        <span class="position-icon"><span
                                                class="dashicons dashicons-arrow-up-alt"></span></span>
                                        <span class="position-label">
                                            <strong><?php _e('ด้านบนบทความ', 'chrysoberyl'); ?></strong>
                                            <small><?php _e('Single Post', 'chrysoberyl'); ?></small>
                                        </span>
                                    </label>
                                    <label class="chrysoberyl-position-item">
                                        <input type="checkbox" name="chrysoberyl_social_display_single_bottom" value="1"
                                            <?php checked(in_array('single_bottom', $display_positions), true); ?> />
                                        <span class="position-icon"><span
                                                class="dashicons dashicons-arrow-down-alt"></span></span>
                                        <span class="position-label">
                                            <strong><?php _e('ด้านล่างบทความ', 'chrysoberyl'); ?></strong>
                                            <small><?php _e('Single Post', 'chrysoberyl'); ?></small>
                                        </span>
                                    </label>
                                    <label class="chrysoberyl-position-item">
                                        <input type="checkbox" name="chrysoberyl_social_display_floating" value="1" <?php checked(in_array('floating', $display_positions), true); ?> />
                                        <span class="position-icon"><span
                                                class="dashicons dashicons-admin-generic"></span></span>
                                        <span class="position-label">
                                            <strong><?php _e('Floating Buttons', 'chrysoberyl'); ?></strong>
                                            <small><?php _e('ด้านข้างหน้าจอ', 'chrysoberyl'); ?></small>
                                        </span>
                                    </label>
                                </div>
                                <p class="description">
                                    <?php _e('เลือกตำแหน่งที่ต้องการแสดงปุ่มแชร์', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label
                                    for="chrysoberyl_social_button_style"><?php _e('Button Style', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <select name="chrysoberyl_social_button_style" id="chrysoberyl_social_button_style">
                                    <option value="icon_only" <?php selected($button_style, 'icon_only'); ?>>
                                        <?php _e('Icon Only (ไอคอนเท่านั้น)', 'chrysoberyl'); ?>
                                    </option>
                                    <option value="icon_text" <?php selected($button_style, 'icon_text'); ?>>
                                        <?php _e('Icon + Text (ไอคอนและข้อความ)', 'chrysoberyl'); ?>
                                    </option>
                                    <option value="button" <?php selected($button_style, 'button'); ?>>
                                        <?php _e('Button Style (สไตล์ปุ่ม)', 'chrysoberyl'); ?>
                                    </option>
                                </select>
                                <p class="description">
                                    <?php _e('เลือกรูปแบบการแสดงผลปุ่มแชร์', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label
                                    for="chrysoberyl_social_button_size"><?php _e('Button Size', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <select name="chrysoberyl_social_button_size" id="chrysoberyl_social_button_size">
                                    <option value="small" <?php selected($button_size, 'small'); ?>>
                                        <?php _e('Small (เล็ก)', 'chrysoberyl'); ?>
                                    </option>
                                    <option value="medium" <?php selected($button_size, 'medium'); ?>>
                                        <?php _e('Medium (กลาง)', 'chrysoberyl'); ?>
                                    </option>
                                    <option value="large" <?php selected($button_size, 'large'); ?>>
                                        <?php _e('Large (ใหญ่)', 'chrysoberyl'); ?>
                                    </option>
                                </select>
                                <p class="description">
                                    <?php _e('เลือกขนาดปุ่มแชร์', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label
                                    for="chrysoberyl_social_icon_style"><?php _e('รูปลักษณะไอคอนแชร์', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <select name="chrysoberyl_social_icon_style" id="chrysoberyl_social_icon_style">
                                    <option value="branded" <?php selected($social_icon_style, 'branded'); ?>>
                                        <?php _e('แบบสีแบรนด์ (Branded)', 'chrysoberyl'); ?>
                                    </option>
                                    <option value="mockup" <?php selected($social_icon_style, 'mockup'); ?>>
                                        <?php _e('แบบ Mockup (เทา วงกลม hover)', 'chrysoberyl'); ?>
                                    </option>
                                </select>
                                <p class="description">
                                    <?php _e('แบบ Mockup: ไอคอนสีเทา วงกลม เมื่อ hover เป็นพื้นหลังเทาอ่อน ตรงกับ mockup single', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Search Settings Tab -->
            <div id="search-tab" class="chrysoberyl-tab-content <?php echo $active_tab === 'search' ? 'active' : ''; ?>">
                <div class="chrysoberyl-settings-section">
                    <h2 class="chrysoberyl-section-title">
                        <span class="dashicons dashicons-search"></span>
                        <?php _e('Search Settings', 'chrysoberyl'); ?>
                    </h2>
                    <p class="chrysoberyl-section-description">
                        <?php _e('ตั้งค่าการค้นหาและ Search Suggestions', 'chrysoberyl'); ?>
                    </p>

                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <?php _e('Enable Search', 'chrysoberyl'); ?>
                            </th>
                            <td>
                                <label class="chrysoberyl-toggle">
                                    <input type="checkbox" name="chrysoberyl_search_enabled" value="1" <?php checked($search_enabled, '1'); ?> />
                                    <span class="toggle-slider"></span>
                                    <span class="toggle-label"><?php _e('เปิดใช้งานการค้นหา', 'chrysoberyl'); ?></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <?php _e('Search Suggestions', 'chrysoberyl'); ?>
                            </th>
                            <td>
                                <label class="chrysoberyl-toggle">
                                    <input type="checkbox" name="chrysoberyl_search_suggestions_enabled" value="1" <?php checked($search_suggestions_enabled, '1'); ?> />
                                    <span class="toggle-slider"></span>
                                    <span
                                        class="toggle-label"><?php _e('เปิดใช้งาน Search Suggestions (Autocomplete)', 'chrysoberyl'); ?></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <?php _e('Live Search', 'chrysoberyl'); ?>
                            </th>
                            <td>
                                <label class="chrysoberyl-toggle">
                                    <input type="checkbox" name="chrysoberyl_search_live_enabled" value="1" <?php checked($search_live_enabled, '1'); ?> />
                                    <span class="toggle-slider"></span>
                                    <span
                                        class="toggle-label"><?php _e('เปิดใช้งาน Live Search (ค้นหาขณะพิมพ์)', 'chrysoberyl'); ?></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label
                                    for="chrysoberyl_search_suggestions_count"><?php _e('Suggestions Count', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <input type="number" name="chrysoberyl_search_suggestions_count"
                                    id="chrysoberyl_search_suggestions_count"
                                    value="<?php echo esc_attr($search_suggestions_count); ?>" min="1" max="20"
                                    class="small-text" />
                                <p class="description">
                                    <?php _e('จำนวน Suggestions ที่แสดง (1-20)', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label
                                    for="chrysoberyl_search_debounce"><?php _e('Debounce Delay (ms)', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <input type="number" name="chrysoberyl_search_debounce" id="chrysoberyl_search_debounce"
                                    value="<?php echo esc_attr($search_debounce); ?>" min="0" max="2000" step="50"
                                    class="small-text" />
                                <p class="description">
                                    <?php _e('ระยะเวลาที่รอก่อนค้นหา (0-2000ms, แนะนำ 300ms)', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label
                                    for="chrysoberyl_search_min_length"><?php _e('Minimum Length', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <input type="number" name="chrysoberyl_search_min_length" id="chrysoberyl_search_min_length"
                                    value="<?php echo esc_attr($search_min_length); ?>" min="1" max="10"
                                    class="small-text" />
                                <p class="description">
                                    <?php _e('ความยาวขั้นต่ำของคำค้นหา (1-10 ตัวอักษร)', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label><?php _e('Post Types to Search', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <div class="chrysoberyl-platforms-grid">
                                    <?php foreach ($available_post_types as $post_type_key => $post_type_name): ?>
                                        <label class="chrysoberyl-platform-item">
                                            <input type="checkbox" name="chrysoberyl_search_post_types[]"
                                                value="<?php echo esc_attr($post_type_key); ?>" <?php checked(in_array($post_type_key, $search_post_types), true); ?> />
                                            <span class="platform-icon">
                                                <i class="fas fa-file-alt"></i>
                                            </span>
                                            <span class="platform-name"><?php echo esc_html($post_type_name); ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                                <p class="description">
                                    <?php _e('เลือก Post Types ที่ต้องการให้ค้นหา', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label><?php _e('Search Fields', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <fieldset>
                                    <label style="display: block; margin-bottom: 8px;">
                                        <input type="checkbox" name="chrysoberyl_search_field_title" value="1" <?php checked(in_array('title', $search_fields), true); ?> />
                                        <?php _e('Title (ชื่อบทความ)', 'chrysoberyl'); ?>
                                    </label>
                                    <label style="display: block; margin-bottom: 8px;">
                                        <input type="checkbox" name="chrysoberyl_search_field_content" value="1" <?php checked(in_array('content', $search_fields), true); ?> />
                                        <?php _e('Content (เนื้อหา)', 'chrysoberyl'); ?>
                                    </label>
                                    <label style="display: block; margin-bottom: 8px;">
                                        <input type="checkbox" name="chrysoberyl_search_field_excerpt" value="1" <?php checked(in_array('excerpt', $search_fields), true); ?> />
                                        <?php _e('Excerpt (คำอธิบาย)', 'chrysoberyl'); ?>
                                    </label>
                                    <label style="display: block; margin-bottom: 8px;">
                                        <input type="checkbox" name="chrysoberyl_search_field_categories" value="1" <?php checked(in_array('categories', $search_fields), true); ?> />
                                        <?php _e('Categories (หมวดหมู่)', 'chrysoberyl'); ?>
                                    </label>
                                    <label style="display: block; margin-bottom: 8px;">
                                        <input type="checkbox" name="chrysoberyl_search_field_tags" value="1" <?php checked(in_array('tags', $search_fields), true); ?> />
                                        <?php _e('Tags (ป้ายกำกับ)', 'chrysoberyl'); ?>
                                    </label>
                                </fieldset>
                                <p class="description">
                                    <?php _e('เลือก Fields ที่ต้องการให้ค้นหา', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label
                                    for="chrysoberyl_search_suggestions_style"><?php _e('Suggestions Style', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <select name="chrysoberyl_search_suggestions_style"
                                    id="chrysoberyl_search_suggestions_style">
                                    <option value="dropdown" <?php selected($search_suggestions_style, 'dropdown'); ?>>
                                        <?php _e('Dropdown (ใต้ search box)', 'chrysoberyl'); ?>
                                    </option>
                                    <option value="modal" <?php selected($search_suggestions_style, 'modal'); ?>>
                                        <?php _e('Modal/Popup', 'chrysoberyl'); ?>
                                    </option>
                                    <option value="fullpage" <?php selected($search_suggestions_style, 'fullpage'); ?>>
                                        <?php _e('Full Page', 'chrysoberyl'); ?>
                                    </option>
                                </select>
                                <p class="description">
                                    <?php _e('เลือกรูปแบบการแสดงผล Suggestions', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label><?php _e('Suggestions Display', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <fieldset>
                                    <label style="display: block; margin-bottom: 8px;">
                                        <input type="checkbox" name="chrysoberyl_search_show_image" value="1" <?php checked(in_array('image', $search_suggestions_display), true); ?> />
                                        <?php _e('Featured Image', 'chrysoberyl'); ?>
                                    </label>
                                    <label style="display: block; margin-bottom: 8px;">
                                        <input type="checkbox" name="chrysoberyl_search_show_excerpt" value="1" <?php checked(in_array('excerpt', $search_suggestions_display), true); ?> />
                                        <?php _e('Excerpt', 'chrysoberyl'); ?>
                                    </label>
                                    <label style="display: block; margin-bottom: 8px;">
                                        <input type="checkbox" name="chrysoberyl_search_show_date" value="1" <?php checked(in_array('date', $search_suggestions_display), true); ?> />
                                        <?php _e('Date', 'chrysoberyl'); ?>
                                    </label>
                                    <label style="display: block; margin-bottom: 8px;">
                                        <input type="checkbox" name="chrysoberyl_search_show_category" value="1" <?php checked(in_array('category', $search_suggestions_display), true); ?> />
                                        <?php _e('Category', 'chrysoberyl'); ?>
                                    </label>
                                </fieldset>
                                <p class="description">
                                    <?php _e('เลือกข้อมูลที่ต้องการแสดงใน Suggestions', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label
                                    for="chrysoberyl_search_results_layout"><?php _e('Results Layout', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <select name="chrysoberyl_search_results_layout" id="chrysoberyl_search_results_layout">
                                    <option value="list" <?php selected($search_results_layout, 'list'); ?>>
                                        <?php _e('List View', 'chrysoberyl'); ?>
                                    </option>
                                    <option value="grid" <?php selected($search_results_layout, 'grid'); ?>>
                                        <?php _e('Grid View', 'chrysoberyl'); ?>
                                    </option>
                                    <option value="mixed" <?php selected($search_results_layout, 'mixed'); ?>>
                                        <?php _e('Mixed View', 'chrysoberyl'); ?>
                                    </option>
                                </select>
                                <p class="description">
                                    <?php _e('เลือกรูปแบบการแสดงผลในหน้า Search Results', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label
                                    for="chrysoberyl_search_results_sort"><?php _e('Default Sort', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <select name="chrysoberyl_search_results_sort" id="chrysoberyl_search_results_sort">
                                    <option value="relevance" <?php selected($search_results_sort, 'relevance'); ?>>
                                        <?php _e('Relevance (ความเกี่ยวข้อง)', 'chrysoberyl'); ?>
                                    </option>
                                    <option value="date_desc" <?php selected($search_results_sort, 'date_desc'); ?>>
                                        <?php _e('Date (ใหม่สุด)', 'chrysoberyl'); ?>
                                    </option>
                                    <option value="date_asc" <?php selected($search_results_sort, 'date_asc'); ?>>
                                        <?php _e('Date (เก่าสุด)', 'chrysoberyl'); ?>
                                    </option>
                                    <option value="title_asc" <?php selected($search_results_sort, 'title_asc'); ?>>
                                        <?php _e('Title (A-Z)', 'chrysoberyl'); ?>
                                    </option>
                                    <option value="title_desc" <?php selected($search_results_sort, 'title_desc'); ?>>
                                        <?php _e('Title (Z-A)', 'chrysoberyl'); ?>
                                    </option>
                                </select>
                                <p class="description">
                                    <?php _e('เลือกวิธีการเรียงลำดับผลการค้นหา', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label
                                    for="chrysoberyl_search_placeholder"><?php _e('Search Placeholder', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="chrysoberyl_search_placeholder" id="chrysoberyl_search_placeholder"
                                    value="<?php echo esc_attr($search_placeholder); ?>" class="regular-text" />
                                <p class="description">
                                    <?php _e('ข้อความที่แสดงใน Search Box', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label><?php _e('Exclude Categories', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <div
                                    style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 4px;">
                                    <?php if (!empty($all_categories)): ?>
                                        <?php foreach ($all_categories as $category): ?>
                                            <label style="display: block; margin-bottom: 8px;">
                                                <input type="checkbox" name="chrysoberyl_search_exclude_categories[]"
                                                    value="<?php echo esc_attr($category->term_id); ?>" <?php checked(in_array($category->term_id, $search_exclude_categories), true); ?> />
                                                <?php echo esc_html($category->name); ?> (<?php echo $category->count; ?>)
                                            </label>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="description"><?php _e('ไม่มีหมวดหมู่', 'chrysoberyl'); ?></p>
                                    <?php endif; ?>
                                </div>
                                <p class="description">
                                    <?php _e('เลือกหมวดหมู่ที่ต้องการยกเว้นจากการค้นหา', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- TOC Settings Tab -->
            <div id="toc-tab" class="chrysoberyl-tab-content <?php echo $active_tab === 'toc' ? 'active' : ''; ?>">
                <div class="chrysoberyl-settings-section">
                    <h2 class="chrysoberyl-section-title">
                        <span class="dashicons dashicons-list-view"></span>
                        <?php _e('Table of Contents Settings', 'chrysoberyl'); ?>
                    </h2>
                    <p class="chrysoberyl-section-description">
                        <?php _e('ตั้งค่า Table of Contents (TOC) สำหรับบทความ', 'chrysoberyl'); ?>
                    </p>

                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <?php _e('Enable TOC', 'chrysoberyl'); ?>
                            </th>
                            <td>
                                <label class="chrysoberyl-toggle">
                                    <input type="checkbox" name="chrysoberyl_toc_enabled" value="1" <?php checked($toc_enabled, '1'); ?> />
                                    <span class="toggle-slider"></span>
                                    <span
                                        class="toggle-label"><?php _e('เปิดใช้งาน Table of Contents', 'chrysoberyl'); ?></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="chrysoberyl_toc_position"><?php _e('Position', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <select name="chrysoberyl_toc_position" id="chrysoberyl_toc_position">
                                    <option value="top" <?php selected($toc_position, 'top'); ?>>
                                        <?php _e('Top (ด้านบนเนื้อหา)', 'chrysoberyl'); ?>
                                    </option>
                                    <option value="sidebar" <?php selected($toc_position, 'sidebar'); ?>>
                                        <?php _e('Sidebar (ด้านข้าง)', 'chrysoberyl'); ?>
                                    </option>
                                    <option value="floating" <?php selected($toc_position, 'floating'); ?>>
                                        <?php _e('Floating (ลอยด้านข้าง)', 'chrysoberyl'); ?>
                                    </option>
                                </select>
                                <p class="description">
                                    <?php _e('เลือกตำแหน่งที่ต้องการแสดง TOC', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label><?php _e('Display on', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <fieldset>
                                    <label style="display: block; margin-bottom: 8px;">
                                        <input type="checkbox" name="chrysoberyl_toc_show_on_single_post" value="1" <?php checked($toc_show_on_single_post, '1'); ?> />
                                        <?php _e('Post single (บทความเดียว)', 'chrysoberyl'); ?>
                                    </label>
                                    <label style="display: block; margin-bottom: 8px;">
                                        <input type="checkbox" name="chrysoberyl_toc_show_on_single_page" value="1" <?php checked($toc_show_on_single_page, '1'); ?> />
                                        <?php _e('Page single (หน้าคงที่)', 'chrysoberyl'); ?>
                                    </label>
                                </fieldset>
                                <p class="description">
                                    <?php _e('เลือกว่าจะแสดง TOC ในหน้าประเภทใด', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <?php _e('Show on Mobile', 'chrysoberyl'); ?>
                            </th>
                            <td>
                                <label class="chrysoberyl-toggle">
                                    <input type="checkbox" name="chrysoberyl_toc_mobile_enabled" value="1" <?php checked($toc_mobile_enabled, '1'); ?> />
                                    <span class="toggle-slider"></span>
                                    <span class="toggle-label"><?php _e('แสดง TOC บน Mobile', 'chrysoberyl'); ?></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label
                                    for="chrysoberyl_toc_mobile_position"><?php _e('Mobile Position', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <select name="chrysoberyl_toc_mobile_position" id="chrysoberyl_toc_mobile_position">
                                    <option value="top" <?php selected($toc_mobile_position, 'top'); ?>>
                                        <?php _e('Top (ด้านบน)', 'chrysoberyl'); ?>
                                    </option>
                                    <option value="bottom" <?php selected($toc_mobile_position, 'bottom'); ?>>
                                        <?php _e('Bottom (ด้านล่าง)', 'chrysoberyl'); ?>
                                    </option>
                                    <option value="floating" <?php selected($toc_mobile_position, 'floating'); ?>>
                                        <?php _e('Floating Button (ปุ่มลอย)', 'chrysoberyl'); ?>
                                    </option>
                                    <option value="collapsible" <?php selected($toc_mobile_position, 'collapsible'); ?>>
                                        <?php _e('Collapsible Menu (เมนูย่อ/ขยาย)', 'chrysoberyl'); ?>
                                    </option>
                                </select>
                                <p class="description">
                                    <?php _e('เลือกตำแหน่งที่ต้องการแสดง TOC บน Mobile', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label><?php _e('Heading Levels', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <fieldset>
                                    <label style="display: block; margin-bottom: 8px;">
                                        <input type="checkbox" name="chrysoberyl_toc_heading_h2" value="1" <?php checked(in_array('h2', $toc_headings), true); ?> />
                                        <?php _e('H2 (หัวข้อหลัก)', 'chrysoberyl'); ?>
                                    </label>
                                    <label style="display: block; margin-bottom: 8px;">
                                        <input type="checkbox" name="chrysoberyl_toc_heading_h3" value="1" <?php checked(in_array('h3', $toc_headings), true); ?> />
                                        <?php _e('H3 (หัวข้อย่อย)', 'chrysoberyl'); ?>
                                    </label>
                                    <label style="display: block; margin-bottom: 8px;">
                                        <input type="checkbox" name="chrysoberyl_toc_heading_h4" value="1" <?php checked(in_array('h4', $toc_headings), true); ?> />
                                        <?php _e('H4 (หัวข้อรอง)', 'chrysoberyl'); ?>
                                    </label>
                                    <label style="display: block; margin-bottom: 8px;">
                                        <input type="checkbox" name="chrysoberyl_toc_heading_h5" value="1" <?php checked(in_array('h5', $toc_headings), true); ?> />
                                        <?php _e('H5', 'chrysoberyl'); ?>
                                    </label>
                                    <label style="display: block; margin-bottom: 8px;">
                                        <input type="checkbox" name="chrysoberyl_toc_heading_h6" value="1" <?php checked(in_array('h6', $toc_headings), true); ?> />
                                        <?php _e('H6', 'chrysoberyl'); ?>
                                    </label>
                                </fieldset>
                                <p class="description">
                                    <?php _e('เลือกระดับ Heading ที่ต้องการแสดงใน TOC', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="chrysoberyl_toc_style"><?php _e('Style', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <select name="chrysoberyl_toc_style" id="chrysoberyl_toc_style">
                                    <option value="simple" <?php selected($toc_style, 'simple'); ?>>
                                        <?php _e('Simple List (รายการธรรมดา)', 'chrysoberyl'); ?>
                                    </option>
                                    <option value="numbered" <?php selected($toc_style, 'numbered'); ?>>
                                        <?php _e('Numbered List (รายการแบบมีเลข)', 'chrysoberyl'); ?>
                                    </option>
                                    <option value="nested" <?php selected($toc_style, 'nested'); ?>>
                                        <?php _e('Nested/Indented (แบบซ้อน)', 'chrysoberyl'); ?>
                                    </option>
                                </select>
                                <p class="description">
                                    <?php _e('เลือกรูปแบบการแสดงผล TOC', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label><?php _e('Features', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <fieldset>
                                    <label style="display: block; margin-bottom: 8px;">
                                        <input type="checkbox" name="chrysoberyl_toc_smooth_scroll" value="1" <?php checked($toc_smooth_scroll, '1'); ?> />
                                        <?php _e('Smooth Scroll (เลื่อนแบบนุ่มนวล)', 'chrysoberyl'); ?>
                                    </label>
                                    <label style="display: block; margin-bottom: 8px;">
                                        <input type="checkbox" name="chrysoberyl_toc_scroll_spy" value="1" <?php checked($toc_scroll_spy, '1'); ?> />
                                        <?php _e('Scroll Spy (Highlight section ที่กำลังอ่าน)', 'chrysoberyl'); ?>
                                    </label>
                                    <label style="display: block; margin-bottom: 8px;">
                                        <input type="checkbox" name="chrysoberyl_toc_collapsible" value="1" <?php checked($toc_collapsible, '1'); ?> />
                                        <?php _e('Collapsible (ย่อ/ขยายได้)', 'chrysoberyl'); ?>
                                    </label>
                                    <label style="display: block; margin-bottom: 8px;">
                                        <input type="checkbox" name="chrysoberyl_toc_sticky" value="1" <?php checked($toc_sticky, '1'); ?> />
                                        <?php _e('Sticky (ติดตามเมื่อ scroll)', 'chrysoberyl'); ?>
                                    </label>
                                    <label style="display: block; margin-bottom: 8px;">
                                        <input type="checkbox" name="chrysoberyl_toc_auto_collapse_mobile" value="1" <?php checked($toc_auto_collapse_mobile, '1'); ?> />
                                        <?php _e('Auto-collapse on Mobile (ย่ออัตโนมัติบนมือถือ)', 'chrysoberyl'); ?>
                                    </label>
                                </fieldset>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label
                                    for="chrysoberyl_toc_min_headings"><?php _e('Minimum Headings', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <input type="number" name="chrysoberyl_toc_min_headings" id="chrysoberyl_toc_min_headings"
                                    value="<?php echo esc_attr($toc_min_headings); ?>" min="0" max="20"
                                    class="small-text" />
                                <p class="description">
                                    <?php _e('จำนวน Heading ขั้นต่ำที่จะแสดง TOC (0 = แสดงเสมอ)', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="chrysoberyl_toc_title"><?php _e('TOC Title', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="chrysoberyl_toc_title" id="chrysoberyl_toc_title"
                                    value="<?php echo esc_attr($toc_title); ?>" class="regular-text" />
                                <p class="description">
                                    <?php _e('ชื่อหัวข้อของ TOC (เช่น: สารบัญ, Table of Contents)', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Widgets Tab -->
            <div id="widgets-tab" class="chrysoberyl-tab-content <?php echo $active_tab === 'widgets' ? 'active' : ''; ?>">
                <div class="chrysoberyl-settings-section">
                    <h2 class="chrysoberyl-section-title">
                        <span class="dashicons dashicons-welcome-widgets-menus"></span>
                        <?php _e('Widget Visibility Settings', 'chrysoberyl'); ?>
                    </h2>
                    <p class="chrysoberyl-section-description">
                        <?php _e('เลือก widgets ที่ต้องการให้แสดงใน WordPress Widgets area (Appearance > Widgets)', 'chrysoberyl'); ?>
                    </p>

                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <?php _e('Sidebar on Single Post', 'chrysoberyl'); ?>
                            </th>
                            <td>
                                <label class="chrysoberyl-toggle">
                                    <input type="checkbox" name="chrysoberyl_sidebar_single_post_enabled" value="1" <?php checked($sidebar_single_post_enabled, '1'); ?> />
                                    <span class="toggle-slider"></span>
                                    <span
                                        class="toggle-label"><?php _e('แสดง sidebar ในหน้าบทความ (Single Post)', 'chrysoberyl'); ?></span>
                                </label>
                                <p class="description" style="margin-top: 8px;">
                                    <?php _e('ปิดใช้ถ้าต้องการให้บทความเต็มความกว้างโดยไม่มี sidebar ด้านขวา', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <?php _e('Author Box on Single Post', 'chrysoberyl'); ?>
                            </th>
                            <td>
                                <label class="chrysoberyl-toggle">
                                    <input type="checkbox" name="chrysoberyl_author_box_single_enabled" value="1" <?php checked($author_box_single_enabled, '1'); ?> />
                                    <span class="toggle-slider"></span>
                                    <span
                                        class="toggle-label"><?php _e('แสดงกล่องข้อมูลผู้เขียน (Author info) ด้านล่างเนื้อหาในหน้าบทความ', 'chrysoberyl'); ?></span>
                                </label>
                                <p class="description" style="margin-top: 8px;">
                                    <?php _e('ปิดใช้ถ้าไม่ต้องการแสดง Author box ก่อนส่วนบทความที่เกี่ยวข้อง', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <?php _e('Sidebar on Page', 'chrysoberyl'); ?>
                            </th>
                            <td>
                                <label class="chrysoberyl-toggle">
                                    <input type="checkbox" name="chrysoberyl_sidebar_single_page_enabled" value="1" <?php checked($sidebar_single_page_enabled, '1'); ?> />
                                    <span class="toggle-slider"></span>
                                    <span
                                        class="toggle-label"><?php _e('แสดง sidebar ในหน้าคงที่ (Page เช่น Privacy Policy, เกี่ยวกับเรา)', 'chrysoberyl'); ?></span>
                                </label>
                                <p class="description" style="margin-top: 8px;">
                                    <?php _e('ปิดใช้ถ้าต้องการให้หน้าคงที่เต็มความกว้างโดยไม่มี sidebar ด้านขวา', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <?php _e('Sidebar on Homepage', 'chrysoberyl'); ?>
                            </th>
                            <td>
                                <label class="chrysoberyl-toggle">
                                    <input type="hidden" name="chrysoberyl_sidebar_home_enabled" value="0" />
                                    <input type="checkbox" name="chrysoberyl_sidebar_home_enabled" value="1" <?php checked($sidebar_home_enabled, '1'); ?> />
                                    <span class="toggle-slider"></span>
                                    <span
                                        class="toggle-label"><?php _e('แสดง sidebar (widget) ในหน้าแรก', 'chrysoberyl'); ?></span>
                                </label>
                                <p class="description" style="margin-top: 8px;">
                                    <?php _e('ปิดใช้ถ้าต้องการให้หน้าแรกเต็มความกว้างโดยไม่มี widget ด้านขวา (Popular Posts, Recent Posts, Trending Tags)', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <?php _e('Sidebar on Archive', 'chrysoberyl'); ?>
                            </th>
                            <td>
                                <label class="chrysoberyl-toggle">
                                    <input type="hidden" name="chrysoberyl_sidebar_archive_enabled" value="0" />
                                    <input type="checkbox" name="chrysoberyl_sidebar_archive_enabled" value="1" <?php checked($sidebar_archive_enabled, '1'); ?> />
                                    <span class="toggle-slider"></span>
                                    <span
                                        class="toggle-label"><?php _e('แสดง sidebar (widget) ในหน้า Archive', 'chrysoberyl'); ?></span>
                                </label>
                                <p class="description" style="margin-top: 8px;">
                                    <?php _e('ปิดใช้ถ้าต้องการให้หน้า Archive (หมวดหมู่, แท็ก, ผู้เขียน ฯลฯ) เต็มความกว้างโดยไม่มี widget ด้านขวา', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <?php _e('Available Widgets', 'chrysoberyl'); ?>
                            </th>
                            <td>
                                <p class="description" style="margin-bottom: 10px;">
                                    <?php _e('ลากเพื่อเรียงลำดับการแสดงผล • เลือกติ๊กเพื่อเปิดใช้', 'chrysoberyl'); ?>
                                </p>
                                <input type="hidden" name="chrysoberyl_widgets_order" id="chrysoberyl_widgets_order"
                                    value="<?php echo esc_attr(implode(',', chrysoberyl_get_widgets_order())); ?>" />
                                <fieldset id="chrysoberyl-widgets-sortable" class="chrysoberyl-widgets-grid"
                                    style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; max-width: 800px;">
                                    <legend class="screen-reader-text">
                                        <span><?php _e('Select widgets to enable', 'chrysoberyl'); ?></span>
                                    </legend>
                                    <?php
                                    $widget_descriptions = array(
                                        'popular_posts' => __('แสดงบทความยอดนิยมตามจำนวน views', 'chrysoberyl'),
                                        'recent_posts' => __('แสดงบทความล่าสุด', 'chrysoberyl'),
                                        'trending_tags' => __('แสดง tags ที่มาแรง (Trending tags)', 'chrysoberyl'),
                                        'related_posts' => __('บทความที่เกี่ยวข้องตามหมวดหมู่/แท็ก (เหมาะกับหน้า single)', 'chrysoberyl'),
                                        'categories' => __('รายการหมวดหมู่พร้อมจำนวนบทความ', 'chrysoberyl'),
                                        'search' => __('ช่องค้นหาใน sidebar', 'chrysoberyl'),
                                        'social_follow' => __('ปุ่มติดตาม Facebook, Twitter, Line, YouTube ฯลฯ', 'chrysoberyl'),
                                        'most_commented' => __('บทความที่มีความเห็นมากที่สุด', 'chrysoberyl'),
                                        'archive' => __('อาร์คิฟแยกตามเดือน', 'chrysoberyl'),
                                        'custom_html' => __('พื้นที่โฆษณาหรือ HTML กำหนดเอง', 'chrysoberyl'),
                                    );
                                    foreach (chrysoberyl_get_widgets_order() as $widget_key):
                                        if (!isset($available_widgets[$widget_key])) {
                                            continue;
                                        }
                                        $widget_name = $available_widgets[$widget_key];
                                        $desc = isset($widget_descriptions[$widget_key]) ? $widget_descriptions[$widget_key] : '';
                                        ?>
                                        <label class="chrysoberyl-widget-item"
                                            data-widget-key="<?php echo esc_attr($widget_key); ?>"
                                            style="display: flex; align-items: flex-start; gap: 10px; margin: 0; padding: 12px; background: #f9f9f9; border-radius: 4px; border-left: 3px solid #2271b1; cursor: move;">
                                            <span class="dashicons dashicons-move"
                                                style="color: #72777c; flex-shrink: 0; margin-top: 2px;"
                                                aria-hidden="true"></span>
                                            <span style="flex: 1;">
                                                <input type="checkbox" name="chrysoberyl_enabled_widgets[]"
                                                    value="<?php echo esc_attr($widget_key); ?>" <?php checked(in_array($widget_key, $enabled_widgets, true)); ?> />
                                                <strong><?php echo esc_html($widget_name); ?></strong>
                                                <?php if ($desc): ?>
                                                    <p class="description" style="margin: 5px 0 0 25px; color: #646970;">
                                                        <?php echo esc_html($desc); ?>
                                                    </p>
                                                <?php endif; ?>
                                            </span>
                                        </label>
                                    <?php endforeach; ?>
                                </fieldset>
                                <p class="description" style="margin-top: 15px;">
                                    <strong><?php _e('หมายเหตุ:', 'chrysoberyl'); ?></strong><br>
                                    <?php _e('• Widgets ที่ถูกเลือกจะแสดงใน Appearance > Widgets และสามารถเพิ่มไปยัง Widget Areas ต่างๆ ได้', 'chrysoberyl'); ?><br>
                                    <?php _e('• Widgets ที่ไม่ถูกเลือกจะไม่แสดงใน Widgets area แต่โค้ดยังคงทำงานอยู่', 'chrysoberyl'); ?><br>
                                    <?php _e('• การเปลี่ยนแปลงจะมีผลหลังจากบันทึกการตั้งค่า', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Floating Left Ad (Skyscraper)', 'chrysoberyl'); ?></th>
                            <?php
                            $floating_left_ad_enabled = get_option('chrysoberyl_floating_left_ad_enabled', '0');
                            $floating_left_ad_size = get_option('chrysoberyl_floating_left_ad_size', '160x600');
                            $floating_left_ad_content = get_option('chrysoberyl_floating_left_ad_content', '');
                            ?>
                            <td>
                                <p>
                                    <label>
                                        <input type="checkbox" name="chrysoberyl_floating_left_ad_enabled" value="1" <?php checked($floating_left_ad_enabled, '1'); ?> />
                                        <?php _e('เปิดใช้โฆษณาแบบลอยติดด้านซ้าย (ขนาด 120x600 หรือ 160x600) แสดงเฉพาะหน้า single post', 'chrysoberyl'); ?>
                                    </label>
                                </p>
                                <p style="margin-top: 10px;">
                                    <label
                                        for="chrysoberyl_floating_left_ad_size"><?php _e('ขนาด:', 'chrysoberyl'); ?></label>
                                    <select name="chrysoberyl_floating_left_ad_size" id="chrysoberyl_floating_left_ad_size">
                                        <option value="120x600" <?php selected($floating_left_ad_size, '120x600'); ?>>120
                                            × 600</option>
                                        <option value="160x600" <?php selected($floating_left_ad_size, '160x600'); ?>>160
                                            × 600</option>
                                    </select>
                                </p>
                                <p style="margin-top: 10px;">
                                    <label
                                        for="chrysoberyl_floating_left_ad_content"><?php _e('โค้ดโฆษณา (HTML / สคริปต์ AdSense):', 'chrysoberyl'); ?></label><br>
                                    <textarea name="chrysoberyl_floating_left_ad_content"
                                        id="chrysoberyl_floating_left_ad_content" class="large-text code" rows="6"
                                        style="width: 100%; max-width: 500px;"><?php echo esc_textarea($floating_left_ad_content); ?></textarea>
                                </p>
                                <p class="description">
                                    <?php _e('ถ้าไม่ใส่โค้ด จะแสดงกรอบ placeholder โฆษณา', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Footer Tab -->
            <?php
            $footer_newsletter_enabled = get_option('chrysoberyl_footer_newsletter_enabled', '1');
            $footer_tags_enabled = get_option('chrysoberyl_footer_tags_enabled', '1');
            $footer_copyright_text = get_option('chrysoberyl_footer_copyright_text', '');
            $nav_menus = wp_get_nav_menus();
            $footer_column_type_options = array(
                'sidebar' => __('Sidebar (Widgets)', 'chrysoberyl'),
                'menu' => __('Menu', 'chrysoberyl'),
                'social' => __('Social', 'chrysoberyl'),
            );
            ?>
            <div id="footer-tab" class="chrysoberyl-tab-content <?php echo $active_tab === 'footer' ? 'active' : ''; ?>">
                <div class="chrysoberyl-settings-section">
                    <h2 class="chrysoberyl-section-title">
                        <span class="dashicons dashicons-editor-kitchensink"></span>
                        <?php _e('Footer Settings', 'chrysoberyl'); ?>
                    </h2>
                    <p class="chrysoberyl-section-description">
                        <?php _e('ปรับแต่งส่วน footer: newsletter, tags, คอลัมน์ widget และข้อความ copyright', 'chrysoberyl'); ?>
                    </p>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e('แสดงส่วน Footer', 'chrysoberyl'); ?></th>
                            <td>
                                <?php
                                $footer_menu_section_enabled = get_option('chrysoberyl_footer_menu_section_enabled', '1');
                                $footer_legal_section_enabled = get_option('chrysoberyl_footer_legal_section_enabled', '1');
                                ?>
                                <label class="chrysoberyl-toggle"
                                    style="margin-bottom: 12px; display: flex !important; align-items: center; gap: 12px;">
                                    <input type="hidden" name="chrysoberyl_footer_menu_section_enabled" value="0" />
                                    <input type="checkbox" name="chrysoberyl_footer_menu_section_enabled" value="1" <?php checked($footer_menu_section_enabled, '1'); ?> />
                                    <span class="toggle-slider" style="flex-shrink: 0;"></span>
                                    <span
                                        class="toggle-label"><?php _e('แสดงส่วนเมนู footer (4 คอลัมน์: About, More from us, Support, Subscribe)', 'chrysoberyl'); ?></span>
                                </label>
                                <label class="chrysoberyl-toggle"
                                    style="display: flex !important; align-items: center; gap: 12px;">
                                    <input type="hidden" name="chrysoberyl_footer_legal_section_enabled" value="0" />
                                    <input type="checkbox" name="chrysoberyl_footer_legal_section_enabled" value="1" <?php checked($footer_legal_section_enabled, '1'); ?> />
                                    <span class="toggle-slider" style="flex-shrink: 0;"></span>
                                    <span
                                        class="toggle-label"><?php _e('แสดงแถบ legal (Logo, Copyright, ลิงก์ Sitemap/FAQ/Privacy/Terms)', 'chrysoberyl'); ?></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('ลิงก์โซเชียล (Footer)', 'chrysoberyl'); ?></th>
                            <td>
                                <?php
                                $fb_url = get_option('chrysoberyl_facebook_url', '');
                                $tw_url = get_option('chrysoberyl_twitter_url', '');
                                $ig_url = get_option('chrysoberyl_instagram_url', '');
                                ?>
                                <p class="description" style="margin-bottom: 10px;">
                                    <?php _e('ใส่ URL สำหรับแสดงใน footer (เว้นว่าง = ไม่แสดงไอคอนนั้น)', 'chrysoberyl'); ?>
                                </p>
                                <p><label>Facebook: <input type="url" name="chrysoberyl_facebook_url"
                                            value="<?php echo esc_attr($fb_url); ?>" class="regular-text"
                                            placeholder="https://facebook.com/..." /></label></p>
                                <p><label>Twitter/X: <input type="url" name="chrysoberyl_twitter_url"
                                            value="<?php echo esc_attr($tw_url); ?>" class="regular-text"
                                            placeholder="https://twitter.com/..." /></label></p>
                                <p><label>Instagram: <input type="url" name="chrysoberyl_instagram_url"
                                            value="<?php echo esc_attr($ig_url); ?>" class="regular-text"
                                            placeholder="https://instagram.com/..." /></label></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Newsletter Box', 'chrysoberyl'); ?></th>
                            <td>
                                <label class="chrysoberyl-toggle">
                                    <input type="hidden" name="chrysoberyl_footer_newsletter_enabled" value="0" />
                                    <input type="checkbox" name="chrysoberyl_footer_newsletter_enabled" value="1" <?php checked($footer_newsletter_enabled, '1'); ?> />
                                    <span class="toggle-slider"></span>
                                    <span
                                        class="toggle-label"><?php _e('แสดงกล่องสมัครรับข่าวสาร (Newsletter) ใน footer', 'chrysoberyl'); ?></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Tags Section', 'chrysoberyl'); ?></th>
                            <td>
                                <label class="chrysoberyl-toggle">
                                    <input type="hidden" name="chrysoberyl_footer_tags_enabled" value="0" />
                                    <input type="checkbox" name="chrysoberyl_footer_tags_enabled" value="1" <?php checked($footer_tags_enabled, '1'); ?> />
                                    <span class="toggle-slider"></span>
                                    <span
                                        class="toggle-label"><?php _e('แสดงส่วน tags มาแรงใน footer', 'chrysoberyl'); ?></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Footer Columns (Footer1–Footer4)', 'chrysoberyl'); ?></th>
                            <td>
                                <p class="description" style="margin-bottom: 15px;">
                                    <?php _e('แต่ละคอลัมน์เลือกได้ว่าจะแสดง: Widget (จาก Appearance > Widgets), Menu หรือ Social', 'chrysoberyl'); ?>
                                </p>
                                <?php for ($col = 1; $col <= 4; $col++):
                                    $col_type = get_option('chrysoberyl_footer' . $col . '_type', 'sidebar');
                                    $col_menu = get_option('chrysoberyl_footer' . $col . '_menu', 0);
                                    $col_show_heading = get_option('chrysoberyl_footer' . $col . '_show_heading', '1');
                                    $col_heading_text = get_option('chrysoberyl_footer' . $col . '_heading_text', '');
                                    $col_heading_icon = get_option('chrysoberyl_footer' . $col . '_heading_icon', '');
                                    $footer_heading_icons = array(
                                        '' => __('— ไม่มีไอคอน', 'chrysoberyl'),
                                        'folder-open' => __('โฟลเดอร์ (folder-open)', 'chrysoberyl'),
                                        'info-circle' => __('ข้อมูล (info-circle)', 'chrysoberyl'),
                                        'share-alt' => __('แชร์ (share-alt)', 'chrysoberyl'),
                                        'link' => __('ลิงก์ (link)', 'chrysoberyl'),
                                        'list' => __('รายการ (list)', 'chrysoberyl'),
                                        'sitemap' => __('แผนผัง (sitemap)', 'chrysoberyl'),
                                        'address-book' => __('สมุดที่อยู่ (address-book)', 'chrysoberyl'),
                                        'envelope' => __('อีเมล (envelope)', 'chrysoberyl'),
                                        'phone' => __('โทรศัพท์ (phone)', 'chrysoberyl'),
                                        'map-marker-alt' => __('ตำแหน่ง (map-marker-alt)', 'chrysoberyl'),
                                        'th-large' => __('กริด (th-large)', 'chrysoberyl'),
                                        'bars' => __('เมนู (bars)', 'chrysoberyl'),
                                        'chevron-right' => __('ลูกศร (chevron-right)', 'chrysoberyl'),
                                        'star' => __('ดาว (star)', 'chrysoberyl'),
                                        'heart' => __('หัวใจ (heart)', 'chrysoberyl'),
                                        'bookmark' => __('ที่คั่น (bookmark)', 'chrysoberyl'),
                                    );
                                    ?>
                                    <div class="chrysoberyl-footer-col-block" data-col="<?php echo (int) $col; ?>"
                                        style="margin-bottom: 20px; padding: 12px; background: #f9f9f9; border-radius: 4px; border-left: 3px solid #2271b1;">
                                        <strong><?php echo esc_html(sprintf(__('Footer%d', 'chrysoberyl'), $col)); ?></strong>
                                        <div style="margin-top: 8px;">
                                            <label>
                                                <?php _e('แสดงเป็น:', 'chrysoberyl'); ?>
                                                <select name="chrysoberyl_footer<?php echo (int) $col; ?>_type"
                                                    class="chrysoberyl-footer-type-select" style="margin-left: 6px;">
                                                    <?php foreach ($footer_column_type_options as $val => $label): ?>
                                                        <option value="<?php echo esc_attr($val); ?>" <?php selected($col_type, $val); ?>><?php echo esc_html($label); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </label>
                                        </div>
                                        <?php if (!empty($nav_menus)): ?>
                                            <div class="chrysoberyl-footer-menu-opt"
                                                style="margin-top: 10px; display: <?php echo $col_type === 'menu' ? 'block' : 'none'; ?>;">
                                                <label>
                                                    <?php _e('เลือกเมนู:', 'chrysoberyl'); ?>
                                                    <select name="chrysoberyl_footer<?php echo (int) $col; ?>_menu"
                                                        style="margin-left: 6px; min-width: 200px;">
                                                        <option value="0">— <?php _e('เลือกเมนู', 'chrysoberyl'); ?> —</option>
                                                        <?php foreach ($nav_menus as $menu): ?>
                                                            <option value="<?php echo esc_attr($menu->term_id); ?>" <?php selected($col_menu, $menu->term_id); ?>>
                                                                <?php echo esc_html($menu->name); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </label>
                                            </div>
                                        <?php endif; ?>
                                        <div class="chrysoberyl-footer-sidebar-hint"
                                            style="margin-top: 10px; padding: 8px 10px; background: #f0f6fc; border-radius: 4px; display: <?php echo $col_type === 'sidebar' ? 'block' : 'none'; ?>;">
                                            <p class="description" style="margin: 0;">
                                                <?php _e('เนื้อหา: ไปที่ Appearance → Widgets แล้วเลือกพื้นที่ Footer1–Footer4 เพื่อเพิ่ม/จัดเรียง Widget ที่ต้องการ (Custom HTML, Categories, Social Follow ฯลฯ)', 'chrysoberyl'); ?>
                                            </p>
                                        </div>
                                        <div class="chrysoberyl-footer-heading-opt"
                                            style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #e0e0e0; display: <?php echo ($col_type === 'menu' || $col_type === 'sidebar') ? 'block' : 'none'; ?>;">
                                            <p style="margin: 0 0 6px; font-weight: 600;">
                                                <?php _e('หัวข้อและไอคอน', 'chrysoberyl'); ?>
                                            </p>
                                            <label class="chrysoberyl-toggle" style="display: inline-flex; margin-right: 15px;">
                                                <input type="hidden"
                                                    name="chrysoberyl_footer<?php echo (int) $col; ?>_show_heading" value="0" />
                                                <input type="checkbox"
                                                    name="chrysoberyl_footer<?php echo (int) $col; ?>_show_heading" value="1"
                                                    <?php checked($col_show_heading, '1'); ?> />
                                                <span class="toggle-slider"></span>
                                                <span class="toggle-label"><?php _e('แสดงหัวข้อ', 'chrysoberyl'); ?></span>
                                            </label>
                                            <label style="display: inline-block; margin-top: 6px;">
                                                <?php _e('ข้อความหัวข้อ:', 'chrysoberyl'); ?>
                                                <input type="text"
                                                    name="chrysoberyl_footer<?php echo (int) $col; ?>_heading_text"
                                                    value="<?php echo esc_attr($col_heading_text); ?>" class="regular-text"
                                                    style="margin-left: 6px; min-width: 180px;"
                                                    placeholder="<?php echo esc_attr__('เช่น หมวดหมู่, เกี่ยวกับเรา', 'chrysoberyl'); ?>" />
                                            </label>
                                            <label style="display: inline-block; margin-top: 6px; margin-left: 15px;">
                                                <?php _e('ไอคอนหัวข้อ:', 'chrysoberyl'); ?>
                                                <select name="chrysoberyl_footer<?php echo (int) $col; ?>_heading_icon"
                                                    style="margin-left: 6px; min-width: 200px;">
                                                    <?php foreach ($footer_heading_icons as $icon_val => $icon_label): ?>
                                                        <option value="<?php echo esc_attr($icon_val); ?>" <?php selected($col_heading_icon, $icon_val); ?>>
                                                            <?php echo esc_html($icon_label); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </label>
                                            <p class="description" style="margin: 6px 0 0;">
                                                <?php _e('เว้นว่าง = ใช้ชื่อเมนู (Menu) หรือหัวข้อตามคอลัมน์ (Sidebar)', 'chrysoberyl'); ?>
                                            </p>
                                        </div>
                                        <div class="chrysoberyl-footer-social-hint"
                                            style="margin-top: 10px; padding: 8px 10px; background: #fff8e5; border-radius: 4px; display: <?php echo $col_type === 'social' ? 'block' : 'none'; ?>;">
                                            <p class="description" style="margin: 0;">
                                                <strong><?php _e('เมื่อเลือก Social', 'chrysoberyl'); ?></strong> —
                                                <?php _e('จะแสดงปุ่มโซเชียล (Facebook, Twitter, Instagram, YouTube). ลิงก์ปัจจุบันเป็นตัวอย่าง (#). ถ้าต้องการกำหนด URL จริง ให้เลือก "Sidebar (Widgets)" แล้วไปที่ Appearance → Widgets → Footer นี้ แล้วใส่ Widget "Chrysoberyl - Theme: Social Follow" แล้วกรอกลิงก์ใน Widget', 'chrysoberyl'); ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endfor; ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label
                                    for="chrysoberyl_footer_copyright_text"><?php _e('Copyright Text', 'chrysoberyl'); ?></label>
                            </th>
                            <td>
                                <textarea name="chrysoberyl_footer_copyright_text" id="chrysoberyl_footer_copyright_text"
                                    class="large-text" rows="3"
                                    style="width: 100%; max-width: 500px;"><?php echo esc_textarea($footer_copyright_text); ?></textarea>
                                <p class="description">
                                    <?php _e('ข้อความด้านซ้ายของแถบ copyright (เว้นว่าง = ใช้ค่าเริ่มต้น). ใช้ {year} สำหรับปี, {sitename} สำหรับชื่อเว็บ', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Footer Copyright Menu', 'chrysoberyl'); ?></th>
                            <td>
                                <p class="description">
                                    <?php _e('เมนูทางขวาของแถบ copyright: ไปที่ Appearance → Menus แล้วกำหนดเมนูให้ตำแหน่ง "Footer Copyright Menu"', 'chrysoberyl'); ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="chrysoberyl-settings-footer">
                <?php submit_button(__('Save Settings', 'chrysoberyl'), 'primary large', 'chrysoberyl_save_settings', false); ?>
            </div>
        </form>
    </div>

    <style>
        /* Chrysoberyl - Theme Settings Styles */
        .chrysoberyl-settings-wrap {
            max-width: 1200px;
        }

        .chrysoberyl-nav-tabs {
            margin: 20px 0 0;
            border-bottom: 2px solid #c3c4c7;
            display: flex;
            flex-wrap: wrap;
            gap: 0;
        }

        .chrysoberyl-nav-tabs .nav-tab {
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            border-bottom: 3px solid transparent;
            background: transparent;
            margin-right: 5px;
            margin-bottom: -2px;
            transition: all 0.3s ease;
        }

        .chrysoberyl-nav-tabs .nav-tab:hover {
            background: #f0f0f1;
            border-bottom-color: #2271b1;
        }

        .chrysoberyl-nav-tabs .nav-tab-active {
            border-bottom-color: #2271b1;
            color: #2271b1;
        }

        .chrysoberyl-nav-tabs .nav-tab .dashicons {
            margin-right: 5px;
            vertical-align: middle;
        }

        .chrysoberyl-tab-content {
            display: none;
            background: #fff;
            border: 1px solid #c3c4c7;
            border-top: none;
            padding: 20px;
            margin-top: -1px;
        }

        .chrysoberyl-tab-content.active {
            display: block;
        }

        .chrysoberyl-settings-section {
            margin-bottom: 30px;
        }

        .chrysoberyl-section-title {
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 10px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f1;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chrysoberyl-section-title .dashicons {
            color: #2271b1;
        }

        .chrysoberyl-section-description {
            color: #646970;
            margin: 0 0 20px;
            font-size: 14px;
        }

        /* Logo Upload */
        .chrysoberyl-logo-preview {
            margin-bottom: 15px;
            padding: 15px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            display: inline-block;
        }

        .chrysoberyl-logo-preview img {
            max-width: 200px;
            height: auto;
            display: block;
        }

        .chrysoberyl-logo-placeholder {
            width: 200px;
            height: 100px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #f0f0f1;
            border: 2px dashed #c3c4c7;
            border-radius: 4px;
            color: #646970;
        }

        .chrysoberyl-logo-placeholder .dashicons {
            font-size: 32px;
            width: 32px;
            height: 32px;
            margin-bottom: 5px;
        }

        .chrysoberyl-logo-placeholder p {
            margin: 0;
            font-size: 12px;
        }

        .chrysoberyl-logo-actions {
            display: flex;
            gap: 10px;
        }

        .chrysoberyl-logo-actions .button .dashicons {
            margin-right: 5px;
        }

        /* Radio Options */
        .chrysoberyl-radio-group {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .chrysoberyl-radio-option {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            border: 2px solid #ddd;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #fff;
            min-width: 200px;
        }

        .chrysoberyl-radio-option:hover {
            border-color: #2271b1;
            background: #f0f6fc;
        }

        .chrysoberyl-radio-option input[type="radio"] {
            margin-right: 12px;
        }

        .chrysoberyl-radio-option input[type="radio"]:checked+.radio-label {
            color: #2271b1;
        }

        .chrysoberyl-radio-option:has(input:checked) {
            border-color: #2271b1;
            background: #f0f6fc;
        }

        .radio-label {
            display: flex;
            flex-direction: column;
        }

        .radio-label strong {
            font-size: 14px;
            margin-bottom: 3px;
        }

        .radio-label small {
            font-size: 12px;
            color: #646970;
        }

        /* Toggle Switch */
        .chrysoberyl-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .chrysoberyl-toggle input[type="checkbox"] {
            display: none;
        }

        .toggle-slider {
            position: relative;
            width: 50px;
            height: 26px;
            background: #c3c4c7;
            border-radius: 13px;
            transition: background 0.3s ease;
        }

        .toggle-slider::before {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            background: #fff;
            border-radius: 50%;
            top: 3px;
            left: 3px;
            transition: transform 0.3s ease;
        }

        .chrysoberyl-toggle input:checked+.toggle-slider {
            background: #2271b1;
        }

        .chrysoberyl-toggle input:checked+.toggle-slider::before {
            transform: translateX(24px);
        }

        /* Platforms Grid */
        .chrysoberyl-platforms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .chrysoberyl-platform-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #fff;
            text-align: center;
        }

        .chrysoberyl-platform-item:hover {
            border-color: #2271b1;
            background: #f0f6fc;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .chrysoberyl-platform-item input[type="checkbox"] {
            display: none;
        }

        .chrysoberyl-platform-item:has(input:checked) {
            border-color: #2271b1;
            background: #f0f6fc;
        }

        .platform-icon {
            font-size: 32px;
            margin-bottom: 10px;
            color: #646970;
            transition: color 0.3s ease;
        }

        .chrysoberyl-platform-item:has(input:checked) .platform-icon {
            color: #2271b1;
        }

        .platform-name {
            font-size: 13px;
            font-weight: 500;
            color: #1d2327;
        }

        /* Positions Grid */
        .chrysoberyl-positions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .chrysoberyl-position-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #fff;
        }

        .chrysoberyl-position-item:hover {
            border-color: #2271b1;
            background: #f0f6fc;
        }

        .chrysoberyl-position-item input[type="checkbox"] {
            margin: 0;
        }

        .chrysoberyl-position-item:has(input:checked) {
            border-color: #2271b1;
            background: #f0f6fc;
        }

        .position-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0f0f1;
            border-radius: 50%;
            color: #646970;
        }

        .chrysoberyl-position-item:has(input:checked) .position-icon {
            background: #2271b1;
            color: #fff;
        }

        .position-label {
            display: flex;
            flex-direction: column;
        }

        .position-label strong {
            font-size: 14px;
            margin-bottom: 3px;
        }

        .position-label small {
            font-size: 12px;
            color: #646970;
        }

        /* Settings Footer */
        .chrysoberyl-settings-footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #c3c4c7;
        }

        @media (max-width: 782px) {

            .chrysoberyl-platforms-grid,
            .chrysoberyl-positions-grid {
                grid-template-columns: 1fr;
            }

            .chrysoberyl-radio-group {
                flex-direction: column;
            }
        }

        .chrysoberyl-widgets-grid .chrysoberyl-widget-item-placeholder {
            min-height: 52px;
            background: #f0f0f1;
            border: 1px dashed #c3c4c7;
            border-radius: 4px;
        }

        @media (max-width: 782px) {
            .chrysoberyl-widgets-grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>

    <script type="text/javascript">
        (function ($) {
            'use strict';

            var logoUploader;

            // Wait for wp.media to be available
            function initLogoUploader() {
                if (typeof wp === 'undefined' || typeof wp.media === 'undefined') {
                    setTimeout(initLogoUploader, 100);
                    return;
                }

                $('#chrysoberyl_upload_logo_btn').off('click').on('click', function (e) {
                    e.preventDefault();

                    // If the uploader object has already been created, reopen it
                    if (logoUploader) {
                        logoUploader.open();
                        return;
                    }

                    // Create the media uploader
                    logoUploader = wp.media({
                        title: '<?php echo esc_js(__('Choose Logo', 'chrysoberyl')); ?>',
                        button: {
                            text: '<?php echo esc_js(__('Use this logo', 'chrysoberyl')); ?>'
                        },
                        library: {
                            type: 'image'
                        },
                        multiple: false
                    });

                    // When an image is selected, run a callback
                    logoUploader.on('select', function () {
                        var attachment = logoUploader.state().get('selection').first().toJSON();
                        $('#chrysoberyl_logo').val(attachment.id);
                        $('#chrysoberyl_logo_preview').html('<img src="' + attachment.url + '" style="max-width: 200px; height: auto; display: block; margin-bottom: 10px;" />');
                        $('#chrysoberyl_upload_logo_btn').text('<?php echo esc_js(__('Change Logo', 'chrysoberyl')); ?>');
                        if ($('#chrysoberyl_remove_logo_btn').length === 0) {
                            $('#chrysoberyl_upload_logo_btn').after('<button type="button" class="button" id="chrysoberyl_remove_logo_btn" style="margin-left: 10px;"><?php echo esc_js(__('Remove Logo', 'chrysoberyl')); ?></button>');
                        }
                    });

                    // Open the uploader
                    logoUploader.open();
                });

                // Remove logo
                $(document).off('click', '#chrysoberyl_remove_logo_btn').on('click', '#chrysoberyl_remove_logo_btn', function (e) {
                    e.preventDefault();
                    $('#chrysoberyl_logo').val('');
                    $('#chrysoberyl_logo_preview').html('');
                    $('#chrysoberyl_upload_logo_btn').text('<?php echo esc_js(__('Upload Logo', 'chrysoberyl')); ?>');
                    $(this).remove();
                });
            }

            // Initialize when DOM is ready
            $(document).ready(function () {
                initLogoUploader();

                // Tab switching
                $('.chrysoberyl-nav-tabs .nav-tab').on('click', function (e) {
                    e.preventDefault();
                    var targetTab = $(this).data('tab');

                    // Update hidden input
                    $('#chrysoberyl_active_tab').val(targetTab);

                    // Update URL hash without triggering hashchange
                    if (history.pushState) {
                        history.pushState(null, null, '#' + targetTab);
                    } else {
                        window.location.hash = '#' + targetTab;
                    }

                    // Update nav tabs
                    $('.chrysoberyl-nav-tabs .nav-tab').removeClass('nav-tab-active');
                    $(this).addClass('nav-tab-active');

                    // Update tab content
                    $('.chrysoberyl-tab-content').removeClass('active');
                    $('#' + targetTab + '-tab').addClass('active');
                });

                // Restore active tab on page load
                // First check URL hash
                var hash = window.location.hash.replace('#', '');
                var activeTab = hash || $('#chrysoberyl_active_tab').val() || 'general';

                // Update hidden input
                $('#chrysoberyl_active_tab').val(activeTab);

                // Switch to the correct tab
                if (activeTab && activeTab !== 'general') {
                    $('.chrysoberyl-nav-tabs .nav-tab[data-tab="' + activeTab + '"]').trigger('click');
                }

                // Handle hash changes (when clicking tabs)
                $(window).on('hashchange', function () {
                    var newHash = window.location.hash.replace('#', '');
                    if (newHash) {
                        var tabElement = $('.chrysoberyl-nav-tabs .nav-tab[data-tab="' + newHash + '"]');
                        if (tabElement.length) {
                            tabElement.trigger('click');
                        }
                    }
                });

                // Widget list: sortable and sync order to hidden input
                if ($('#chrysoberyl-widgets-sortable').length && $.fn.sortable) {
                    $('#chrysoberyl-widgets-sortable').sortable({
                        items: 'label.chrysoberyl-widget-item',
                        placeholder: 'chrysoberyl-widget-item-placeholder',
                        tolerance: 'pointer',
                        opacity: 0.8,
                        update: function () {
                            var order = [];
                            $('#chrysoberyl-widgets-sortable .chrysoberyl-widget-item').each(function () {
                                var key = $(this).data('widget-key');
                                if (key) order.push(key);
                            });
                            $('#chrysoberyl_widgets_order').val(order.join(','));
                        }
                    });
                }

                // Footer column: show/hide options based on "Display as" (type) selection
                function chrysoberylFooterColToggle(block) {
                    var type = block.find('.chrysoberyl-footer-type-select').val();
                    block.find('.chrysoberyl-footer-menu-opt').toggle(type === 'menu');
                    block.find('.chrysoberyl-footer-sidebar-hint').toggle(type === 'sidebar');
                    block.find('.chrysoberyl-footer-heading-opt').toggle(type === 'menu' || type === 'sidebar');
                    block.find('.chrysoberyl-footer-social-hint').toggle(type === 'social');
                }
                $('.chrysoberyl-footer-col-block').each(function () {
                    chrysoberylFooterColToggle($(this));
                });
                $(document).on('change', '.chrysoberyl-footer-type-select', function () {
                    chrysoberylFooterColToggle($(this).closest('.chrysoberyl-footer-col-block'));
                });
            });
        })(jQuery);
    </script>
    <?php
}

/**
 * Register Custom Post Types
 * FAQ: chrysoberyl_faq for FAQ page (Questions & Answers).
 */
function chrysoberyl_register_post_types()
{
    $faq_labels = array(
        'name'               => _x( 'FAQs', 'post type general name', 'chrysoberyl' ),
        'singular_name'      => _x( 'FAQ', 'post type singular name', 'chrysoberyl' ),
        'menu_name'          => _x( 'FAQs', 'admin menu', 'chrysoberyl' ),
        'add_new'            => _x( 'Add New', 'FAQ', 'chrysoberyl' ),
        'add_new_item'       => __( 'Add New FAQ', 'chrysoberyl' ),
        'edit_item'          => __( 'Edit FAQ', 'chrysoberyl' ),
        'new_item'           => __( 'New FAQ', 'chrysoberyl' ),
        'view_item'          => __( 'View FAQ', 'chrysoberyl' ),
        'search_items'       => __( 'Search FAQs', 'chrysoberyl' ),
        'not_found'          => __( 'No FAQs found.', 'chrysoberyl' ),
        'not_found_in_trash' => __( 'No FAQs found in Trash.', 'chrysoberyl' ),
    );
    register_post_type( 'chrysoberyl_faq', array(
        'labels'              => $faq_labels,
        'public'              => false,
        'publicly_queryable'  => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_icon'           => 'dashicons-editor-help',
        'menu_position'       => 26,
        'supports'            => array( 'title', 'editor', 'page-attributes' ),
        'has_archive'         => false,
        'rewrite'             => false,
        'capability_type'     => 'post',
    ) );
}
add_action('init', 'chrysoberyl_register_post_types');

/**
 * Flush rewrite rules on theme activation
 */
function chrysoberyl_flush_rewrite_rules()
{
    chrysoberyl_register_post_types();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'chrysoberyl_flush_rewrite_rules');

/**
 * Register Custom Taxonomies for Custom Post Types
 * faq_category: group FAQs (About Us, Usage, Join Us, etc.)
 */
function chrysoberyl_register_taxonomies()
{
    $faq_cat_labels = array(
        'name'          => _x( 'FAQ Categories', 'taxonomy general name', 'chrysoberyl' ),
        'singular_name' => _x( 'FAQ Category', 'taxonomy singular name', 'chrysoberyl' ),
        'search_items'  => __( 'Search FAQ Categories', 'chrysoberyl' ),
        'all_items'     => __( 'All FAQ Categories', 'chrysoberyl' ),
        'edit_item'     => __( 'Edit FAQ Category', 'chrysoberyl' ),
        'add_new_item'  => __( 'Add New FAQ Category', 'chrysoberyl' ),
    );
    register_taxonomy( 'faq_category', array( 'chrysoberyl_faq' ), array(
        'labels'            => $faq_cat_labels,
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'rewrite'           => array( 'slug' => 'faq-category' ),
    ) );
}
add_action('init', 'chrysoberyl_register_taxonomies');

/**
 * Create default FAQ categories if none exist (mockup: เกี่ยวกับเรา, การใช้งาน, ร่วมงานกับเรา)
 */
function chrysoberyl_maybe_create_default_faq_categories() {
    if ( get_option( 'chrysoberyl_faq_categories_created', false ) ) {
        return;
    }
    $terms = get_terms( array( 'taxonomy' => 'faq_category', 'hide_empty' => false ) );
    if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
        update_option( 'chrysoberyl_faq_categories_created', true );
        return;
    }
    $defaults = array(
        'about-us'   => _x( 'About Us', 'FAQ category', 'chrysoberyl' ),
        'usage'      => _x( 'Usage', 'FAQ category', 'chrysoberyl' ),
        'join-us'    => _x( 'Join Us', 'FAQ category', 'chrysoberyl' ),
    );
    foreach ( $defaults as $slug => $name ) {
        if ( ! term_exists( $slug, 'faq_category' ) ) {
            wp_insert_term( $name, 'faq_category', array( 'slug' => $slug ) );
        }
    }
    update_option( 'chrysoberyl_faq_categories_created', true );
}
add_action( 'init', 'chrysoberyl_maybe_create_default_faq_categories', 20 );

/**
 * Add custom columns to post type admin lists
 */
function chrysoberyl_add_custom_columns($columns)
{
    // Add thumbnail column at the beginning for all post types
    $new_columns = array();

    // Add checkbox column first
    if (isset($columns['cb'])) {
        $new_columns['cb'] = $columns['cb'];
        unset($columns['cb']);
    }

    // Add thumbnail column after checkbox
    $new_columns['featured_image'] = __('Image', 'chrysoberyl');

    // Merge with existing columns
    $columns = array_merge($new_columns, $columns);

    // Add custom columns for posts
    if (!isset($_GET['post_type']) || $_GET['post_type'] === 'post') {
        $columns['post_views'] = __('Views', 'chrysoberyl');
    }

    return $columns;
}
add_filter('manage_posts_columns', 'chrysoberyl_add_custom_columns');
add_filter('manage_pages_columns', 'chrysoberyl_add_custom_columns');

/**
 * Display custom column content
 */
function chrysoberyl_custom_column_content($column, $post_id)
{
    switch ($column) {
        case 'featured_image':
            if (has_post_thumbnail($post_id)) {
                $thumbnail = get_the_post_thumbnail($post_id, array(60, 60), array('style' => 'width: 60px; height: 60px; object-fit: cover; border-radius: 4px;'));
                $edit_link = get_edit_post_link($post_id);
                echo '<a href="' . esc_url($edit_link) . '">' . $thumbnail . '</a>';
            } else {
                echo '<span style="display: inline-block; width: 60px; height: 60px; background: #f0f0f0; border-radius: 4px; text-align: center; line-height: 60px; color: #999; font-size: 11px;">' . __('No image', 'chrysoberyl') . '</span>';
            }
            break;

        case 'post_views':
            $views = get_post_meta($post_id, 'post_views', true);
            echo $views ? number_format_i18n($views) : '0';
            break;
    }
}
add_action('manage_posts_custom_column', 'chrysoberyl_custom_column_content', 10, 2);
add_action('manage_pages_custom_column', 'chrysoberyl_custom_column_content', 10, 2);
