<?php
/**
 * Shortcodes for Chrysoberyl theme
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcode [code lang="html"]...[/code] – แสดง code block พร้อมภาษา (สำหรับ Classic Editor)
 * ใช้ในโหมด Visual หรือ Text: ใส่ shortcode แล้ววางโค้ดระหว่างเปิด-ปิด
 *
 * ตัวอย่าง:
 * [code lang="html"]
 * <!DOCTYPE html>
 * <html>...</html>
 * [/code]
 *
 * ค่า lang ที่ใช้ได้: html, javascript, js, css, php, python, ruby, etc. (ตามที่ Prism รองรับ)
 */
function chrysoberyl_code_shortcode( $atts, $content = '' ) {
	$atts = shortcode_atts(
		array(
			'lang' => 'html',
		),
		$atts,
		'code'
	);
	$lang = sanitize_key( $atts['lang'] );
	// แปลง js → javascript สำหรับ Prism
	if ( 'js' === $lang ) {
		$lang = 'javascript';
	}
	// ลบ tag ที่ TinyMCE อาจใส่ (โหมด Visual) และ decode entities ที่ shortcode สร้าง
	$content = trim( strip_tags( $content ) );
	$content = preg_replace( '/<br\s*\/?>\s*/i', "\n", $content );
	if ( '' === $content ) {
		return '';
	}
	return '<pre class="language-' . esc_attr( $lang ) . '"><code class="language-' . esc_attr( $lang ) . '">' . esc_html( $content ) . '</code></pre>';
}
add_shortcode( 'code', 'chrysoberyl_code_shortcode' );
