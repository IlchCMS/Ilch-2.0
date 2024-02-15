import { View } from '@ckeditor/ckeditor5-ui';

import '../../theme/characterinfo.css';

export default class CharacterInfoView extends View {
	constructor( locale ) {
		super( locale );

		const bind = this.bindTemplate;

		this.set( 'character', null );

		this.set( 'name', null );

		this.bind( 'code' ).to( this, 'character', characterToUnicodeString );

		this.setTemplate( {
			tag: 'div',
			children: [
				{
					tag: 'span',
					attributes: {
						class: [
							'ck-character-info__name'
						]
					},
					children: [
						{
							// Note: ZWSP to prevent vertical collapsing.
							text: bind.to( 'name', name => name ? name : '\u200B' )
						}
					]
				},
				{
					tag: 'span',
					attributes: {
						class: [
							'ck-character-info__code'
						]
					},
					children: [
						{
							text: bind.to( 'code' )
						}
					]
				}
			],
			attributes: {
				class: [
					'ck',
					'ck-character-info'
				]
			}
		} );
	}
}

// Converts a character into a "Unicode string", for instance:
//
//	"$" -> "U+0024"
//
// Returns an empty string when the character is `null`.
//
// @param {String} character
// @returns {String}
function characterToUnicodeString( character ) {
	if ( character === null ) {
		return '';
	}

	const hexCode = character.codePointAt( 0 ).toString( 16 );

	return 'U+' + ( '0000' + hexCode ).slice( -4 );
}
