<?php
if($this->get('pages') != '')
{
?>
<table class="table table-hover">
	<thead>
		<tr>
			<th></th>
			<th>Seitentitel</th>
			<th>URL</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach($this->get('pages') as $page)
		{
			echo '<tr>
					<td><a href="'.$this->url(array('module' => 'page', 'controller' => 'index', 'action' => 'change', 'id' => $page->getId())).'"<i class="icon-edit"></i></a></td>
					<td>'.$page->getTitle().'</td>
					<td><a target="_blank" href="'.$this->url().'/index.php/'.$page->getPerma().'">Öffnen</a></td>
				</tr>';
		}
		?>
	</tbody>
</table>
<?php
}
else
{
	echo 'Keine Seiten vorhanden';
}
?>