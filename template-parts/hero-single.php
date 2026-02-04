<?php
/**
 * Template part: Hero เดี่ยว 1 รายการ (mockup: index.html Hero Section)
 * แหล่งข้อมูล: โพสต์ Sticky แล้ว fallback โพสต์ล่าสุด
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

$hero_id = function_exists( 'chrysoberyl_get_hero_single_post_id' ) ? chrysoberyl_get_hero_single_post_id() : null;
if ( ! $hero_id ) {
    return;
}
$hero_post = get_post( $hero_id );
if ( ! $hero_post || $hero_post->post_status !== 'publish' ) {
    return;
}

setup_postdata( $hero_post );
$permalink   = chrysoberyl_fix_url( get_permalink( $hero_post ) );
$title       = get_the_title( $hero_post );
$excerpt     = has_excerpt( $hero_post ) ? get_the_excerpt( $hero_post ) : wp_trim_words( get_post_field( 'post_content', $hero_post ), 28 );
$thumb_url   = get_the_post_thumbnail_url( $hero_post->ID, 'large' );
$thumb_url   = $thumb_url ?: '';

$categories = get_the_category( $hero_post->ID );
$first_cat   = ! empty( $categories ) ? $categories[0] : null;
$cat_link    = $first_cat ? get_category_link( $first_cat->term_id ) : '';
$cat_name    = $first_cat ? $first_cat->name : '';
?>

<section class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center mb-10" aria-label="<?php esc_attr_e( 'Featured story', 'chrysoberyl' ); ?>">
    <!-- Hero Content (ซ้าย) -->
    <div class="lg:col-span-5 order-2 lg:order-1">
        <?php if ( $cat_name && $cat_link ) : ?>
            <a href="<?php echo esc_url( $cat_link ); ?>" class="inline-block mb-6">
                <span class="text-google-blue font-bold text-sm tracking-wider uppercase"><?php echo esc_html( $cat_name ); ?></span>
            </a>
        <?php endif; ?>
        <h1 class="text-4xl md:text-5xl lg:text-[56px] leading-[1.1] font-normal text-google-gray mb-6 tracking-tight">
            <a href="<?php echo esc_url( $permalink ); ?>" class="text-google-gray hover:text-google-blue transition-colors"><?php echo esc_html( $title ); ?></a>
        </h1>
        <?php if ( $excerpt ) : ?>
            <p class="text-lg md:text-xl text-google-gray-500 leading-relaxed mb-8 font-light">
                <?php echo esc_html( $excerpt ); ?>
            </p>
        <?php endif; ?>
        <div class="flex items-center gap-4">
            <a href="<?php echo esc_url( $permalink ); ?>"
                class="inline-flex items-center justify-center px-8 py-3 bg-google-blue text-white font-medium rounded-pill hover:bg-blue-700 transition-all shadow-md hover:shadow-lg">
                <?php _e( 'Read Story', 'chrysoberyl' ); ?>
            </a>
        </div>
    </div>
    <!-- Hero Image (ขวา) -->
    <div class="lg:col-span-7 order-1 lg:order-2">
        <a href="<?php echo esc_url( $permalink ); ?>"
            class="block overflow-hidden rounded-card relative aspect-video shadow-none hover:shadow-card-hover transition-shadow duration-300">
            <?php if ( $thumb_url ) : ?>
                <img src="<?php echo esc_url( $thumb_url ); ?>"
                    alt="<?php echo esc_attr( $title ); ?>"
                    class="w-full h-full object-cover"
                    fetchpriority="high"
                    decoding="async">
            <?php else : ?>
                <span class="w-full h-full flex items-center justify-center bg-google-gray-100 text-google-gray-500 text-lg"><?php esc_html_e( 'No image', 'chrysoberyl' ); ?></span>
            <?php endif; ?>
        </a>
    </div>
</section>

<?php
wp_reset_postdata();
