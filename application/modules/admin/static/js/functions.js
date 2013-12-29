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