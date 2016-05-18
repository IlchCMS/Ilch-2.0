/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
    config.toolbar = 'ilch_bbcode';
    config.toolbar_ilch_bbcode =
        [
            ['Undo', 'Redo' ],
            ['RemoveFormat'],
            [ 'Link', 'Unlink', 'Image', 'Smiley'],
            [ 'Bold', 'Italic', 'Underline'],
            [ 'Maximize']
        ];
};
