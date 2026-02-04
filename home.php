<?php
/**
 * The main template file for blog/news archive
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

get_header();

$show_sidebar_home = ( get_option( 'chrysoberyl_sidebar_home_enabled', '1' ) === '1' );
?>

<main id="main-content" class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-0 pb-8 w-full">

    <!-- Hero Section (mockup: เดี่ยว 1 รายการ — Sticky หรือโพสต์ล่าสุด) -->
    <?php get_template_part( 'template-parts/hero-single' ); ?>

    <!-- Category Filters (mockup: pills desktop, dropdown mobile) -->
    <?php get_template_part( 'template-parts/category-filters' ); ?>

    <!-- Latest News Grid -->
    <div class="flex flex-col lg:flex-row gap-10">

        <!-- Main Feed -->
        <div class="<?php echo $show_sidebar_home ? 'lg:w-2/3' : 'lg:w-full'; ?>">
            <?php
            $posts_page_id = (int) get_option( 'page_for_posts' );
            if ( $posts_page_id ) {
                $archive_url = get_permalink( $posts_page_id );
            } else {
                $archive_url = home_url( '/all-posts/' );
            }
            if ( ! $archive_url ) {
                $archive_url = home_url( '/all-posts/' );
            }
            ?>
            <div class="flex justify-between items-end mb-6">
                <h2 class="text-2xl font-bold text-gray-900 border-l-4 border-accent pl-3">
                    <?php _e( 'ข่าวล่าสุด', 'chrysoberyl' ); ?>
                </h2>
                <a href="<?php echo esc_url( $archive_url ); ?>"
                   class="text-sm text-gray-500 hover:text-accent">
                    <?php _e( 'ดูทั้งหมด', 'chrysoberyl' ); ?> <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="<?php echo esc_attr( chrysoberyl_get_home_news_grid_class() ); ?>" id="news-grid">
                <?php
                if ( have_posts() ) :
                    while ( have_posts() ) :
                        the_post();
                        get_template_part( 'template-parts/news-card' );
                    endwhile;
                else :
                    get_template_part( 'template-parts/content', 'none' );
                endif;
                ?>
            </div>

            <?php
            global $wp_query;
            $pagination_type = get_option( 'chrysoberyl_pagination_type', 'load_more' );
            
            if ( $wp_query->max_num_pages > 1 ) :
                if ( $pagination_type === 'pagination' ) :
                    // Show Pagination
                    get_template_part( 'template-parts/pagination' );
                else :
                    // Show Load More Button
                    ?>
                    <div class="mt-20 text-center">
                        <button type="button"
                            class="inline-block px-10 py-3 border border-gray-300 rounded-pill text-google-blue font-medium hover:bg-blue-50 hover:border-blue-200 transition-all w-full md:w-auto"
                            id="load-more-btn"
                            data-page="1"
                            aria-label="<?php esc_attr_e( 'Load more stories', 'chrysoberyl' ); ?>">
                            <?php _e( 'Load more stories', 'chrysoberyl' ); ?>
                        </button>
                    </div>
                    <?php
                endif;
            endif;
            ?>
        </div>

        <?php if ( $show_sidebar_home ) : ?>
            <!-- Sidebar -->
            <?php get_template_part( 'template-parts/sidebar' ); ?>
        <?php endif; ?>

    </div>

</main>

<?php
get_footer();
?>
