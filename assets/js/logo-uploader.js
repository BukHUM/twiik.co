/**
 * Logo Uploader Script for Chrysoberyl Theme
 * 
 * @package chrysoberyl
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    var logoUploader;
    
    // Wait for wp.media to be available
    function initLogoUploader() {
        if (typeof wp === 'undefined' || typeof wp.media === 'undefined') {
            setTimeout(initLogoUploader, 100);
            return;
        }
        
        $('#chrysoberyl_upload_logo_btn').off('click').on('click', function(e) {
            e.preventDefault();
            
            // If the uploader object has already been created, reopen it
            if (logoUploader) {
                logoUploader.open();
                return;
            }
            
            // Create the media uploader
            logoUploader = wp.media({
                title: chrysoberylLogo.chooseLogo,
                button: {
                    text: chrysoberylLogo.useLogo
                },
                library: {
                    type: 'image'
                },
                multiple: false
            });
            
            // When an image is selected, run a callback
            logoUploader.on('select', function() {
                var attachment = logoUploader.state().get('selection').first().toJSON();
                $('#chrysoberyl_logo').val(attachment.id);
                $('#chrysoberyl_logo_preview').html('<img src="' + attachment.url + '" style="max-width: 200px; height: auto; display: block; margin-bottom: 10px;" />');
                $('#chrysoberyl_upload_logo_btn').text(chrysoberylLogo.changeLogo);
                if ($('#chrysoberyl_remove_logo_btn').length === 0) {
                    $('#chrysoberyl_upload_logo_btn').after('<button type="button" class="button" id="chrysoberyl_remove_logo_btn" style="margin-left: 10px;">' + chrysoberylLogo.removeLogo + '</button>');
                }
            });
            
            // Open the uploader
            logoUploader.open();
        });
        
        // Remove logo
        $(document).off('click', '#chrysoberyl_remove_logo_btn').on('click', '#chrysoberyl_remove_logo_btn', function(e) {
            e.preventDefault();
            $('#chrysoberyl_logo').val('');
            $('#chrysoberyl_logo_preview').html('');
            $('#chrysoberyl_upload_logo_btn').text(chrysoberylLogo.uploadLogo);
            $(this).remove();
        });
    }
    
    // Initialize when DOM is ready
    $(document).ready(function() {
        initLogoUploader();
    });
    
})(jQuery);
