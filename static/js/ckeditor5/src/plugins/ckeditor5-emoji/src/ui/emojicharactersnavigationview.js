import { Collection } from '@ckeditor/ckeditor5-utils';
import { ViewModel, FormHeaderView, createDropdown, addListToDropdown } from '@ckeditor/ckeditor5-ui';

export default class EmojiCharactersNavigationView extends FormHeaderView {

	constructor( locale, groupNames ) {
		super( locale );

		const t = locale.t;

		this.set( 'class', 'ck-special-characters-navigation' );

		this.groupDropdownView = this._createGroupDropdown( groupNames );
		this.groupDropdownView.panelPosition = locale.uiLanguageDirection === 'rtl' ? 'se' : 'sw';

		/**
		 * @inheritDoc
		 */
		this.label = t( 'Emoji' );

		/**
		 * @inheritDoc
		 */
		this.children.add( this.groupDropdownView );
	}

	/**
	 * Returns the name of the character group currently selected in the {@link #groupDropdownView}.
	 *
	 * @returns {String}
	 */
	get currentGroupName() {
		return this.groupDropdownView.value;
	}

	_createGroupDropdown( groupNames ) {
		const locale = this.locale;
		const t = locale.t;
		const dropdown = createDropdown( locale );
		const groupDefinitions = this._getCharacterGroupListItemDefinitions( dropdown, groupNames );

		dropdown.set( 'value', groupDefinitions.first.model.label );

		dropdown.buttonView.bind( 'label' ).to( dropdown, 'value' );

		dropdown.buttonView.set( {
			isOn: false,
			withText: true,
			tooltip: t( 'Emoji categories' ),
			class: [ 'ck-dropdown__button_label-width_auto' ]
		} );

		dropdown.on( 'execute', evt => {
			dropdown.value = evt.source.label;
		} );

		dropdown.delegate( 'execute' ).to( this );

		addListToDropdown( dropdown, groupDefinitions );

		return dropdown;
	}

	/**
	 * Returns list item definitions to be used in the character group dropdown
	 * representing specific character groups.
	 *
	 */
	_getCharacterGroupListItemDefinitions( dropdown, groupNames ) {
		const groupDefs = new Collection();

		for ( const name of groupNames ) {
			const definition = {
				type: 'button',
				model: new ViewModel( {
					label: name,
					withText: true
				} )
			};

			definition.model.bind( 'isOn' ).to( dropdown, 'value', value => {
				return value === definition.model.label;
			} );

			groupDefs.add( definition );
		}

		return groupDefs;
	}
}
