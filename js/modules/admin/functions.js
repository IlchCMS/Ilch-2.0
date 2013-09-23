/**
 * toggle the Sidebar
 */
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
		$('.slideLeft').removeClass('icon-chevron-left');
	}
	else
	{
		$('.slideRight').removeClass('icon-chevron-right');
	}

	$sidebar.animate({
		width: state
	}, speed);


	$content.animate({
		marginLeft: margin
	}, speed, function() {
		if(state == 'hide')
		{
			$('.slideRight').addClass('icon-chevron-right');
		}
		else
		{
			$('.slideLeft').addClass('icon-chevron-left');
		}
	});

	$watermark.animate({
		width: state
	}, speed);
}