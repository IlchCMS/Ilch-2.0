/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
    if (typeof ilchSmileysPlugin !== "undefined") {
        CKEDITOR.plugins.addExternal( 'ilchsmileys', ilchSmileysPlugin);
        config.extraPlugins = "ilchsmileys,bbcode";
    }
    config.toolbar = 'ilch_bbcode';
    config.toolbar_ilch_bbcode =
        [
            ['Undo', 'Redo'],
            ['RemoveFormat'],
            ['Link', 'Unlink', 'Image', 'ilchsmileys'],
            ['Bold', 'Italic', 'Underline'],
            ['Maximize']
        ];
};
