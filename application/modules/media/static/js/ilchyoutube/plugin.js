CKEDITOR.plugins.add( 'ilchyoutube', {
    icons: 'ilchyoutube',
    class: 'cke_label',
    init: function( editor ) {
        editor.addCommand( 'ilchyoutubeDialog', new CKEDITOR.dialogCommand( 'ilchyoutubeDialog', {} ) );
        editor.ui.addButton( 'ilchyoutube', {
            label: 'Youtube',
            command: 'ilchyoutubeDialog',
            toolbar: 'ilchyoutube'
        });

        CKEDITOR.dialog.add( 'ilchyoutubeDialog', this.path + 'dialogs/ilchyoutube.js' );
    }
});