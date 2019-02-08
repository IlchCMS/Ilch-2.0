CKEDITOR.dialog.add('ilchmediaDialog', function(editor) {
    return{
        title: 'Ilch Media',
        minWidth: 500,
        minHeight: 200,
        contents: [
            {
                id: 'tab-basic',
                label: editor.lang.ilchmedia.insertImage,
                elements: [
                    {
                        type: 'text',
                        id: 'src',
                        label: editor.lang.ilchmedia.imageLink,
                        labelStyle: 'font-weight: bold'
                    },
                    {
                        type: 'hbox',
                        widths: ['50%', '50%'],
                        children:
                                [
                                    {
                                        type: 'text',
                                        id: 'width',
                                        label: editor.lang.ilchmedia.widthLabel,
                                        labelStyle: 'font-weight: bold',
                                        style: 'float:left;display:inline;'
                                    },
                                    {
                                        type: 'text',
                                        id: 'height',
                                        label: editor.lang.ilchmedia.heightLabel,
                                        labelStyle: 'font-weight: bold',
                                        style: 'float:left;display:inline;'
                                    }
                                ]
                    },
                    {
                        type: 'button',
                        label: editor.lang.ilchmedia.selectFromServer,
                        labelStyle: 'font-weight: bold',
                        id: 'urlbutton',
                        onClick: function() {
                            $('#mediaModal').modal('show');

                            let src = iframeUrlImageCkeditor;
                            let height = '100%';
                            let width = '100%';

                            $("#mediaModal iframe").attr({'src': src,
                                'height': height,
                                'width': width});
                        }
                    }
                ]
            },
            {
                id: 'tab-adv',
                label: editor.lang.ilchmedia.insertFile,
                elements: [
                    {
                        type: 'text',
                        id: 'file',
                        label: editor.lang.ilchmedia.fileLink,
                        labelStyle: 'font-weight: bold'
                    },
                    {
                        type: 'text',
                        id: 'alt',
                        label: editor.lang.ilchmedia.fileLinkName,
                        labelStyle: 'font-weight: bold'
                    },
                    {
                        type: 'button',
                        label: editor.lang.ilchmedia.selectFromServer,
                        labelStyle: 'font-weight: bold',
                        id: 'urlbutton',
                        onClick: function() {
                            $('#mediaModal').modal('show');

                            let src = iframeUrlFileCkeditor;
                            let height = '100%';
                            let width = '100%';

                            $("#mediaModal iframe").attr({'src': src,
                                'height': height,
                                'width': width});
                        }
                    }
                ]
            },
            {
                id: 'tab-mov',
                label: editor.lang.ilchmedia.insertVideo,
                elements: [
                    {
                        type: 'text',
                        id: 'video',
                        label: editor.lang.ilchmedia.videoLink,
                        labelStyle: 'font-weight: bold'
                    },
                    {
                        type: 'hbox',
                        widths: ['50%', '50%'],
                        children:
                                [
                                    {
                                        type: 'text',
                                        id: 'width',
                                        label: editor.lang.ilchmedia.widthLabel,
                                        labelStyle: 'font-weight: bold',
                                        style: 'float:left;display:inline;'
                                    },
                                    {
                                        type: 'text',
                                        id: 'height',
                                        label: editor.lang.ilchmedia.heightLabel,
                                        labelStyle: 'font-weight: bold',
                                        style: 'float:left;display:inline;'
                                    }
                                ]
                    },
                    {
                        type: 'button',
                        label: editor.lang.ilchmedia.selectFromServer,
                        labelStyle: 'font-weight: bold',
                        id: 'urlbutton',
                        onClick: function() {
                            $('#mediaModal').modal('show');

                            let src = iframeUrlVideoCkeditor;
                            let height = '100%';
                            let width = '100%';

                            $("#mediaModal iframe").attr({'src': src,
                                'height': height,
                                'width': width});
                        }
                    }
                ]
            },{
                id: 'tab-upload',
                label: editor.lang.ilchmedia.uploadToServer,
                elements: [
                    {
                        type: 'button',
                        label: editor.lang.ilchmedia.uploadToServer,
                        labelStyle: 'font-weight: bold',
                        id: 'uploadbutton',
                        onClick: function() {
                            $('#mediaModal').modal('show');

                            let src = iframeMediaUploadCkeditor;
                            let height = '100%';
                            let width = '100%';

                            $("#mediaModal iframe").attr({'src': src,
                                'height': height,
                                'width': width});
                        }
                    }
                ]
            }
        ],
        onOk: function() {
            let dialog = this;
            if (dialog.getValueOf('tab-basic', 'src') !== '') {
                let custimage = editor.document.createElement('img');
                let width = dialog.getValueOf('tab-basic', 'width');
                let height = dialog.getValueOf('tab-basic', 'height');
                custimage.setAttribute('src', dialog.getValueOf('tab-basic', 'src'));
                custimage.setAttribute('width', width);
                custimage.setAttribute('height', height);
                editor.insertElement(custimage);
            }

            if (dialog.getValueOf('tab-adv', 'file') !== '') {
                let link = dialog.getValueOf('tab-adv', 'file');
                let linkname = dialog.getValueOf('tab-adv', 'alt');
                let custlink = CKEDITOR.dom.element.createFromHtml('<a href="' + link + '">' + linkname + '</a>');
                editor.insertElement(custlink);
            }

            if (dialog.getValueOf('tab-mov', 'video') !== '') {
                let link = dialog.getValueOf('tab-mov', 'video');
                let width = dialog.getValueOf('tab-mov', 'width');
                let height = dialog.getValueOf('tab-mov', 'height');

                if (ytVidId(dialog.getValueOf('tab-mov', 'video'))) {
                    let url = new URL(dialog.getValueOf('tab-mov', 'video'));
                    let param = url.searchParams.get("v");

                    let custlink = CKEDITOR.dom.element.createFromHtml('<div class="ckeditor-bbcode--youtube"><iframe class="embed-responsive-item" src="https://www.youtube-nocookie.com/embed/' + param + '" frameborder="0" allowfullscreen width="' + width + '" height="' + height + '" ></iframe></div>');
                    editor.insertElement(custlink);
                } else {
                    let custlink = CKEDITOR.dom.element.createFromHtml('<video controls autoplay preload="auto" width="' + width + '" height="' + height + '" src="' + link + '"></video>');
                    editor.insertElement(custlink);
                }
            }
        }
    };
});

/**
 * JavaScript function to match (and return) the video Id
 * of any valid Youtube Url, given as input string.
 * @author: Stephan Schmitz <eyecatchup@gmail.com>
 * @url: http://stackoverflow.com/a/10315969/624466
 */
function ytVidId(url) {
    let p = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
    return (url.match(p)) ? RegExp.$1 : false;
}