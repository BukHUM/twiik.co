<?php
/**
 * The footer template file (mockup: components/footer.html)
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

$show_menu_section = get_option( 'chrysoberyl_footer_menu_section_enabled', '1' ) === '1';
$show_legal_section = get_option( 'chrysoberyl_footer_legal_section_enabled', '1' ) === '1';
$show_newsletter = get_option( 'chrysoberyl_footer_newsletter_enabled', '1' ) === '1';
$show_tags = get_option( 'chrysoberyl_footer_tags_enabled', '1' ) === '1';
$fb_url = get_option( 'chrysoberyl_facebook_url', '' );
$tw_url = get_option( 'chrysoberyl_twitter_url', '' );
$ig_url = get_option( 'chrysoberyl_instagram_url', '' );
$footer2_menu = (int) get_option( 'chrysoberyl_footer2_menu', 0 );
$footer3_menu = (int) get_option( 'chrysoberyl_footer3_menu', 0 );
$copyright_text = get_option( 'chrysoberyl_footer_copyright_text', '' );
$theme_logo_id = get_option( 'chrysoberyl_logo', '' );
$theme_logo_url = $theme_logo_id ? wp_get_attachment_image_url( $theme_logo_id, 'full' ) : '';
if ( ! $theme_logo_url && has_custom_logo() ) {
    $cid = get_theme_mod( 'custom_logo' );
    $theme_logo_url = $cid ? wp_get_attachment_image_url( $cid, 'full' ) : '';
}

if ( ! class_exists( 'Chrysoberyl_Footer_Menu_Walker' ) ) {
    class Chrysoberyl_Footer_Menu_Walker extends Walker_Nav_Menu {
        public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
            $output .= '<li><a href="' . esc_url( $item->url ) . '" class="hover:text-google-blue transition-colors">' . esc_html( $item->title ) . '</a></li>';
        }
    }
}
// เมื่อปิดทั้งส่วนเมนู footer และแถบ legal = ไม่แสดง footer เลย (รวม newsletter, tags ด้วย)
$show_footer = $show_menu_section || $show_legal_section;
?>

<?php if ( $show_footer ) : ?>
<?php
// เมื่อแสดงเฉพาะแถบ legal ไม่ใส่เส้นบนและ padding บนของ footer เพื่อให้เนื้อหาชิดแถบ legal
$footer_top_classes = $show_menu_section ? 'border-t border-gray-200 pt-12 md:pt-16' : 'pt-0';
?>
<footer class="bg-google-gray-50 <?php echo esc_attr( $footer_top_classes ); ?>" role="contentinfo">
    <?php if ( $show_menu_section ) : ?>
    <div class="container mx-auto px-4 md:px-6 lg:px-8 max-w-[1248px] mb-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
            <!-- About -->
            <div class="col-span-1 md:col-span-1">
                <h4 class="font-medium text-google-gray mb-4"><?php _e( 'About', 'chrysoberyl' ); ?> <?php bloginfo( 'name' ); ?></h4>
                <p class="text-sm text-google-gray-500 leading-relaxed">
                    <?php echo esc_html( get_bloginfo( 'description' ) ?: __( 'A modern platform for sharing insights, stories, and innovations in technology and digital transformation.', 'chrysoberyl' ) ); ?>
                </p>
            </div>
            <!-- More from us -->
            <div class="col-span-1">
                <h4 class="font-medium text-google-gray mb-4"><?php _e( 'More from us', 'chrysoberyl' ); ?></h4>
                <?php
                if ( $footer2_menu ) {
                    wp_nav_menu( array( 'menu' => $footer2_menu, 'container' => false, 'menu_class' => 'space-y-3 text-sm text-google-gray-500', 'depth' => 1, 'walker' => new Chrysoberyl_Footer_Menu_Walker() ) );
                } else {
                    echo '<ul class="space-y-3 text-sm text-google-gray-500">';
                    $cats = get_categories( array( 'number' => 5, 'orderby' => 'count', 'order' => 'DESC' ) );
                    foreach ( $cats as $cat ) {
                        echo '<li><a href="' . esc_url( get_category_link( $cat->term_id ) ) . '" class="hover:text-google-blue transition-colors">' . esc_html( $cat->name ) . '</a></li>';
                    }
                    if ( empty( $cats ) ) {
                        echo '<li><a href="' . esc_url( home_url( '/' ) ) . '" class="hover:text-google-blue transition-colors">' . esc_html__( 'Latest Stories', 'chrysoberyl' ) . '</a></li>';
                    }
                    echo '</ul>';
                }
                ?>
            </div>
            <!-- Support -->
            <div class="col-span-1">
                <h4 class="font-medium text-google-gray mb-4"><?php _e( 'Support', 'chrysoberyl' ); ?></h4>
                <?php
                if ( $footer3_menu ) {
                    wp_nav_menu( array( 'menu' => $footer3_menu, 'container' => false, 'menu_class' => 'space-y-3 text-sm text-google-gray-500', 'depth' => 1, 'walker' => new Chrysoberyl_Footer_Menu_Walker() ) );
                } elseif ( has_nav_menu( 'footer' ) ) {
                    wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'menu_class' => 'space-y-3 text-sm text-google-gray-500', 'depth' => 1, 'walker' => new Chrysoberyl_Footer_Menu_Walker() ) );
                } else {
                    echo '<ul class="space-y-3 text-sm text-google-gray-500">';
                    ?>
                    <li><a href="<?php echo esc_url( home_url( '/about' ) ); ?>" class="hover:text-google-blue transition-colors"><?php _e( 'About Us', 'chrysoberyl' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="hover:text-google-blue transition-colors"><?php _e( 'Contact Us', 'chrysoberyl' ); ?></a></li>
                    <li><a href="<?php echo esc_url( get_privacy_policy_url() ); ?>" class="hover:text-google-blue transition-colors"><?php _e( 'Privacy Policy', 'chrysoberyl' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/terms' ) ); ?>" class="hover:text-google-blue transition-colors"><?php _e( 'Terms of Service', 'chrysoberyl' ); ?></a></li>
                    <?php
                    echo '</ul>';
                }
                ?>
            </div>
            <!-- Subscribe + Social -->
            <div class="col-span-1">
                <h4 class="font-medium text-google-gray mb-4"><?php _e( 'Subscribe', 'chrysoberyl' ); ?></h4>
                <p class="text-xs text-google-gray-500 mb-4"><?php _e( 'Get the latest updates delivered to your inbox.', 'chrysoberyl' ); ?></p>
                <?php if ( $show_newsletter ) : ?>
                <form class="flex gap-2 mb-6" onsubmit="event.preventDefault(); if(typeof handleNewsletterSubmit==='function') handleNewsletterSubmit(event);" aria-label="<?php esc_attr_e( 'Newsletter subscription', 'chrysoberyl' ); ?>">
                    <input type="email" name="email" placeholder="<?php esc_attr_e( 'Email address', 'chrysoberyl' ); ?>"
                        class="w-full px-4 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-google-blue focus:ring-1 focus:ring-google-blue newsletter-input"
                        id="footer-newsletter-email" required>
                    <button type="submit" class="px-4 py-2 bg-google-blue text-white rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors whitespace-nowrap"><?php _e( 'Sign up', 'chrysoberyl' ); ?></button>
                </form>
                <?php endif; ?>
                <div class="flex gap-4">
                    <?php if ( $fb_url ) : ?><a href="<?php echo esc_url( $fb_url ); ?>" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-gray-600 transition-colors" aria-label="Facebook"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path></svg></a><?php endif; ?>
                    <?php if ( $tw_url ) : ?><a href="<?php echo esc_url( $tw_url ); ?>" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-gray-600 transition-colors" aria-label="Twitter"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path></svg></a><?php endif; ?>
                    <?php if ( $ig_url ) : ?><a href="<?php echo esc_url( $ig_url ); ?>" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-gray-600 transition-colors" aria-label="Instagram"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 016.05 2.812c.636-.247 1.363-.416 2.427-.465C9.673 2.317 10 2.3 12 2.3H12.315zM12 7a5 5 0 100 10 5 5 0 000-10zm0 8a3 3 0 110-6 3 3 0 010 6zm5.338-3.205a1.2 1.2 0 110-2.4 1.2 1.2 0 010 2.4z" clip-rule="evenodd"></path></svg></a><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ( $show_menu_section && $show_tags ) : ?>
    <?php get_template_part( 'template-parts/trending-tags' ); ?>
    <?php endif; ?>

    <?php if ( $show_legal_section ) : ?>
    <div class="border-t border-gray-200 bg-google-gray-100">
        <div class="container mx-auto px-4 md:px-6 lg:px-8 max-w-[1248px]">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center py-8">
                <div class="flex justify-center md:justify-start">
                    <?php if ( $theme_logo_url ) : ?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php bloginfo( 'name' ); ?>">
                            <img src="<?php echo esc_url( $theme_logo_url ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="h-8 grayscale opacity-60 w-auto">
                        </a>
                    <?php else : ?>
                        <span class="text-sm font-medium text-google-gray-500 opacity-60"><?php bloginfo( 'name' ); ?></span>
                    <?php endif; ?>
                </div>
                <div class="flex justify-center items-center gap-2 text-sm text-google-gray-500 whitespace-nowrap flex-wrap">
                    <span class="text-base leading-none shrink-0" aria-hidden="true">©</span>
                    <?php
                    if ( $copyright_text !== '' ) {
                        $copyright_text = str_replace( array( '{year}', '{sitename}', '{tagline}' ), array( date( 'Y' ), get_bloginfo( 'name' ), get_bloginfo( 'description' ) ), $copyright_text );
                        echo wp_kses_post( $copyright_text );
                    } else {
                        $site_name = get_bloginfo( 'name' );
                        $tagline   = get_bloginfo( 'description' );
                        $default_copyright_url  = 'https://twiik.co';
                        $default_copyright_name = 'twiik.co';
                        echo '<a href="' . esc_url( $default_copyright_url ) . '" class="hover:text-google-blue transition-colors" target="_blank" rel="noopener noreferrer">' . esc_html( $default_copyright_name ) . '</a>';
                        if ( $tagline !== '' ) {
                            echo ' <span class="opacity-90">— ' . esc_html( $tagline ) . '</span>';
                        }
                    }
                    ?>
                </div>
                <div class="flex justify-center md:justify-end gap-6 text-sm text-google-gray-500 flex-wrap">
                    <?php
                    if ( has_nav_menu( 'footer_copyright' ) ) {
                        wp_nav_menu( array(
                            'theme_location' => 'footer_copyright',
                            'menu_class'     => 'flex justify-center md:justify-end gap-6 flex-wrap',
                            'container'      => false,
                            'depth'          => 1,
                        ) );
                    } else {
                        ?><a href="<?php echo esc_url( home_url( '/sitemap' ) ); ?>" class="hover:text-google-blue transition-colors"><?php _e( 'Sitemap', 'chrysoberyl' ); ?></a>
                        <a href="<?php echo esc_url( home_url( '/faq' ) ); ?>" class="hover:text-google-blue transition-colors"><?php _e( 'FAQ', 'chrysoberyl' ); ?></a>
                        <a href="<?php echo esc_url( get_privacy_policy_url() ); ?>" class="hover:text-google-blue transition-colors"><?php _e( 'Privacy', 'chrysoberyl' ); ?></a>
                        <a href="<?php echo esc_url( home_url( '/terms' ) ); ?>" class="hover:text-google-blue transition-colors"><?php _e( 'Terms', 'chrysoberyl' ); ?></a>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</footer>
<?php endif; ?>

<?php
$display_positions = get_option( 'chrysoberyl_social_display_positions', array( 'single_bottom' ) );
$show_floating_share = in_array( 'floating', $display_positions );
if ( is_single() && get_option( 'chrysoberyl_social_show_on_post', '1' ) === '1' && $show_floating_share ) {
    get_template_part( 'template-parts/social-share-floating' );
}
if ( is_page() && get_option( 'chrysoberyl_social_show_on_page', '1' ) === '1' && $show_floating_share ) {
    get_template_part( 'template-parts/social-share-floating' );
}
if ( is_singular() ) {
    $toc_show_item = function_exists( 'chrysoberyl_show_toc_for_post' ) ? chrysoberyl_show_toc_for_post( get_queried_object_id() ) : ( get_option( 'chrysoberyl_toc_enabled', '1' ) === '1' );
    $toc_show_on_post = get_option( 'chrysoberyl_toc_show_on_single_post', '1' ) === '1';
    $toc_show_on_page = get_option( 'chrysoberyl_toc_show_on_single_page', '0' ) === '1';
    $toc_show = $toc_show_item && ( ( is_single() && $toc_show_on_post ) || ( is_page() && $toc_show_on_page ) );
    $toc_position = get_option( 'chrysoberyl_toc_position', 'top' );
    if ( $toc_show && $toc_position === 'floating' ) {
        get_template_part( 'template-parts/table-of-contents' );
    }
}
if ( is_single() ) {
    get_template_part( 'template-parts/floating-left-ad' );
}
get_template_part( 'template-parts/search-modal' );
get_template_part( 'template-parts/image-lightbox' );
if ( ! is_user_logged_in() ) {
    get_template_part( 'template-parts/login-modal' );
}
?>

<button id="back-to-top"
    class="fixed bottom-8 right-8 z-[100] p-3 bg-google-blue text-white rounded-full shadow-lg opacity-0 translate-y-20 pointer-events-none transition-all duration-300 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2"
    aria-label="<?php esc_attr_e( 'Back to top', 'chrysoberyl' ); ?>"
    onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"></polyline></svg>
</button>

<?php wp_footer(); ?>
</body>
</html>
