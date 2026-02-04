<?php
/**
 * Template part for displaying social share buttons
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

$button_style = get_option( 'chrysoberyl_social_button_style', 'icon_only' );
$button_size = get_option( 'chrysoberyl_social_button_size', 'medium' );
$icon_style   = get_option( 'chrysoberyl_social_icon_style', 'branded' );

// Size classes
$size_classes = array(
    'small' => 'text-sm px-3 py-2',
    'medium' => 'text-base px-4 py-3',
    'large' => 'text-lg px-5 py-4',
);

$size_class = isset( $size_classes[ $button_size ] ) ? $size_classes[ $button_size ] : $size_classes['medium'];

// Style classes
$style_classes = array(
    'icon_only' => 'chrysoberyl-share-icon-only',
    'icon_text' => 'chrysoberyl-share-icon-text',
    'button' => 'chrysoberyl-share-button',
);

$style_class = isset( $style_classes[ $button_style ] ) ? $style_classes[ $button_style ] : $style_classes['icon_only'];
$mockup_class = ( $icon_style === 'mockup' ) ? ' chrysoberyl-share-style-mockup' : '';

$post_id = get_the_ID();
$post_url = chrysoberyl_fix_url( get_permalink( $post_id ) );
$post_title = get_the_title( $post_id );
?>

<div class="chrysoberyl-social-share <?php echo esc_attr( $style_class ); ?> <?php echo esc_attr( $size_class ); ?><?php echo esc_attr( $mockup_class ); ?>" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-post-url="<?php echo esc_url( $post_url ); ?>" data-post-title="<?php echo esc_attr( $post_title ); ?>">
    <?php if ( $button_style === 'icon_text' || $button_style === 'button' ) : ?>
        <span class="chrysoberyl-share-label"><?php _e( 'แชร์', 'chrysoberyl' ); ?></span>
    <?php endif; ?>
    
    <div class="chrysoberyl-share-buttons">
        <?php foreach ( $selected_platforms as $platform ) : 
            $share_url = chrysoberyl_get_share_url( $platform, $post_id );
            $label = chrysoberyl_get_share_label( $platform );
            $icon = chrysoberyl_get_share_icon( $platform );
            $color = chrysoberyl_get_share_color( $platform );
        ?>
            <?php if ( $platform === 'copy_link' ) : ?>
                <a href="#" 
                   class="chrysoberyl-share-btn chrysoberyl-share-<?php echo esc_attr( $platform ); ?> chrysoberyl-share-copy_link" 
                   data-platform="<?php echo esc_attr( $platform ); ?>"
                   data-post-url="<?php echo esc_url( $post_url ); ?>"
                   aria-label="<?php echo esc_attr( sprintf( __( 'Copy link', 'chrysoberyl' ), $label ) ); ?>"
                   <?php if ( $icon_style !== 'mockup' ) : ?>style="background-color: <?php echo esc_attr( $color ); ?>;"<?php endif; ?>>
                    <i class="<?php echo esc_attr( $icon ); ?>"></i>
                    <?php if ( $button_style === 'icon_text' || $button_style === 'button' ) : ?>
                        <span class="chrysoberyl-share-btn-label"><?php echo esc_html( $label ); ?></span>
                    <?php endif; ?>
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url( $share_url ); ?>" 
                   class="chrysoberyl-share-btn chrysoberyl-share-<?php echo esc_attr( $platform ); ?>" 
                   data-platform="<?php echo esc_attr( $platform ); ?>"
                   target="_blank" 
                   rel="noopener noreferrer"
                   aria-label="<?php echo esc_attr( sprintf( __( 'Share on %s', 'chrysoberyl' ), $label ) ); ?>"
                   <?php if ( $icon_style !== 'mockup' ) : ?>style="background-color: <?php echo esc_attr( $color ); ?>;"<?php endif; ?>>
                    <i class="<?php echo esc_attr( $icon ); ?>"></i>
                    <?php if ( $button_style === 'icon_text' || $button_style === 'button' ) : ?>
                        <span class="chrysoberyl-share-btn-label"><?php echo esc_html( $label ); ?></span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
