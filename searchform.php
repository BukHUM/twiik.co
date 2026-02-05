<?php
/**
 * Template for displaying search forms
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

$unique_id = wp_unique_id( 'search-form-' );
$search_placeholder = get_option( 'chrysoberyl_search_placeholder', __( 'Search...', 'chrysoberyl' ) );
?>

<form role="search" method="get" class="search-form relative" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label for="<?php echo esc_attr( $unique_id ); ?>" class="screen-reader-text">
        <?php echo esc_html_x( 'Search for:', 'label', 'chrysoberyl' ); ?>
    </label>
    <input type="search"
        id="<?php echo esc_attr( $unique_id ); ?>"
        class="search-field w-full px-5 py-3 pl-12 rounded-full border border-gray-300 focus:border-google-blue focus:ring-2 focus:ring-google-blue focus:ring-opacity-20 outline-none transition-all text-google-gray placeholder-google-gray-500"
        placeholder="<?php echo esc_attr( $search_placeholder ); ?>"
        value="<?php echo get_search_query(); ?>"
        name="s" />
    <svg class="search-icon w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-google-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
    </svg>
    <button type="submit" class="screen-reader-text">
        <?php echo esc_html_x( 'Search', 'submit button', 'chrysoberyl' ); ?>
    </button>
</form>
