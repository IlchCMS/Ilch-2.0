/*jslint browser: true*/
/*global $, jQuery, alert*/
$(document).ready(function () {
    "use strict";
    function loadMedia(btn, mainContainer) {
        btn.button('loading');

        var req = $.ajax({
            url: btn.data('url'),
            type: "GET",
            dataType: "json"
        });

        req.done(function (data) {
            $(mainContainer).find("h3").removeClass('hidden').addClass('show');

            $.each(data.media, function (i, obj) {
                var container, item;

                container = $('<div>').attr('class', 'col-xs-2');
                item = $('<img>').attr({
                    src: '/' + obj.thumb,
                    alt: obj.name,
                    'class': 'media img-responsive',
                    'data-id': obj.id,
                    'data-toggle': 'popover',
                    'data-content': 'ID: <b>' + obj.id + '</b><br>Filename: <b>' + obj.name + '</b><br>Extension: <b>' + obj.ext + '</b><br>Date: <b>' + obj.date + '</b><br>Kategorie: <b>' + obj.catName + '</b>'
                });

                item.popover({
                    placement: 'bottom',
                    trigger: 'hover',
                    container: 'body',
                    html: true
                });

                container.html(item);
                mainContainer.append(container);
            });

            btn.removeClass('btn-danger').addClass('btn-success').button('complete');
        });

        req.fail(function (jqXHR, textStatus) {
            btn.removeClass('btn-success').addClass('btn-danger').button('failed');
        });
    }

    var mediaContainer = $("#mediaContainer"), mediaStateButton = $("#mediaStateButton");

    $("#mediaStateButton").on('click', function (e) {
        mediaContainer.empty();
        loadMedia($(this), mediaContainer);
    });

    $("#closeMediaModalButton").on('click', function (e) {
        mediaContainer.empty();
        loadMedia(mediaStateButton, mediaContainer);
        $("#mediaUploadModal .modal-body").empty();
    });

    $("#mediaUploadButton").on('click', function (e) {
        e.preventDefault();
        var btn, req;

        btn = $(this);
        $('#mediaUploadModal').modal({backdrop: 'static'}).modal('show');
        req = $.ajax({
            url: btn.data('url'),
            type: "GET",
            dataType: "html"
        });

        req.done(function (data) {
            $("#mediaUploadModal .modal-body").html(data);
        });
    });
});
