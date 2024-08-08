/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */
import { View, FocusCycler, FocusTracker, KeystrokeHandler } from 'ckeditor5';
/**
 * A view that glues pieces of the emoji dropdown panel together:
 *
 * * the navigation view (allows selecting the category),
 * * the grid view (displays characters as a grid),
 * * and the info view (displays detailed info about a specific character).
 */
export default class EmojiView extends View {
    /**
     * Creates an instance of the `EmojiView`.
     */
    constructor(locale, categoriesView, gridView, infoView) {
        super(locale);
        this.categoriesView = categoriesView;
        this.gridView = gridView;
        this.infoView = infoView;
        this.items = this.createCollection();
        this.focusTracker = new FocusTracker();
        this.keystrokes = new KeystrokeHandler();
        this._focusCycler = new FocusCycler({
            focusables: this.items,
            focusTracker: this.focusTracker,
            keystrokeHandler: this.keystrokes,
            actions: {
                focusPrevious: 'shift + tab',
                focusNext: 'tab'
            }
        });
        this.setTemplate({
            tag: 'div',
            children: [
                this.categoriesView,
                this.gridView,
                this.infoView
            ],
            attributes: {
                // Avoid focus loss when the user clicks the area of the grid that is not a button.
                // https://github.com/ckeditor/ckeditor5/pull/12319#issuecomment-1231779819
                tabindex: '-1'
            }
        });
        this.items.add(this.categoriesView);
        this.items.add(this.gridView);
    }
    /**
     * @inheritDoc
     */
    render() {
        super.render();
        this.focusTracker.add(this.categoriesView.element);
        this.focusTracker.add(this.gridView.element);
        // Start listening for the keystrokes coming from #element.
        this.keystrokes.listenTo(this.element);
    }
    /**
     * @inheritDoc
     */
    destroy() {
        super.destroy();
        this.focusTracker.destroy();
        this.keystrokes.destroy();
    }
    /**
     * Focuses the first focusable in {@link #items}.
     */
    focus() {
        this._focusCycler.focusFirst();
    }
}
