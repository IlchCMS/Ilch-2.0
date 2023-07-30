
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

CKEDITOR.dialog.add('ilchYoutubeHtmlDialog', function (editor) {
    return {
        title: 'YouTube',
        minWidth: 450,
        minHeight: 200,
        contents: [
            {
                id: 'ilchYoutubeHtml',
                elements: [
                    {
                        id: 'url',
                        type: 'text',
                        label: editor.lang.ilchyoutubehtml.videoLabel,
                        labelStyle: 'font-weight: bold',
                        validate: function () {
                            if (!this.getValue()) {
                                alert(editor.lang.ilchyoutubehtml.noURL);
                                return false;
                            } else {
                                var video = ytVidId(this.getValue());

                                if (this.getValue().length === 0 || video === false) {
                                    alert(editor.lang.ilchyoutubehtml.invalidURL + ' https://www.youtube.com/watch?v=ZmFa2jqBhJY');
                                    return false;
                                }
                            }
                        }
                    },
                    {
                        type : 'html',
                        html : editor.lang.ilchyoutubehtml.example + ' https://www.youtube.com/watch?v=ZmFa2jqBhJY'
                    },
                    {
                        type: 'hbox',
                        widths: ['50%', '50%'],
                        children: [
                            {
                                id: 'width',
                                type: 'text',
                                label: editor.lang.ilchyoutubehtml.widthLabel,
                                labelStyle: 'font-weight: bold'
                            },
                            {
                                id: 'height',
                                type: 'text',
                                label: editor.lang.ilchyoutubehtml.heightLabel,
                                labelStyle: 'font-weight: bold'
                            }
                        ]
                    },
                    {
                        id : 'control',
                        type : 'checkbox',
                        default: 'checked',
                        label : editor.lang.ilchyoutubehtml.controlLabel
                    },
                    {
                        id : 'rel',
                        type : 'checkbox',
                        label : editor.lang.ilchyoutubehtml.relLabel
                    }
                ]
            }
        ],
        onOk: function () {
            let videoId = ytVidId(this.getValueOf('ilchYoutubeHtml', 'url'));
            let url;
            let params = [];
            let width = this.getValueOf('ilchYoutubeHtml', 'width');
            let height = this.getValueOf('ilchYoutubeHtml', 'height');
            let control = this.getValueOf('ilchYoutubeHtml', 'control');
            let rel = this.getValueOf('ilchYoutubeHtml', 'rel');

            if (control === false) {
                params.push('controls=0');
            }
            if (rel === false) {
                params.push('rel=0');
            }

            if (videoId) {
                if (params.length > 0) {
                    url = videoId + '?' + params.join('&');
                } else {
                    url = videoId;
                }
            }

            let custlink = CKEDITOR.dom.element.createFromHtml('<div class="ckeditor-youtube"><iframe class="embed-responsive-item" src="https://www.youtube-nocookie.com/embed/' + url + '" frameborder="0" allowfullscreen width="' + width + '" height="' + height + '" ></iframe></div>');
            editor.insertElement(custlink);
        }
    };
});
