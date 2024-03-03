import { Plugin } from '@ckeditor/ckeditor5-core';
import IlchMediaUI from './ilchmediaui';

export default class IlchMedia extends Plugin {
    static get requires() {
        return [ IlchMediaUI ];
    }
}
