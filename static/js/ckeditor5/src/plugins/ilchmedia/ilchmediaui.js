import { Plugin } from '@ckeditor/ckeditor5-core';
import ButtonView from '@ckeditor/ckeditor5-ui/src/model';
import { createDropdown, addListToDropdown } from '@ckeditor/ckeditor5-ui/src/dropdown/utils';
import ListSeparatorView from '@ckeditor/ckeditor5-ui/src//list/listseparatorview.js';
import Collection from '@ckeditor/ckeditor5-utils/src/collection';
import imageIcon from './icons/image.svg';
import fileIcon from './icons/file.svg';
import videoIcon from './icons/file-video.svg';

export default class IlchMediaUI extends Plugin {
    init() {
        const editor = this.editor;
        // const t = editor.t;
        // const pickImageFromServer = t('Pick image from server');

        // Register UI component
        editor.ui.componentFactory.add('ilchmedia', locale => {

            const dropdownView = createDropdown( locale );

            dropdownView.buttonView.set({
                label: 'IlchMedia',
                icon: imageIcon,
                tooltip: true
            });
            dropdownView.extendTemplate( {
                attributes: {
                    class: [
                        'ck-image-dropdown'
                    ]
                }
            });

            // The collection of the list items.
            const items = new Collection();

            if (typeof iframeUrlImageCkeditor !== 'undefined' && iframeUrlImageCkeditor) {
                items.add( {
                    type: 'button',
                    model: new ButtonView( {
                        id: 'pickImage',
                        label: 'Pick image',
                        icon: imageIcon,
                        withText: true
                    })
                });
            }

            if (typeof iframeUrlFileCkeditor !== 'undefined' && iframeUrlFileCkeditor) {
                items.add( {
                    type: 'button',
                    model: new ButtonView( {
                        id: 'pickFile',
                        label: 'Pick file',
                        icon: fileIcon,
                        withText: true
                    })
                });
            }

            if (typeof iframeUrlVideoCkeditor !== 'undefined' && iframeUrlVideoCkeditor) {
                items.add( {
                    type: 'button',
                    model: new ButtonView( {
                        id: 'pickVideo',
                        label: 'Pick video',
                        icon: videoIcon,
                        withText: true
                    })
                });
            }

            if (typeof iframeMediaUploadCkeditor !== 'undefined' && iframeMediaUploadCkeditor) {
                items.add( { type: 'separator', model: new ListSeparatorView() } );
                
                items.add( {
                    type: 'button',
                    model: new ButtonView( {
                        id: 'uploadImage',
                        label: 'Upload image',
                        icon: imageIcon,
                        withText: true
                    })
                });

                items.add( {
                    type: 'button',
                    model: new ButtonView( {
                        id: 'uploadFile',
                        label: 'Upload file',
                        icon: fileIcon,
                        withText: true
                    })
                });

                items.add( {
                    type: 'button',
                    model: new ButtonView( {
                        id: 'uploadVideo',
                        label: 'Upload video',
                        icon: videoIcon,
                        withText: true
                    })
                });
            }

            if (typeof iframeUrlUserGallery !== 'undefined' && iframeUrlUserGallery) {
                items.add( { type: 'separator', model: new ListSeparatorView() } );

                items.add( {
                    type: 'button',
                    model: new ButtonView( {
                        id: 'pickUserGallery',
                        label: 'Pick image from user gallery',
                        icon: imageIcon,
                        withText: true
                    })
                });
            }

            // Create a dropdown with a list inside the panel.
            addListToDropdown( dropdownView, items );

            var idButton = '';

            dropdownView.on('execute', (eventInfo) => {
                const { id, label } = eventInfo.source;

                switch (id) {
                    case 'pickImage':
                        {
                            $('#mediaModal').modal('show');

                            let src = iframeUrlImageCkeditor;
                            let height = '100%';
                            let width = '100%';

                            $("#mediaModal iframe").attr({'src': src,
                                'height': height,
                                'width': width});
                        }
                        break;
                    case 'pickFile':
                        {
                            $('#mediaModal').modal('show');

                            let src = iframeUrlFileCkeditor;
                            let height = '100%';
                            let width = '100%';

                            $("#mediaModal iframe").attr({'src': src,
                                'height': height,
                                'width': width});
                        }
                        break;
                    case 'pickVideo':
                        {
                            $('#mediaModal').modal('show');

                            let src = iframeUrlVideoCkeditor;
                            let height = '100%';
                            let width = '100%';

                            $("#mediaModal iframe").attr({'src': src,
                                'height': height,
                                'width': width});
                        }
                        break;
                    case 'pickUserGallery':
                        {
                            console.log('executed');
                            $('#mediaModal').modal('show');

                            let src = iframeUrlUserGallery;
                            let height = '100%';
                            let width = '100%';

                            $("#mediaModal iframe").attr({'src': src,
                                'height': height,
                                'width': width});
                        }
                        break;
                    case 'uploadImage':
                    case 'uploadFile':
                    case 'uploadVideo':
                        {
                            $('#mediaModal').modal('show');

                            let src = iframeMediaUploadCkeditor;
                            let height = '100%';
                            let width = '100%';

                            $("#mediaModal iframe").attr({'src': src,
                                'height': height,
                                'width': width});
                        }
                        break;
                    default:
                }

                idButton = id;
            });

            $('#mediaModal').on('hidden.bs.modal', function() {
                let url = $(this).attr('url');

                if (url) {
                    switch (idButton) {
                        case 'pickImage':
                        case 'pickUserGallery':
                            {
                                // https://ckeditor.com/docs/ckeditor5/latest/api/module_image_image_insertimagecommand-InsertImageCommand.html
                                editor.execute( 'insertImage', { source: url } );
                            }
                            break;
                        case 'pickFile':
                            {
                                // https://ckeditor.com/docs/ckeditor5/latest/features/link.html#common-api
                                editor.execute( 'link', url );
                            }
                            break;
                        case 'pickVideo':
                            {
                                // https://ckeditor.com/docs/ckeditor5/latest/features/media-embed.html#common-api
                                editor.execute( 'mediaEmbed', url );
                            }
                            break;
                        default:
                    }
                }
            });

            return dropdownView;
        });
    }
}
