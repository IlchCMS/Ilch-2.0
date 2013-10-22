<?php
$menuItems = $this->get('menuItems');
$menuMapper = $this->get('menuMapper');

function rec($item, $menuMapper)
{
	$subItems = $menuMapper->getMenuItemsByParent(1, $item->getId());
	$class = 'mjs-nestedSortable-branch mjs-nestedSortable-collapsed';

	if(empty($subItems))
	{
		$class = 'mjs-nestedSortable-leaf';
	}

	echo '<li id="list_'.$item->getId().'" class="'.$class.'">';
	echo '<div><span class="disclose">
					<input type="hidden" name="items['.$item->getId().'][id]" value="'.$item->getId().'" />
					<input type="hidden" name="items['.$item->getId().'][title]" value="'.$item->getTitle().'" />
					<input type="hidden" name="items['.$item->getId().'][href]" value="'.$item->getHref().'" />
					<span></span>
				</span>'.$item->getTitle().'</div>';

	if(!empty($subItems))
	{
		echo '<ol>';

		foreach($subItems as $subItem)
		{
			rec($subItem, $menuMapper);
		}

		echo '</ol>';
	}

	echo '</li>';
}
?>
<style>
	ol.sortable, ol.sortable ol {
		margin: 0 0 0 25px;
		padding: 0;
		list-style-type: none;
	}

	ol.sortable {
		margin: 0;
	}

	.sortable li {
		margin: 5px 0 0 0;
		padding: 0;
	}

	.sortable li div  {
		border: 1px solid #d4d4d4;
		-webkit-border-radius: 3px;
		-moz-border-radius: 3px;
		border-radius: 3px;
		border-color: #D4D4D4 #D4D4D4 #BCBCBC;
		padding: 6px;
		margin: 0;
		cursor: move;
		background: #f6f6f6;
		background: -moz-linear-gradient(top,  #ffffff 0%, #f6f6f6 47%, #ededed 100%);
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(47%,#f6f6f6), color-stop(100%,#ededed));
		background: -webkit-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
		background: -o-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
		background: -ms-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
		background: linear-gradient(to bottom,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#ededed',GradientType=0 );
	}

	.sortable li.mjs-nestedSortable-branch div {
		background: -moz-linear-gradient(top,  #ffffff 0%, #f6f6f6 47%, #f0ece9 100%);
		background: -webkit-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#f0ece9 100%);

	}

	.sortable li.mjs-nestedSortable-leaf div {
		background: -moz-linear-gradient(top,  #ffffff 0%, #f6f6f6 47%, #bcccbc 100%);
		background: -webkit-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#bcccbc 100%);

	}

	li.mjs-nestedSortable-collapsed.mjs-nestedSortable-hovering div {
		border-color: #999;
		background: #fafafa;
	}

	.disclose {
		cursor: pointer;
		width: 10px;
		display: none;
	}

	.sortable li.mjs-nestedSortable-collapsed > ol {
		display: none;
	}

	.sortable li.mjs-nestedSortable-branch > div > .disclose {
		display: inline-block;
	}

	.sortable li.mjs-nestedSortable-collapsed > div > .disclose > span:before {
		content: '+ ';
	}

	.sortable li.mjs-nestedSortable-expanded > div > .disclose > span:before {
		content: '- ';
	}
</style>
<form class="form-horizontal" id="menuForm" method="POST" action="<?php echo $this->url(array('action' => $this->getRequest()->getActionName())); ?>">
	<legend><?php echo $this->trans('menuChange'); ?></legend>
	<div class="row">
		<div class="col-md-6">
		  <ol id="sortable" class="sortable">
				<?php
					if(!empty($menuItems))
					{
						foreach($menuItems as $item)
						{
							rec($item, $menuMapper);
						}
					}
				?>
			</ol>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label for="maintenanceMode" class="col-lg-2 control-label">
					Seitentitel
				</label>
				<div class="col-lg-4">
					<input type="text" class="form-control" id="title" />
				</div>
			</div>
			<div class="form-group">
				<label for="maintenanceMode" class="col-lg-2 control-label">
					Adresse
				</label>
				<div class="col-lg-4">
					<input type="text" class="form-control" id="href" value="http://" />
				</div>
			</div>
			<button type="button" id="menuItemAdd" class="btn">
				<?php echo $this->trans('menuItemAdd'); ?>
			</button>
		</div>
	</div>
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
			var itemId = 999;
			$('.sortable').nestedSortable
			({
				handle: 'div',
				items: 'li',
				toleranceElement: '> div',
			});
			
			$('.disclose').on('click', function()
			{
				$(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
			});
			
			$('#menuForm').submit
			(
				function()
				{
					$('#hiddenMenu').val(JSON.stringify($('.sortable').nestedSortable('toArray', {startDepthCount: 0})));
				}
			);
				
			$('#menuItemAdd').click
			(
				function()
				{
					$('<li id="tmp_'+itemId+'"><div><span class="disclose"><span>'
							+'<input type="hidden" name="items[tmp_'+itemId+'][id]" value="tmp_'+itemId+'" />'
							+'<input type="hidden" name="items[tmp_'+itemId+'][title]" value="'+$('#title').val()+'" />'
							+'<input type="hidden" name="items[tmp_'+itemId+'][href]" value="'+$('#href').val()+'" />'
							+'</span></span>'+$('#title').val()+'</div></li>').appendTo('#sortable');
					itemId++;
				}
			);
		}
	);
</script>