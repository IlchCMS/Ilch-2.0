/**
 * Venobox Admin Settings Helper
 */
$(document).ready(function() {

    // 1. Reset functionality
    $('#resetVenobox').on('click', function() {
        if (confirm(galleryTrans.resetConfirm)) {

            const defaults = {
                'venoboxNumeration': '1',
                'venoboxInfiniteGallery': '1',
                'venoboxTitleattr': '1',
                'venoboxNavigation': '1',
                'venoboxShare': '0',
                'venoboxFitView': '0',
                'venoboxAutoplay': '0',
                'venoboxOverlayColor': '#000000cc',
                'venoboxBgcolor': '#ffffff',
                'venoboxSpinColor': '#3498db',
                'venoboxToolsBackground': '#1a1a1a',
                'venoboxToolsColor': '#ffffff',
                'venoboxBorder': '0px',
                'venoboxMaxWidth': '100%',
                'venoboxNavSpeed': '300',
                'venoboxSpinner': 'double-bounce',
                'venoboxShareStyle': 'pill',
                'venoboxRatio': '16x9'
            };

            $.each(defaults, function(name, value) {
                let $el = $('[name="' + name + '"]');

                if ($el.length > 0) {
                    if ($el.is(':radio')) {
                        $('[name="' + name + '"][value="' + value + '"]').prop('checked', true);
                    } else {
                        $el.val(value);
                        // Update jscolor if present
                        if ($el[0].jscolor) {
                            $el[0].jscolor.fromString(value);
                        }
                    }
                }
            });

            // Visual feedback
            let $btn = $(this);
            let originalText = $btn.html();
            $btn.html('<i class="fa-solid fa-check"></i> ' + galleryTrans.resetApplied).addClass('btn-success').removeClass('btn-outline-danger');

            alert(galleryTrans.resetAlertText);

            setTimeout(function() {
                $btn.html(originalText).addClass('btn-outline-danger').removeClass('btn-success');
            }, 3000);
        }
    });

    // 2. Choices.js initialization for image source
    const sourceSelect = document.getElementById('pictureOfXSource');
    if (sourceSelect) {
        new Choices(sourceSelect, {
            removeItemButton: true,
            searchEnabled: true,
            itemSelectText: '',
            placeholder: true,
            placeholderValue: galleryTrans.pleaseSelect
        });
    }
});
