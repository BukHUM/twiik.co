<?php
/**
 * Custom Post Type Helper Functions
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Get featured stories (CPT removed — returns empty query).
 *
 * @param int    $number Number of posts to retrieve.
 * @param string $orderby Order by field.
 * @param string $order Order direction.
 * @return WP_Query Query object.
 */
function chrysoberyl_get_featured_stories( $number = 5, $orderby = 'meta_value_num', $order = 'DESC' ) {
    return new WP_Query( array( 'post_type' => 'featured_story', 'posts_per_page' => 0 ) );
}

/**
 * Get latest video news (CPT removed — returns empty query).
 *
 * @param int $number Number of posts to retrieve.
 * @return WP_Query Query object.
 */
function chrysoberyl_get_latest_videos( $number = 6 ) {
    return new WP_Query( array( 'post_type' => 'video_news', 'posts_per_page' => 0 ) );
}

/**
 * Get latest galleries (CPT removed — returns empty query).
 *
 * @param int $number Number of posts to retrieve.
 * @return WP_Query Query object.
 */
function chrysoberyl_get_latest_galleries( $number = 6 ) {
    return new WP_Query( array( 'post_type' => 'gallery', 'posts_per_page' => 0 ) );
}

/**
 * Get posts by post type (only post/page allowed; CPT removed).
 *
 * @param string|array $post_types Post type(s).
 * @param int          $number Number of posts.
 * @param array        $args Additional query arguments.
 * @return WP_Query Query object.
 */
function chrysoberyl_get_posts_by_type( $post_types, $number = 10, $args = array() ) {
    $allowed = array( 'post', 'page' );
    $post_types = is_array( $post_types ) ? array_intersect( $post_types, $allowed ) : ( in_array( $post_types, $allowed, true ) ? $post_types : 'post' );
    $defaults = array(
        'post_type'      => $post_types,
        'posts_per_page' => $number,
        'orderby'        => 'date',
        'order'          => 'DESC',
    );
    $args = wp_parse_args( $args, $defaults );
    return new WP_Query( $args );
}

/**
 * Check if featured story is expired
 *
 * @param int $post_id Post ID.
 * @return bool True if expired, false otherwise.
 */
function chrysoberyl_is_featured_expired( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $expiry = get_post_meta( $post_id, 'featured_expiry', true );

    if ( ! $expiry ) {
        return false;
    }

    return strtotime( $expiry ) < time();
}
