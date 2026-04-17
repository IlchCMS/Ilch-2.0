import { Plugin, ButtonView } from 'ckeditor5';

import ilchpsIcon from './icons/ilchps.svg';

export default class Ilchps extends Plugin {
	static get pluginName() {
		return 'Ilchps';
	}

	init() {
		const editor = this.editor;
		const t = editor.t;
		const model = editor.model;

		// Register the "ilchps" button, so it can be displayed in the toolbar.
		editor.ui.componentFactory.add( 'ilchps', locale => {
			const view = new ButtonView( locale );

			view.iconView.set( 'viewBox', '0 0 32 32' );
			view.set( {
				label: t( 'Ilchps' ),
				icon: ilchpsIcon,
				tooltip: true
			} );

			// Insert a text into the editor after clicking the button.
			this.listenTo( view, 'execute', () => {
				model.change( writer => {
					const textNode = writer.createText( '[PREVIEWSTOP]' );

					model.insertContent( textNode );
				} );

				editor.editing.view.focus();
			} );

			return view;
		} );
	}
}
