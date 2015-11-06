CKEDITOR.dialog.add('ilchmediaDialog', function(editor) {
    return{
        title: 'Ilch Media',
        minWidth: 400,
        minHeight: 200,
        contents: [
            {
                id: 'tab-basic',
                label: 'Insert Image',
                elements: [
                    {
                        type: 'text',
                        id: 'src',
                        label: 'Image URL',
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
                                        label: 'Width (optional)',
                                        labelStyle: 'font-weight: bold',
                                        style: 'float:left;display:inline;'
                                    },
                                    {
                                        type: 'text',
                                        id: 'height',
                                        label: 'Height (optional)',
                                        labelStyle: 'font-weight: bold',
                                        style: 'float:left;display:inline;'
                                    }
                                ]
                    },
                    {
                        type: 'button',
                        label: 'Select from Server',
                        labelStyle: 'font-weight: bold',
                        id: 'urlbutton',
                        onClick: function() {
                            $('#MediaModal').modal('show');

                            var src = iframeUrlImageCkeditor;
                            var height = '100%';
                            var width = '100%';

                            $("#MediaModal iframe").attr({'src': src,
                                'height': height,
                                'width': width});
                        }
                    }
                ]
            },
            {
                id: 'tab-adv',
                label: 'Insert File',
                elements: [
                    {
                        type: 'text',
                        id: 'file',
                        label: 'Link URL',
                        labelStyle: 'font-weight: bold'
                    },
                    {
                        type: 'text',
                        id: 'alt',
                        label: 'Link Name',
                        labelStyle: 'font-weight: bold'
                    },
                    {
                        type: 'button',
                        label: 'Select from Server',
                        labelStyle: 'font-weight: bold',
                        id: 'urlbutton',
                        onClick: function() {
                            $('#MediaModal').modal('show');

                            var src = iframeUrlFileCkeditor;
                            var height = '100%';
                            var width = '100%';

                            $("#MediaModal iframe").attr({'src': src,
                                'height': height,
                                'width': width});
                        }
                    }
                ]
            },
            {
                id: 'tab-mov',
                label: 'Insert Video',
                elements: [
                    {
                        type: 'text',
                        id: 'video',
                        label: 'Video URL',
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
                                        label: 'Width (optional)',
                                        labelStyle: 'font-weight: bold',
                                        style: 'float:left;display:inline;'
                                    },
                                    {
                                        type: 'text',
                                        id: 'height',
                                        label: 'Height (optional)',
                                        labelStyle: 'font-weight: bold',
                                        style: 'float:left;display:inline;'
                                    }
                                ]
                    },
                    {
                        type: 'button',
                        label: 'Select from Server',
                        labelStyle: 'font-weight: bold',
                        id: 'urlbutton',
                        onClick: function() {
                            $('#MediaModal').modal('show');

                            var src = iframeUrlVideoCkeditor;
                            var height = '100%';
                            var width = '100%';

                            $("#MediaModal iframe").attr({'src': src,
                                'height': height,
                                'width': width});
                        }
                    }
                ]
            },{
                id: 'tab-upload',
                label: 'Upload to Server',
                elements: [
                    {
                        type: 'button',
                        label: 'Upload to Server',
                        labelStyle: 'font-weight: bold',
                        id: 'uploadbutton',
                        onClick: function() {
                            $('#MediaModal').modal('show');

                            var src = iframeMediaUploadCkeditor;
                            var height = '100%';
                            var width = '100%';

                            $("#MediaModal iframe").attr({'src': src,
                                'height': height,
                                'width': width});
                        }
                    }
                ]
            }
        ],
        onOk: function() {
            var dialog = this;
            if (dialog.getValueOf('tab-basic', 'src') !== '') {
                var custimage = editor.document.createElement('img');
                var width = dialog.getValueOf('tab-basic', 'width');
                var height = dialog.getValueOf('tab-basic', 'height');
                custimage.setAttribute('src', dialog.getValueOf('tab-basic', 'src'));
                custimage.setAttribute('width', width);
                custimage.setAttribute('height', height);
                editor.insertElement(custimage);
            }

            if (dialog.getValueOf('tab-adv', 'file') !== '') {
                var link = dialog.getValueOf('tab-adv', 'file');
                var linkname = dialog.getValueOf('tab-adv', 'alt');
                var custlink = CKEDITOR.dom.element.createFromHtml('<a href="' + link + '">' + linkname + '</a>');
                editor.insertElement(custlink);
            }

            if (dialog.getValueOf('tab-mov', 'video') !== '') {
                var link = dialog.getValueOf('tab-mov', 'video');
                var width = dialog.getValueOf('tab-mov', 'width');
                var height = dialog.getValueOf('tab-mov', 'height');
                var custlink = CKEDITOR.dom.element.createFromHtml('<video controls autoplay preload="auto" width="' + width + '" height="' + height + '" src="' + link + '"></video>');
                editor.insertElement(custlink);
            }
        }
    };
});