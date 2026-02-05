<?php
/**
 * Security Functions
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Sanitize array of values
 *
 * @param array $values Array of values to sanitize.
 * @param string $type Sanitization type (text, int, url, email).
 * @return array Sanitized array.
 */
function chrysoberyl_sanitize_array( $values, $type = 'text' ) {
    if ( ! is_array( $values ) ) {
        return array();
    }
    
    $sanitized = array();
    foreach ( $values as $key => $value ) {
        switch ( $type ) {
            case 'int':
                $sanitized[ $key ] = absint( $value );
                break;
            case 'url':
                $sanitized[ $key ] = esc_url_raw( $value );
                break;
            case 'email':
                $sanitized[ $key ] = sanitize_email( $value );
                break;
            case 'text':
            default:
                $sanitized[ $key ] = sanitize_text_field( $value );
                break;
        }
    }
    
    return $sanitized;
}

/**
 * Validate and sanitize search query
 *
 * @param string $query Search query.
 * @return string Sanitized search query.
 */
function chrysoberyl_sanitize_search_query( $query ) {
    // Remove potentially dangerous characters
    $query = sanitize_text_field( $query );
    $query = trim( $query );
    
    // Limit length
    if ( strlen( $query ) > 200 ) {
        $query = substr( $query, 0, 200 );
    }
    
    return $query;
}

/**
 * Add security headers
 */
function chrysoberyl_add_security_headers() {
    if ( ! is_admin() ) {
        header( 'X-Content-Type-Options: nosniff' );
        header( 'X-Frame-Options: SAMEORIGIN' );
        header( 'X-XSS-Protection: 1; mode=block' );
        header( 'Referrer-Policy: strict-origin-when-cross-origin' );
        
        // Content Security Policy (basic). worker-src blob: for wp-emoji-loader.
        if ( ! headers_sent() ) {
            $csp = "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://fonts.googleapis.com blob:; worker-src 'self' blob:; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net; font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com; img-src 'self' data: https:; connect-src 'self' https:;";
            header( "Content-Security-Policy: $csp" );
        }
    }
}
add_action( 'send_headers', 'chrysoberyl_add_security_headers' );

/**
 * Rate limit AJAX requests
 *
 * @param string $action AJAX action name.
 * @param int $limit Maximum requests per minute.
 * @return bool True if allowed, false if rate limited.
 */
function chrysoberyl_check_rate_limit( $action, $limit = 60 ) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $transient_key = 'chrysoberyl_rate_limit_' . md5( $ip . $action );
    
    $count = get_transient( $transient_key );
    if ( false === $count ) {
        $count = 0;
    }
    
    if ( $count >= $limit ) {
        return false;
    }
    
    set_transient( $transient_key, $count + 1, MINUTE_IN_SECONDS );
    return true;
}

/**
 * Enhanced nonce verification for AJAX
 *
 * @param string $action Action name.
 * @param string $nonce_name Nonce field name.
 * @return bool True if verified, false otherwise.
 */
function chrysoberyl_verify_ajax_nonce( $action, $nonce_name = 'nonce' ) {
    if ( ! isset( $_POST[ $nonce_name ] ) ) {
        return false;
    }
    
    $nonce = sanitize_text_field( $_POST[ $nonce_name ] );
    return wp_verify_nonce( $nonce, $action );
}

/**
 * Sanitize file upload
 *
 * @param array $file File array from $_FILES.
 * @param array $allowed_types Allowed MIME types.
 * @param int $max_size Maximum file size in bytes.
 * @return array|WP_Error Sanitized file array or WP_Error.
 */
function chrysoberyl_sanitize_file_upload( $file, $allowed_types = array(), $max_size = 5242880 ) {
    if ( empty( $file ) || ! isset( $file['tmp_name'] ) ) {
        return new WP_Error( 'no_file', __( 'No file uploaded.', 'chrysoberyl' ) );
    }
    
    // Check file size
    if ( $file['size'] > $max_size ) {
        return new WP_Error( 'file_too_large', __( 'File is too large.', 'chrysoberyl' ) );
    }
    
    // Check file type
    if ( ! empty( $allowed_types ) ) {
        $file_type = wp_check_filetype( $file['name'] );
        if ( ! in_array( $file_type['type'], $allowed_types, true ) ) {
            return new WP_Error( 'invalid_file_type', __( 'Invalid file type.', 'chrysoberyl' ) );
        }
    }
    
    // Validate file content (basic check)
    $file_content = file_get_contents( $file['tmp_name'] );
    if ( false === $file_content ) {
        return new WP_Error( 'file_read_error', __( 'Could not read file.', 'chrysoberyl' ) );
    }
    
    return $file;
}

/**
 * Escape output for JavaScript
 *
 * @param string $string String to escape.
 * @return string Escaped string.
 */
function chrysoberyl_escape_js( $string ) {
    return esc_js( $string );
}

/**
 * Escape output for HTML attributes
 *
 * @param string $string String to escape.
 * @return string Escaped string.
 */
function chrysoberyl_escape_attr( $string ) {
    return esc_attr( $string );
}

/**
 * Escape output for HTML
 *
 * @param string $string String to escape.
 * @return string Escaped string.
 */
function chrysoberyl_escape_html( $string ) {
    return esc_html( $string );
}

/**
 * Clear cache when post is saved
 */
function chrysoberyl_clear_cache_on_save( $post_id ) {
    // Clear breaking news cache
    delete_transient( 'chrysoberyl_breaking_news_4' );
    delete_transient( 'chrysoberyl_breaking_news_5' );
    delete_transient( 'chrysoberyl_breaking_news_6' );
    
    // Clear popular posts cache
    delete_transient( 'chrysoberyl_popular_views_5' );
    delete_transient( 'chrysoberyl_popular_comments_5' );
    delete_transient( 'chrysoberyl_popular_date_5' );
}
add_action( 'save_post', 'chrysoberyl_clear_cache_on_save' );
add_action( 'delete_post', 'chrysoberyl_clear_cache_on_save' );
