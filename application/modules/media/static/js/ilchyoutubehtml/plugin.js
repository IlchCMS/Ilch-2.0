CKEDITOR.plugins.add('ilchyoutubehtml', {
    lang: ['de', 'en'],
    init: function(editor) {
        editor.addCommand('ilchYoutubeHtmlDialog', new CKEDITOR.dialogCommand('ilchYoutubeHtmlDialog'));
        editor.ui.addButton('ilchyoutubehtml', {
            label: '',
            toolbar: 'insert',
            command: 'ilchYoutubeHtmlDialog',
            icon: this.path + 'icons/youtube.png'
        });

        CKEDITOR.dialog.add('ilchYoutubeHtmlDialog', this.path + 'dialogs/ilchyoutubehtml.js');
    }
});
