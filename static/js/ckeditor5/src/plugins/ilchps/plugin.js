import { Command } from 'ckeditor5/src/core';
import { ButtonView } from 'ckeditor5/src/ui';
import ilchpsIcon from '/src/plugins/ilchps/icons/ilchps.svg';

export function IlchPs( editor ) {
    console.log( 'IlchPs plugin has been registered' );
    
    editor.commands.add( 'ilchps', new IlchPsCommand( editor ) );

    editor.ui.componentFactory.add( 'ilchps', ( locale ) => {
        const button = new ButtonView( locale );
        const command = editor.commands.get( 'ilchps' );
        const t = editor.t;

        button.iconView.set( 'viewBox', '0 0 32 32' );
        button.set( {
            label: t( 'IlchPs' ),
            icon: ilchpsIcon,
            tooltip: true
        } );

        button.on( 'execute', () => {
            editor.execute( 'ilchps' );
            editor.editing.view.focus();
        } );

        button.bind( 'isOn', 'isEnabled' ).to( command, 'value', 'isEnabled' );

        return button;
    } );
}

class IlchPsCommand extends Command {
    execute() {
        const model = this.editor.model;

        model.change( ( writer ) => {
            const range = model.document.selection.getFirstRange();

            model.insertContent( writer.createText( '[PREVIEWSTOP]' ), range )
        } );
    }
}
