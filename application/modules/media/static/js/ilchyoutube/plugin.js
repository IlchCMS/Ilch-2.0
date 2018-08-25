CKEDITOR.plugins.add('ilchyoutube', {
    init: function(editor) {
        editor.addCommand('ilchYoutubeDialog', new CKEDITOR.dialogCommand('ilchYoutubeDialog', {}));
        editor.ui.addButton('ilchyoutube', {
            label: '',
            toolbar: 'ilchyoutube',
            command: 'ilchYoutubeDialog',
            icon: this.path + 'icons/youtube.png'
        });

        CKEDITOR.dialog.add('ilchYoutubeDialog', this.path + 'dialogs/ilchyoutube.js');
    }
});