<legend>Gästebuch verwalten</legend>

<?php if (is_array($this->get('entries')) && count($this->get('entries')) > 0) : ?>
	<div class="table-responsive">
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Von</th>
					<th>Text</th>
					<th>Löschen</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->get('entries') as $entry) : ?>
					<tr>
						<td><?php echo $this->escape($entry['name']); ?></td>
						<td><?php echo $entry['text']; ?></td>
						<td><a href="<?php echo $this->url(array('module' => 'guestbook', 'controller' => 'index', 'action' => 'del', 'id' => $entry['id'])); ?>">
							<span class="item_delete" title="Löschen"><i class="fa fa-times-circle"></i></span></a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php endif; ?>




 

