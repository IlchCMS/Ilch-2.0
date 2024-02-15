export default class EmojiCharactersNavigationView extends FormHeaderView {
    constructor(locale: any, groupNames: any);
    groupDropdownView: import("@ckeditor/ckeditor5-ui").DropdownView;
    /**
     * @inheritDoc
     */
    label: any;
    /**
     * Returns the name of the character group currently selected in the {@link #groupDropdownView}.
     *
     * @returns {String}
     */
    get currentGroupName(): string;
    _createGroupDropdown(groupNames: any): import("@ckeditor/ckeditor5-ui").DropdownView;
    /**
     * Returns list item definitions to be used in the character group dropdown
     * representing specific character groups.
     *
     */
    _getCharacterGroupListItemDefinitions(dropdown: any, groupNames: any): Collection<Record<string, any>>;
}
import { FormHeaderView } from '@ckeditor/ckeditor5-ui';
import { Collection } from '@ckeditor/ckeditor5-utils';
