<link href="<?=$this->getBaseUrl('application/modules/media/static/css/media.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('mediaFromImport') ?>
    <a class="badge rounded-pill bg-secondary" data-bs-toggle="modal" data-bs-target="#infoModal">
        <i class="fa-solid fa-info" ></i>
    </a>
</h1>
<?php if (!empty($this->get('media'))): ?>
    <form method="POST">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                        <col class="icon_width">
                        <col class="col-1">
                        <col class="col-xl-1">
                        <col class="col-xl-1">
                        <col>
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_medias') ?></th>
                        <th><?=$this->getTrans('type') ?></th>
                        <th></th>
                        <th><?=$this->getTrans('size') ?></th>
                        <th><?=$this->getTrans('name') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('media') as $media): ?>
                        <?php
                        $upload = new \Ilch\Upload();
                        $upload->setFile($media);
                        ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_medias', $media) ?></td>
                            <td><?=$this->escape($upload->getEnding()) ?></td>
                            <td>
                                <?php if (in_array($upload->getEnding(), explode(' ', $this->get('media_ext_img')))): ?>
                                    <img class="img-preview" src="<?=$this->getBaseUrl($media) ?>" alt="">
                                <?php else: ?>
                                    <img src="<?=$this->getBaseUrl('application/modules/media/static/img/nomedia.png') ?>"
                                         class="img-preview"
                                         alt="<?=$this->escape($upload->getName()) ?>"
                                         style="width:50px; height:auto;" />
                                <?php endif; ?>
                            </td>
                            <td><?=$upload->getSize() ?></td>
                            <td><?=$this->escape($upload->getName()) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getSaveBar('add') ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noMedias') ?>
<?php endif; ?>

<?=$this->getDialog('infoModal', $this->getTrans('info'), $this->getTrans('importInfoText')) ?>
