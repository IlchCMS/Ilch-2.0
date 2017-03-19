/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
    if (typeof ilchSmileysPlugin !== "undefined") {
        CKEDITOR.plugins.addExternal( 'ilchsmileys', ilchSmileysPlugin);
        config.extraPlugins = "ilchsmileys,bbcode,codesnippet";
    }
    config.toolbar = 'ilch_bbcode';
    config.toolbar_ilch_bbcode =
        [
            ['Undo', 'Redo'],
            ['RemoveFormat'],
            ['Bold', 'Italic', 'Underline', 'Strike'],
            ['NumberedList', 'BulletedList', 'Blockquote', 'CodeSnippet'],
            ['Link', 'Unlink', 'Image', 'ilchsmileys'],
            ['Maximize']
        ];
};
