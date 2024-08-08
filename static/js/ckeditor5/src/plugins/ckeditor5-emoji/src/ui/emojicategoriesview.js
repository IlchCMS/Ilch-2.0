/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */
import { addListToDropdown, Collection, createLabeledDropdown, LabeledFieldView, View, ViewModel } from 'ckeditor5';
/**
 * A class representing the navigation part of the emoji UI. It is responsible
 * for describing the feature and allowing the user to select a particular character group.
 */
export default class EmojiCategoriesView extends View {
    /**
     * Creates an instance of the {@link module:special-characters/ui/specialcharacterscategoriesview~EmojiCategoriesView}
     * class.
     *
     * @param locale The localization services instance.
     * @param groupNames The names of the character groups.
     */
    constructor(locale, groupNames) {
        super(locale);
        this.set('currentGroupName', Array.from(groupNames.entries())[0][0]);
        this._groupNames = groupNames;
        this._dropdownView = new LabeledFieldView(locale, createLabeledDropdown);
        this.setTemplate({
            tag: 'div',
            attributes: {
                class: ['ck', 'ck-character-categories']
            },
            children: [
                this._dropdownView
            ]
        });
    }
    /**
     * @inheritDoc
     */
    render() {
        super.render();
        this._setupDropdown();
    }
    /**
     * @inheritDoc
     */
    focus() {
        this._dropdownView.focus();
    }
    /**
     * Creates dropdown item list, sets up bindings and fills properties.
     */
    _setupDropdown() {
        const items = new Collection();
        for (const [name, label] of this._groupNames) {
            const item = {
                type: 'button',
                model: new ViewModel({
                    name,
                    label,
                    role: 'menuitemradio',
                    withText: true
                })
            };
            item.model.bind('isOn').to(this, 'currentGroupName', value => {
                return value === name;
            });
            items.add(item);
        }
        const t = this.locale.t;
        const accessibleLabel = t('Category');
        this._dropdownView.set({
            label: accessibleLabel,
            isEmpty: false
        });
        this._dropdownView.fieldView.panelPosition = this.locale.uiLanguageDirection === 'rtl' ? 'se' : 'sw';
        this._dropdownView.fieldView.buttonView.set({
            withText: true,
            tooltip: accessibleLabel,
            ariaLabel: accessibleLabel,
            ariaLabelledBy: undefined,
            isOn: false
        });
        this._dropdownView.fieldView.buttonView.bind('label')
            .to(this, 'currentGroupName', value => this._groupNames.get(value));
        this._dropdownView.fieldView.on('execute', ({ source }) => {
            this.currentGroupName = source.name;
        });
        addListToDropdown(this._dropdownView.fieldView, items, {
            ariaLabel: accessibleLabel,
            role: 'menu'
        });
    }
}
