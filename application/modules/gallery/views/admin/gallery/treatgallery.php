<legend>
    <?=$this->getTrans('gallery'); ?>: <?=$this->get('galleryTitle'); ?>
</legend>
<?=$this->get('pagination')->getHtml($this, array('action' => 'treatgallery', 'id' => $this->getRequest()->getParam('id'))); ?>
<?php 
    $image = $this->get('image');
    if (!empty($image)) {
?>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName(), 'id' => $this->getRequest()->getParam('id'))); ?>">
    <?=$this->getTokenField(); ?>

    <div class="col-xs-12">
    <div class="table-responsive">
        <table class="table table-hover table-striped">
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
                    <th><?=$this->getTrans('images'); ?></th>
                    <th><?=$this->getTrans('imageTitle'); ?></th>
                    <th><?=$this->getTrans('imageDesc'); ?></th>
                </tr>
            </thead>
            <tbody><?php foreach ($this->get('image') as $image) : ?>
                <tr>
                    <td><input value="<?=$image->getId()?>" type="checkbox" name="check_gallery[]" /></td>
                    <td><?=$this->getEditIcon(array('controller' => 'image', 'action' => 'treatimage', 'gallery' => $image->getCat(), 'id' => $image->getId()))?></td>
                    <td><?=$this->getDeleteIcon(array('action' => 'del', 'id' => $image->getId(), 'gallery' => $this->getRequest()->getParam('id')))?></td>
                    <td>
                        <img class="image thumbnail img-responsive" src="<?=$this->getUrl().'/'.$image->getImageThumb(); ?>"/>
                    </td>
                    <td><?=$image->getImageTitle()?></td>
                    <td><div  class="table_text"><?=$image->getImageDesc()?></div></td>
                </tr><?php endforeach; ?>
            </tbody>
        </table>
    </div>
    </div>
<?=$this->getListBar(array('delete' => 'delete'))?>
</form>
<?php
} else {
    echo $this->getTrans('noImage');
} ?>
<script>
    function media(){ $('#MediaModal').modal('show');
        var src = iframeSingleUrlGallery+'id/'+<?=$this->getRequest()->getParam('id') ?>;
        var height = '100%';
        var width = '100%';

        $("#MediaModal iframe").attr({'src': src,
            'height': height,
            'width': width});
    };

    function reload(){
        setTimeout(function(){window.location.reload(1);}, 1000);
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