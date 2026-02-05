<?php
/**
 * Template part for displaying breadcrumb navigation
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */


// Check if breadcrumbs are globally enabled
$breadcrumb_enabled = get_option('chrysoberyl_breadcrumb_enabled', '1');
if ($breadcrumb_enabled !== '1') {
    return;
}

// Check if breadcrumbs should show on current page type
$should_show = false;

if (is_front_page()) {
    $should_show = get_option('chrysoberyl_breadcrumb_show_on_home', '0') === '1';
} elseif (is_single()) {
    $should_show = get_option('chrysoberyl_breadcrumb_show_on_single', '1') === '1';
} elseif (is_page()) {
    $should_show = get_option('chrysoberyl_breadcrumb_show_on_page', '1') === '1';
} elseif (is_category()) {
    $should_show = get_option('chrysoberyl_breadcrumb_show_on_category', '1') === '1';
} elseif (( is_home() && ! is_front_page() ) || (int) get_query_var( 'chrysoberyl_all_posts' ) === 1) {
    $should_show = get_option('chrysoberyl_breadcrumb_show_on_category', '1') === '1';
} elseif (is_tag()) {
    $should_show = get_option('chrysoberyl_breadcrumb_show_on_tag', '1') === '1';
} elseif (is_author()) {
    $should_show = get_option('chrysoberyl_breadcrumb_show_on_author', '1') === '1';
} elseif (is_date()) {
    $should_show = get_option('chrysoberyl_breadcrumb_show_on_date', '1') === '1';
} elseif (is_search()) {
    $should_show = get_option('chrysoberyl_breadcrumb_show_on_search', '1') === '1';
} elseif (is_404()) {
    $should_show = get_option('chrysoberyl_breadcrumb_show_on_404', '1') === '1';
}

if (!$should_show) {
    return;
}

$show_on_mobile = get_option('chrysoberyl_breadcrumb_show_on_mobile', '1') === '1';
$breadcrumb_visibility_class = $show_on_mobile ? 'block' : 'hidden md:block';

?>

<?php
// ตรง mockup: archive/single ใช้ text-google-blue สำหรับรายการปัจจุบัน
$breadcrumb_current_class = (is_archive() || is_single()) ? 'text-google-blue font-medium' : 'text-gray-800 font-medium';
?>
<nav class="chrysoberyl-breadcrumb <?php echo esc_attr($breadcrumb_visibility_class); ?> text-sm" aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center gap-x-1 md:gap-x-2 text-google-gray-500">
        <li class="inline-flex items-center shrink-0">
            <a href="<?php echo esc_url(home_url('/')); ?>"
                class="inline-flex items-center gap-1.5 py-1 rounded hover:text-google-blue transition-colors">
                <?php _e('หน้าแรก', 'chrysoberyl'); ?>
            </a>
        </li>

        <?php if (is_category()): ?>
            <?php
            $cat_title = single_cat_title('', false);
            $cat_title = strip_tags($cat_title);
            $cat_title = preg_replace('/^(หมวดหมู่|Category):\s*/i', '', $cat_title);
            $cat_title = trim($cat_title);
            ?>
            <li class="inline-flex items-center shrink-0" aria-current="page">
                <span class="chrysoberyl-breadcrumb-sep mx-1" aria-hidden="true">/</span>
                <span
                    class="chrysoberyl-breadcrumb-current <?php echo esc_attr($breadcrumb_current_class); ?>"><?php echo esc_html($cat_title); ?></span>
            </li>
        <?php elseif (is_tag()): ?>
            <li class="inline-flex items-center shrink-0" aria-current="page">
                <span class="chrysoberyl-breadcrumb-sep mx-1" aria-hidden="true">/</span>
                <span
                    class="chrysoberyl-breadcrumb-current <?php echo esc_attr($breadcrumb_current_class); ?>"><?php echo esc_html(strip_tags(single_tag_title('', false))); ?></span>
            </li>
        <?php elseif (is_author()): ?>
            <li class="inline-flex items-center shrink-0" aria-current="page">
                <span class="chrysoberyl-breadcrumb-sep mx-1" aria-hidden="true">/</span>
                <span
                    class="chrysoberyl-breadcrumb-current <?php echo esc_attr($breadcrumb_current_class); ?>"><?php echo esc_html(get_the_author()); ?></span>
            </li>
        <?php elseif (is_date()): ?>
            <li class="inline-flex items-center shrink-0" aria-current="page">
                <span class="chrysoberyl-breadcrumb-sep mx-1" aria-hidden="true">/</span>
                <span
                    class="chrysoberyl-breadcrumb-current <?php echo esc_attr($breadcrumb_current_class); ?>"><?php echo esc_html(get_the_archive_title()); ?></span>
            </li>
        <?php elseif (( is_home() && ! is_front_page() ) || (int) get_query_var( 'chrysoberyl_all_posts' ) === 1): ?>
            <li class="inline-flex items-center shrink-0" aria-current="page">
                <span class="chrysoberyl-breadcrumb-sep mx-1" aria-hidden="true">/</span>
                <span
                    class="chrysoberyl-breadcrumb-current <?php echo esc_attr($breadcrumb_current_class); ?>"><?php echo esc_html(_x('ข่าวล่าสุด', 'breadcrumb', 'chrysoberyl')); ?></span>
            </li>
        <?php elseif (is_single()): ?>
            <?php
            $categories = get_the_category();
            if (!empty($categories)):
                $category = $categories[0];
                $category_name = strip_tags($category->name);
                $category_name = preg_replace('/^(หมวดหมู่|Category):\s*/i', '', $category_name);
                $category_name = trim($category_name);
                ?>
                <li class="inline-flex items-center shrink-0">
                    <span class="chrysoberyl-breadcrumb-sep mx-1" aria-hidden="true">/</span>
                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>"
                        class="py-1 rounded hover:text-google-blue transition-colors text-google-gray-500">
                        <?php echo esc_html($category_name); ?>
                    </a>
                </li>
            <?php endif; ?>
            <li class="inline-flex items-center min-w-0 flex-1" aria-current="page">
                <span class="chrysoberyl-breadcrumb-sep mx-1" aria-hidden="true">/</span>
                <span
                    class="chrysoberyl-breadcrumb-current chrysoberyl-breadcrumb-title <?php echo esc_attr($breadcrumb_current_class); ?>"><?php echo esc_html(strip_tags(get_the_title())); ?></span>
            </li>
        <?php elseif (is_page()): ?>
            <li class="inline-flex items-center min-w-0 flex-1" aria-current="page">
                <span class="chrysoberyl-breadcrumb-sep"><i class="fas fa-chevron-right" aria-hidden="true"></i></span>
                <span
                    class="chrysoberyl-breadcrumb-current chrysoberyl-breadcrumb-title text-gray-800 font-medium"><?php echo esc_html(strip_tags(get_the_title())); ?></span>
            </li>
        <?php elseif (is_search()): ?>
            <li class="inline-flex items-center shrink-0" aria-current="page">
                <span class="chrysoberyl-breadcrumb-sep"><i class="fas fa-chevron-right" aria-hidden="true"></i></span>
                <span
                    class="chrysoberyl-breadcrumb-current text-gray-800 font-medium"><?php _e('ผลการค้นหา', 'chrysoberyl'); ?></span>
            </li>
        <?php elseif (is_404()): ?>
            <li class="inline-flex items-center shrink-0" aria-current="page">
                <span class="chrysoberyl-breadcrumb-sep"><i class="fas fa-chevron-right" aria-hidden="true"></i></span>
                <span
                    class="chrysoberyl-breadcrumb-current text-gray-800 font-medium"><?php _e('ไม่พบหน้า', 'chrysoberyl'); ?></span>
            </li>
        <?php endif; ?>
    </ol>
</nav>