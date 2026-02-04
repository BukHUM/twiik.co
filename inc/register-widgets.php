<?php
/**
 * Register Custom Widgets
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register custom widgets
 */
function chrysoberyl_register_widgets() {
    // Get enabled widgets from settings
    $enabled_widgets = chrysoberyl_get_enabled_widgets();
    
    // Register widgets based on settings
    if ( in_array( 'popular_posts', $enabled_widgets, true ) ) {
        register_widget( 'chrysoberyl_Popular_Posts_Widget' );
    }
    
    if ( in_array( 'recent_posts', $enabled_widgets, true ) ) {
        register_widget( 'chrysoberyl_Recent_Posts_Widget' );
    }
    
    if ( in_array( 'trending_tags', $enabled_widgets, true ) ) {
        register_widget( 'chrysoberyl_Trending_Tags_Widget' );
    }
    if ( in_array( 'related_posts', $enabled_widgets, true ) ) {
        register_widget( 'chrysoberyl_Related_Posts_Widget' );
    }
    if ( in_array( 'categories', $enabled_widgets, true ) ) {
        register_widget( 'chrysoberyl_Categories_Widget' );
    }
    if ( in_array( 'search', $enabled_widgets, true ) ) {
        register_widget( 'chrysoberyl_Search_Widget' );
    }
    if ( in_array( 'social_follow', $enabled_widgets, true ) ) {
        register_widget( 'chrysoberyl_Social_Follow_Widget' );
    }
    if ( in_array( 'most_commented', $enabled_widgets, true ) ) {
        register_widget( 'chrysoberyl_Most_Commented_Widget' );
    }
    if ( in_array( 'archive', $enabled_widgets, true ) ) {
        register_widget( 'chrysoberyl_Archive_Widget' );
    }
    if ( in_array( 'custom_html', $enabled_widgets, true ) ) {
        register_widget( 'chrysoberyl_Custom_HTML_Widget' );
    }
    
    // Note: Newsletter widget is removed - newsletter is now in footer only
}
add_action( 'widgets_init', 'chrysoberyl_register_widgets' );

/**
 * Handle newsletter subscription
 */
function chrysoberyl_handle_newsletter_subscription() {
    if ( ! isset( $_POST['newsletter_nonce'] ) || ! wp_verify_nonce( $_POST['newsletter_nonce'], 'chrysoberyl_newsletter' ) ) {
        wp_die( __( 'Security check failed', 'chrysoberyl' ) );
    }

    $email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';

    if ( ! is_email( $email ) ) {
        wp_redirect( add_query_arg( 'newsletter', 'invalid', wp_get_referer() ) );
        exit;
    }

    // Store email (you can integrate with Mailchimp, etc. here)
    $subscribers = get_option( 'chrysoberyl_newsletter_subscribers', array() );
    if ( ! in_array( $email, $subscribers, true ) ) {
        $subscribers[] = $email;
        update_option( 'chrysoberyl_newsletter_subscribers', $subscribers );
    }

    // Redirect with success message
    wp_redirect( add_query_arg( 'newsletter', 'success', wp_get_referer() ) );
    exit;
}
add_action( 'admin_post_chrysoberyl_newsletter_subscribe', 'chrysoberyl_handle_newsletter_subscription' );
add_action( 'admin_post_nopriv_chrysoberyl_newsletter_subscribe', 'chrysoberyl_handle_newsletter_subscription' );
