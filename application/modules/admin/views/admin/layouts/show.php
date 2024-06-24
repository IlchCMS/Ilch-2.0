<?php
$layoutsList = url_get_contents($this->get('updateserver') . 'layouts.json');
$layouts = json_decode($layoutsList);
?>

<link href="<?=$this->getModuleUrl('static/css/extsearch.css') ?>" rel="stylesheet">
<link href="<?=$this->getVendorUrl('kartik-v/bootstrap-star-rating/css/star-rating.min.css') ?>" rel="stylesheet">

<?php

if (empty($layouts)) {
    echo $this->getTrans('noLayoutsAvailable');
    return;
}

foreach ($layouts as $layout): ?>
    <?php if ($layout->id == $this->getRequest()->getParam('id')): ?>
        <div id="layout" class="tab-content">
            <?php if (!empty($layout->thumbs)): ?>
                <div id="layout-search-carousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" role="listbox">
                        <?php $itemI = 0; ?>
                        <?php foreach ($layout->thumbs as $thumb): ?>
                            <div class="carousel-item text-center <?=$itemI == 0 ? 'active' : '' ?>">
                                <img class="d-block ms-auto me-auto" src="<?=$this->get('updateserver').'layouts/images/'.$thumb->img ?>" alt="<?=$this->escape($layout->name) ?>">
                                <div class="carousel-caption d-none d-md-block">
                                    <?php if ($thumb->desc != ''): ?>
                                        <?=$this->escape($thumb->desc) ?>
                                    <?php else: ?>
                                        <?=$this->escape($layout->name) ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php $itemI++; ?>
                        <?php endforeach; ?>
                    </div>

                    <?php if(count($layout->thumbs) > 1): ?>
                        <a class="left carousel-control" href="#layout-search-carousel" role="button" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </a>
                        <a class="right carousel-control" href="#layout-search-carousel" role="button" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                <li class="nav-item"><a class="nav-link active" href="#info" data-bs-toggle="tab"><?=$this->getTrans('info') ?></a></li>
                <li class="nav-item"><a class="nav-link"href="#changelog" data-bs-toggle="tab"><?=$this->getTrans('changelog') ?></a></li>
            </ul>
            <br />

            <div class="tab-pane active" id="info">
                <div class="col-12 col-lg-6">
                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <b><?=$this->getTrans('name') ?>:</b>
                        </div>
                        <div class="col-md-9 col-sm-6">
                            <?=$this->escape($layout->name) ?>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <b><?=$this->getTrans('version') ?>:</b>
                        </div>
                        <div class="col-md-9 col-sm-6">
                            <?=$layout->version ?>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <b><?=$this->getTrans('author') ?>:</b>
                        </div>
                        <div class="col-md-9 col-6">
                            <?php if ($layout->link != ''): ?>
                                <a href="<?=$layout->link ?>" alt="<?=$this->escape($layout->author) ?>" title="<?=$this->escape($layout->author) ?>" target="_blank" rel="noopener">
                                    <i><?=$this->escape($layout->author) ?></i>
                                </a>
                            <?php else: ?>
                                <i><?=$this->escape($layout->author) ?></i>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <b><?=$this->getTrans('hits') ?>:</b>
                        </div>
                        <div class="col-md-9 col-sm-6">
                            <?=$layout->hits ?>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <b><?=$this->getTrans('downloads') ?>:</b>
                        </div>
                        <div class="col-md-9 col-sm-6">
                            <?=$layout->downs ?>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <b><?=$this->getTrans('rating') ?>:</b>
                        </div>
                        <div class="col-md-9 col-sm-6">
                            <span title="<?=$layout->rating ?> <?php if ($layout->rating == 1) { echo $this->getTrans('star'); } else { echo $this->getTrans('stars'); } ?>">
                                <input type="number"
                                       class="rating"
                                       value="<?=$layout->rating ?>"
                                       data-size="xs"
                                       data-readonly="true"
                                       data-show-clear="false"
                                       data-show-caption="false">
                            </span>
                        </div>
                    </div>
                    <br />
                    <?php if (!empty($layout->ilchCore)): ?>
                        <div class="row">
                            <div class="col-12">
                                <b><?=$this->getTrans('requirements') ?>:</b>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <b><?=$this->getTrans('ilchCoreVersion') ?>:</b>
                            </div>
                            <div class="col-md-9 col-sm-6">
                                <?=$this->escape($layout->ilchCore) ?>
                            </div>
                        </div>
                        <br />
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-12">
                            <b><?=$this->getTrans('desc') ?>:</b>
                        </div>
                        <div class="col-12">
                            <?=$this->escape($layout->desc) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="changelog">
                <div class="col-12">
                    <?php if (!empty($layout->changelog)) {
                        echo $layout->changelog;
                    } else {
                        echo $this->getTrans('noChangelog');
                    } ?>
                </div>
            </div>
        </div>

        <div class="content_savebox">
            <?php if (in_array($layout->key, $this->get('layouts'))): ?>
                <button class="btn btn-outline-secondary disabled" title="<?=$this->getTrans('alreadyExists') ?>">
                    <i class="fa-solid fa-check text-success"></i> <?=$this->getTrans('alreadyExists') ?>
                </button>
            <?php else: ?>
                <form method="POST" action="<?=$this->getUrl(['module' => 'admin', 'controller' => 'layouts', 'action' => 'search', 'key' => $layout->key, 'version' => $layout->version]) ?>">
                    <?=$this->getTokenField() ?>
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-download"></i> <?=$this->getTrans('download') ?>
                    </button>
                </form>
            <?php endif; ?>
        </div>
    <?php endif; ?>
<?php endforeach; ?>

<script src="<?=$this->getVendorUrl('kartik-v/bootstrap-star-rating/js/star-rating.min.js') ?>"></script>
<script>
$(document).ready(function(){
    $('#layout-search-carousel').carousel();
});
</script>
