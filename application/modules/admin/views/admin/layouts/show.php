<?php
$layoutsList = url_get_contents($this->get('updateserver').'layouts.php');
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
                <div id="layout-search-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner" role="listbox">
                        <?php $itemI = 0; ?>
                        <?php foreach ($layout->thumbs as $thumb): ?>
                            <div class="item <?=$itemI == 0 ? 'active' : '' ?>">
                                <img src="<?=$this->get('updateserver').'layouts/images/'.$thumb->img ?>" alt="<?=$this->escape($layout->name) ?>">
                                <div class="carousel-caption">
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
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="right carousel-control" href="#layout-search-carousel" role="button" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                <li class="active"><a href="#info" data-toggle="tab"><?=$this->getTrans('info') ?></a></li>
                <li><a href="#changelog" data-toggle="tab"><?=$this->getTrans('changelog') ?></a></li>
            </ul>
            <br />

            <div class="tab-pane active" id="info">
                <div class="col-xs-12 col-lg-6">
                    <div class="row">
                        <div class="col-sm-3 col-xs-6">
                            <b><?=$this->getTrans('name') ?>:</b>
                        </div>
                        <div class="col-sm-9 col-xs-6">
                            <?=$this->escape($layout->name) ?>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <b><?=$this->getTrans('version') ?>:</b>
                        </div>
                        <div class="col-sm-9 col-xs-6">
                            <?=$layout->version ?>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <b><?=$this->getTrans('author') ?>:</b>
                        </div>
                        <div class="col-sm-9 col-xs-6">
                            <?php if ($layout->link != ''): ?>
                                <a href="<?=$layout->link ?>" alt="<?=$this->escape($layout->author) ?>" title="<?=$this->escape($layout->author) ?>" target="_blank" rel="noopener">
                                    <i><?=$this->escape($layout->author) ?></i>
                                </a>
                            <?php else: ?>
                                <i><?=$this->escape($layout->author) ?></i>
                            <?php endif; ?>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <b><?=$this->getTrans('hits') ?>:</b>
                        </div>
                        <div class="col-sm-9 col-xs-6">
                            <?=$layout->hits ?>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <b><?=$this->getTrans('downloads') ?>:</b>
                        </div>
                        <div class="col-sm-9 col-xs-6">
                            <?=$layout->downs ?>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <b><?=$this->getTrans('rating') ?>:</b>
                        </div>
                        <div class="col-sm-9 col-xs-6">
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
                    <div class="row">
                        <div class="col-xs-12">
                            <b><?=$this->getTrans('desc') ?>:</b>
                        </div>
                        <div class="col-xs-12">
                            <?=$this->escape($layout->desc) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="changelog">
                <div class="col-xs-12">
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
                <button class="btn btn-default disabled" title="<?=$this->getTrans('alreadyExists') ?>">
                    <i class="fa fa-check text-success"></i> <?=$this->getTrans('alreadyExists') ?>
                </button>
            <?php else: ?>
                <form method="POST" action="<?=$this->getUrl(['module' => 'admin', 'controller' => 'layouts', 'action' => 'search', 'key' => $layout->key]) ?>">
                    <?=$this->getTokenField() ?>
                    <button type="submit" class="btn btn-default">
                        <i class="fa fa-download"></i> <?=$this->getTrans('download') ?>
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
