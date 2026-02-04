<?php
/**
 * The header template file
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class( 'bg-gray-50 text-gray-800 font-sans antialiased flex flex-col min-h-screen' ); ?>>
<?php wp_body_open(); ?>

<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-0 focus:left-0 focus:z-50 focus:px-4 focus:py-2 focus:bg-accent focus:text-white"><?php _e( 'Skip to content', 'chrysoberyl' ); ?></a>

<?php
// Same header/navbar on every page (404, home, single, page, archive, search, sitemap, etc.)
get_template_part( 'template-parts/navbar' );
?>
