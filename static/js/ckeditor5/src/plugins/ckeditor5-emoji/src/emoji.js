import { Plugin } from '@ckeditor/ckeditor5-core';
import { Typing } from '@ckeditor/ckeditor5-typing';
import { createDropdown } from '@ckeditor/ckeditor5-ui';
import { CKEditorError } from '@ckeditor/ckeditor5-utils';
import EmojiCharactersNavigationView from './ui/emojicharactersnavigationview';
import CharacterGridView from './ui/charactergridview';
import CharacterInfoView from './ui/characterinfoview';

import emojiIcon from '../theme/icons/emoji-icon.svg';
import '../theme/emoji-characters.css';

const ALL_EMOJI_CHARACTERS_GROUP = 'All';

export default class Emoji extends Plugin {
	/**
	 * @inheritDoc
	 */
	static get requires() {
		return [ Typing ];
	}

	/**
	 * @inheritDoc
	 */
	static get pluginName() {
		return 'Emoji';
	}

	/**
	 * @inheritDoc
	 */
	constructor( editor ) {
		super( editor );
		this._characters = new Map();
		this._groups = new Map();
	}

	/**
	 * @inheritDoc
	 */
	init() {
		const editor = this.editor;
		const t = editor.t;

		const inputCommand = editor.commands.get( 'input' );

		editor.ui.componentFactory.add( 'emoji', locale => {
			const dropdownView = createDropdown( locale );
			let dropdownPanelContent;

			dropdownView.buttonView.set( {
				label: t( 'Emoji' ),
				icon: emojiIcon,
				tooltip: true
			} );

			dropdownView.bind( 'isEnabled' ).to( inputCommand );

			// Insert a special character when a tile was clicked.
			dropdownView.on( 'execute', ( evt, data ) => {
				editor.execute( 'input', { text: data.character } );
				editor.editing.view.focus();
			} );

			dropdownView.on( 'change:isOpen', () => {
				if ( !dropdownPanelContent ) {
					dropdownPanelContent = this._createDropdownPanelContent( locale, dropdownView );

					dropdownView.panelView.children.add( dropdownPanelContent.navigationView );
					dropdownView.panelView.children.add( dropdownPanelContent.gridView );
					dropdownView.panelView.children.add( dropdownPanelContent.infoView );
				}

				dropdownPanelContent.infoView.set( {
					character: null,
					name: null
				} );
			} );

			return dropdownView;
		} );
	}

	addItems( groupName, items ) {
		if ( groupName === ALL_EMOJI_CHARACTERS_GROUP ) {
			throw new CKEditorError(
				`emoji-group-error-name: The name "${ ALL_EMOJI_CHARACTERS_GROUP }" is reserved and cannot be used.`
			);
		}

		const group = this._getGroup( groupName );

		for ( const item of items ) {
			group.add( item.title );
			this._characters.set( item.title, item.character );
		}
	}

	getGroups() {
		return this._groups.keys();
	}

	getCharactersForGroup( groupName ) {
		if ( groupName === ALL_EMOJI_CHARACTERS_GROUP ) {
			return new Set( this._characters.keys() );
		}

		return this._groups.get( groupName );
	}

	getCharacter( title ) {
		return this._characters.get( title );
	}

	_getGroup( groupName ) {
		if ( !this._groups.has( groupName ) ) {
			this._groups.set( groupName, new Set() );
		}

		return this._groups.get( groupName );
	}

	_updateGrid( currentGroupName, gridView ) {
		gridView.tiles.clear();
		const characterTitles = this.getCharactersForGroup( currentGroupName );
		for ( const title of characterTitles ) {
			const character = this.getCharacter( title );
			gridView.tiles.add( gridView.createTile( character, title ) );
		}
	}

	_createDropdownPanelContent( locale, dropdownView ) {
		const specialCharsGroups = [ ...this.getGroups() ];

		// Add a special group that shows all available special characters.
		specialCharsGroups.unshift( ALL_EMOJI_CHARACTERS_GROUP );

		const navigationView = new EmojiCharactersNavigationView( locale, specialCharsGroups );
		const gridView = new CharacterGridView( locale );
		const infoView = new CharacterInfoView( locale );

		gridView.delegate( 'execute' ).to( dropdownView );

		gridView.on( 'tileHover', ( evt, data ) => {
			infoView.set( data );
		} );

		// Update the grid of special characters when a user changed the character group.
		navigationView.on( 'execute', () => {
			this._updateGrid( navigationView.currentGroupName, gridView );
		} );

		// Set the initial content of the special characters grid.
		this._updateGrid( navigationView.currentGroupName, gridView );

		return { navigationView, gridView, infoView };
	}
}
