<link href="<?=$this->getBaseUrl('application/modules/media/static/css/media.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('media') ?></h1>
<?php if ($this->get('medias') != ''): ?>
    <div id="filter-media" >
        <div id="filter-panel" class="collapse filter-panel">
            <form class="form-horizontal" method="POST">
                <?=$this->getTokenField() ?>
                <div class="form-group">
                    <label class="col-lg-2 control-label" for="pref-perpage"><?=$this->getTrans('rowsPerPage') ?>:</label>
                    <div class="col-lg-2">
                        <select class="form-control" id="pref-perpage" name="rows">
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option selected="selected" value="20">20</option>
                            <option value="30">30</option>
                            <option value="40">40</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="200">200</option>
                            <option value="300">300</option>
                            <option value="400">400</option>
                            <option value="500">500</option>
                            <option value="1000">1000</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label" for="pref-orderby"><?=$this->getTrans('orderBy') ?>:</label>
                    <div class="col-lg-2">
                        <select class="form-control" id="pref-orderby" name="order">
                            <option value="ASC"><?=$this->getTrans('ascending') ?></option>
                            <option value="DESC"><?=$this->getTrans('descending') ?></option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label" for="pref-orderbytype"><?=$this->getTrans('mediaType') ?>:</label>
                    <div class="col-lg-2">
                        <select class="form-control" id="pref-orderbytype" name="orderbytype">
                            <option value="all"><?=$this->getTrans('all') ?></option>
                            <option value="image"><?=$this->getTrans('image') ?></option>
                            <option value="video"><?=$this->getTrans('video') ?></option>
                            <option value="file"><?=$this->getTrans('file') ?></option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-default filter-col" name="search" value="search">
                        <span class="fa fa-search"></span> <?=$this->getTrans('search') ?>
                    </button>
                </div>
            </form>
        </div>
        <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#filter-panel">
            <span class="fa fa-cogs"></span> <?=$this->getTrans('advancedSearch') ?>
        </button>
    </div>
    <?=$this->get('pagination')->getHtml($this, $this->get('rows')) ?>
    <form class="form-horizontal" method="POST">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-xs-1">
                    <col class="col-lg-1">
                    <col>
                    <col class="col-lg-2">
                    <col class="col-lg-2">
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_medias') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('type') ?></th>
                        <th></th>
                        <th><?=$this->getTrans('name') ?></th>
                        <th><?=$this->getTrans('date') ?></th>
                        <th><?=$this->getTrans('cat') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('medias') as $media): ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_medias', $media->getId()) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $media->getId()]) ?></td>
                            <td><a href="<?=$this->getUrl(['action' => 'refresh', 'id' => $media->getId()]) ?>"><i class="fa fa-refresh" title="<?=$this->getTrans('refreshThumbnail') ?>"></i></a></td>
                            <td><?=$this->escape($media->getEnding()) ?></td>
                            <td>
                                <?php if (in_array($media->getEnding(), explode(' ',$this->get('media_ext_img')))): ?>
                                    <a href="<?=$this->getBaseUrl($media->getUrl()) ?>" title="<?=$this->escape($media->getName()) ?>">
                                        <?php if (file_exists($media->getUrlThumb())): ?>
                                            <img class="img-preview" src="<?=$this->getBaseUrl($media->getUrlThumb()) ?>" alt="<?=$this->escape($media->getName()) ?>">
                                        <?php else: ?>
                                            <img class="img-preview" src="<?=$this->getBaseUrl('application/modules/media/static/img/nomedia.png') ?>" alt="<?=$this->escape($media->getName()) ?>">
                                        <?php endif; ?>
                                    </a>
                                <?php else: ?>
                                    <img src="<?=$this->getBaseUrl('application/modules/media/static/img/nomedia.png') ?>"
                                         class="img-preview"
                                         alt="<?=$this->escape($media->getName()) ?>"
                                         style="width:50px; height:auto;" />
                                <?php endif; ?>
                            </td>
                            <td><a href="<?=$this->getBaseUrl($media->getUrl()) ?>" download="<?=$this->escape($media->getName().".".$media->getEnding()) ?>"><?=$this->escape($media->getName()) ?></a></td>
                            <td><?=$media->getDatetime() ?></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button"
                                            class="btn btn-outline-secondary dropdown-toggle"
                                            aria-expanded="false"
                                            data-bs-toggle="dropdown"><?=$this->escape($media->getCatName()) ?>
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu listChooser" role="menu">
                                        <?php if ($this->get('catnames') != ''): ?>
                                            <?php foreach ($this->get('catnames') as $name): ?>
                                                <li>
                                                    <a class="dropdown-item" href="<?=$this->getUrl(['controller' => 'cats', 'action' => 'setCat', 'catid' => $name->getId(), 'mediaid' => $media->getId()]) ?>"><?=$this->escape($name->getCatName()) ?></a>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="modal" id="modalDialogAssignCategory" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4><?=$this->getTrans('assignCategory') ?></h4>
                    </div>
                    <div class="modal-body">
                        <ul class="list-unstyled">
                            <?php foreach ($this->get('catnames') as $name): ?>
                                <li><button name="assignedCategory" class="btn btn-default list-group-item" type="submit" value="<?=$name->getId() ?>"><?=$this->escape($name->getCatName()) ?></button></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-primary"
                                data-dismiss="modal"><?=$this->getTrans('close') ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <?=$this->get('pagination')->getHtml($this, $this->get('rows')) ?>
        <?=$this->getListBar(['delete' => 'delete', 'assignCategory' => 'assignCategory']) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noMedias') ?>
<?php endif; ?>

<script>
    $('#assignCategory').click(
        function()
        {
            let dialog = $('#modalDialogAssignCategory');
            dialog.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + $(window).scrollTop()) + "px");
            dialog.modal()
        }
    );
</script>
