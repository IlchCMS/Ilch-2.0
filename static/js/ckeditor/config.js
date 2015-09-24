/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
    if (typeof ilchMediaPlugin !== "undefined" && typeof ilchPsPlugin !== "undefined") {
        CKEDITOR.plugins.addExternal( 'ilchmedia', ilchMediaPlugin);
        CKEDITOR.plugins.addExternal( 'ilchps', ilchPsPlugin);
        config.extraPlugins = "ilchmedia,ilchps";
    }
    else if (typeof ilchMediaPlugin !== "undefined") {
        CKEDITOR.plugins.addExternal( 'ilchmedia', ilchMediaPlugin);
        config.extraPlugins = "ilchmedia,showprotected";
    }

    config.protectedSource.push(/<\?[\s\S]*?\?>/g);
    config.toolbar = 'ilch_html';
    config.toolbar_ilch_html =
        [
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
            { name: 'insert', items: [ 'ilchmedia', 'ilchps' ] }
        ];

    config.toolbar = 'ilch_bbcode';
    config.toolbar_ilch_bbcode =
        [
            ['Undo', 'Redo' ],
            ['RemoveFormat'],
            [ 'Link', 'Unlink', 'Image'],
            [ 'Bold', 'Italic', 'Underline'],
            [ 'Maximize']
        ];
};