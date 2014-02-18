$(document).ready(function(){
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
});

