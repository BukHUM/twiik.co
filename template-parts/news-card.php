<?php
/**
 * Template part: News article card (mockup index.html Article Card)
 *
 * @package Chrysoberyl
 * @since 1.0.0
 *
 * @var WP_Post $post Current post object
 */

if ( ! isset( $post ) ) {
    global $post;
}

$categories = get_the_category();
$category   = ! empty( $categories ) ? $categories[0] : null;
$permalink  = chrysoberyl_fix_url( get_permalink() );
$title      = get_the_title() ? get_the_title() : get_the_date();
$excerpt_raw = has_excerpt() ? get_the_excerpt() : get_post_field( 'post_content', $post );
$excerpt    = wp_trim_words( $excerpt_raw, 12 );
?>

<article class="flex flex-col h-full group cursor-pointer" role="article" aria-label="<?php echo esc_attr( $title ); ?>"
    onclick="window.location.href='<?php echo esc_url( $permalink ); ?>'">
    <div class="mb-5 overflow-hidden rounded-card relative aspect-[3/2]">
        <?php if ( has_post_thumbnail() ) : ?>
            <?php
            $thumbnail_id = get_post_thumbnail_id();
            if ( $thumbnail_id ) {
                echo wp_get_attachment_image(
                    $thumbnail_id,
                    'chrysoberyl-card',
                    false,
                    array(
                        'class' => 'w-full h-full object-cover transition-transform duration-500 group-hover:scale-105',
                        'alt'   => esc_attr( $title ),
                        'loading' => 'lazy',
                        'srcset' => wp_get_attachment_image_srcset( $thumbnail_id, 'chrysoberyl-card' ),
                        'sizes'  => '(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw',
                    )
                );
            }
            ?>
        <?php else : ?>
            <div class="w-full h-full bg-google-gray-100 flex items-center justify-center text-google-gray-500 text-sm">
                <?php esc_html_e( 'No image', 'chrysoberyl' ); ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="flex flex-col flex-grow">
        <?php if ( $category ) : ?>
            <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="inline-block mb-3 text-google-blue font-bold text-xs uppercase tracking-wider hover:underline" onclick="event.stopPropagation();">
                <?php echo esc_html( $category->name ); ?>
            </a>
        <?php endif; ?>
        <h3 class="text-2xl font-normal text-google-gray mb-3 leading-snug group-hover:text-google-blue transition-colors">
            <a href="<?php echo esc_url( $permalink ); ?>" onclick="event.stopPropagation();"><?php echo esc_html( $title ); ?></a>
        </h3>
        <?php if ( $excerpt ) : ?>
            <p class="text-google-gray-500 text-base leading-relaxed line-clamp-2 mb-4 flex-grow">
                <?php echo esc_html( $excerpt ); ?>
            </p>
        <?php endif; ?>
        <div class="text-xs text-google-gray-500">
            <?php echo esc_html( get_the_date() ); ?>
        </div>
    </div>
</article>
