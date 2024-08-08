/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */
import { Plugin, Typing, CKEditorError, ButtonView, MenuBarMenuListItemButtonView, DialogViewPosition, Dialog } from 'ckeditor5';
import CharacterGridView from './ui/charactergridview.js';
import CharacterInfoView from './ui/characterinfoview.js';
import EmojiView from './ui/emojiview.js';
import emojiIcon from '../theme/icons/emoji-icon.svg';
import '../theme/specialcharacters.css';
import EmojiCategoriesView from './ui/emojicategoriesview.js';
const ALL_EMOJI_GROUP = 'All';
/**
 * The emoji feature.
 *
 * Introduces the `'emoji'` dropdown.
 */
export default class Emoji extends Plugin {
    /**
     * @inheritDoc
     */
    static get requires() {
        return [Typing, Dialog];
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
    constructor(editor) {
        super(editor);
        const t = editor.t;
        this._characters = new Map();
        this._groups = new Map();
        this._allSpecialCharactersGroupLabel = t('All');
    }
    /**
     * @inheritDoc
     */
    init() {
        const editor = this.editor;
        editor.ui.componentFactory.add('emoji', () => {
            const button = this._createDialogButton(ButtonView);
            button.set({
                tooltip: true
            });
            return button;
        });
        editor.ui.componentFactory.add('menuBar:emoji', () => {
            return this._createDialogButton(MenuBarMenuListItemButtonView);
        });
    }
    /**
     * Adds a collection of emoji to the specified group. The title of a emoji must be unique.
     *
     * **Note:** The "All" category name is reserved by the plugin and cannot be used as a new name for a special
     * characters category.
     */
    addItems(groupName, items, options = { label: groupName }) {
        if (groupName === ALL_EMOJI_GROUP) {
            /**
             * The name "All" for a special category group cannot be used because it is a special category that displays all
             * available emoji.
             *
             * @error emoji-invalid-group-name
             */
            throw new CKEditorError('emoji-invalid-group-name', null);
        }
        const group = this._getGroup(groupName, options.label);
        for (const item of items) {
            group.items.add(item.title);
            this._characters.set(item.title, item.character);
        }
    }
    /**
     * Returns emoji groups in an order determined based on configuration and registration sequence.
     */
    getGroups() {
        const groups = Array.from(this._groups.keys());
        const order = this.editor.config.get('emoji.order') || [];
        const invalidGroup = order.find(item => !groups.includes(item));
        if (invalidGroup) {
            /**
             * One of the emoji groups in the "emoji.order" configuration doesn't exist.
             *
             * @error emoji-invalid-order-group-name
             */
            throw new CKEditorError('emoji-invalid-order-group-name', null, { invalidGroup });
        }
        return new Set([
            ...order,
            ...groups
        ]);
    }
    /**
     * Returns a collection of emoji symbol names (titles).
     */
    getCharactersForGroup(groupName) {
        if (groupName === ALL_EMOJI_GROUP) {
            return new Set(this._characters.keys());
        }
        const group = this._groups.get(groupName);
        if (group) {
            return group.items;
        }
    }
    /**
     * Returns the symbol of a emoji for the specified name. If the emoji could not be found, `undefined`
     * is returned.
     *
     * @param title The title of a emoji.
     */
    getCharacter(title) {
        return this._characters.get(title);
    }
    /**
     * Returns a group of emoji. If the group with the specified name does not exist, it will be created.
     *
     * @param groupName The name of the group to create.
     * @param label The label describing the new group.
     */
    _getGroup(groupName, label) {
        if (!this._groups.has(groupName)) {
            this._groups.set(groupName, {
                items: new Set(),
                label
            });
        }
        return this._groups.get(groupName);
    }
    /**
     * Updates the symbol grid depending on the currently selected character group.
     */
    _updateGrid(currentGroupName, gridView) {
        // Updating the grid starts with removing all tiles belonging to the old group.
        gridView.tiles.clear();
        const characterTitles = this.getCharactersForGroup(currentGroupName);
        for (const title of characterTitles) {
            const character = this.getCharacter(title);
            gridView.tiles.add(gridView.createTile(character, title));
        }
    }
    /**
     * Initializes the dropdown, used for lazy loading.
     *
     * @returns An object with `categoriesView`, `gridView` and `infoView` properties, containing UI parts.
     */
    _createDropdownPanelContent(locale) {
        const groupEntries = Array
            .from(this.getGroups())
            .map(name => ([name, this._groups.get(name).label]));
        // The map contains a name of category (an identifier) and its label (a translational string).
        const specialCharsGroups = new Map([
            // Add a special group that shows all available emoji.
            [ALL_EMOJI_GROUP, this._allSpecialCharactersGroupLabel],
            ...groupEntries
        ]);
        const categoriesView = new EmojiCategoriesView(locale, specialCharsGroups);
        const gridView = new CharacterGridView(locale);
        const infoView = new CharacterInfoView(locale);
        gridView.on('tileHover', (evt, data) => {
            infoView.set(data);
        });
        gridView.on('tileFocus', (evt, data) => {
            infoView.set(data);
        });
        // Update the grid of emoji when a user changed the character group.
        categoriesView.on('change:currentGroupName', (evt, propertyName, newValue) => {
            this._updateGrid(newValue, gridView);
        });
        // Set the initial content of the emoji grid.
        this._updateGrid(categoriesView.currentGroupName, gridView);
        return { categoriesView, gridView, infoView };
    }
    /**
     * Creates a button for toolbar and menu bar that will show emoji dialog.
     */
    _createDialogButton(ButtonClass) {
        const editor = this.editor;
        const locale = editor.locale;
        const buttonView = new ButtonClass(editor.locale);
        const command = editor.commands.get('insertText');
        const t = locale.t;
        const dialogPlugin = this.editor.plugins.get('Dialog');
        buttonView.set({
            label: t('Emoji'),
            icon: emojiIcon,
            isToggleable: true
        });
        buttonView.bind('isOn').to(dialogPlugin, 'id', id => id === 'emoji');
        buttonView.bind('isEnabled').to(command, 'isEnabled');
        buttonView.on('execute', () => {
            if (dialogPlugin.id === 'emoji') {
                dialogPlugin.hide();
                return;
            }
            this._showDialog();
        });
        return buttonView;
    }
    _showDialog() {
        const editor = this.editor;
        const dialog = editor.plugins.get('Dialog');
        const locale = editor.locale;
        const t = locale.t;
        const { categoriesView, gridView, infoView } = this._createDropdownPanelContent(locale);
        const content = new EmojiView(locale, categoriesView, gridView, infoView);
        gridView.on('execute', (evt, data) => {
            editor.execute('insertText', { text: data.character });
        });
        dialog.show({
            id: 'emoji',
            title: t('Emoji'),
            className: 'ck-special-characters',
            content,
            position: DialogViewPosition.EDITOR_TOP_SIDE
        });
    }
}
