<table class="table table-hover">
	<thead>
		<tr>
			<th></th>
			<th><?php echo $this->getTranslator()->trans('required'); ?></th>
			<th><?php echo $this->getTranslator()->trans('available'); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php echo $this->getTranslator()->trans('phpVersion'); ?></td>
			<td class="text-success">>= 5.3</td>
			<td><?php echo $this->phpVersion; ?></td>
		</tr>
		<tr>
			<td><?php echo $this->getTranslator()->trans('writableConfig').' "'.CONFIG_PATH.'"' ?></td>
			<td class="text-success">writable</td>
			<td>
				<?php
					if(is_writable(CONFIG_PATH))
					{
						echo '<span class="text-success">writable</span>';
					}
					else
					{
						echo '<span class="text-error">not writable</span>';
					}
				?>
			</td>
		</tr>
	</tbody>
</table>