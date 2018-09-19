/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

var basePath = CKEDITOR.basePath;
basePath = basePath.substr(0, basePath.indexOf("vendor/ckeditor/"));

CKEDITOR.plugins.addExternal('ilchmedia', basePath+'application/modules/media/static/js/ilchmedia/');

CKEDITOR.editorConfig = function( config ) {
    if (typeof ilchPsPlugin !== "undefined") {
        CKEDITOR.plugins.addExternal('ilchps', ilchPsPlugin);
        config.extraPlugins = "justify,font,colorbutton,colordialog,tableresize,ilchmedia,ilchps";
    } else {
        config.extraPlugins = "justify,font,colorbutton,colordialog,tableresize,ilchmedia";
    }

    config.protectedSource.push(/<i[^>]*><\/i>/g);
    config.protectedSource.push(/<\?[\s\S]*?\?>/g);
    config.toolbar = 'ilch_html';
    config.toolbar_ilch_html = [
        { name: 'document', groups: [ 'mode' ], items: [ 'Source' ] },
        { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
        { name: 'editing', groups: [ 'spellchecker' ], items: [ 'Scayt' ] },
        '/',
        { name: 'basicstyles', groups: [ 'basicstyles', 'alignment', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'RemoveFormat' ] },
        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
        { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
        { name: 'insert', items: [ 'Table', 'HorizontalRule' ] },
        '/',
        { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
        { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
        { name: 'tools', items: [ 'Maximize' ] },
        { name: 'insert', items: [ 'ilchmedia', 'ilchps' ] }
    ];
};
