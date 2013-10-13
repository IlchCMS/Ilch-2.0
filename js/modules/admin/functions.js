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
		$('.slideLeft').removeClass('icon-angle-left');
	}
	else
	{
		$('.slideRight').removeClass('icon-angle-right');
	}

	$sidebar.animate({
		width: state
	}, speed);


	$content.animate({
		marginLeft: margin
	}, speed, function() {
		if(state == 'hide')
		{
			$('.slideRight').addClass('icon-angle-right');
		}
		else
		{
			$('.slideLeft').addClass('icon-angle-left');
		}
	});

	$watermark.animate({
		width: state
	}, speed);
}