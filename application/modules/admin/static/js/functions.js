$(document).ready(function(){

                function remove() {
                    $('body').removeClass('hidden-menu');
                };

                function add() {
                    $('body').addClass('hidden-menu');
                };

                var menu = document.getElementById('toggleLeftMenu');

                if (menu !== null) {
                    menu.onclick = function() {
                        if($('body').hasClass('hidden-menu')){
                            remove();
                        }else {
                            add();
                        }
                    };
                }
            

                function removeSearch() {
                        $('#search-div').removeClass('opens');
                    };

                function addSearch() {
                    $('#search-div').addClass('opens');
                };

                document.getElementById('search-header').onclick = function() {
                    if($('#search-div').hasClass('opens')){
                        removeSearch();
                    }else {
                        addSearch();
                    }
                };
            });

$(document).ready
(
    function()
    {
        $('.listChooser a').click
        (
            function()
            {
                if ($(this).data('hiddenkey') == 'setfree') {
                    txt = 'Sollen die markierten Einträge wirklich freigeschalten werden ?'
                }

                if ($(this).data('hiddenkey') == 'delete') {
                    txt = 'Sollen die markierten Einträge wirklich gelöscht werden ?'
                }

                if (confirm(txt)) {
                    $('.content_savebox_hidden').val($(this).data('hiddenkey'));
                    $(this).closest('form').submit();
                }
            }
        );

        $('.check_all').click
        (
            function()
            {
                $('input[name='+$(this).data('childs')+'\\[\\]]').prop('checked', $(this).is(":checked"));
            }
        );

        $('.delete_button').click
        (
            function(event)
            {
                if (!confirm("Sollen der Eintrag wirklich gelöscht werden ?")) {
                    event.preventDefault();
                }
            }
        );
    }
);
