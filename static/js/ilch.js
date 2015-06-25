$(document).ready(function(){
    if ($("#ilch_html").length) {
        CKEDITOR.env.isCompatible = true;
        CKEDITOR.replace('ilch_html', {
             removePlugins: 'bbcode',
             disableObjectResizing: false,
             allowedContent: true,
             toolbar: [
                { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
                { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
                { name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
                '/',
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
                { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
                { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
                { name: 'insert', items: [ 'Table', 'HorizontalRule' ] },
                '/',
                { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
                { name: 'insert', items: [ 'ilchmedia', 'ilchps' ] },
            ],
         });
    }

    if ($("#ilch_bbcode").length) {
        CKEDITOR.env.isCompatible = true;
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