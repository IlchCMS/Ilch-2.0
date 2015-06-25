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
        config.extraPlugins = "ilchmedia";
    }
};