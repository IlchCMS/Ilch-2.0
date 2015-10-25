<link href="<?=$this->getBaseUrl('application/modules/media/static/css/media.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('mediaFromImport') ?>
    <a class="badge" data-toggle="modal" data-target="#infoModal">
        <i class="fa fa-info" ></i>
    </a>
</legend>
<?php if (!empty($this->get('media'))): ?>
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
                    <?php foreach ($this->get('media') as $media): ?>
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

    <div class="modal fade" id="infoModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?=$this->getTrans('info') ?></h4>
                </div>
                <div class="modal-body">
                    <p id="modalText"><?=$this->getTrans('importInfoText') ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-primary"
                            data-dismiss="modal"><?=$this->getTrans('close') ?></button>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <?=$this->getTrans('noMedias') ?>
<?php endif; ?>
