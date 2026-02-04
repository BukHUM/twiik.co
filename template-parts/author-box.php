<?php
/**
 * Template part: Author box (single post) — ตรง mockup single.html Author Bio
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

$post_id = get_the_ID();
$author_id = get_post_field( 'post_author', $post_id );
$author_name = chrysoberyl_get_author_name( $post_id );
$author_bio  = chrysoberyl_get_author_bio( $post_id );
if ( empty( $author_bio ) ) {
    $author_bio = get_the_author_meta( 'description', $author_id );
}
$author_url = get_the_author_meta( 'url', $author_id );
$author_posts_url = get_author_posts_url( $author_id );
?>

<div class="mt-16 pt-8 border-t border-gray-200" aria-label="<?php esc_attr_e( 'About the author', 'chrysoberyl' ); ?>">
    <div class="flex flex-col sm:flex-row items-start gap-6 bg-google-gray-50 rounded-card p-6">
        <?php echo get_avatar( $author_id, 80, '', $author_name, array( 'class' => 'w-20 h-20 rounded-full object-cover flex-shrink-0' ) ); ?>
        <div class="min-w-0 flex-1">
            <h3 class="font-medium text-google-gray mb-2">
                <a href="<?php echo esc_url( $author_posts_url ); ?>" class="hover:text-google-blue transition-colors">
                    <?php echo esc_html( $author_name ); ?>
                </a>
            </h3>
            <?php if ( ! empty( $author_bio ) ) : ?>
                <p class="text-sm text-google-gray-500 mb-4 leading-relaxed">
                    <?php echo wp_kses_post( nl2br( $author_bio ) ); ?>
                </p>
            <?php endif; ?>
            <div class="flex gap-3">
                <a href="<?php echo esc_url( $author_posts_url ); ?>" class="text-google-gray-500 hover:text-google-blue transition-colors" aria-label="<?php esc_attr_e( 'All posts by author', 'chrysoberyl' ); ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6m4-4h-1m-1 4h6m4-4h-1m-1 4h6m4-4h-1M3 20h18M3 4h18" />
                    </svg>
                </a>
                <?php if ( ! empty( $author_url ) ) : ?>
                    <a href="<?php echo esc_url( $author_url ); ?>" class="text-google-gray-500 hover:text-google-blue transition-colors" aria-label="<?php esc_attr_e( 'Author website', 'chrysoberyl' ); ?>" rel="noopener noreferrer" target="_blank">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" />
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
