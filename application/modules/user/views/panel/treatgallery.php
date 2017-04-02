<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<div class="row">
    <div class="col-lg-12 profile">
        <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>

        <div class="profile-content active">
            <h1>
                <?=$this->getTrans('gallery') ?>: <?=$this->get('galleryTitle') ?>
                <a id="<?=$this->getRequest()->getParam('id') ?>" href="javascript:media(<?=$this->getRequest()->getParam('id') ?>)">
                    <i class="fa fa-cloud-upload"></i>
                </a>
            </h1>
            <?=$this->get('pagination')->getHtml($this, ['action' => 'treatgallery', 'id' => $this->getRequest()->getParam('id')]) ?>
            <?php if ($this->get('image')): ?>
                <form class="form-horizontal" method="POST" action="">
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
                                    <th><?=$this->getTrans('title') ?></th>
                                    <th><?=$this->getTrans('description') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($this->get('image') as $image): ?>
                                    <tr>
                                        <td><?=$this->getDeleteCheckbox('check_gallery', $image->getImageId()) ?></td>
                                        <td><?=$this->getEditIcon(['action' => 'treatgalleryimage', 'gallery' => $this->getRequest()->getParam('id'), 'id' => $image->getId()]) ?></td>
                                        <td><?=$this->getDeleteIcon(['action' => 'delgalleryimage', 'gallery' => $this->getRequest()->getParam('id'), 'id' => $image->getImageId()]) ?></td>
                                        <?php if (file_exists($image->getImageThumb())): ?>
                                            <td><img class="image thumbnail img-responsive" src="<?=$this->getUrl().'/'.$image->getImageThumb() ?>"/></td>
                                        <?php else: ?>
                                            <td><img class="image thumbnail img-responsive" src="<?=$this->getBaseUrl('application/modules/media/static/img/nomedia.png') ?>"/></td>
                                        <?php endif; ?>
                                        <td><?=$this->escape($image->getImageTitle()) ?></td>
                                        <td><?=$this->escape($image->getImageDesc()) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?=$this->getListBar(['delete' => 'delete']) ?> 
                </form>
            <?php else: ?>
                <?=$this->getTrans('noImages') ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
<script>
// Set a variable to later display a translated message. Used in ../application/modules/admin/static/js/functions.js
var deleteSelectedEntries = <?=json_encode($this->getTrans('deleteSelectedEntries')) ?>;

<?=$this->getMedia()
        ->addActionButton($this->getUrl('user/panel/treatgallery/id/'))
        ->addMediaButton($this->getUrl('user/iframe/multi/type/multi/id/'))
        ->addUploadController($this->getUrl('user/iframe/upload'))
?>

function reload() {
    setTimeout(function(){window.location.reload(1);}, 1000);
};
</script>
