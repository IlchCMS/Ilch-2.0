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
			<td>>= 5.3</td>
			<td><?php echo $this->phpVersion; ?></td>
		</tr>
	</tbody>
</table>