<?php
/**
 * Template part for displaying floating social share buttons
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

// Check if social sharing is enabled
$social_sharing_enabled = get_option( 'chrysoberyl_social_sharing_enabled', '1' );
if ( $social_sharing_enabled !== '1' ) {
    return;
}

$selected_platforms = get_option( 'chrysoberyl_social_platforms', array( 'facebook', 'twitter', 'line' ) );
if ( empty( $selected_platforms ) ) {
    return;
}

$icon_style = get_option( 'chrysoberyl_social_icon_style', 'branded' );
$mockup_class = ( $icon_style === 'mockup' ) ? ' chrysoberyl-floating-share-style-mockup' : '';

$post_id = get_the_ID();
$post_url = chrysoberyl_fix_url( get_permalink( $post_id ) );
$post_title = get_the_title( $post_id );
?>

<div class="chrysoberyl-floating-share<?php echo esc_attr( $mockup_class ); ?>" id="chrysoberyl-floating-share">
    <button class="chrysoberyl-floating-share-toggle" aria-label="<?php _e( 'แชร์', 'chrysoberyl' ); ?>">
        <i class="fas fa-share-alt"></i>
    </button>
    <div class="chrysoberyl-floating-share-buttons">
        <?php foreach ( $selected_platforms as $platform ) : 
            $share_url = chrysoberyl_get_share_url( $platform, $post_id );
            $label = chrysoberyl_get_share_label( $platform );
            $icon = chrysoberyl_get_share_icon( $platform );
            $color = chrysoberyl_get_share_color( $platform );
        ?>
            <?php if ( $platform === 'copy_link' ) : ?>
                <a href="#" 
                   class="chrysoberyl-floating-share-btn chrysoberyl-share-<?php echo esc_attr( $platform ); ?> chrysoberyl-share-copy_link" 
                   data-platform="<?php echo esc_attr( $platform ); ?>"
                   data-post-url="<?php echo esc_url( $post_url ); ?>"
                   aria-label="<?php echo esc_attr( sprintf( __( 'Copy link', 'chrysoberyl' ), $label ) ); ?>"
                   <?php if ( $icon_style !== 'mockup' ) : ?>style="background-color: <?php echo esc_attr( $color ); ?>;"<?php endif; ?>
                   title="<?php echo esc_attr( $label ); ?>">
                    <i class="<?php echo esc_attr( $icon ); ?>"></i>
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url( $share_url ); ?>" 
                   class="chrysoberyl-floating-share-btn chrysoberyl-share-<?php echo esc_attr( $platform ); ?>" 
                   data-platform="<?php echo esc_attr( $platform ); ?>"
                   target="_blank" 
                   rel="noopener noreferrer"
                   aria-label="<?php echo esc_attr( sprintf( __( 'Share on %s', 'chrysoberyl' ), $label ) ); ?>"
                   <?php if ( $icon_style !== 'mockup' ) : ?>style="background-color: <?php echo esc_attr( $color ); ?>;"<?php endif; ?>
                   title="<?php echo esc_attr( $label ); ?>">
                    <i class="<?php echo esc_attr( $icon ); ?>"></i>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
