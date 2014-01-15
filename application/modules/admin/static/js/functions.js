function toggleSidebar(){
	var $sidebar = $('.app_left');
	var $content = $('.app_right');
	var $watermark = $('.watermark');

	var state = 'show';
	var margin = '300px';
	var speed = 'slow';

	if($sidebar.is(':visible'))
	{
		state = 'hide';
		margin = '0px';
	}

	if(state == 'hide')
	{
		$('.slideLeft').removeClass('fa fa-angle-left');
	}
	else
	{
		$('.slideRight').removeClass('fa fa-angle-right');
	}

	$sidebar.animate({
		width: state
	}, speed);


	$content.animate({
		marginLeft: margin
	}, speed, function() {
		if(state == 'hide')
		{
			$('.slideRight').addClass('fa fa-angle-right');
		}
		else
		{
			$('.slideLeft').addClass('fa fa-angle-left');
		}
	});

	$watermark.animate({
		width: state
	}, speed);
}

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
