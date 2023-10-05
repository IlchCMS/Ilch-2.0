<style>
tbody tr td {
    padding-bottom: 0 !important;
}
.file {
    width: 100px;
    max-width: 100px;
    margin-bottom: 10px !important;
}
</style>

<h1><?=$this->getTrans('downloads') ?>: <?=$this->get('downloadsTitle') ?></h1>
<?php if ($this->get('file') != ''): ?>
    <?=$this->get('pagination')->getHtml($this, ['action' => 'treatdownloads', 'id' => $this->getRequest()->getParam('id')]) ?>
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
                        <th><?=$this->getCheckAllCheckbox('check_downloads') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('file') ?></th>
                        <th><?=$this->getTrans('fileTitle') ?></th>
                        <th><?=$this->getTrans('fileDesc') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('file') as $file): ?>
                        <?php $image = '' ?>
                        <?php if ($file->getFileImage() != ''): ?>
                            <?php $image = $this->getBaseUrl($file->getFileImage()) ?>
                        <?php else: ?>
                            <?php $image = $this->getBaseUrl('application/modules/media/static/img/nomedia.png') ?>
                        <?php endif; ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_downloads', $file->getId()) ?></td>
                            <td><?=$this->getEditIcon(['controller' => 'file', 'action' => 'treatfile', 'downloads' => $file->getCat(), 'id' => $file->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $file->getId(), 'downloads' => $this->getRequest()->getParam('id')]) ?></td>
                            <td><img class="file thumbnail img-responsive" src="<?=$image ?>" alt="<?=$this->escape($file->getFileTitle()) ?>" />
                            </td>
                            <td><?=$this->escape($file->getFileTitle()) ?></td>
                            <td><?=$this->escape($file->getFileDesc()) ?></td>
                        </tr>
                     <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noFile') ?>
<?php endif; ?>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
<script>
<?=$this->getMedia()
        ->addMediaButton($this->getUrl('admin/media/iframe/multi/type/file/id/'.$this->getRequest()->getParam('id').'/'))
        ->addActionButton($this->getUrl('admin/downloads/downloads/treatdownloads/id/'.$this->getRequest()->getParam('id').'/'))
        ->addUploadController($this->getUrl('admin/media/index/upload'))
?>

function reload() {
    setTimeout(function(){window.location.reload(1);}, 1000);
}
</script>
