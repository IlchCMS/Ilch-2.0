<legend><?php echo $this->trans('media'); ?></legend>
<?php
if ($this->get('medias') != '') {
?>
<table class="table table-hover">
    <colgroup>
        <col class="col-lg-1">
        <col />
    </colgroup>
    <thead>
        <tr>
            <th><?php echo $this->trans('mediatype'); ?></th>
            <th><?php echo $this->trans('description'); ?></th>
            <th><?php echo $this->getTranslator()->trans('options'); ?></th>
        </tr>
    </thead>
    
        <tbody><?php foreach ($this->get('medias') as $media) : ?>
            <tr>
                <td><img src="/<?php echo $media->getUrl(); ?>" class="thumbnail img-responsive"/></td>
                <td></td>
                <td>
					<a href="/index.php/admin/gallery/index/show/id/"><i class="fa fa-edit"></i></a>
                    <a href="<?php  echo $this->url(array('action' => 'del', 'id' => $media->getId())); ?>"><i class="fa fa-times-circle"></i></a>
				</td>
			</tr><?php endforeach; ?>
        </tbody>
    
</table>
<?php
} else {
    echo $this->trans('noMedias');
}
?>