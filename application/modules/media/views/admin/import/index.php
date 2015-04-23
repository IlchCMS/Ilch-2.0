<link href="<?=$this->getBaseUrl('application/modules/media/static/css/media.css') ?>" rel="stylesheet">
<legend><?=$this->getTrans('mediaFromImport') ?></legend>
<?php $medias = $this->get('media'); ?>
<?php if (!empty($medias)): ?>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                    <col class="icon_width" />
                    <col class="col-xs-1"/>
                    <col class="col-lg-1" />
                    <col class="col-lg-1" />
                    <col />
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
                <?php foreach ($medias as $media): ?>
                    <?php
                    $upload = new \Ilch\Upload();
                    $upload->setFile($media);
                    ?>
                    <tr>
                        <td><input value="<?=$media ?>" type="checkbox" name="check_medias[]" /></td>
                        <td><?=$upload->getEnding() ?></td>
                        <td>
                            <?php if (in_array($upload->getEnding(), explode(' ',$this->get('media_ext_img')))): ?>
                                <img class="img-preview" src="<?=$this->getBaseUrl($this->get('path').$upload->getName().'.'.$upload->getEnding()) ?>" alt="">
                            <?php else: ?>
                                <img src="<?=$this->getBaseUrl('application/modules/media/static/img/nomedia.png') ?>"
                                     class="img-preview"
                                     alt="<?=$upload->getName() ?>"
                                     style="width:50px; height:auto;" />
                            <?php endif; ?>
                        </td>
                        <td><?=$upload->getSize() ?></td>
                        <td><?=$upload->getName() ?></td>
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
