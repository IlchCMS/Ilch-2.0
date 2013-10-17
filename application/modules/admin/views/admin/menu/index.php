<form class="form-horizontal" id="menuForm" method="POST" action="<?php echo $this->url(array('action' => $this->getRequest()->getActionName())); ?>">
	<ol class="sortable">
		<li id="list_9"><div><span class="disclose"><span></span></span>Item 5</div></li>
		<li id="list_10"><div><span class="disclose"><span></span></span>Item 5</div></li>
	</ol>
	<input type="hidden" id="hiddenMenu" name="hiddenMenu" value="" />
	<div class="content_savebox">
		<button type="submit" name="save" class="btn">
			<?php echo $this->trans('saveButton'); ?>
		</button>
	</div>
</form>

<script>
	$(document).ready
	(
		function()
		{
			$('.sortable').nestedSortable
			({
				handle: 'div',
				items: 'li',
				toleranceElement: '> div'
			});
			
			$('#menuForm').submit
			(
				function()
				{
					$('#hiddenMenu').val(JSON.stringify($('.sortable').nestedSortable('toArray', {startDepthCount: 0})));;
				}
			)
		}
	);
</script>