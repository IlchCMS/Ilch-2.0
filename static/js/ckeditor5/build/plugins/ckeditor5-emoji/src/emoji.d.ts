export default class Emoji extends Plugin {
    /**
     * @inheritDoc
     */
    static get requires(): (typeof Typing)[];
    /**
     * @inheritDoc
     */
    static get pluginName(): string;
    /**
     * @inheritDoc
     */
    constructor(editor: any);
    _characters: Map<any, any>;
    _groups: Map<any, any>;
    /**
     * @inheritDoc
     */
    init(): void;
    addItems(groupName: any, items: any): void;
    getGroups(): IterableIterator<any>;
    getCharactersForGroup(groupName: any): any;
    getCharacter(title: any): any;
    _getGroup(groupName: any): any;
    _updateGrid(currentGroupName: any, gridView: any): void;
    _createDropdownPanelContent(locale: any, dropdownView: any): {
        navigationView: EmojiCharactersNavigationView;
        gridView: CharacterGridView;
        infoView: CharacterInfoView;
    };
}
import { Plugin } from '@ckeditor/ckeditor5-core';
import EmojiCharactersNavigationView from './ui/emojicharactersnavigationview';
import CharacterGridView from './ui/charactergridview';
import CharacterInfoView from './ui/characterinfoview';
import { Typing } from '@ckeditor/ckeditor5-typing';
