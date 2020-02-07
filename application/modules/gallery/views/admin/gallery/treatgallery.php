<h1><?=$this->getTrans('gallery') ?>: <?=$this->get('galleryTitle') ?></h1>
<?=$this->get('pagination')->getHtml($this, ['action' => 'treatgallery', 'id' => $this->getRequest()->getParam('id')]) ?>
<?php if ($this->get('image')): ?>
    <form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName(), 'id' => $this->getRequest()->getParam('id')]) ?>">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-lg-2">
                    <col class="col-lg-4">
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_gallery') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('images') ?></th>
                        <th><?=$this->getTrans('imageTitle') ?></th>
                        <th><?=$this->getTrans('imageDesc') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('image') as $image): ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_gallery', $image->getId()) ?></td>
                            <td><?=$this->getEditIcon(['controller' => 'image', 'action' => 'treatimage', 'gallery' => $image->getCat(), 'id' => $image->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $image->getId(), 'gallery' => $this->getRequest()->getParam('id')]) ?></td>
                            <?php if (file_exists($image->getImageThumb())): ?>
                                <td><img class="image thumbnail img-responsive" src="<?=$this->getUrl().'/'.$image->getImageThumb() ?>"/></td>
                            <?php else: ?>
                                <td><img class="image thumbnail img-responsive" src="<?=$this->getBaseUrl('application/modules/media/static/img/nomedia.png') ?>"/></td>
                            <?php endif; ?>
                            <td><?=$this->escape($image->getImageTitle()) ?></td>
                            <td><div class="table_text"><?=$this->escape($image->getImageDesc()) ?></div></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noImage') ?>
<?php endif; ?>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
<script>
<?=$this->getMedia()
        ->addActionButton($this->getUrl('admin/gallery/gallery/treatgallery/id/'.$this->getRequest()->getParam('id').'/'))
        ->addMediaButton($this->getUrl('admin/media/iframe/multi/type/multi/id/'.$this->getRequest()->getParam('id').'/'))
        ->addUploadController($this->getUrl('admin/media/index/upload'))
?>

function reload() {
    setTimeout(function(){window.location.reload(1);}, 1000);
};
</script>
