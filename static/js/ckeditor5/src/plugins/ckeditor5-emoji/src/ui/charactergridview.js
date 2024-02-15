import { View, ButtonView } from '@ckeditor/ckeditor5-ui';

import '../../theme/charactergrid.css';


export default class CharacterGridView extends View {

	constructor( locale ) {
		super( locale );

		this.tiles = this.createCollection();

		this.setTemplate( {
			tag: 'div',
			children: [
				{
					tag: 'div',
					attributes: {
						class: [
							'ck',
							'ck-character-grid__tiles'
						]
					},
					children: this.tiles
				}
			],
			attributes: {
				class: [
					'ck',
					'ck-character-grid'
				]
			}
		} );
	}

	createTile( character, name ) {
		const tile = new ButtonView( this.locale );

		tile.set( {
			label: character,
			withText: true,
			class: 'ck-character-grid__tile ck-emoji-grid-font-size'
		} );

		// Labels are vital for the users to understand what character they're looking at.
		// For now we're using native title attribute for that, see #5817.
		tile.extendTemplate( {
			attributes: {
				title: name
			},
			on: {
				mouseover: tile.bindTemplate.to( 'mouseover' )
			}
		} );

		tile.on( 'mouseover', () => {
			this.fire( 'tileHover', { name, character } );
		} );

		tile.on( 'execute', () => {
			this.fire( 'execute', { name, character } );
		} );

		return tile;
	}
}
