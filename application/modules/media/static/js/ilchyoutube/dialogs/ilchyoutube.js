CKEDITOR.dialog.add('ilchyoutubeDialog', function(editor) {
    return{
        title: 'Ilch YouTube',
        minWidth: 500,
        minHeight: 200,
        contents: [
            {
                id: 'tab-mov',
                label: 'Insert YouTube',
                elements: [
                    {
                        type: 'text',
                        id: 'video',
                        label: 'YouTube Link',
                        labelStyle: 'font-weight: bold'
                    },
                    {
                        type : 'html',
                        html : '<div id="bbcode_infotext"><b>Information</b>.</br>Zb. aus folgender URL </br><b>https://www.youtube.com/watch?v=ZmFa2jqBhJY</b></br> wird nur <b>ZmFa2jqBhJY</b></br> rauskopiert!</div>'
                    }
                ]
            }
        ],
        onOk: function() {
            var dialog = this;
            if (dialog.getValueOf('tab-mov', 'video') !== '') {
                var link = dialog.getValueOf('tab-mov', 'video');
                var custlink = '[youtube]' + link + '[/youtube]';
                editor.insertText(custlink);
            }
        }
    };
});