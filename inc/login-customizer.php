<?php
/**
 * Login page customizer - match Chrysoberyl - Theme theme design
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue custom styles and font on login page (same as theme: Google Sans + Noto Sans Thai)
 */
function chrysoberyl_login_enqueue_scripts() {
	wp_enqueue_style(
		'chrysoberyl-login-font',
		'https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;700&family=Noto+Sans+Thai:wght@300;400;500;600;700&display=swap',
		array(),
		null
	);
	wp_enqueue_style(
		'chrysoberyl-login',
		get_template_directory_uri() . '/assets/css/login.css',
		array( 'chrysoberyl-login-font' ),
		wp_get_theme()->get( 'Version' )
	);
}

/**
 * Logo link goes to site home
 */
function chrysoberyl_login_headerurl( $url ) {
	return home_url( '/' );
}

/**
 * Logo title/alt text
 */
function chrysoberyl_login_headertext( $title ) {
	return get_bloginfo( 'name' );
}

/**
 * Output custom logo and inline styles for login header (when theme logo is set)
 */
function chrysoberyl_login_head() {
	$logo_id = get_option( 'chrysoberyl_logo', '' );
	if ( ! $logo_id ) {
		return;
	}
	$logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
	if ( ! $logo_url ) {
		return;
	}
	?>
	<style type="text/css">
		.login h1 a {
			background-image: url(<?php echo esc_url( $logo_url ); ?>);
			background-size: contain;
			background-position: center;
			width: 100%;
			max-width: 280px;
			height: 60px;
		}
	</style>
	<?php
}

// Only apply theme style to login page when option is enabled (Theme Settings > General).
if ( get_option( 'chrysoberyl_login_use_theme_style', '1' ) === '1' ) {
	add_action( 'login_enqueue_scripts', 'chrysoberyl_login_enqueue_scripts' );
	add_filter( 'login_headerurl', 'chrysoberyl_login_headerurl' );
	add_filter( 'login_headertext', 'chrysoberyl_login_headertext' );
	add_action( 'login_head', 'chrysoberyl_login_head' );
}
