<?php
/**
 * Template for displaying "all posts" archive (layout like category archive, content from all categories).
 * Used when visiting /blog/ or the Posts page when theme uses this layout.
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

get_header();

$show_sidebar_archive = ( get_option( 'chrysoberyl_sidebar_archive_enabled', '1' ) === '1' );

$locale = get_locale();
$is_en = ( $locale === 'en_US' || strpos( $locale, 'en_' ) === 0 );
$archive_h1 = $is_en ? __( 'Latest News', 'chrysoberyl' ) : __( 'ข่าวล่าสุด', 'chrysoberyl' );
$archive_description = $is_en ? __( 'Latest articles from all categories', 'chrysoberyl' ) : __( 'บทความล่าสุดจากทุกหมวดหมู่', 'chrysoberyl' );
?>

<main id="main-content" class="flex-grow w-full">
    <div class="container mx-auto px-4 md:px-6 lg:px-8 max-w-[1248px] pb-20">
        <!-- Archive Hero (same as category/tag archive) -->
        <section class="mb-12">
            <?php get_template_part( 'template-parts/breadcrumb' ); ?>
            <h1 class="text-4xl md:text-5xl font-normal text-google-gray mb-4 mt-4"><?php echo esc_html( $archive_h1 ); ?></h1>
            <p class="text-lg text-google-gray-500 max-w-3xl"><?php echo esc_html( $archive_description ); ?></p>
        </section>

        <div class="flex flex-col lg:flex-row gap-10">
            <!-- Main Feed -->
            <div class="<?php echo $show_sidebar_archive ? 'lg:w-2/3' : 'lg:w-full'; ?>">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16" id="news-grid">
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
                        get_template_part( 'template-parts/pagination' );
                    else :
                        ?>
                        <div class="mt-10 text-center">
                            <button
                                class="bg-white border-2 border-gray-300 text-gray-700 font-medium py-3 px-8 rounded-full hover:bg-gray-50 hover:text-black hover:border-accent transition-all duration-200 shadow-sm hover:shadow-md w-full md:w-auto btn-primary"
                                id="load-more-btn"
                                data-page="1"
                                aria-label="<?php _e( 'โหลดข่าวเพิ่มเติม', 'chrysoberyl' ); ?>">
                                <span class="relative z-10"><?php _e( 'โหลดข่าวเพิ่มเติม', 'chrysoberyl' ); ?></span>
                                <i class="fas fa-arrow-down ml-2 relative z-10"></i>
                            </button>
                        </div>
                        <?php
                    endif;
                endif;
                ?>
                </div>

            <?php if ( $show_sidebar_archive ) : ?>
                <?php get_template_part( 'template-parts/sidebar-archive' ); ?>
            <?php endif; ?>

        </div>
    </div>
</main>

<?php
get_footer();
