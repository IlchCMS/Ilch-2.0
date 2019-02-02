/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

var basePath = CKEDITOR.basePath;
basePath = basePath.substr(0, basePath.indexOf("vendor/ckeditor/"));

CKEDITOR.plugins.addExternal('emojione', basePath+'static/js/ckeditor/plugins/emojione/');
CKEDITOR.plugins.addExternal('ilchyoutube', basePath+'application/modules/media/static/js/ilchyoutube/');

CKEDITOR.editorConfig = function (config) {
    config.extraPlugins = 'bbcode,justify,font,colorbutton,codesnippet,emojione,ilchyoutube';

    config.fontSize_sizes = "30/30%;50/50%;100/100%;120/120%;150/150%;200/200%;300/300%";
    config.toolbar = 'ilch_bbcode';
    config.toolbar_ilch_bbcode = [
        ['Undo', 'Redo'],
        ['RemoveFormat', '-', 'Bold', 'Italic', 'Underline', 'FontSize', 'TextColor', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
        ['NumberedList', 'BulletedList', 'Blockquote', 'CodeSnippet'],
        ['Link', 'Unlink', 'Image', 'Emojione', 'ilchyoutube'],
        ['Maximize']
    ];

    config.removeDialogTabs = 'link:advanced';
};

CKEDITOR.on('dialogDefinition', function(ev) {
    var dialogName = ev.data.name,
        dialogDefinition = ev.data.definition,
        dialog = dialogDefinition.dialog;

    if (dialogName === 'codeSnippet') {
        dialog.on('show', function () {
            this.getContentElement('info','lang').getElement().hide();
        });
    }
});
