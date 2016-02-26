<legend><?=$this->getTrans('updateProcess') ?></legend>
<?php
$getVersions = $this->get('versions');
$doUpdate = $this->getRequest()->getParam('doupdate');
$doSave = $this->getRequest()->getParam('dosave');
?>

<?php if ($getVersions != ''): ?>
    <p><?=$this->getTrans('versionNow') ?><?=$this->get('version') ?></p>
    <p><?=$this->getTrans('readReleas') ?></p>
    <?php if ($this->get('foundNewVersions')): ?>
        <p><?=$this->getTrans('foundNewVersions') ?>:<?=$this->get('aV') ?></p>
        <?php if(!$doUpdate): ?>
            <?php if (is_file(ROOT_PATH.'/updates/Master-'.$this->get('aV').'.zip')): ?>
                <?php if(!$doSave): ?>
                    <p><?=$this->getTrans('isSave') ?></p>
                <?php else: ?>
                    <p><?=$this->getTrans('save') ?></p>
                <?php endif; ?>
                <p><?=$this->getTrans('updateReady') ?>
                    <a class="btn btn-primary"
                       href="<?=$this->getUrl(array('action' => 'index', 'doupdate' => 'true')) ?>"><?=$this->getTrans('installNow') ?>
                    </a>
                </p>
            <?php else: ?>
                <p><?=$this->getTrans('doSave') ?>
                    <a class="btn btn-primary btn-xs"
                       href="<?=$this->getUrl(array('action' => 'index', 'dosave' => 'true')) ?>"><?=$this->getTrans('doSaveNow') ?>
                    </a>
                </p>
            <?php endif; ?>
        <?php endif; ?>
        <?php if($doUpdate): ?>
                <div class="list-files" id="list-files">
            <?php foreach ($this->get('content') as $list): ?>
                <p><?=$list ?></p>
            <?php endforeach; ?>
            </div>
            <p>UPDATE COMPLIED</p>
        <?php endif; ?>
    <?php endif; ?>
<?php else: ?>
    <p><?=$this->getTrans('noReleas') ?></p>
<?php endif; ?>

    <style>
        .list-files{
            height: 200px;
            overflow-x: hidden;
            border: 1px solid #CCC !important;
            border-radius: 0px;
            padding: 5px;
        }
    </style>
    <script>
        $(document).ready(function(){
           var objDiv = document.getElementById("list-files");
            objDiv.scrollTop = objDiv.scrollHeight;
        });
    </script>
