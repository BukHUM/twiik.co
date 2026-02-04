<?php
/**
 * Admin Menu and Settings Pages
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
                                <span class="chrysoberyl-credit-label"><?php _e('เว็บที่ใช้งานจริง:', 'chrysoberyl'); ?></span>
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

        /* Dashboard specific styles embedded here as they are unique to this page */
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
 * Import Demo Data page callback (placeholder)
 */
function chrysoberyl_import_demo_page()
{
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(__('Import Demo Data', 'chrysoberyl')); ?></h1>
        <p><?php esc_html_e('Content coming soon.', 'chrysoberyl'); ?></p>
    </div>
    <?php
}

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
