CKEDITOR.plugins.add( 'ilchmedia', {
    icons: 'ilchmedia',
    class: 'cke_label',
    init: function( editor ) {
        editor.addCommand( 'ilchmediaDialog', new CKEDITOR.dialogCommand( 'ilchmediaDialog', { allowedContent: 'video[*];iframe[*]' } ) );
        editor.ui.addButton( 'ilchmedia', {
            label: 'Ilchmedia',
            command: 'ilchmediaDialog',
            toolbar: 'ilchmedia'
        });

        CKEDITOR.dialog.add( 'ilchmediaDialog', this.path + 'dialogs/ilchmedia.js' );
    }
});