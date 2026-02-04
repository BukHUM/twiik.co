<?php
/**
 * Template part: Floating Left Ad (Skyscraper 120x600 or 160x600)
 * Shown only on single post when enabled in Theme Settings. Sticks to left like TOC on right.
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

$enabled = get_option( 'chrysoberyl_floating_left_ad_enabled', '0' );
if ( $enabled !== '1' || ! is_single() ) {
    return;
}

$size    = get_option( 'chrysoberyl_floating_left_ad_size', '160x600' );
$content = get_option( 'chrysoberyl_floating_left_ad_content', '' );

$width  = ( $size === '120x600' ) ? 120 : 160;
$height = 600;

if ( empty( $content ) ) {
    $label = __( 'โฆษณา', 'chrysoberyl' );
    $content = '<div class="chrysoberyl-floating-left-ad-placeholder" style="width:100%;height:100%;background:#f3f4f6;border:1px dashed #d1d5db;border-radius:0.5rem;display:flex;align-items:center;justify-content:center;">
        <span style="color:#9ca3af;font-size:0.75rem;font-weight:500;text-align:center;padding:8px;">' . esc_html( $label ) . '<br>' . (int) $width . '&times;' . (int) $height . '</span>
    </div>';
}

$size_class = ( $size === '120x600' ) ? 'chrysoberyl-floating-left-ad-120' : 'chrysoberyl-floating-left-ad-160';
?>

<aside id="chrysoberyl-floating-left-ad" class="chrysoberyl-floating-left-ad <?php echo esc_attr( $size_class ); ?>" role="complementary" aria-label="<?php esc_attr_e( 'โฆษณา', 'chrysoberyl' ); ?>">
    <div class="chrysoberyl-floating-left-ad-inner">
        <?php echo wp_kses_post( $content ); ?>
    </div>
</aside>
