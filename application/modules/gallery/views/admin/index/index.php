<legend>Gallery</legend>

 
<?php if (is_array($this->get('cats')) && count($this->get('cats')) > 0) : ?>
<div class="table-responsive">

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
				<th >Kategorie</th>
                <th>Beschreibung</th>
            </tr>
        </thead>
		<?php foreach ($this->get('cats') as $cats) : ?>
        <tbody>
            <tr>
                <td><?php echo $this->escape($cats['name']); ?></td>
                <td><?php echo $this->escape($cats['besch']); ?>
				<div class="span6 pull-right">
					<a href="/index.php/admin/gallery/index/upload/id/<?php echo $this->escape($cats['id']); ?>"><span class="item_delete"><i class="fa fa-times-circle"></i></span></a>
					<a href="/index.php/admin/gallery/index/show/id/<?php echo $this->escape($cats['id']); ?>"><span class="item_edit"><i class="fa fa-edit"></i></span></a>
				</div>
				</td>
		<?php endforeach; ?>           
	        </tr>
        </tbody>
    </table>

</div>


	<div class="form-group">
		<a href="/index.php/admin/gallery/index/newcat" class="btn btn-default btn-lg" >Neue Kategorie</a>
	</div>

<?php endif; ?>