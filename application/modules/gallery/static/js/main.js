/**
 * Venobox Admin Settings Helper
 */
$(document).ready(function() {

    // 1. Reset-Funktionalität
    $('#resetVenobox').on('click', function() {
        if (confirm('Möchten Sie alle Venobox-Einstellungen auf die Standardwerte zurücksetzen?')) {

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

            // Optisches Feedback
            let $btn = $(this);
            let originalText = $btn.html();
            $btn.html('<i class="fa-solid fa-check"></i> Eingetragen').addClass('btn-success').removeClass('btn-outline-danger');

            alert('Die Standardwerte wurden ins Formular eingetragen. Bitte klicke unten auf Speichern, um sie dauerhaft zu übernehmen.');

            setTimeout(function() {
                $btn.html(originalText).addClass('btn-outline-danger').removeClass('btn-success');
            }, 3000);
        }
    });

    // 2. Choices.js Initialisierung für Bildquelle
    const sourceSelect = document.getElementById('pictureOfXSource');
    if (sourceSelect) {
        new Choices(sourceSelect, {
            removeItemButton: true,
            searchEnabled: true,
            itemSelectText: '',
            placeholder: true,
            placeholderValue: 'Bitte wählen...'
        });
    }
});