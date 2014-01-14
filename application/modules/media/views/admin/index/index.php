<legend><?php echo $this->trans('media'); ?></legend>
<?php
if ($this->get('medias') != '') {
?>
<table class="table table-hover">
    <colgroup>
        <col class="col-lg-12">
        <col />
    </colgroup>
    <thead>
        <tr>
            <th><?php echo $this->trans('mediatype'); ?></th>
            <th><?php echo $this->trans('name'); ?></th>
            <th><?php echo $this->trans('ending'); ?></th>
            <th><?php echo $this->trans('date'); ?></th>
            <th><?php echo $this->getTranslator()->trans('options'); ?></th>
        </tr>
    </thead>
    
        <tbody><?php foreach ($this->get('medias') as $media) : ?>
            <tr>
                <td><?php if( $media->getEnding() != 'zip'){
                echo '<a class="fancybox" href="/'.$media->getUrl().'" data-fancybox-group="gallery" title="Lorem ipsum dolor sit amet"><img src="/'.$media->getUrlThumb().'" alt=""></a>';
                }  else {
                    echo '<img src="'.$this->staticUrl('../application/modules/media/static/img/nomedia.jpg').'" class="thumbnail img-responsive" style="width:150px; height:auto;"  />';
                }
                    ?></td>
                <td><?php echo $media->getName(); ?></td>
                <td><?php echo $media->getEnding(); ?></td>
                <td><?php echo $media->getDatetime(); ?></td>
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
<script>$(document).ready(function(){
   $('.fancybox').fancybox(); 
});</script>