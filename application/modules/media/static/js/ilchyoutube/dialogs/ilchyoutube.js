CKEDITOR.dialog.add('ilchYoutubeDialog', function (editor) {
    return {
        title: 'YouTube',
        minWidth: 450,
        minHeight: 200,
        contents: [
            {
                id: 'ilchYoutube',
                elements: [
                    {
                        id: 'url',
                        type: 'text',
                        label: editor.lang.ilchyoutube.videoLabel,
                        labelStyle: 'font-weight: bold',
                        validate: function () {
                            if (!this.getValue()) {
                                alert(editor.lang.ilchyoutube.noURL);
                                return false;
                            } else {
                                var video = ytVidId(this.getValue());

                                if (this.getValue().length === 0 || video === false) {
                                    alert(editor.lang.ilchyoutube.invalidURL + ' https://www.youtube.com/watch?v=ZmFa2jqBhJY');
                                    return false;
                                }
                            }
                        }
                    },
                    {
                        type : 'html',
                        html : editor.lang.ilchyoutube.example + ' https://www.youtube.com/watch?v=ZmFa2jqBhJY'
                    },
                    {
                        type: 'hbox',
                        widths: ['50%', '50%'],
                        children: [
                            {
                                id: 'width',
                                type: 'text',
                                label: editor.lang.ilchyoutube.widthLabel,
                                labelStyle: 'font-weight: bold'
                            },
                            {
                                id: 'height',
                                type: 'text',
                                label: editor.lang.ilchyoutube.heightLabel,
                                labelStyle: 'font-weight: bold'
                            }
                        ]
                    },
                    {
                        id : 'control',
                        type : 'checkbox',
                        default: 'checked',
                        label : editor.lang.ilchyoutube.controlLabel
                    },
                    {
                        id : 'autoplay',
                        type : 'checkbox',
                        label : editor.lang.ilchyoutube.autoplayLabel
                    },
                    {
                        id : 'rel',
                        type : 'checkbox',
                        label : editor.lang.ilchyoutube.relLabel
                    }
                ]
            }
        ],
        onOk: function () {
            var video = ytVidId(this.getValueOf('ilchYoutube', 'url')),
                width = this.getContentElement('ilchYoutube', 'width'),
                height = this.getContentElement('ilchYoutube', 'height'),
                control = this.getContentElement('ilchYoutube', 'control'),
                autoplay = this.getContentElement('ilchYoutube', 'autoplay'),
                rel = this.getContentElement('ilchYoutube', 'rel'),
                url = video, params = [],
                content = '[youtube', options = [];

            if (width.getValue()) {
                options.push('w=' + this.getValueOf('ilchYoutube', 'width') + '');
            }
            if (height.getValue()) {
                options.push('h=' + this.getValueOf('ilchYoutube', 'height') + '');
            }
            if (autoplay.getValue() === true) {
                params.push('autoplay=1');
            }
            if (control.getValue() === false) {
                params.push('controls=0');
            }
            if (rel.getValue() === false) {
                params.push('rel=0');
            }
            if (options.length > 0) {
                content = content + ' ' + options.join(' ');
            }
            if (params.length > 0) {
                url = url + '?' + params.join('&');
            }

            content += ']' + url + '[/youtube]';

            editor.insertText(content);
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
    var p = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
    return (url.match(p)) ? RegExp.$1 : false;
}
