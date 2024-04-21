$(document).ready(function(){
    let index = 1;

    $('.ckeditor').each(function() {
        let toolbar = $(this).attr('toolbar');
        let config = {};
        let indexStr = (index > 1) ? index : '';

        if (toolbar === 'ilch_html') {
            // ilch_html configuration
            config = {
                allowedContent: true,
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
                        'imageTextAlternative',
                        'toggleImageCaption',
                        'imageStyle:inline',
                        'imageStyle:block',
                        'imageStyle:side'
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
                }
            };
        } else if(toolbar === 'ilch_html_frontend') {
            // ilch_html_frontend configuration
            config = {
                allowedContent: true,
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
                        'imageTextAlternative',
                        'toggleImageCaption',
                        'imageStyle:inline',
                        'imageStyle:block',
                        'imageStyle:side'
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
                }
            };
        };

        if (index > 1) {
            $(this).addClass('ckeditor' + indexStr)
        }

        ClassicEditor
            .create( document.querySelector( '.ckeditor' + indexStr), config )
            .then( ckeditor => {
                window['editor' + indexStr] = ckeditor;
            } )
            .catch( err => {
                console.error( err.stack );
            } );

        index++;
    });

    // Remove input value on click
    $('.input-group .input-group-text .fa-times').on("click", function() {
        $(this).parents('.input-group').find('input').val("");
    });
});
