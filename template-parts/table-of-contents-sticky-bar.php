<?php
/**
 * Template part: Mobile-only sticky TOC bar (dropdown ติด header)
 * ใช้เมื่อเลือก Sticky Dropdown — แสดงที่ต้น main เพื่อให้อยู่ใต้ header เสมอ
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

$toc_title = get_option( 'chrysoberyl_toc_title', __( 'สารบัญ', 'chrysoberyl' ) );
?>
<div class="chrysoberyl-toc-sticky-bar-wrapper chrysoberyl-toc-sticky-bar-mobile-only" aria-hidden="false">
    <div class="chrysoberyl-toc-sticky-bar">
        <button type="button" class="chrysoberyl-toc-sticky-bar-toggle w-full flex items-center justify-between px-4 py-3 text-left bg-google-gray-100 text-google-gray border-b border-gray-200" aria-expanded="false" aria-label="<?php echo esc_attr( $toc_title ); ?>">
            <span class="chrysoberyl-toc-sticky-bar-title font-medium">
                <i class="fas fa-list-ul mr-2 text-google-gray-500"></i>
                <?php echo esc_html( $toc_title ); ?>
            </span>
            <i class="fas fa-chevron-down chrysoberyl-toc-sticky-bar-icon text-google-gray-500 transition-transform" aria-hidden="true"></i>
        </button>
    </div>
    <div class="chrysoberyl-toc-sticky-dropdown chrysoberyl-toc-sticky-dropdown-closed">
        <nav class="chrysoberyl-toc-mobile-nav chrysoberyl-toc-sticky-dropdown-nav" role="navigation" aria-label="<?php echo esc_attr( $toc_title ); ?>">
            <!-- TOC list filled by JS from .chrysoberyl-toc config -->
        </nav>
    </div>
</div>
