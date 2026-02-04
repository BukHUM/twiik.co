<?php
/**
 * Template part: Search overlay (mockup: header.html search-overlay)
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

$search_enabled = get_option( 'chrysoberyl_search_enabled', '1' );
if ( $search_enabled !== '1' ) {
    return;
}

$search_placeholder = get_option( 'chrysoberyl_search_placeholder', __( 'Search Chrysoberyl...', 'chrysoberyl' ) );
$search_suggestions_style = get_option( 'chrysoberyl_search_suggestions_style', 'modal' );

if ( $search_suggestions_style !== 'modal' && $search_suggestions_style !== 'fullpage' ) {
    return;
}

// Dynamic popular searches: same source as search results page (top tags by count)
$popular_tags = get_tags( array(
    'orderby' => 'count',
    'order'   => 'DESC',
    'number'  => 6,
) );
$default_popular = array();
if ( ! empty( $popular_tags ) ) {
    foreach ( $popular_tags as $tag ) {
        $default_popular[] = $tag->name;
    }
}
if ( empty( $default_popular ) ) {
    $default_popular = array( 'AI Update', 'Gemini', 'Android 15' ); // fallback when no tags
}
$popular_searches = apply_filters( 'chrysoberyl_popular_searches', $default_popular );
?>

<div id="chrysoberyl-search-modal" class="chrysoberyl-search-modal hidden fixed inset-0 z-[60] flex items-start justify-center pt-24 px-4" aria-modal="true" aria-hidden="true">
    <div class="chrysoberyl-search-backdrop fixed inset-0 bg-gray-600 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>

    <div class="chrysoberyl-search-modal-content relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all">
        <div class="flex items-center gap-4 px-6 py-4">
            <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" role="search" class="flex flex-1 items-center gap-4 min-w-0">
                <svg class="text-gray-400 w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="search" name="s"
                    class="chrysoberyl-search-input w-full text-xl font-normal text-google-gray placeholder-gray-400 border-none focus:ring-0 bg-transparent h-12"
                    placeholder="<?php echo esc_attr( $search_placeholder ); ?>"
                    autocomplete="off"
                    aria-label="<?php esc_attr_e( 'Search', 'chrysoberyl' ); ?>">
            </form>
            <button type="button" class="chrysoberyl-search-close p-2 text-gray-400 hover:text-gray-600 rounded-full transition-colors shrink-0" aria-label="<?php esc_attr_e( 'Close search', 'chrysoberyl' ); ?>">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Popular Searches (mockup) -->
        <div class="border-t border-gray-100 px-6 py-4 bg-gray-50">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3"><?php _e( 'Popular Searches', 'chrysoberyl' ); ?></p>
            <div class="flex flex-wrap gap-2">
                <?php foreach ( $popular_searches as $term ) :
                    $term_slug = is_array( $term ) ? ( $term['slug'] ?? $term['label'] ?? '' ) : $term;
                    $term_label = is_array( $term ) ? ( $term['label'] ?? $term_slug ) : $term;
                    $search_url = home_url( '/?s=' . rawurlencode( $term_label ) );
                ?>
                <a href="<?php echo esc_url( $search_url ); ?>"
                    class="px-3 py-1 bg-white border border-gray-200 rounded-lg text-sm text-gray-600 hover:border-google-blue hover:text-google-blue transition-colors">
                    <?php echo esc_html( $term_label ); ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="chrysoberyl-search-suggestions border-t border-gray-100 max-h-64 overflow-y-auto"></div>
    </div>
</div>
