<?php
/**
 * AJAX Handlers
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Load more posts via AJAX
 */
function chrysoberyl_load_more_posts() {
    check_ajax_referer( 'chrysoberyl-nonce', 'nonce' );

    $page = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
    $posts_per_page = isset( $_POST['posts_per_page'] ) ? absint( $_POST['posts_per_page'] ) : get_option( 'posts_per_page' );
    $category = isset( $_POST['category'] ) ? sanitize_text_field( $_POST['category'] ) : '';
    $search_query = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
    $tag_id = isset( $_POST['tag_id'] ) ? absint( $_POST['tag_id'] ) : 0;
    $cat_id = isset( $_POST['cat_id'] ) ? absint( $_POST['cat_id'] ) : 0;

    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $posts_per_page,
        'paged'          => $page,
        'post_status'    => 'publish',
    );

    // Handle category filter (from category filters)
    if ( ! empty( $category ) && $category !== 'all' ) {
        $args['cat'] = absint( $category );
    }
    
    // Handle archive category
    if ( $cat_id > 0 ) {
        $args['cat'] = $cat_id;
    }
    
    // Handle archive tag
    if ( $tag_id > 0 ) {
        $args['tag_id'] = $tag_id;
    }
    
    // Handle search query
    if ( ! empty( $search_query ) ) {
        $args['s'] = $search_query;
    }

    $query = new WP_Query( $args );

    ob_start();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            get_template_part( 'template-parts/news-card' );
        }
        wp_reset_postdata();
    }

    $html = ob_get_clean();

    wp_send_json_success( array(
        'html' => $html,
        'has_more' => $query->max_num_pages > $page,
        'next_page' => $page + 1,
    ) );
}
add_action( 'wp_ajax_load_more_posts', 'chrysoberyl_load_more_posts' );
add_action( 'wp_ajax_nopriv_load_more_posts', 'chrysoberyl_load_more_posts' );

/**
 * Filter posts by category via AJAX
 */
function chrysoberyl_filter_posts() {
    check_ajax_referer( 'chrysoberyl-nonce', 'nonce' );

    $category = isset( $_POST['category'] ) ? sanitize_text_field( $_POST['category'] ) : 'all';
    $posts_per_page = isset( $_POST['posts_per_page'] ) ? absint( $_POST['posts_per_page'] ) : get_option( 'posts_per_page' );

    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $posts_per_page,
        'post_status'    => 'publish',
    );

    if ( ! empty( $category ) && $category !== 'all' ) {
        $args['cat'] = absint( $category );
    }

    $query = new WP_Query( $args );

    ob_start();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            get_template_part( 'template-parts/news-card' );
        }
        wp_reset_postdata();
    } else {
        get_template_part( 'template-parts/content', 'none' );
    }

    $html = ob_get_clean();

    wp_send_json_success( array(
        'html' => $html,
        'found_posts' => $query->found_posts,
        'max_pages' => $query->max_num_pages,
    ) );
}
add_action( 'wp_ajax_filter_posts', 'chrysoberyl_filter_posts' );
add_action( 'wp_ajax_nopriv_filter_posts', 'chrysoberyl_filter_posts' );

/**
 * Search suggestions via AJAX
 */
function chrysoberyl_search_suggestions() {
    check_ajax_referer( 'chrysoberyl-nonce', 'nonce' );
    
    // Rate limiting
    if ( ! chrysoberyl_check_rate_limit( 'search_suggestions', 30 ) ) {
        wp_send_json_error( array( 'message' => __( 'Too many requests. Please try again later.', 'chrysoberyl' ) ) );
    }

    // Get search settings
    $search_enabled = get_option( 'chrysoberyl_search_enabled', '1' );
    $search_suggestions_enabled = get_option( 'chrysoberyl_search_suggestions_enabled', '1' );
    $search_min_length = get_option( 'chrysoberyl_search_min_length', 2 );
    $search_suggestions_count = get_option( 'chrysoberyl_search_suggestions_count', 5 );
    $search_post_types = get_option( 'chrysoberyl_search_post_types', array( 'post' ) );
    $search_fields = get_option( 'chrysoberyl_search_fields', array( 'title', 'content' ) );
    $search_suggestions_display = get_option( 'chrysoberyl_search_suggestions_display', array( 'image', 'excerpt' ) );
    $search_exclude_categories = get_option( 'chrysoberyl_search_exclude_categories', array() );

    if ( $search_enabled !== '1' || $search_suggestions_enabled !== '1' ) {
        wp_send_json_success( array( 'suggestions' => array() ) );
    }

    $search_term = isset( $_POST['search'] ) ? chrysoberyl_sanitize_search_query( $_POST['search'] ) : '';

    if ( strlen( $search_term ) < $search_min_length ) {
        wp_send_json_success( array( 'suggestions' => array() ) );
    }

    // Build search query
    $args = array(
        'post_type'      => $search_post_types,
        'posts_per_page' => $search_suggestions_count,
        's'              => $search_term,
        'post_status'    => 'publish',
    );

    // Exclude categories
    if ( ! empty( $search_exclude_categories ) ) {
        $args['category__not_in'] = $search_exclude_categories;
    }

    $query = new WP_Query( $args );

    $suggestions = array();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $suggestion = array(
                'title' => get_the_title(),
                'url'   => chrysoberyl_fix_url( get_permalink() ),
                'type'  => get_post_type(),
            );

            // Add optional fields based on settings
            if ( in_array( 'image', $search_suggestions_display ) && has_post_thumbnail() ) {
                $suggestion['image'] = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
            }

            if ( in_array( 'excerpt', $search_suggestions_display ) ) {
                $suggestion['excerpt'] = wp_trim_words( get_the_excerpt(), 15, '...' );
            }

            if ( in_array( 'date', $search_suggestions_display ) ) {
                $suggestion['date'] = human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) . ' ' . __( 'ago', 'chrysoberyl' );
            }

            if ( in_array( 'category', $search_suggestions_display ) ) {
                $categories = get_the_category();
                if ( ! empty( $categories ) ) {
                    $suggestion['category'] = $categories[0]->name;
                    $suggestion['category_color'] = get_term_meta( $categories[0]->term_id, 'category_color', true ) ?: '#3B82F6';
                }
            }

            $suggestions[] = $suggestion;
        }
        wp_reset_postdata();
    }

    wp_send_json_success( array( 'suggestions' => $suggestions ) );
}
add_action( 'wp_ajax_search_suggestions', 'chrysoberyl_search_suggestions' );
add_action( 'wp_ajax_nopriv_search_suggestions', 'chrysoberyl_search_suggestions' );

/**
 * Increment post views via AJAX
 */
function chrysoberyl_increment_views() {
    check_ajax_referer( 'chrysoberyl-nonce', 'nonce' );

    $post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;

    if ( ! $post_id ) {
        wp_send_json_error( array( 'message' => __( 'Invalid post ID', 'chrysoberyl' ) ) );
    }

    chrysoberyl_increment_post_views( $post_id );
    $views = chrysoberyl_get_post_views( $post_id );

    wp_send_json_success( array( 'views' => $views ) );
}
add_action( 'wp_ajax_increment_views', 'chrysoberyl_increment_views' );
add_action( 'wp_ajax_nopriv_increment_views', 'chrysoberyl_increment_views' );

/**
 * AJAX login (modal) — stay on current page after login
 */
function chrysoberyl_ajax_login() {
    check_ajax_referer( 'chrysoberyl_ajax_login', 'chrysoberyl_login_nonce' );

    $log = isset( $_POST['log'] ) ? sanitize_text_field( $_POST['log'] ) : '';
    $pwd = isset( $_POST['pwd'] ) ? $_POST['pwd'] : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
    $remember = ! empty( $_POST['remember'] );
    $redirect_to = isset( $_POST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_to'] ) ) : '';

    if ( empty( $log ) || empty( $pwd ) ) {
        wp_send_json_error( array( 'message' => __( 'Please enter username and password.', 'chrysoberyl' ) ) );
    }

    // Allow only same-site redirect
    $allowed = ( $redirect_to && strpos( $redirect_to, home_url() ) === 0 );
    if ( ! $allowed ) {
        $redirect_to = get_permalink( get_queried_object_id() ) ?: home_url( '/' );
    }
    if ( empty( $redirect_to ) ) {
        $redirect_to = home_url( '/' );
    }

    $credentials = array(
        'user_login'    => $log,
        'user_password' => $pwd,
        'remember'      => $remember,
    );
    $user = wp_signon( $credentials, is_ssl() );

    if ( is_wp_error( $user ) ) {
        $err_msg = $user->get_error_message();
        $message = $err_msg ? wp_strip_all_tags( $err_msg ) : __( 'Invalid username or password.', 'chrysoberyl' );
        wp_send_json_error( array( 'message' => $message ) );
    }

    wp_send_json_success( array( 'redirect' => $redirect_to ) );
}
add_action( 'wp_ajax_nopriv_chrysoberyl_ajax_login', 'chrysoberyl_ajax_login' );

