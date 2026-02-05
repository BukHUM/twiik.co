<?php
/**
 * Template part for displaying header & navbar (mockup: components/header.html)
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

$search_enabled = get_option( 'chrysoberyl_search_enabled', '1' );
$search_suggestions_style = get_option( 'chrysoberyl_search_suggestions_style', 'modal' );
$show_language = apply_filters( 'chrysoberyl_show_language_switcher', false );
?>

<header class="fixed w-full top-0 z-50 bg-google-gray-50 transition-shadow duration-300" id="main-header" role="banner">
    <div class="border-b border-gray-200">
        <div class="container mx-auto px-4 md:px-6 lg:px-8 h-16 flex items-center justify-between max-w-[1248px]">
            <!-- Logo Area -->
            <div class="flex items-center gap-6">
                <?php
                $theme_logo_id = get_option( 'chrysoberyl_logo', '' );
                $theme_logo_url = $theme_logo_id ? wp_get_attachment_image_url( $theme_logo_id, 'full' ) : '';
                if ( ! $theme_logo_url && has_custom_logo() ) {
                    $theme_logo_id = get_theme_mod( 'custom_logo' );
                    $theme_logo_url = $theme_logo_id ? wp_get_attachment_image_url( $theme_logo_id, 'full' ) : '';
                }
                $show_site_name = get_option( 'chrysoberyl_show_site_name', '1' );
                $site_name_style = get_option( 'chrysoberyl_site_name_style', 'gray' );
                $use_google_colors = ( $site_name_style === 'google_colors' );
                ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-2 group" aria-label="<?php bloginfo( 'name' ); ?>">
                    <?php if ( $theme_logo_url ) : ?>
                        <img src="<?php echo esc_url( $theme_logo_url ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="h-8 w-auto" />
                    <?php else : ?>
                        <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-google-blue text-white font-bold text-sm"><?php echo esc_html( substr( get_bloginfo( 'name' ), 0, 1 ) ); ?></span>
                    <?php endif; ?>
                    <?php if ( $show_site_name === '1' ) : ?>
                        <?php if ( $use_google_colors ) : ?>
                            <span class="text-xl font-normal relative top-[1px] chrysoberyl-site-name-google-colors"><?php echo chrysoberyl_get_site_name_google_colors(); ?></span>
                        <?php else : ?>
                            <span class="text-xl text-google-gray-500 font-normal relative top-[1px]"><?php bloginfo( 'name' ); ?></span>
                        <?php endif; ?>
                    <?php endif; ?>
                </a>
            </div>

            <!-- Navigation (Desktop) -->
            <nav class="hidden md:flex items-center gap-6 lg:gap-8" aria-label="<?php esc_attr_e( 'Main navigation', 'chrysoberyl' ); ?>">
                <?php
                wp_nav_menu( array(
                    'theme_location'  => 'primary',
                    'menu_class'      => 'flex items-center gap-6 lg:gap-8 chrysoberyl-nav-desktop',
                    'container'       => false,
                    'fallback_cb'     => false,
                    'walker'          => new chrysoberyl_Walker_Nav_Menu(),
                    'depth'           => 2,
                ) );
                ?>
            </nav>

            <!-- Icons: Language, Search, Mobile menu -->
            <div class="flex items-center gap-2">
                <?php if ( $show_language ) : ?>
                <button type="button" onclick="typeof toggleLanguageModal === 'function' && toggleLanguageModal();"
                    class="p-2 text-google-gray-500 hover:bg-google-gray-100 rounded-full transition-colors"
                    aria-label="<?php esc_attr_e( 'Language', 'chrysoberyl' ); ?>">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="#5f6368" aria-hidden="true">
                        <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zm6.93 6h-2.95a15.65 15.65 0 0 0-1.38-3.56c1.84.63 3.37 1.91 4.33 3.56zM12 4.04c.83 1.2 1.48 2.53 1.91 3.96h-3.82c.43-1.43 1.08-2.76 1.91-3.96zM4.26 14C4.1 13.36 4 12.69 4 12s.1-1.36.26-2h3.38c-.08.66-.14 1.32-.14 2 0 .68.06 1.34.14 2H4.26zm.82 2h2.95c.32 1.25.78 2.45 1.38 3.56-1.84-.63-3.37-1.9-4.33-3.56zm2.95-8H5.08c.96-1.66 2.49-2.93 4.33-3.56C8.81 5.55 8.35 6.75 8.03 8zM12 19.96c-.83-1.2-1.48-2.53-1.91-3.96h3.82c-.43 1.43-1.08 2.76-1.91 3.96zM14.34 14H9.66c-.09-.66-.16-1.32-.16-2 0-.68.07-1.35.16-2h4.68c.09.65.16 1.32.16 2 0 .68-.07 1.34-.16 2zm.25 5.56c.6-1.11 1.06-2.31 1.38-3.56h2.95c-.96 1.65-2.49 2.93-4.33 3.56zM16.36 14c.08-.66.14-1.32.14-2 0-.68-.06-1.34-.14-2h3.38c.16.64.26 1.31.26 2s-.1 1.36-.26 2h-3.38z" />
                    </svg>
                </button>
                <?php endif; ?>

                <?php if ( $search_enabled === '1' && ( $search_suggestions_style === 'modal' || $search_suggestions_style === 'fullpage' ) ) : ?>
                <button type="button" class="chrysoberyl-search-toggle p-2 text-google-gray-500 hover:bg-google-gray-100 rounded-full transition-colors"
                    aria-label="<?php esc_attr_e( 'Search', 'chrysoberyl' ); ?>">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="#5f6368" aria-hidden="true">
                        <path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" />
                    </svg>
                </button>
                <?php elseif ( $search_enabled === '1' ) : ?>
                <a href="<?php echo esc_url( home_url( '/?s=' ) ); ?>"
                    class="p-2 text-google-gray-500 hover:bg-google-gray-100 rounded-full transition-colors"
                    aria-label="<?php esc_attr_e( 'Search', 'chrysoberyl' ); ?>">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="#5f6368"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" /></svg>
                </a>
                <?php endif; ?>

                <!-- Mobile Menu Button -->
                <button type="button" onclick="typeof toggleMobileMenu === 'function' && toggleMobileMenu();"
                    class="md:hidden p-2 text-google-gray-500 hover:bg-google-gray-100 rounded-full transition-colors"
                    aria-label="<?php esc_attr_e( 'Toggle menu', 'chrysoberyl' ); ?>"
                    aria-expanded="false"
                    id="mobile-menu-button"
                    aria-controls="mobile-menu-drawer">
                    <svg class="mobile-menu-icon-open w-6 h-6" width="24" height="24" viewBox="0 0 24 24" fill="#5f6368" aria-hidden="true">
                        <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z" />
                    </svg>
                    <svg class="mobile-menu-icon-close w-6 h-6 hidden" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Drawer (mockup: right-side drawer) -->
    <div id="mobile-menu-drawer" class="fixed inset-0 z-40 hidden md:hidden" aria-hidden="true">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75 transition-opacity" onclick="typeof toggleMobileMenu === 'function' && toggleMobileMenu();" aria-hidden="true"></div>
        <nav class="fixed top-0 right-0 z-50 w-64 h-full bg-white shadow-xl py-6 px-6 transform transition-transform duration-300 ease-in-out" role="navigation" aria-label="<?php esc_attr_e( 'Mobile menu', 'chrysoberyl' ); ?>">
            <div class="flex items-center justify-between mb-8">
                <span class="text-lg font-medium text-google-gray"><?php _e( 'Menu', 'chrysoberyl' ); ?></span>
                <button type="button" onclick="typeof toggleMobileMenu === 'function' && toggleMobileMenu();" class="p-2 text-gray-500 hover:text-gray-700" aria-label="<?php esc_attr_e( 'Close menu', 'chrysoberyl' ); ?>">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="flex flex-col gap-6">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'flex flex-col gap-6 chrysoberyl-nav-mobile',
                    'container'      => false,
                    'fallback_cb'    => false,
                    'walker'         => new chrysoberyl_Walker_Nav_Menu_Mobile(),
                    'depth'          => 2,
                ) );
                ?>
            </div>
        </nav>
    </div>

    <?php if ( $show_language ) : ?>
    <!-- Language Modal (mockup: footer.html) -->
    <div id="chrysoberyl-language-modal" class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4" aria-modal="true" aria-hidden="true">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75 transition-opacity" onclick="typeof toggleLanguageModal === 'function' && toggleLanguageModal();"></div>
        <div class="relative bg-white rounded-2xl shadow-xl max-w-sm w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-google-gray"><?php _e( 'Language', 'chrysoberyl' ); ?></h3>
                <button type="button" onclick="typeof toggleLanguageModal === 'function' && toggleLanguageModal();" class="p-2 text-gray-400 hover:text-gray-600 rounded-full" aria-label="<?php esc_attr_e( 'Close', 'chrysoberyl' ); ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <p class="text-sm text-google-gray-500"><?php _e( 'Select your language. Add WPML/Polylang for multilingual.', 'chrysoberyl' ); ?></p>
        </div>
    </div>
    <?php endif; ?>
</header>
<?php
// Spacers shared by all pages (mockup: header min-h-[64px] then Spacer h-4 md:h-6).
// 1) h-16 compensates for fixed header so main content starts below the bar. On .page we collapse this via CSS to match mockup.
// 2) h-4 md:h-6 matches mockup gap between header and content. Do not remove for consistency.
?>
<div class="chrysoberyl-header-spacer h-16" aria-hidden="true"></div>
<div class="h-4 md:h-6" aria-hidden="true"></div>
