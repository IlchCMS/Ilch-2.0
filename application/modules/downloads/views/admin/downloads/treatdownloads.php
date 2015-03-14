<legend>
    <?=$this->getTrans('downloads'); ?>: <?=$this->get('downloadsTitle'); ?>
</legend>
<?=$this->get('pagination')->getHtml($this, array('action' => 'treatdownloads', 'id' => $this->getRequest()->getParam('id'))); ?>
<?php 
    $file = $this->get('file');
    if (!empty($file)) {
?>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName(), 'id' => $this->getRequest()->getParam('id'))); ?>">
    <?=$this->getTokenField(); ?>

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
                <th><?=$this->getCheckAllCheckbox('check_downloads')?></th>
                <th></th>
                <th></th>
                <th><?=$this->getTrans('file'); ?></th>
                <th><?=$this->getTrans('fileTitle'); ?></th>
                <th><?=$this->getTrans('fileDesc'); ?></th>
            </tr>
        </thead>
        <tbody><?php foreach ($this->get('file') as $file) : ?>
            <tr>
                <td><input value="<?=$file->getId()?>" type="checkbox" name="check_downloads[]" /></td>
                <td><?=$this->getEditIcon(array('controller' => 'file', 'action' => 'treatfile', 'downloads' => $file->getCat(), 'id' => $file->getId()))?></td>
                <td><?=$this->getDeleteIcon(array('action' => 'del', 'id' => $file->getId(), 'downloads' => $this->getRequest()->getParam('id')))?></td>
                <td>
                    <img class="file thumbnail img-responsive" src="<?=$this->getStaticUrl('../application/modules/media/static/img/nomedia.png')?>"/>
                </td>
                <td></td>
                <td></td>
            </tr><?php endforeach; ?>
        </tbody>
    </table>
<?=$this->getListBar(array('delete' => 'delete'))?>
</form>
<?php
} else {
    echo $this->getTrans('noFile');
} ?>
<script>
    <?php echo $this->getMediaModal(
            $mediaButton = $this->getUrl('admin/media/iframe/multi/type/file/id/'.$this->getRequest()->getParam('id')),
            $actionButton =$this->getUrl('admin/downloads/downloads/treatdownloads/id/'.$this->getRequest()->getParam('id')))?>
    function reload(){
        setTimeout(function(){window.location.reload(1);}, 1000);
    };
</script>
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