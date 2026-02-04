<?php
/**
 * Template Name: RankMath
 * Template for Rank Math HTML Sitemap page. Google-style design with categorized sections.
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

get_header();
?>

<main id="main-content" class="flex-grow w-full pt-0">

    <!-- Spacer (mockup: h-4 md:h-6 only) -->
    <div class="h-4 md:h-6"></div>

    <div class="container mx-auto px-4 md:px-6 lg:px-8 max-w-[1248px] pb-20">

        <?php
        while (have_posts()):
            the_post();
            ?>

            <!-- Hero Section (mockup: compact spacing) -->
            <section class="sitemap-hero text-center py-12 md:py-16 mb-12">
                <h1 class="text-4xl md:text-5xl font-normal text-google-gray mb-4">
                    <?php the_title(); ?>
                </h1>
                <?php if (has_excerpt()): ?>
                    <p class="text-lg text-google-gray-500">
                        <?php echo get_the_excerpt(); ?>
                    </p>
                <?php else: ?>
                    <p class="text-lg text-google-gray-500">
                        <?php _e( 'All links of Chrysoberyl for easy navigation', 'chrysoberyl' ); ?>
                    </p>
                <?php endif; ?>
            </section>

            <?php
            // Get all published pages
            $all_pages = get_pages(array(
                'post_status' => 'publish',
                'sort_column' => 'menu_order',
                'sort_order' => 'ASC'
            ));

            // Categorize pages
            $main_pages = array();
            $content_pages = array();
            $legal_pages = array();

            foreach ($all_pages as $page) {
                $slug = $page->post_name;

                // Main pages
                if (in_array($slug, array('about', 'contact', 'faq'))) {
                    $main_pages[] = $page;
                }
                // Legal pages
                elseif (in_array($slug, array('privacy', 'terms', 'sitemap')) || strpos($slug, 'privacy') !== false || strpos($slug, 'terms') !== false) {
                    $legal_pages[] = $page;
                }
                // Content pages
                else {
                    $content_pages[] = $page;
                }
            }

            // Get categories and tags
            $categories = get_categories(array('hide_empty' => true));
            $tags = get_tags(array('hide_empty' => true, 'number' => 10));

            // Calculate total pages
            $total_pages = count($all_pages) + count($categories) + count($tags);
            ?>

            <!-- Sitemap Grid -->
            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                <!-- Main Pages -->
                <div class="bg-google-gray-50 rounded-card p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-google-blue rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-medium text-google-gray"><?php _e( 'Main Pages', 'chrysoberyl' ); ?></h2>
                    </div>
                    <ul class="space-y-3">
                        <li>
                            <a href="<?php echo esc_url(home_url('/')); ?>"
                                class="flex items-center gap-2 text-google-gray-500 hover:text-google-blue transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                                <?php _e( 'Home (Homepage)', 'chrysoberyl' ); ?>
                            </a>
                        </li>
                        <?php foreach ($main_pages as $page): ?>
                            <li>
                                <a href="<?php echo esc_url(get_permalink($page->ID)); ?>"
                                    class="flex items-center gap-2 text-google-gray-500 hover:text-google-blue transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                        </path>
                                    </svg>
                                    <?php echo esc_html($page->post_title); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Content Pages -->
                <div class="bg-google-gray-50 rounded-card p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-[#ea4335] rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                </path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-medium text-google-gray"><?php _e( 'Content', 'chrysoberyl' ); ?></h2>
                    </div>
                    <ul class="space-y-3">
                        <?php
                        $blog_url = get_permalink(get_option('page_for_posts')) ?: home_url('/');
                        $category_url = !empty($categories) ? get_category_link($categories[0]->term_id) : $blog_url;
                        ?>
                        <li>
                            <a href="<?php echo esc_url($category_url); ?>"
                                class="flex items-center gap-2 text-google-gray-500 hover:text-google-blue transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                <?php _e( 'Category', 'chrysoberyl' ); ?>
                            </a>
                        </li>
                        <?php
                        $recent_post = get_posts(array('numberposts' => 1, 'post_type' => 'post', 'post_status' => 'publish'));
                        if (!empty($recent_post)): ?>
                            <li>
                                <a href="<?php echo esc_url(get_permalink($recent_post[0]->ID)); ?>"
                                    class="flex items-center gap-2 text-google-gray-500 hover:text-google-blue transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    <?php _e( 'Single Post', 'chrysoberyl' ); ?>
                                </a>
                            </li>
                        <?php endif;
                        if (!empty($tags)): ?>
                            <li>
                                <a href="<?php echo esc_url(get_tag_link($tags[0]->term_id)); ?>"
                                    class="flex items-center gap-2 text-google-gray-500 hover:text-google-blue transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    <?php _e( 'Tag Archive', 'chrysoberyl' ); ?>
                                </a>
                            </li>
                        <?php endif;
                        $first_author = get_users(array('number' => 1, 'orderby' => 'post_count', 'order' => 'DESC', 'who' => 'authors'));
                        if (!empty($first_author)): ?>
                            <li>
                                <a href="<?php echo esc_url(get_author_posts_url($first_author[0]->ID)); ?>"
                                    class="flex items-center gap-2 text-google-gray-500 hover:text-google-blue transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    <?php _e( 'Author', 'chrysoberyl' ); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li>
                            <a href="<?php echo esc_url(home_url('/?s=')); ?>"
                                class="flex items-center gap-2 text-google-gray-500 hover:text-google-blue transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                <?php _e( 'Search Results (Search)', 'chrysoberyl' ); ?>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Legal & Other -->
                <div class="bg-google-gray-50 rounded-card p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-[#34a853] rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-medium text-google-gray"><?php _e( 'Legal & Other', 'chrysoberyl' ); ?></h2>
                    </div>
                    <ul class="space-y-3">
                        <?php
                        $current_page_id = (int) get_queried_object_id();
                        foreach ($legal_pages as $page):
                            $is_current = ( (int) $page->ID === $current_page_id );
                            $link_class = 'flex items-center gap-2 text-google-gray-500 hover:text-google-blue transition-colors';
                            if ( $is_current ) {
                                $link_class .= ' text-google-blue font-medium chrysoberyl-sitemap-current';
                            }
                        ?>
                            <li>
                                <a href="<?php echo esc_url(get_permalink($page->ID)); ?>"
                                    class="<?php echo esc_attr( $link_class ); ?>"
                                    <?php if ( $is_current ) : ?>style="color:#1a73e8!important;font-weight:500;"<?php endif; ?>>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    <span class="chrysoberyl-sitemap-link-text"><?php echo esc_html($page->post_title); ?></span>
                                    <?php if ( $is_current ) : ?>
                                        ← <?php _e( 'You are here', 'chrysoberyl' ); ?>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <li>
                            <a href="<?php echo esc_url(home_url('/page-not-found-404/')); ?>"
                                class="flex items-center gap-2 text-google-gray-500 hover:text-google-blue transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                <?php _e( 'Page not found (404 Error)', 'chrysoberyl' ); ?>
                            </a>
                        </li>
                        <?php foreach ($content_pages as $page): ?>
                            <li>
                                <a href="<?php echo esc_url(get_permalink($page->ID)); ?>"
                                    class="flex items-center gap-2 text-google-gray-500 hover:text-google-blue transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    <?php echo esc_html($page->post_title); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

            </section>

            <!-- Visual Sitemap -->
            <section class="mt-16">
                <h2 class="text-2xl font-normal text-google-gray text-center mb-8">
                    <?php _e( 'Site structure', 'chrysoberyl' ); ?>
                </h2>
                <div class="bg-google-gray-50 rounded-card p-8 overflow-x-auto">
                    <div class="min-w-[600px]">
                        <!-- Tree Structure -->
                        <div class="flex flex-col items-center">
                            <!-- Root -->
                            <a href="<?php echo esc_url(home_url('/')); ?>"
                                class="px-6 py-3 bg-google-blue text-white rounded-lg font-medium shadow-md hover:bg-blue-700 transition-colors">
                                🏠 Homepage
                            </a>

                            <!-- Connector Line -->
                            <div class="w-px h-8 bg-gray-300"></div>

                            <!-- Second Level -->
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-32 h-px bg-gray-300"></div>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div class="w-32 h-px bg-gray-300"></div>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div class="w-32 h-px bg-gray-300"></div>
                                </div>
                            </div>

                            <div class="flex gap-8 flex-wrap justify-center">
                                <!-- Content Branch -->
                                <div class="flex flex-col items-center">
                                    <div class="w-px h-6 bg-gray-300"></div>
                                    <?php if (!empty($categories)): ?>
                                        <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>"
                                            class="px-4 py-2 bg-[#ea4335] text-white rounded-lg text-sm font-medium hover:opacity-90 transition-opacity">
                                            📁 <?php echo esc_html($categories[0]->name); ?>
                                        </a>
                                    <?php else: ?>
                                        <div class="px-4 py-2 bg-[#ea4335] text-white rounded-lg text-sm font-medium">
                                            📁 <?php _e( 'Category', 'chrysoberyl' ); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="w-px h-4 bg-gray-300"></div>
                                    <div class="flex gap-2">
                                        <?php
                                        $recent_posts = get_posts(array('numberposts' => 1));
                                        if (!empty($recent_posts)):
                                            ?>
                                            <a href="<?php echo esc_url(get_permalink($recent_posts[0]->ID)); ?>"
                                                class="px-3 py-1.5 bg-white border border-gray-200 rounded text-xs hover:border-google-blue transition-colors">
                                                <?php _e( 'Single', 'chrysoberyl' ); ?>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (!empty($tags)): ?>
                                            <a href="<?php echo esc_url(get_tag_link($tags[0]->term_id)); ?>"
                                                class="px-3 py-1.5 bg-white border border-gray-200 rounded text-xs hover:border-google-blue transition-colors">
                                                <?php _e( 'Tag', 'chrysoberyl' ); ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- About Branch -->
                                <?php
                                $about_page = get_page_by_path('about');
                                if ($about_page):
                                    ?>
                                    <div class="flex flex-col items-center">
                                        <div class="w-px h-6 bg-gray-300"></div>
                                        <a href="<?php echo esc_url(get_permalink($about_page->ID)); ?>"
                                            class="px-4 py-2 bg-[#fbbc04] text-google-gray rounded-lg text-sm font-medium hover:opacity-90 transition-opacity">
                                            ℹ️ <?php echo esc_html($about_page->post_title); ?>
                                        </a>
                                        <div class="w-px h-4 bg-gray-300"></div>
                                        <div class="flex gap-2">
                                            <?php
                                            $contact_page = get_page_by_path('contact');
                                            $faq_page = get_page_by_path('faq');
                                            if ($contact_page):
                                                ?>
                                                <a href="<?php echo esc_url(get_permalink($contact_page->ID)); ?>"
                                                    class="px-3 py-1.5 bg-white border border-gray-200 rounded text-xs hover:border-google-blue transition-colors">
                                                    <?php echo esc_html($contact_page->post_title); ?>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($faq_page): ?>
                                                <a href="<?php echo esc_url(get_permalink($faq_page->ID)); ?>"
                                                    class="px-3 py-1.5 bg-white border border-gray-200 rounded text-xs hover:border-google-blue transition-colors">
                                                    <?php echo esc_html($faq_page->post_title); ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Utility Branch -->
                                <div class="flex flex-col items-center">
                                    <div class="w-px h-6 bg-gray-300"></div>
                                    <div class="px-4 py-2 bg-[#34a853] text-white rounded-lg text-sm font-medium">
                                        ⚙️ <?php _e( 'Utility', 'chrysoberyl' ); ?>
                                    </div>
                                    <div class="w-px h-4 bg-gray-300"></div>
                                    <div class="flex gap-2">
                                        <a href="<?php echo esc_url(home_url('/?s=')); ?>"
                                            class="px-3 py-1.5 bg-white border border-gray-200 rounded text-xs hover:border-google-blue transition-colors">
                                            <?php _e( 'Search', 'chrysoberyl' ); ?>
                                        </a>
                                        <a href="<?php echo esc_url(home_url('/page-not-found-404/')); ?>"
                                            class="px-3 py-1.5 bg-white border border-gray-200 rounded text-xs hover:border-google-blue transition-colors">
                                            404
                                        </a>
                                    </div>
                                </div>

                                <!-- Legal Branch -->
                                <?php if (!empty($legal_pages)): ?>
                                    <div class="flex flex-col items-center">
                                        <div class="w-px h-6 bg-gray-300"></div>
                                        <div class="px-4 py-2 bg-google-gray-500 text-white rounded-lg text-sm font-medium">
                                            📜 <?php _e( 'Legal', 'chrysoberyl' ); ?>
                                        </div>
                                        <div class="w-px h-4 bg-gray-300"></div>
                                        <div class="flex gap-2">
                                            <?php foreach (array_slice($legal_pages, 0, 2) as $legal_page): ?>
                                                <a href="<?php echo esc_url(get_permalink($legal_page->ID)); ?>"
                                                    class="px-3 py-1.5 bg-white border border-gray-200 rounded text-xs hover:border-google-blue transition-colors">
                                                    <?php echo esc_html($legal_page->post_title); ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Stats -->
            <section class="mt-12 text-center">
                <p class="text-google-gray-500">
                    <?php printf( __( 'Total of %s pages (including Sitemap)', 'chrysoberyl' ), '<span class="text-google-blue font-medium">' . (int) $total_pages . '</span>' ); ?>
                </p>
            </section>

            <?php
        endwhile;
        ?>

    </div>

</main>

<?php get_footer(); ?>