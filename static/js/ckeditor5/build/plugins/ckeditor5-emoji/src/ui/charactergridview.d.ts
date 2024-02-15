export default class CharacterGridView extends View<HTMLElement> {
    constructor(locale: any);
    tiles: import("@ckeditor/ckeditor5-ui").ViewCollection<View<HTMLElement>>;
    createTile(character: any, name: any): ButtonView;
}
import { View } from '@ckeditor/ckeditor5-ui';
import { ButtonView } from '@ckeditor/ckeditor5-ui';
