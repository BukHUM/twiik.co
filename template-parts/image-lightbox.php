<?php
/**
 * Template part: Image lightbox modal (ภาพประกอบบทความ – คลิกขยายแบบ modal)
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */
?>
<div id="chrysoberyl-image-lightbox" class="chrysoberyl-image-lightbox hidden fixed inset-0 z-[70] flex items-center justify-center p-4" aria-modal="true" aria-hidden="true" role="dialog" aria-label="<?php esc_attr_e( 'ขยายภาพ', 'chrysoberyl' ); ?>">
    <div class="chrysoberyl-image-lightbox-backdrop absolute inset-0 bg-black/80 backdrop-blur-sm" aria-hidden="true"></div>
    <div class="chrysoberyl-image-lightbox-inner relative z-10 max-w-[90vw] max-h-[90vh] flex items-center justify-center">
        <img src="" alt="" class="chrysoberyl-image-lightbox-img max-w-full max-h-[90vh] w-auto h-auto object-contain rounded-lg shadow-2xl" />
        <button type="button" class="chrysoberyl-image-lightbox-close absolute -top-12 right-0 p-2 text-white/90 hover:text-white rounded-full hover:bg-white/10 transition-colors focus:outline-none focus:ring-2 focus:ring-white/50" aria-label="<?php esc_attr_e( 'ปิด', 'chrysoberyl' ); ?>">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
</div>
