/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function (config) {
    if (typeof ilchSmileysPlugin !== "undefined") {
        CKEDITOR.plugins.addExternal('ilchsmileys', ilchSmileysPlugin);
        CKEDITOR.plugins.addExternal('ilchyoutube', ilchYoutubePlugin);
        config.extraPlugins = "bbcode,font,colorbutton,codesnippet,ilchsmileys,ilchyoutube";
        config.fontSize_sizes = "30/30%;50/50%;100/100%;120/120%;150/150%;200/200%;300/300%";
    }
    config.toolbar = 'ilch_bbcode';
    config.toolbar_ilch_bbcode = [
        ['Undo', 'Redo'],
        ['RemoveFormat', '-', 'Bold', 'Italic', 'Underline', 'FontSize', 'TextColor'],
        ['NumberedList', 'BulletedList', 'Blockquote', 'CodeSnippet'],
        ['Link', 'Unlink', 'Image', 'ilchsmileys', 'ilchyoutube'],
        ['Maximize']
    ];
};
