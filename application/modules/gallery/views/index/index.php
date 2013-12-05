<h2>Gallery</h2>

 
<?php if (is_array($this->get('cats')) && count($this->get('cats')) > 0) : ?>
<div class="table-responsive">

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
				<th >Kategorie</th>
                <th>Beschreibung</th>
				<th>Ansehen</th>
            </tr>
        </thead>
		<?php foreach ($this->get('cats') as $cats) : ?>
        <tbody>
            <tr>
                <td><?php echo $this->escape($cats['name']); ?></td>
                <td><?php echo $this->escape($cats['besch']); ?>
				<td><div><a href="/index.php/gallery/index/show/id/<?php echo $this->escape($cats['id']); ?>"><span class="item_delete"><i class="fa fa-times-circle">Ansehen</i></span></a></div></td>
		<?php endforeach; ?>           
	        </tr>
        </tbody>
    </table>

</div>
<?php endif; ?>