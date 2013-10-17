<?php
$menus = $this->get('menus');
?>
<form class="form-horizontal" id="menuForm" method="POST" action="<?php echo $this->url(array('action' => $this->getRequest()->getActionName())); ?>">
	<?php
		foreach($menus as $menu)
		{
			$menuItems = json_decode($menu->getContent());
	?>
		<ol class="sortable">
			<?php
				foreach($menuItems as $item)
				{
					if(empty($item->item_id))
					{
						continue;
					}

					echo '<li id="list_'.$item->item_id.'"><div><span class="disclose"><span></span></span>'.$item->item_id.'</div></li>';
				}
			?>
		</ol>
	<?php
		}
	?>
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
					$('#hiddenMenu').val(JSON.stringify($('.sortable').nestedSortable('toArray', {startDepthCount: 0})));
				}
			)
		}
	);
</script>