$(document).ready(function(){
    if ($("#ilch_html").length) {
        CKEDITOR.replace('ilch_html', {
             removePlugins: 'bbcode',
             disableObjectResizing: false,
             toolbar: 'Basic',
         });
    }

    if ($("#ilch_bbcode").length) {
        CKEDITOR.replace('ilch_bbcode', {
            extraPlugins: 'bbcode',
            disableObjectResizing: true,
            toolbar: [
                ['Undo', 'Redo' ],
                ['RemoveFormat'],
                [ 'Link', 'Unlink', 'Image'],
                [ 'Bold', 'Italic', 'Underline'],
                [ 'Maximize']
            ],
        });
    }
});