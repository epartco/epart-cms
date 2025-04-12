import './bootstrap';

import Alpine from 'alpinejs';

// Import TinyMCE
import tinymce from 'tinymce/tinymce';
import 'tinymce/themes/silver/theme'; // Theme
import 'tinymce/icons/default/icons'; // Icons
import 'tinymce/skins/ui/oxide/skin.min.css'; // Default skin
import 'tinymce/skins/content/default/content.min.css'; // Default content CSS
import 'tinymce/models/dom/model'; // Required for the editor to function

// Import plugins
import 'tinymce/plugins/lists';
import 'tinymce/plugins/link';
import 'tinymce/plugins/image';
import 'tinymce/plugins/code';
import 'tinymce/plugins/table';
import 'tinymce/plugins/media'; // Added media plugin

window.Alpine = Alpine;

// Initialize TinyMCE
document.addEventListener('DOMContentLoaded', () => {
    tinymce.init({
        selector: 'textarea.tinymce-editor',
        plugins: 'lists link image code table media', // Added media plugin
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image media | code', // Added media button
        skin: false, // Use the imported skin
        content_css: false, // Use the imported content CSS
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }', // Basic styling
        height: 500,
        file_picker_callback: function (callback, value, meta) {
            // Provide file and text for the link dialog
            if (meta.filetype == 'file') {
                // Implement file picker if needed, for now just allow manual URL entry
            }

            // Provide image and media dimensions/alt text for the image dialog
            if (meta.filetype == 'image' || meta.filetype == 'media') {
                // Dispatch event to open the Alpine modal
                window.dispatchEvent(new CustomEvent('open-media-modal', {
                    detail: {
                        callback: (url, metadata) => {
                            // Pass the selected URL and metadata (like alt text) back to TinyMCE
                            callback(url, metadata);
                        }
                    }
                }));
            }
        },
        // Ensure relative URLs are not converted to absolute
        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,
    });
});

Alpine.start();
