<?php
/**
 * The template for displaying 404 pages (not found)
 * Design aligned with mockup/404.html
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

get_header();
?>

<main id="main-content" class="flex-grow flex items-center justify-center px-4 sm:px-6 lg:px-8 w-full">
    <div class="text-center max-w-lg w-full">
        <!-- 404 Illustration (mockup: colored 4-0-4) -->
        <div class="mb-8">
            <div class="text-[120px] md:text-[180px] font-medium leading-none select-none" aria-hidden="true">
                <span class="text-google-blue">4</span>
                <span class="text-[#ea4335]">0</span>
                <span class="text-[#fbbc04]">4</span>
            </div>
        </div>

        <h1 class="text-2xl md:text-3xl font-normal text-google-gray mb-4">
            <?php _e( 'The page you are looking for was not found', 'chrysoberyl' ); ?>
        </h1>
        <p class="text-lg text-google-gray-500 mb-8">
            <?php _e( 'The page you want may have been moved, removed, or the URL is incorrect.', 'chrysoberyl' ); ?>
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                class="inline-flex items-center justify-center px-8 py-3 bg-google-blue text-white font-medium rounded-pill hover:bg-blue-700 transition-all shadow-md hover:shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <?php _e( 'Back to home', 'chrysoberyl' ); ?>
            </a>
            <?php
            $contact_url = home_url( '/contact' );
            $contact_page = get_page_by_path( 'contact' );
            if ( $contact_page ) {
                $contact_url = get_permalink( $contact_page );
            }
            ?>
            <a href="<?php echo esc_url( $contact_url ); ?>"
                class="inline-flex items-center justify-center px-8 py-3 border border-gray-300 text-google-gray font-medium rounded-pill hover:bg-google-gray-50 transition-all">
                <?php _e( 'Report a problem', 'chrysoberyl' ); ?>
            </a>
        </div>

        <!-- Search Suggestion (mockup) -->
        <div class="mt-12 pt-8 border-t border-gray-200">
            <p class="text-sm text-google-gray-500 mb-4">
                <?php _e( 'Or try searching for what you need', 'chrysoberyl' ); ?>
            </p>
            <div class="max-w-md mx-auto">
                <form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="relative" role="search">
                    <label for="404-search" class="sr-only"><?php esc_attr_e( 'Search', 'chrysoberyl' ); ?></label>
                    <input type="search"
                        id="404-search"
                        name="s"
                        class="w-full px-5 py-3 pl-12 rounded-full border border-gray-300 focus:border-google-blue focus:ring-2 focus:ring-google-blue focus:ring-opacity-20 outline-none transition-all text-google-gray placeholder-google-gray-500"
                        placeholder="<?php esc_attr_e( 'Search articles...', 'chrysoberyl' ); ?>"
                        value="<?php echo get_search_query() ? esc_attr( get_search_query() ) : ''; ?>">
                    <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-google-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </form>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
