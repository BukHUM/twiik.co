<?php
/**
 * Demo Data Import System
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Include demo posts data
require_once __DIR__ . '/demo-posts.php';

/**
 * Get demo data definitions
 */
function chrysoberyl_get_demo_data()
{
    return array(
        'categories' => array(
            'label' => __('Categories', 'chrysoberyl'),
            'items' => array(
                array('name' => 'Technology', 'slug' => 'technology', 'description' => 'Latest news and updates from the tech world.', 'color' => '#1a73e8'),
                array('name' => 'Business', 'slug' => 'business', 'description' => 'Business news, market updates, and economic insights.', 'color' => '#34a853'),
                array('name' => 'Lifestyle', 'slug' => 'lifestyle', 'description' => 'Tips and trends for modern living.', 'color' => '#ea4335'),
                array('name' => 'Entertainment', 'slug' => 'entertainment', 'description' => 'Movies, music, games, and pop culture.', 'color' => '#fbbc04'),
                array('name' => 'Health', 'slug' => 'health', 'description' => 'Health tips, wellness advice, and medical news.', 'color' => '#4285f4'),
            ),
        ),
        'posts' => array(
            'label' => __('Sample Posts', 'chrysoberyl'),
            'items' => chrysoberyl_get_demo_posts_data(),
        ),
        'pages' => array(
            'label' => __('Pages', 'chrysoberyl'),
            'items' => array(
                array(
                    'title' => 'About Us',
                    'slug' => 'about-us',
                    'content' => '<h2>Our Story</h2><p>Welcome to Chrysoberyl, your trusted source for the latest news and updates.</p><h2>Our Mission</h2><p>We are dedicated to delivering accurate, timely, and engaging content.</p>',
                ),
                array(
                    'title' => 'Contact Us',
                    'slug' => 'contact-us',
                    'content' => '<h2>Get in Touch</h2><p>We\'d love to hear from you!</p><h2>Contact Information</h2><p><strong>Email:</strong> contact@twiik.co<br><strong>Phone:</strong> +1 (555) 123-4567</p>',
                ),
                array(
                    'title' => 'FAQ',
                    'slug' => 'faq',
                    'content' => '<h2>Frequently Asked Questions</h2><h3>How can I subscribe?</h3><p>Enter your email in the subscription form at the bottom of any page.</p><h3>How do I submit a news tip?</h3><p>Send tips to tips@twiik.co.</p>',
                ),
                array(
                    'title' => 'Terms of Service',
                    'slug' => 'terms-of-service',
                    'content' => '<h2>Terms of Service</h2><p>Last updated: January 2024</p><h3>1. Acceptance of Terms</h3><p>By using this website, you agree to these terms.</p>',
                ),
                array(
                    'title' => 'Privacy Policy',
                    'slug' => 'privacy-policy',
                    'content' => '<h2>Privacy Policy</h2><p>Last updated: January 2024</p><h3>Information We Collect</h3><p>We collect information you provide directly to us.</p>',
                ),
            ),
        ),
        'menus' => array(
            'label' => __('Navigation Menus', 'chrysoberyl'),
            'items' => array(
                array(
                    'name' => 'Primary Menu',
                    'slug' => 'demo-primary-menu',
                    'location' => 'primary',
                    'items' => array(
                        array('title' => 'Home', 'type' => 'custom', 'url' => home_url('/')),
                        array('title' => 'Technology', 'type' => 'category', 'slug' => 'technology'),
                        array('title' => 'Business', 'type' => 'category', 'slug' => 'business'),
                        array('title' => 'Lifestyle', 'type' => 'category', 'slug' => 'lifestyle'),
                        array('title' => 'Entertainment', 'type' => 'category', 'slug' => 'entertainment'),
                        array('title' => 'Health', 'type' => 'category', 'slug' => 'health'),
                    ),
                ),
                array(
                    'name' => 'Footer Menu',
                    'slug' => 'demo-footer-menu',
                    'location' => 'footer',
                    'items' => array(
                        array('title' => 'About Us', 'type' => 'page', 'slug' => 'about-us'),
                        array('title' => 'Contact Us', 'type' => 'page', 'slug' => 'contact-us'),
                        array('title' => 'FAQ', 'type' => 'page', 'slug' => 'faq'),
                        array('title' => 'Privacy Policy', 'type' => 'page', 'slug' => 'privacy-policy'),
                        array('title' => 'Terms of Service', 'type' => 'page', 'slug' => 'terms-of-service'),
                    ),
                ),
            ),
        ),
    );
}

/**
 * Check if demo item exists
 */
function chrysoberyl_demo_item_exists($type, $slug)
{
    switch ($type) {
        case 'categories':
            $term = get_term_by('slug', $slug, 'category');
            return $term ? $term : false;
        case 'posts':
            $post = get_page_by_path($slug, OBJECT, 'post');
            return $post ? $post : false;
        case 'pages':
            $page = get_page_by_path($slug, OBJECT, 'page');
            return $page ? $page : false;
        case 'menus':
            $menu = wp_get_nav_menu_object($slug);
            return $menu ? $menu : false;
    }
    return false;
}

/**
 * Download image from URL and set as featured image
 */
function chrysoberyl_download_and_set_featured_image($image_url, $post_id)
{
    if (!function_exists('media_sideload_image')) {
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
    }

    $tmp = download_url($image_url);
    if (is_wp_error($tmp))
        return false;

    $file_array = array(
        'name' => 'demo-' . basename(parse_url($image_url, PHP_URL_PATH)) . '.jpg',
        'tmp_name' => $tmp,
    );

    $attachment_id = media_handle_sideload($file_array, $post_id);
    if (is_wp_error($attachment_id)) {
        @unlink($file_array['tmp_name']);
        return false;
    }

    set_post_thumbnail($post_id, $attachment_id);
    return $attachment_id;
}

/**
 * Import demo data item
 */
function chrysoberyl_import_demo_item($type, $item, $overwrite = false)
{
    $result = array('success' => false, 'message' => '', 'id' => 0);

    switch ($type) {
        case 'categories':
            $existing = get_term_by('slug', $item['slug'], 'category');
            if ($existing && !$overwrite) {
                $result['message'] = sprintf(__('Category "%s" already exists.', 'chrysoberyl'), $item['name']);
                $result['id'] = $existing->term_id;
                return $result;
            }
            if ($existing && $overwrite) {
                wp_update_term($existing->term_id, 'category', array('name' => $item['name'], 'description' => $item['description']));
                if (!empty($item['color']))
                    update_term_meta($existing->term_id, 'category_color', $item['color']);
                $result['success'] = true;
                $result['message'] = sprintf(__('Category "%s" updated.', 'chrysoberyl'), $item['name']);
                $result['id'] = $existing->term_id;
                return $result;
            }
            $term = wp_insert_term($item['name'], 'category', array('slug' => $item['slug'], 'description' => $item['description']));
            if (is_wp_error($term)) {
                $result['message'] = $term->get_error_message();
            } else {
                if (!empty($item['color']))
                    update_term_meta($term['term_id'], 'category_color', $item['color']);
                $result['success'] = true;
                $result['message'] = sprintf(__('Category "%s" created.', 'chrysoberyl'), $item['name']);
                $result['id'] = $term['term_id'];
            }
            break;

        case 'posts':
            $existing = get_page_by_path($item['slug'], OBJECT, 'post');
            if ($existing && !$overwrite) {
                $result['message'] = sprintf(__('Post "%s" already exists.', 'chrysoberyl'), $item['title']);
                $result['id'] = $existing->ID;
                return $result;
            }
            $content = $item['content'];
            // Append backlink for posts
            $content .= '<p>Source: <a href="https://twiik.co" target="_blank" rel="noopener noreferrer">twiik.co</a></p>';

            $post_data = array(
                'post_title' => $item['title'],
                'post_name' => $item['slug'],
                'post_content' => $content,
                'post_excerpt' => $item['excerpt'],
                'post_status' => 'publish',
                'post_type' => 'post',
            );
            if ($existing && $overwrite) {
                $post_data['ID'] = $existing->ID;
                $post_id = wp_update_post($post_data);
            } else {
                $post_id = wp_insert_post($post_data);
            }
            if (is_wp_error($post_id)) {
                $result['message'] = $post_id->get_error_message();
            } else {
                if (!empty($item['category'])) {
                    $cat = get_term_by('slug', $item['category'], 'category');
                    if ($cat)
                        wp_set_post_categories($post_id, array($cat->term_id));
                }
                if (!empty($item['tags'])) {
                    wp_set_post_tags($post_id, $item['tags']);
                }
                if (!empty($item['image'])) {
                    chrysoberyl_download_and_set_featured_image($item['image'], $post_id);
                }
                $result['success'] = true;
                $result['message'] = $existing && $overwrite
                    ? sprintf(__('Post "%s" updated.', 'chrysoberyl'), $item['title'])
                    : sprintf(__('Post "%s" created.', 'chrysoberyl'), $item['title']);
                $result['id'] = $post_id;
            }
            break;

        case 'pages':
            $existing = get_page_by_path($item['slug'], OBJECT, 'page');
            if ($existing && !$overwrite) {
                $result['message'] = sprintf(__('Page "%s" already exists.', 'chrysoberyl'), $item['title']);
                $result['id'] = $existing->ID;
                return $result;
            }
            $page_data = array(
                'post_title' => $item['title'],
                'post_name' => $item['slug'],
                'post_content' => $item['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
            );
            if ($existing && $overwrite) {
                $page_data['ID'] = $existing->ID;
                $page_id = wp_update_post($page_data);
            } else {
                $page_id = wp_insert_post($page_data);
            }
            if (is_wp_error($page_id)) {
                $result['message'] = $page_id->get_error_message();
            } else {
                $result['success'] = true;
                $result['message'] = $existing && $overwrite
                    ? sprintf(__('Page "%s" updated.', 'chrysoberyl'), $item['title'])
                    : sprintf(__('Page "%s" created.', 'chrysoberyl'), $item['title']);
                $result['id'] = $page_id;
            }
            break;

        case 'menus':
            $existing = wp_get_nav_menu_object($item['slug']);
            if ($existing && !$overwrite) {
                $result['message'] = sprintf(__('Menu "%s" already exists.', 'chrysoberyl'), $item['name']);
                $result['id'] = $existing->term_id;
                return $result;
            }

            // Delete existing menu if overwriting
            if ($existing && $overwrite) {
                wp_delete_nav_menu($existing->term_id);
            }

            // Create new menu
            $menu_id = wp_create_nav_menu($item['name']);
            if (is_wp_error($menu_id)) {
                $result['message'] = $menu_id->get_error_message();
                return $result;
            }

            // Update menu slug
            wp_update_term($menu_id, 'nav_menu', array('slug' => $item['slug']));

            // Add menu items
            if (!empty($item['items'])) {
                foreach ($item['items'] as $menu_item) {
                    $menu_item_data = array(
                        'menu-item-title' => $menu_item['title'],
                        'menu-item-status' => 'publish',
                    );

                    switch ($menu_item['type']) {
                        case 'custom':
                            $menu_item_data['menu-item-type'] = 'custom';
                            $menu_item_data['menu-item-url'] = $menu_item['url'];
                            break;
                        case 'category':
                            $cat = get_term_by('slug', $menu_item['slug'], 'category');
                            if ($cat) {
                                $menu_item_data['menu-item-type'] = 'taxonomy';
                                $menu_item_data['menu-item-object'] = 'category';
                                $menu_item_data['menu-item-object-id'] = $cat->term_id;
                            }
                            break;
                        case 'page':
                            $page = get_page_by_path($menu_item['slug']);
                            if ($page) {
                                $menu_item_data['menu-item-type'] = 'post_type';
                                $menu_item_data['menu-item-object'] = 'page';
                                $menu_item_data['menu-item-object-id'] = $page->ID;
                            }
                            break;
                    }

                    wp_update_nav_menu_item($menu_id, 0, $menu_item_data);
                }
            }

            // Assign to location
            if (!empty($item['location'])) {
                $locations = get_theme_mod('nav_menu_locations');
                if (!is_array($locations))
                    $locations = array();
                $locations[$item['location']] = $menu_id;
                set_theme_mod('nav_menu_locations', $locations);
            }

            $result['success'] = true;
            $result['message'] = $existing && $overwrite
                ? sprintf(__('Menu "%s" updated.', 'chrysoberyl'), $item['name'])
                : sprintf(__('Menu "%s" created.', 'chrysoberyl'), $item['name']);
            $result['id'] = $menu_id;
            break;
    }
    return $result;
}

/**
 * Delete demo data item
 */
function chrysoberyl_delete_demo_item($type, $item)
{
    $result = array('success' => false, 'message' => '');

    switch ($type) {
        case 'categories':
            $term = get_term_by('slug', $item['slug'], 'category');
            if (!$term) {
                $result['message'] = sprintf(__('Category "%s" not found.', 'chrysoberyl'), $item['name']);
                return $result;
            }
            $deleted = wp_delete_term($term->term_id, 'category');
            if (is_wp_error($deleted)) {
                $result['message'] = $deleted->get_error_message();
            } else {
                $result['success'] = true;
                $result['message'] = sprintf(__('Category "%s" deleted.', 'chrysoberyl'), $item['name']);
            }
            break;

        case 'posts':
            $post = get_page_by_path($item['slug'], OBJECT, 'post');
            if (!$post) {
                $result['message'] = sprintf(__('Post "%s" not found.', 'chrysoberyl'), $item['title']);
                return $result;
            }
            // Delete featured image first
            $thumbnail_id = get_post_thumbnail_id($post->ID);
            if ($thumbnail_id) {
                wp_delete_attachment($thumbnail_id, true);
            }
            $deleted = wp_delete_post($post->ID, true);
            if ($deleted) {
                $result['success'] = true;
                $result['message'] = sprintf(__('Post "%s" deleted.', 'chrysoberyl'), $item['title']);
            } else {
                $result['message'] = __('Failed to delete post.', 'chrysoberyl');
            }
            break;

        case 'pages':
            $page = get_page_by_path($item['slug'], OBJECT, 'page');
            if (!$page) {
                $result['message'] = sprintf(__('Page "%s" not found.', 'chrysoberyl'), $item['title']);
                return $result;
            }
            $deleted = wp_delete_post($page->ID, true);
            if ($deleted) {
                $result['success'] = true;
                $result['message'] = sprintf(__('Page "%s" deleted.', 'chrysoberyl'), $item['title']);
            } else {
                $result['message'] = __('Failed to delete page.', 'chrysoberyl');
            }
            break;

        case 'menus':
            $menu = wp_get_nav_menu_object($item['slug']);
            if (!$menu) {
                $result['message'] = sprintf(__('Menu "%s" not found.', 'chrysoberyl'), $item['name']);
                return $result;
            }
            $deleted = wp_delete_nav_menu($menu->term_id);
            if ($deleted) {
                $result['success'] = true;
                $result['message'] = sprintf(__('Menu "%s" deleted.', 'chrysoberyl'), $item['name']);
            } else {
                $result['message'] = __('Failed to delete menu.', 'chrysoberyl');
            }
            break;
    }
    return $result;
}

/**
 * Handle import AJAX request
 */
function chrysoberyl_ajax_import_demo_data()
{
    check_ajax_referer('chrysoberyl_import_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('Permission denied.', 'chrysoberyl')));
    }

    $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';
    $index = isset($_POST['index']) ? intval($_POST['index']) : 0;
    $overwrite = isset($_POST['overwrite']) && $_POST['overwrite'] === 'true';

    $demo_data = chrysoberyl_get_demo_data();
    if (!isset($demo_data[$type]) || !isset($demo_data[$type]['items'][$index])) {
        wp_send_json_error(array('message' => __('Invalid data type or index.', 'chrysoberyl')));
    }

    $item = $demo_data[$type]['items'][$index];
    $result = chrysoberyl_import_demo_item($type, $item, $overwrite);
    wp_send_json_success($result);
}
add_action('wp_ajax_chrysoberyl_import_demo_data', 'chrysoberyl_ajax_import_demo_data');

/**
 * Handle delete AJAX request
 */
function chrysoberyl_ajax_delete_demo_data()
{
    check_ajax_referer('chrysoberyl_import_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('Permission denied.', 'chrysoberyl')));
    }

    $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';
    $index = isset($_POST['index']) ? intval($_POST['index']) : 0;

    $demo_data = chrysoberyl_get_demo_data();
    if (!isset($demo_data[$type]) || !isset($demo_data[$type]['items'][$index])) {
        wp_send_json_error(array('message' => __('Invalid data type or index.', 'chrysoberyl')));
    }

    $item = $demo_data[$type]['items'][$index];
    $result = chrysoberyl_delete_demo_item($type, $item);
    wp_send_json_success($result);
}
add_action('wp_ajax_chrysoberyl_delete_demo_data', 'chrysoberyl_ajax_delete_demo_data');

/**
 * Import Data admin page
 */
function chrysoberyl_import_demo_page()
{
    $demo_data = chrysoberyl_get_demo_data();
    ?>
    <div class="wrap">
        <h1><?php _e('Import Demo Data', 'chrysoberyl'); ?></h1>
        <p class="description">
            <?php _e('Select the demo content you want to import or delete. Existing items will be marked.', 'chrysoberyl'); ?>
        </p>

        <div id="import-messages" style="margin: 20px 0;"></div>

        <div style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <button type="button" class="button"
                onclick="toggleDemoPages(true)"><?php _e('Select All', 'chrysoberyl'); ?></button>
            <button type="button" class="button"
                onclick="toggleDemoPages(false)"><?php _e('Unselect All', 'chrysoberyl'); ?></button>
            <label style="margin-left: 20px;">
                <input type="checkbox" id="overwrite-existing" value="1">
                <?php _e('Overwrite existing items', 'chrysoberyl'); ?>
            </label>
        </div>

        <?php foreach ($demo_data as $type => $data): ?>
            <div class="card" style="max-width: 100%; margin-bottom: 20px;">
                <h2 style="margin-top: 0;">
                    <label>
                        <input type="checkbox" class="demo-type-toggle" data-type="<?php echo esc_attr($type); ?>" checked>
                        <?php echo esc_html($data['label']); ?>
                        <span style="color: #666; font-weight: normal;">(<?php echo count($data['items']); ?> items)</span>
                    </label>
                </h2>

                <table class="wp-list-table widefat fixed striped" style="margin-top: 10px;">
                    <thead>
                        <tr>
                            <th style="width: 40px;"></th>
                            <th><?php _e('Name', 'chrysoberyl'); ?></th>
                            <th style="width: 180px;"><?php _e('Status', 'chrysoberyl'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['items'] as $index => $item):
                            $name = isset($item['title']) ? $item['title'] : $item['name'];
                            $slug = $item['slug'];
                            $exists = chrysoberyl_demo_item_exists($type, $slug);
                            ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="demo-item" data-type="<?php echo esc_attr($type); ?>"
                                        data-index="<?php echo esc_attr($index); ?>" data-name="<?php echo esc_attr($name); ?>"
                                        <?php checked(!$exists); ?>>
                                </td>
                                <td>
                                    <strong><?php echo esc_html($name); ?></strong>
                                    <br><code style="font-size: 11px;"><?php echo esc_html($slug); ?></code>
                                </td>
                                <td id="status-<?php echo esc_attr($type . '-' . $index); ?>">
                                    <?php if ($exists): ?>
                                        <span style="color: #d63638;">
                                            <span class="dashicons dashicons-warning"></span>
                                            <?php _e('Exists', 'chrysoberyl'); ?>
                                        </span>
                                    <?php else: ?>
                                        <span style="color: #00a32a;">
                                            <span class="dashicons dashicons-yes"></span>
                                            <?php _e('Ready', 'chrysoberyl'); ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>

        <p style="display: flex; gap: 10px; flex-wrap: wrap;">
            <button type="button" id="import-btn" class="button button-primary button-hero">
                <span class="dashicons dashicons-download" style="margin-top: 5px;"></span>
                <?php _e('Import Selected', 'chrysoberyl'); ?>
            </button>
            <button type="button" id="delete-btn" class="button button-hero"
                style="background: #d63638; border-color: #d63638; color: #fff;">
                <span class="dashicons dashicons-trash" style="margin-top: 5px;"></span>
                <?php _e('Delete Selected', 'chrysoberyl'); ?>
            </button>
        </p>
    </div>

    <script>
        jQuery(document).ready(function ($) {
            $('.demo-type-toggle').on('change', function () {
                var type = $(this).data('type');
                var checked = $(this).prop('checked');
                $('.demo-item[data-type="' + type + '"]').prop('checked', checked);
            });

            // Import
            $('#import-btn').on('click', function () {
                var $btn = $(this);
                var items = [];
                var overwrite = $('#overwrite-existing').prop('checked');

                $('.demo-item:checked').each(function () {
                    items.push({ type: $(this).data('type'), index: $(this).data('index'), name: $(this).data('name') });
                });

                if (items.length === 0) { alert('<?php echo esc_js(__('Please select items.', 'chrysoberyl')); ?>'); return; }

                $btn.prop('disabled', true).text('<?php echo esc_js(__('Importing...', 'chrysoberyl')); ?>');
                $('#import-messages').html('');

                var queue = items.slice(), results = [];

                function processNext() {
                    if (queue.length === 0) {
                        $btn.prop('disabled', false).html('<span class="dashicons dashicons-download" style="margin-top: 5px;"></span> <?php echo esc_js(__('Import Selected', 'chrysoberyl')); ?>');
                        var success = results.filter(r => r.success).length;
                        $('#import-messages').html('<div class="notice notice-success"><p><?php echo esc_js(__('Done!', 'chrysoberyl')); ?> ' + success + ' <?php echo esc_js(__('imported', 'chrysoberyl')); ?></p></div>');
                        return;
                    }

                    var item = queue.shift();
                    var $status = $('#status-' + item.type + '-' + item.index);
                    $status.html('<span class="spinner is-active" style="float:none;margin:0;"></span>');

                    $.post(ajaxurl, {
                        action: 'chrysoberyl_import_demo_data',
                        nonce: '<?php echo wp_create_nonce('chrysoberyl_import_nonce'); ?>',
                        type: item.type,
                        index: item.index,
                        overwrite: overwrite ? 'true' : 'false'
                    }, function (response) {
                        if (response.success && response.data) {
                            results.push(response.data);
                            var icon = response.data.success ? 'yes-alt' : 'dismiss';
                            var color = response.data.success ? '#00a32a' : '#d63638';
                            $status.html('<span style="color:' + color + '"><span class="dashicons dashicons-' + icon + '"></span> ' + response.data.message + '</span>');
                        }
                        setTimeout(processNext, 100);
                    }).fail(function () { results.push({ success: false }); setTimeout(processNext, 100); });
                }
                processNext();
            });

            // Delete
            $('#delete-btn').on('click', function () {
                if (!confirm('<?php echo esc_js(__('Are you sure you want to delete selected items? This cannot be undone.', 'chrysoberyl')); ?>')) return;

                var $btn = $(this);
                var items = [];

                $('.demo-item:checked').each(function () {
                    items.push({ type: $(this).data('type'), index: $(this).data('index'), name: $(this).data('name') });
                });

                if (items.length === 0) { alert('<?php echo esc_js(__('Please select items.', 'chrysoberyl')); ?>'); return; }

                $btn.prop('disabled', true).text('<?php echo esc_js(__('Deleting...', 'chrysoberyl')); ?>');
                $('#import-messages').html('');

                var queue = items.slice(), results = [];

                function processNext() {
                    if (queue.length === 0) {
                        $btn.prop('disabled', false).html('<span class="dashicons dashicons-trash" style="margin-top: 5px;"></span> <?php echo esc_js(__('Delete Selected', 'chrysoberyl')); ?>');
                        var success = results.filter(r => r.success).length;
                        $('#import-messages').html('<div class="notice notice-warning"><p><?php echo esc_js(__('Done!', 'chrysoberyl')); ?> ' + success + ' <?php echo esc_js(__('deleted', 'chrysoberyl')); ?></p></div>');
                        return;
                    }

                    var item = queue.shift();
                    var $status = $('#status-' + item.type + '-' + item.index);
                    $status.html('<span class="spinner is-active " style="float:none;margin:0;"></span>');

                    $.post(ajaxurl, {
                        action: 'chrysoberyl_delete_demo_data',
                        nonce: '<?php echo wp_create_nonce('chrysoberyl_import_nonce'); ?>',
                        type: item.type,
                        index: item.index
                    }, function (response) {
                        if (response.success && response.data) {
                            results.push(response.data);
                            var icon = response.data.success ? 'yes-alt' : 'dismiss';
                            var color = response.data.success ? '#00a32a' : '#d63638';
                            $status.html('<span style="color:' + color + '"><span class="dashicons dashicons-' + icon + '"></span> ' + response.data.message + '</span>');
                        }
                        setTimeout(processNext, 100);
                    }).fail(function () { results.push({ success: false }); setTimeout(processNext, 100); });
                }
                processNext();
            });
        });

        function toggleDemoPages(selectAll) {
            document.querySelectorAll('.demo-item, .demo-type-toggle').forEach(function (cb) { cb.checked = selectAll; });
        }
    </script>
    <?php
}
