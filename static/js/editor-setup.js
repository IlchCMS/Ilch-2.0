import {
    Alignment,
    AutoImage,
    AutoLink,
    Autoformat,
    Base64UploadAdapter,
    BlockQuote,
    Bold,
    ClassicEditor,
    Code,
    CodeBlock,
    Emoji,
    Essentials,
    FindAndReplace,
    FontBackgroundColor,
    FontColor,
    FontFamily,
    FontSize,
    GeneralHtmlSupport,
    Heading,
    HorizontalLine,
    Image,
    ImageCaption,
    ImageInsert,
    ImageResize,
    ImageStyle,
    ImageToolbar,
    ImageUpload,
    Indent,
    IndentBlock,
    Italic,
    Link,
    List,
    MediaEmbed,
    Mention,
    Paragraph,
    PasteFromOffice,
    RemoveFormat,
    SelectAll,
    SourceEditing,
    SpecialCharacters,
    SpecialCharactersArrows,
    SpecialCharactersCurrency,
    SpecialCharactersEssentials,
    SpecialCharactersLatin,
    SpecialCharactersMathematical,
    SpecialCharactersText,
    Strikethrough,
    Subscript,
    Superscript,
    Table,
    TableCaption,
    TableCellProperties,
    TableColumnResize,
    TableProperties,
    TableToolbar,
    TextTransformation,
    Underline,
    Undo
} from 'ckeditor5';

import { Ilchps } from 'Ilchps';
import { Ilchmedia } from 'Ilchmedia';

// Define reusable base + named configs
const baseConfig = {
  plugins: [
        Alignment,
        Autoformat,
        AutoImage,
        AutoLink,
        Autoformat,
        Base64UploadAdapter,
        BlockQuote,
        Bold,
        Code,
        CodeBlock,
        Emoji,
        Essentials,
        FindAndReplace,
        FontBackgroundColor,
        FontColor,
        FontFamily,
        FontSize,
        GeneralHtmlSupport,
        Heading,
        HorizontalLine,
        Ilchps,
        Ilchmedia,
        Image,
        ImageCaption,
        ImageInsert,
        ImageResize,
        ImageStyle,
        ImageToolbar,
        ImageUpload,
        Indent,
        IndentBlock,
        Italic,
        Link,
        List,
        MediaEmbed,
        Mention,
        Paragraph,
        PasteFromOffice,
        RemoveFormat,
        SelectAll,
        SourceEditing,
        SpecialCharacters,
        SpecialCharactersArrows,
        SpecialCharactersCurrency,
        SpecialCharactersEssentials,
        SpecialCharactersLatin,
        SpecialCharactersMathematical,
        SpecialCharactersText,
        Strikethrough,
        Subscript,
        Superscript,
        Table,
        TableCaption,
        TableCellProperties,
        TableColumnResize,
        TableProperties,
        TableToolbar,
        TextTransformation,
        Underline,
        Undo
        ],
  toolbar: [ 'undo', 'redo', '|', 'bold', 'italic' ],
  licenseKey: 'GPL'
};

const configs = {
  ilch_html: {
    ...baseConfig,
    toolbar: {
        items: [
            'sourceEditing',
            '|',
            'heading',
            '|',
            'bold',
            'italic',
            'underline',
            'code',
            'strikethrough',
            'subscript',
            'superscript',
            '|',
            'alignment',
            '|',
            'outdent',
            'indent',
            '|',
            'bulletedList',
            'numberedList',
            'removeFormat',
            '|',
            'link',
            'specialCharacters',
            'emoji',
            '|',
            'imageInsert',
            'blockQuote',
            'codeBlock',
            'insertTable',
            'horizontalLine',
            'mediaEmbed',
            'undo',
            'redo',
            '-',
            'fontSize',
            'fontFamily',
            'fontBackgroundColor',
            'fontColor',
            'findAndReplace',
            'selectAll',
            'ilchps',
            'ilchmedia'
        ],
        shouldNotGroupWhenFull: true
    },
    language: navigator.language.split("-")[0],
    image: {
        toolbar: [
            'imageTextAlternative', 'toggleImageCaption', '|',
            'imageStyle:inline', 'imageStyle:breakText', 'imageStyle:wrapText'
        ]
    },
    table: {
        contentToolbar: [
            'tableColumn',
            'tableRow',
            'mergeTableCells',
            'tableCellProperties',
            'tableProperties'
        ]
    },
    mediaEmbed: {
        previewsInData: true,
        removeProviders: [ 'instagram', 'twitter', 'googleMaps', 'flickr', 'facebook' ],
        extraProviders: [
            {
                name: 'localhost',
                url: '^' + document.location.protocol + '\/\/' + document.location.hostname + '.+',
                html: match => `<div style="position:relative; padding-bottom:100%; height:0"><video style="position:absolute; width:100%; height:100%; top:0; left:0" controls src="${ match[ 0 ] }"></video></div>`
            }
        ]
    },
    fontSize: {
        options: [
            'default',
            8,
            9,
            10,
            11,
            12,
            14,
            16,
            18,
            20,
            22,
            24,
            26,
            28,
            36,
            48,
            72
        ]
    },
    htmlSupport: {
        allow: [
            {
                name: /.*/,
                attributes: true,
                classes: true,
                styles: true
            }
        ]
    },
    emoji: {
        definitionsUrl: baseUrl + '/static/js/ckeditor5/emoji-definitions.json'
    }
  },
  ilch_html_frontend: {
    ...baseConfig,
    toolbar: {
        items: [
            'heading',
            '|',
            'bold',
            'italic',
            'underline',
            'code',
            'strikethrough',
            'subscript',
            'superscript',
            '|',
            'alignment',
            '|',
            'outdent',
            'indent',
            '|',
            'bulletedList',
            'numberedList',
            'removeFormat',
            '|',
            'link',
            'specialCharacters',
            'emoji',
            '|',
            'imageInsert',
            'blockQuote',
            'codeBlock',
            'insertTable',
            'horizontalLine',
            'mediaEmbed',
            'undo',
            'redo',
            '-',
            'fontSize',
            'fontFamily',
            'fontBackgroundColor',
            'fontColor',
            'findAndReplace',
            'selectAll',
            'ilchmedia'
        ],
        shouldNotGroupWhenFull: true
    },
    language: navigator.language.split("-")[0],
    image: {
        toolbar: [
            'imageTextAlternative', 'toggleImageCaption', '|',
            'imageStyle:inline', 'imageStyle:breakText', 'imageStyle:wrapText'
        ]
    },
    table: {
        contentToolbar: [
            'tableColumn',
            'tableRow',
            'mergeTableCells',
            'tableCellProperties',
            'tableProperties'
        ]
    },
    mediaEmbed: {
        previewsInData: true,
        removeProviders: [ 'instagram', 'twitter', 'googleMaps', 'flickr', 'facebook' ]
    },
    fontSize: {
        options: [
            'default',
            8,
            9,
            10,
            11,
            12,
            14,
            16,
            18,
            20,
            22,
            24,
            26,
            28,
            36,
            48,
            72
        ]
    },
    emoji: {
        definitionsUrl: baseUrl + '/static/js/ckeditor5/emoji-definitions.json'
    }
  }
};

// Initialize one or more editors based on attribute
document.querySelectorAll('.ckeditor').forEach(el => {
  const key = el.getAttribute('toolbar') || 'ilch_html';
  const cfg = configs[key] || configs.ilch_html;

  ClassicEditor
    .create( {
        attachTo: el,
        ...cfg
    } )
    .then( editor => {
        window.editor = editor;
    } )
    .catch( error => {
        console.error( error );
    } );
});
