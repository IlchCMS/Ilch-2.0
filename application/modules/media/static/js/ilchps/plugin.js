CKEDITOR.plugins.add( 'ilchps', {
    icons: 'ilchps',
    label: 'PREVIEWSTOP',
    class: 'cke_label',
    init: function ( editor ) {
        editor.addCommand('ps',
        {
            exec: function(editor){
                var previewstop = '[PREVIEWSTOP]';
                editor.insertHtml(previewstop);
            }
        });
        editor.ui.addButton( 'ilchps',
            {
                toolbar: 'ilchps',
                label: 'PREVIEWSTOP',
                command: 'ps'
            }
        );
    }
});