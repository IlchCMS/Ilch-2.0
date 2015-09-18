$(document).ready(function(){

        $('.ckeditor').each(function() {
        var id = $(this).attr('id');
        var toolbar = $(this).attr('toolbar');

        if (toolbar === 'ilch_html') {
            CKEDITOR.env.isCompatible = true;
            CKEDITOR.replace( id , {
                removePlugins: 'bbcode',
                disableObjectResizing: false,
                allowedContent: true,
                toolbar : toolbar
            });
        } else {
            CKEDITOR.env.isCompatible = true;
            CKEDITOR.replace( id , {
                disableObjectResizing: false,
                allowedContent: true,
                toolbar : toolbar
            });
        };
    });
});
