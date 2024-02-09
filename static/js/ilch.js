$(document).ready(function(){
    $('.ckeditor').each(function() {
        let toolbar = $(this).attr('toolbar');
        let config = {};

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
                        'imageUpload',
                        'blockQuote',
                        'codeBlock',
                        'insertTable',
                        'mediaEmbed',
                        'undo',
                        'redo',
                        '-',
                        'fontSize',
                        'fontFamily',
                        'fontBackgroundColor',
                        'fontColor'
                    ],
                    shouldNotGroupWhenFull: true
                },
                language: 'de',
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
                }
            };
        } else if(toolbar === 'ilch_html_frontend') {
            // ilch_html_frontend configuration
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
                        'imageUpload',
                        'blockQuote',
                        'codeBlock',
                        'insertTable',
                        'mediaEmbed',
                        'undo',
                        'redo',
                        '-',
                        'fontSize',
                        'fontFamily',
                        'fontBackgroundColor',
                        'fontColor'
                    ],
                    shouldNotGroupWhenFull: true
                },
                language: 'de',
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
                }
            };
        };

        ClassicEditor
            .create( document.querySelector( '.ckeditor' ), config )
            .then( ckeditor => {
                window.editor = ckeditor;
            } )
            .catch( err => {
                console.error( err.stack );
            } );
    });

    // Remove input value on click
    $('.input-group .input-group-addon .fa-times').on("click", function() {
        $(this).parents('.input-group').find('input').val("");
    });
});
