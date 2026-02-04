<?php
/**
 * The template for displaying archive pages (category, tag, author, date).
 * ตรง mockup category / tag / author — breadcrumb, hero (H1 + คำอธิบาย), กริด + sidebar ตามหลังบ้าน
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

get_header();

$show_sidebar_archive = ( get_option( 'chrysoberyl_sidebar_archive_enabled', '1' ) === '1' );

// หัวข้อ H1 สำหรับ hero
$archive_h1 = '';
if ( is_category() ) {
    $archive_h1 = single_cat_title( '', false );
} elseif ( is_tag() ) {
    $archive_h1 = single_tag_title( '', false );
} elseif ( is_author() ) {
    $archive_h1 = get_the_author();
} elseif ( is_date() ) {
    $archive_h1 = get_the_archive_title();
} else {
    $archive_h1 = __( 'Archive', 'chrysoberyl' );
}
$archive_h1 = strip_tags( $archive_h1 );
$archive_h1 = preg_replace( '/^(หมวดหมู่|Category|แท็ก|Tag|ผู้เขียน|Author|Archive):\s*/i', '', $archive_h1 );
$archive_h1 = trim( $archive_h1 );
?>

<main id="main-content" class="flex-grow w-full">
    <div class="container mx-auto px-4 md:px-6 lg:px-8 max-w-[1248px] pb-20">
        <!-- Archive Hero (mockup: category / tag / author) -->
        <section class="mb-12">
            <?php get_template_part( 'template-parts/breadcrumb' ); ?>
            <h1 class="text-4xl md:text-5xl font-normal text-google-gray mb-4 mt-4"><?php echo esc_html( $archive_h1 ); ?></h1>
            <?php
            $description = get_the_archive_description();
            if ( $description ) :
                ?>
                <p class="text-lg text-google-gray-500 max-w-3xl"><?php echo wp_kses_post( $description ); ?></p>
            <?php endif; ?>
            <?php if ( is_tag() ) : ?>
                <?php
                $tag_obj = get_queried_object();
                if ( $tag_obj && isset( $tag_obj->count ) ) :
                    ?>
                    <p class="text-sm text-google-gray-500 mt-2"><span class="font-medium text-google-gray"><?php echo (int) $tag_obj->count; ?></span> <?php _e( 'บทความ', 'chrysoberyl' ); ?></p>
                <?php endif; ?>
            <?php endif; ?>
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
                    // Show Pagination
                    get_template_part( 'template-parts/pagination' );
                else :
                    // Show Load More Button
                    ?>
                    <div class="mt-10 text-center">
                        <button
                            class="bg-white border-2 border-gray-300 text-gray-700 font-medium py-3 px-8 rounded-full hover:bg-gray-50 hover:text-black hover:border-accent transition-all duration-200 shadow-sm hover:shadow-md w-full md:w-auto btn-primary"
                            id="load-more-btn"
                            data-page="1"
                            <?php if ( is_category() ) : ?>
                                data-cat-id="<?php echo esc_attr( get_queried_object_id() ); ?>"
                            <?php elseif ( is_tag() ) : ?>
                                data-tag-id="<?php echo esc_attr( get_queried_object_id() ); ?>"
                            <?php endif; ?>
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
                <!-- Sidebar: แสดงเฉพาะ widget ที่เปิดใช้และลำดับจากหลังบ้าน (Theme Options > Widgets) -->
                <?php get_template_part( 'template-parts/sidebar-archive' ); ?>
            <?php endif; ?>

        </div>
    </div>
</main>

<?php
get_footer();
?>
