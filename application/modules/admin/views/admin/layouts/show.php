<?php

/** @var \Ilch\View $this */

/** @var \Modules\Admin\Models\Layout[] $layouts */
$layouts = $this->get('layouts');

$layoutsList = url_get_contents($this->get('updateserver') . 'layouts2.php');
$layoutsOnUpdateServer = json_decode($layoutsList, true);
?>

<link href="<?=$this->getModuleUrl('static/css/extsearch.css') ?>" rel="stylesheet">
<link href="<?=$this->getVendorUrl('kartik-v/bootstrap-star-rating/css/star-rating.min.css') ?>" rel="stylesheet">

<?php

if (empty($layoutsOnUpdateServer)) {
    echo $this->getTrans('noLayoutsAvailable');
    return;
}

foreach ($layoutsOnUpdateServer as $layout) : ?>
    <?php if ($layout['id'] == $this->getRequest()->getParam('id')) : ?>
        <?php
        $layoutModel = new \Modules\Admin\Models\Layout();
        $layoutModel->setByArray($layout);

        $ilchCore = null;
        if (!empty($layoutModel->getIlchCore())) {
            if ($layoutModel->hasCoreVersion()) {
                $ilchCore = '<font color="#3c763d">' . $this->escape($layoutModel->getIlchCore()) . '</font>';
            } else {
                $ilchCore = '<font color="#a94442">' . $this->escape($layoutModel->getIlchCore()) . '</font>';
            }
        }
        ?>
        <div id="layout" class="tab-content">
            <?php if (!empty($layout['thumbs'])) : ?>
                <div id="layout-search-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner" role="listbox">
                        <?php $itemI = 0; ?>
                        <?php foreach ($layout['thumbs'] as $thumb) : ?>
                            <div class="item <?=$itemI == 0 ? 'active' : '' ?>">
                                <img src="<?=$this->get('updateserver') . 'layouts/images/' . $thumb['img'] ?>" alt="<?=$this->escape($layout['name']) ?>">
                                <div class="carousel-caption">
                                    <?php if ($layoutModel->getDesc() != '') : ?>
                                        <?=$this->escape($layoutModel->getDesc()) ?>
                                    <?php else : ?>
                                        <?=$this->escape($layout['name']) ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php $itemI++; ?>
                        <?php endforeach; ?>
                    </div>

                    <?php if (count($layout->thumbs) > 1) : ?>
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
                            <?=$this->escape($layout['name']) ?>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <b><?=$this->getTrans('version') ?>:</b>
                        </div>
                        <div class="col-sm-9 col-xs-6">
                            <?=$layoutModel->getVersion() ?>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <b><?=$this->getTrans('author') ?>:</b>
                        </div>
                        <div class="col-sm-9 col-xs-6">
                            <?php if ($layoutModel->getLink() != '') : ?>
                                <a href="<?=$layoutModel->getLink() ?>" alt="<?=$this->escape($layoutModel->getAuthor()) ?>" title="<?=$this->escape($layoutModel->getAuthor()) ?>" target="_blank" rel="noopener">
                                    <i><?=$this->escape($layoutModel->getAuthor()) ?></i>
                                </a>
                            <?php else : ?>
                                <i><?=$this->escape($layoutModel->getAuthor()) ?></i>
                            <?php endif; ?>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <b><?=$this->getTrans('hits') ?>:</b>
                        </div>
                        <div class="col-sm-9 col-xs-6">
                            <?=$layout['hits'] ?>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <b><?=$this->getTrans('downloads') ?>:</b>
                        </div>
                        <div class="col-sm-9 col-xs-6">
                            <?=$layout['downs'] ?>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <label for="rating" class="control-label"><b><?=$this->getTrans('rating') ?>:</b></label>
                        </div>
                        <div class="col-sm-9 col-xs-6">
                            <span title="<?=$layout['rating'] ?>
                            <?php
                            if ($layout['rating'] == 1) {
                                echo $this->getTrans('star');
                            } else {
                                echo $this->getTrans('stars');
                            }
                            ?>">
                                <input id="rating" name="rating" type="number" class="rating" value="<?=$layout['rating'] ?>">
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
                    <?php if ($ilchCore) : ?>
                        <div class="row">
                            <div class="col-xs-12">
                                <b><?=$this->getTrans('requirements') ?>:</b>
                            </div>
                            <div class="col-sm-3 col-xs-6">
                                <b><?=$this->getTrans('ilchCoreVersion') ?>:</b>
                            </div>
                            <div class="col-sm-9 col-xs-6">
                                <?=$ilchCore ?>
                            </div>
                        </div>
                        <br />
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-xs-12">
                            <b><?=$this->getTrans('desc') ?>:</b>
                        </div>
                        <div class="col-xs-12">
                            <?=$this->escape($layoutModel->getDesc()) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="changelog">
                <div class="col-xs-12">
                    <?php
                    if (!empty($layout['changelog'])) {
                        echo $layout['changelog'];
                    } else {
                        echo $this->getTrans('noChangelog');
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="content_savebox">
            <?php if (isset($layouts[$layoutModel->getKey()])) : ?>
                <button class="btn btn-default disabled" title="<?=$this->getTrans('alreadyExists') ?>">
                    <i class="fa-solid fa-check text-success"></i> <?=$this->getTrans('alreadyExists') ?>
                </button>
            <?php else : ?>
                <form method="POST" action="<?=$this->getUrl(['module' => 'admin', 'controller' => 'layouts', 'action' => 'search', 'key' => $layout->key, 'version' => $layout->version]) ?>">
                    <?=$this->getTokenField() ?>
                    <button type="submit" class="btn btn-default">
                        <i class="fa-solid fa-download"></i> <?=$this->getTrans('download') ?>
                    </button>
                </form>
            <?php endif; ?>
        </div>
    <?php endif; ?>
<?php endforeach; ?>

<script src="<?=$this->getVendorUrl('kartik-v/bootstrap-star-rating/js/star-rating.min.js') ?>"></script>
<script>
    $('#rating').rating({
        theme: 'krajee-fa',
        filledStar: '<i class="fa-solid fa-star"></i>',
        emptyStar: '<i class="fa-solid fa-star-o"></i>',
        stars: 5,
        min: 0,
        max: 5,
        step: 0.5,
        size: 'xs'
    }).on('rating:change', function(event, value, caption) {
        window.open("<?=$this->getUrl(['action' => 'vote', 'id' => $this->getRequest()->getParam('id')], null, true) ?>/rating/" + value, "_self")
    });

    $(document).ready(function(){
        $('#layout-search-carousel').carousel();
    });
</script>
