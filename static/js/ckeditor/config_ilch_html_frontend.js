/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

var basePath = CKEDITOR.basePath;
basePath = basePath.substr(0, basePath.indexOf("vendor/ckeditor/"));

CKEDITOR.plugins.addExternal('emojione', basePath+'static/js/ckeditor/plugins/emojione/');
CKEDITOR.plugins.addExternal('ilchyoutubehtml', basePath+'application/modules/media/static/js/ilchyoutubehtml/');

CKEDITOR.editorConfig = function( config ) {
    config.extraPlugins = "justify,font,colorbutton,colordialog,codesnippet,tableresize,emojione,ilchyoutubehtml";
    config.protectedSource.push(/<i[^>]*><\/i>/g);
    config.protectedSource.push(/<\?[\s\S]*?\?>/g);
    config.toolbarGroups = [
        { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
        { name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
        { name: 'links' },
        { name: 'insert' },
        { name: 'forms' },
        { name: 'tools' },
        { name: 'document',       groups: [ 'mode', 'document', 'doctools' ] },
        { name: 'others' },
        '/',
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
        { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
        { name: 'styles' },
        { name: 'colors' }
    ];
};
