import { Plugin } from 'ckeditor5';
import IlchMediaUI from './ilchmediaui';

export default class IlchMedia extends Plugin {
    static get requires() {
        return [ IlchMediaUI ];
    }
}
