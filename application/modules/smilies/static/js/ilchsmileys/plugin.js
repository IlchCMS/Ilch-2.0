/*
 * @example An iframe-based dialog with frame window fit dialog size.
 */
(function() {
    CKEDITOR.plugins.add( 'ilchsmileys',
        {
            init: function (editor) {
                var pluginName = 'ilchsmileys';
                editor.ui.addButton('ilchsmileys',
                    {
                        label: 'Smiley',
                        command: 'OpenWindow',
                        icon: CKEDITOR.plugins.getPath('ilchsmileys') + 'icons/ilchsmileys.png'
                    });
                var cmd = editor.addCommand('OpenWindow', { exec: showMyDialog });
            }
        });
    function showMyDialog(e) {
        $('#smiliesModal').modal('show');

        var src = ilchSmileysPluginUrl;
        var height = '100%';
        var width = '100%';

        $("#smiliesModal iframe").attr({'src': src,
            'height': height,
            'width': width});
    }
})();
