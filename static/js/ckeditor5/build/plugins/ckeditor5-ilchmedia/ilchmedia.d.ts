export default class IlchMedia extends Plugin {
    static get requires(): (typeof IlchMediaUI)[];
}
import { Plugin } from '@ckeditor/ckeditor5-core';
import IlchMediaUI from './ilchmediaui';
