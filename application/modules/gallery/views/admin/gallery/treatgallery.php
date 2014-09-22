<legend>
    <?php echo $this->getTrans('gallery'); ?>: <?php echo $this->get('galleryTitle'); ?>
</legend>
<?php echo $this->get('pagination')->getHtml($this, array('action' => 'treatgallery', 'id' => $this->getRequest()->getParam('id'))); ?>
<?php 
    $image = $this->get('image');
    if (!empty($image)) {
?>
<form class="form-horizontal" method="POST" action="<?php echo $this->getUrl(array('action' => $this->getRequest()->getActionName(), 'id' => $this->getRequest()->getParam('id'))); ?>">
    <?php echo $this->getTokenField(); ?>

    <table class="table table-hover">
        <colgroup>
                <col class="icon_width" />
                <col class="icon_width" />
                <col class="icon_width" />
                <col class="col-lg-2" />
                <col class="col-lg-4" />
                <col />
        </colgroup>
        <thead>
            <tr>
                <th><?=$this->getCheckAllCheckbox('check_gallery')?></th>
                <th></th>
                <th></th>
                <th><?php echo $this->getTrans('images'); ?></th>
                <th><?php echo $this->getTrans('imageTitle'); ?></th>
                <th><?php echo $this->getTrans('imageDesc'); ?></th>
            </tr>
        </thead>
        <tbody><?php foreach ($this->get('image') as $image) : ?>
            <tr>
                <td><input value="<?=$image->getId()?>" type="checkbox" name="check_gallery[]" /></td>
                <td><?=$this->getEditIcon(array('controller' => 'image', 'action' => 'treatimage', 'gallery' => $image->getCat(), 'id' => $image->getId()))?></td>
                <td><?=$this->getDeleteIcon(array('action' => 'del', 'id' => $image->getId(), 'gallery' => $this->getRequest()->getParam('id')))?></td>
                <td>
                    <img class="image thumbnail img-responsive" src="<?php echo $this->getUrl().'/'.$image->getImageThumb(); ?>"/>
                </td>
                <td><?php echo $image->getImageTitle()?></td>
                <td><?php echo $image->getImageDesc()?></td>
            </tr><?php endforeach; ?>
        </tbody>
    </table>
<?=$this->getListBar(array('delete' => 'delete'))?>
</form>
<?php
} else {
    echo $this->getTrans('noImage');
} ?>
<script>
    function media(){ $('#MediaModal').modal('show');
        var src = iframeSingleUrlGallery+'id/'+<?php echo $this->getRequest()->getParam('id') ?>;
        var height = '100%';
        var width = '100%';

        $("#MediaModal iframe").attr({'src': src,
            'height': height,
            'width': width});
    };
</script>
<style>
    tbody tr td {
        padding-bottom: 0 !important;
    }
    .image {
        width: 100px;
        max-width: 100px;
        margin-bottom: 10px !important;
    }
</style>