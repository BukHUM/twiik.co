<?php
/**
 * Custom fields and meta boxes
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add custom meta boxes
 * (Additional Information and SEO Options removed — use Rank Math instead)
 */
function chrysoberyl_add_meta_boxes() {
    global $post;
    if ( ! $post ) {
        return;
    }
    // FAQ Items meta box (page with FAQ template only)
    if ( $post->post_type === 'page' ) {
        $template = get_post_meta( $post->ID, '_wp_page_template', true );
        if ( $template === 'page-faq.php' ) {
            add_meta_box(
                'chrysoberyl_faq_manage',
                __( 'FAQ Items', 'chrysoberyl' ),
                'chrysoberyl_faq_manage_meta_box',
                'page',
                'side',
                'high'
            );
        }
    }
    // Table of Contents meta box (post and page)
    add_meta_box(
        'chrysoberyl_show_toc',
        __( 'Table of Contents', 'chrysoberyl' ),
        'chrysoberyl_show_toc_meta_box',
        'post',
        'side',
        'default'
    );
    add_meta_box(
        'chrysoberyl_show_toc',
        __( 'Table of Contents', 'chrysoberyl' ),
        'chrysoberyl_show_toc_meta_box',
        'page',
        'side',
        'default'
    );
}

/**
 * Meta box content: link to add/edit FAQs (post type chrysoberyl_faq)
 */
function chrysoberyl_faq_manage_meta_box( $post ) {
    $add_url  = admin_url( 'post-new.php?post_type=chrysoberyl_faq' );
    $list_url = admin_url( 'edit.php?post_type=chrysoberyl_faq' );
    $count    = wp_count_posts( 'chrysoberyl_faq' );
    $total    = isset( $count->publish ) ? (int) $count->publish : 0;
    ?>
    <p class="description" style="margin-top:0;">
        <?php _e( 'FAQ items are managed separately. Use the links below to add or edit questions and answers.', 'chrysoberyl' ); ?>
    </p>
    <p style="margin-bottom:12px;">
        <a href="<?php echo esc_url( $add_url ); ?>" class="button button-primary button-large" style="width:100%;text-align:center;">
            <?php _e( 'Add New FAQ', 'chrysoberyl' ); ?>
        </a>
    </p>
    <p style="margin-bottom:0;">
        <a href="<?php echo esc_url( $list_url ); ?>">
            <?php printf( _n( 'View %s FAQ item', 'View all %s FAQ items', $total, 'chrysoberyl' ), number_format_i18n( $total ) ); ?>
        </a>
    </p>
    <?php
}

/**
 * Meta box content: Table of Contents (use theme default / show / hide)
 * Post/page option overrides Theme Setting when set.
 */
function chrysoberyl_show_toc_meta_box( $post ) {
    wp_nonce_field( 'chrysoberyl_show_toc_save', 'chrysoberyl_show_toc_nonce' );
    $value = get_post_meta( $post->ID, 'chrysoberyl_show_toc', true );
    if ( $value !== '1' && $value !== '0' ) {
        $value = '';
    }
    $theme_default = get_option( 'chrysoberyl_toc_enabled', '1' ) === '1';
    ?>
    <p class="description" style="margin-top:0;">
        <?php _e( 'Override the theme default (Theme Settings → Table of Contents).', 'chrysoberyl' ); ?>
    </p>
    <p style="margin-bottom:0;">
        <label><input type="radio" name="chrysoberyl_show_toc" value="" <?php checked( $value, '' ); ?> />
            <?php _e( 'Use theme default', 'chrysoberyl' ); ?>
            (<?php echo $theme_default ? esc_html__( 'Show', 'chrysoberyl' ) : esc_html__( 'Hide', 'chrysoberyl' ); ?>)
        </label><br>
        <label><input type="radio" name="chrysoberyl_show_toc" value="1" <?php checked( $value, '1' ); ?> />
            <?php _e( 'Show', 'chrysoberyl' ); ?>
        </label><br>
        <label><input type="radio" name="chrysoberyl_show_toc" value="0" <?php checked( $value, '0' ); ?> />
            <?php _e( 'Hide', 'chrysoberyl' ); ?>
        </label>
    </p>
    <?php
}

/**
 * Save Table of Contents meta.
 */
function chrysoberyl_show_toc_save_meta( $post_id ) {
    if ( ! isset( $_POST['chrysoberyl_show_toc_nonce'] ) || ! wp_verify_nonce( $_POST['chrysoberyl_show_toc_nonce'], 'chrysoberyl_show_toc_save' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    $post_type = get_post_type( $post_id );
    if ( $post_type !== 'post' && $post_type !== 'page' ) {
        return;
    }
    $val = isset( $_POST['chrysoberyl_show_toc'] ) ? sanitize_text_field( $_POST['chrysoberyl_show_toc'] ) : '';
    if ( $val === '1' || $val === '0' ) {
        update_post_meta( $post_id, 'chrysoberyl_show_toc', $val );
    } else {
        delete_post_meta( $post_id, 'chrysoberyl_show_toc' );
    }
}
add_action( 'save_post', 'chrysoberyl_show_toc_save_meta' );

/**
 * Whether to show TOC for a post/page. Post/page option overrides Theme Setting.
 *
 * @param int|null $post_id Post or page ID. Default: current post in loop.
 * @return bool True to show TOC, false to hide.
 */
function chrysoberyl_show_toc_for_post( $post_id = null ) {
    if ( $post_id === null ) {
        $post_id = get_the_ID();
    }
    if ( ! $post_id ) {
        return get_option( 'chrysoberyl_toc_enabled', '1' ) === '1';
    }
    $override = get_post_meta( $post_id, 'chrysoberyl_show_toc', true );
    if ( $override === '1' ) {
        return true;
    }
    if ( $override === '0' ) {
        return false;
    }
    return get_option( 'chrysoberyl_toc_enabled', '1' ) === '1';
}

add_action( 'add_meta_boxes', 'chrysoberyl_add_meta_boxes' );
