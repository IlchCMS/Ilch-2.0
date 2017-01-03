$(function() {
    var ul = $('#upload ul');

    $('#drop a').click(function() {
        // Simulate a click on the file input button
        // to show the file browser dialog
        $(this).parent().find('input').click();
    });

    // Initialize the jQuery File Upload plugin
    $('#upload').fileupload({

        // This element will accept file drag/drop uploading
        dropZone: $('#drop'),

        // This function is called when a file is added to the queue;
        // either via the browse button, or via drag/drop:
        add: function (e, data) {

            var tpl = $('<li class="working"><input type="text" value="0" data-width="48" data-height="48"'+
                ' data-fgColor="#00AEFF" data-readOnly="1" data-bgColor="#3e4043" /><p></p><b class="suss-none">Finish</b><span class=""></span></li>');

            // regular expression to get the file-extension
            var re = /(?:\.([^.]+))?$/;
            var ext = re.exec(data.files[0].name)[1].toLowerCase();

            if(jQuery.inArray(ext,allowedExtensions) == -1) {
                // File-extension is not one of the allowed ones
                tpl.find('p').text(extensionNotAllowed);
            } else if (data.files[0].size <= maxFileSize) {
                // Append the file name and file size
                tpl.find('p').text(data.files[0].name)
                             .append('<i>' + formatFileSize(data.files[0].size) + '</i>');
            } else {
                tpl.find('p').text(fileTooBig);
            }

            // Add the HTML to the UL element
            data.context = tpl.appendTo(ul);

            // Initialize the knob plugin
            tpl.find('input').knob();

            if (data.files[0].size > maxFileSize || jQuery.inArray(ext,allowedExtensions) == -1) {
                return;
            }

            // Listen for clicks on the cancel icon
            tpl.find('span').click(function() {

                if (tpl.hasClass('working')) {
                    jqXHR.abort();
                }

                tpl.fadeOut(function() {
                    tpl.remove();
                });

            });

            // Automatically upload the file once it is added to the queue
            var jqXHR = data.submit();
        },

        progress: function(e, data) {

            // Calculate the completion percentage of the upload
            var progress = parseInt(data.loaded / data.total * 100, 10);

            // Update the hidden input field and trigger a change
            // so that the jQuery knob plugin knows to update the dial
            data.context.find('input').val(progress).change();
            data.context.find('span').addClass('fa fa-spinner');
            if (progress == 100) {
                data.context.removeClass('working');
                
            }
        },

        fail:function(e, data) {
            // Something has gone wrong!
            data.context.addClass('error');
            data.context.find('span').removeClass('fa fa-spinner');
            data.context.find('span').addClass('fa fa-bolt');
        },
        
        done:function(e, data) {
            // Success!
            data.context.addClass('finish');
            data.context.find('span').removeClass('fa fa-spinner');
            data.context.find('span').addClass('fa fa-check');
            data.context.find('b').removeClass('suss-none');
            data.context.find('b').addClass('suss');
        }

    });

    // Prevent the default action when a file is dropped on the window
    $(document).on('drop dragover', function (e) {
        e.preventDefault();
    });

    // Helper function that formats the file sizes
    function formatFileSize(bytes) {
        if (typeof bytes !== 'number') {
            return '';
        }

        if (bytes >= 1000000000) {
            return (bytes / 1000000000).toFixed(2) + ' GB';
        }

        if (bytes >= 1000000) {
            return (bytes / 1000000).toFixed(2) + ' MB';
        }

        return (bytes / 1000).toFixed(2) + ' KB';
    }
});
