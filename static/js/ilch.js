$(document).ready(function(){
    $('.ckeditor').each(function() {
        var id = $(this).attr('id');
        var toolbar = $(this).attr('toolbar');

        if (CKEDITOR.instances[id]) {
            CKEDITOR.instances[id].destroy();
        }

        if (toolbar === 'ilch_html') {
            CKEDITOR.env.isCompatible = true;
            CKEDITOR.replace( id , {
                removePlugins: 'bbcode',
                disableObjectResizing: false,
                contentsCss: [CKEDITOR.basePath + '../../../vendor/twbs/bootstrap/dist/css/bootstrap.min.css'],
                allowedContent: true,
                customConfig: '../../../static/js/ckeditor/config_ilch_html.js',
                toolbar : toolbar
            });
        } else {
            CKEDITOR.env.isCompatible = true;
            CKEDITOR.replace( id , {
                disableObjectResizing: false,
                allowedContent: true,
                customConfig: '../../../static/js/ckeditor/config_ilch_bbcode.js',
                toolbar : toolbar
            });
        };
    });
});
