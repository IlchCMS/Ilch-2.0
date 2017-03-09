<?php
$layoutsList = url_get_contents($this->get('updateserver').'downloads/layouts/list.php');
$layouts = json_decode($layoutsList);
?>

<link href="<?=$this->getModuleUrl('static/css/extsearch.css') ?>" rel="stylesheet">
<link href="<?=$this->getVendorUrl('kartik-v/bootstrap-star-rating/css/star-rating.min.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('js/jssor.slider/jssor.slider.css') ?>" rel="stylesheet">

<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
    <li class="active"><a href="#info" data-toggle="tab"><?=$this->getTrans('info') ?></a></li>
    <li><a href="#changelog" data-toggle="tab"><?=$this->getTrans('changelog') ?></a></li>
</ul>
<br />
<?php

if (empty($layouts)) {
    echo $this->getTrans('noLayoutsAvailable');
    return;
}

foreach ($layouts as $layout): ?>
    <?php if ($layout->id == $this->getRequest()->getParam('id')): ?>
        <div id="layout" class="tab-content">
            <div class="tab-pane active" id="info">
                <div class="col-xs-12 col-lg-6">
                    <?php if (!empty($layout->thumbs)): ?>
                        <div class="col-xs-12">
                            <div id="jssor_1" class="slider">
                                <div data-u="slides" class="slides">
                                    <?php foreach ($layout->thumbs as $thumb): ?>
                                        <div data-p="112.50" style="display: none;">
                                            <img data-u="image" src="<?=$this->get('updateserver').'downloads/layouts/img/'.$thumb->img ?>" />
                                            <img data-u="thumb" src="<?=$this->get('updateserver').'downloads/layouts/img/'.$thumb->img ?>" />
                                            <div data-u="caption" data-t="5" class="caption">
                                                <?php if ($thumb->desc != ''): ?>
                                                    <?=$this->escape($thumb->desc) ?>
                                                <?php else: ?>
                                                    <?=$this->escape($layout->name) ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <!-- Bullet Navigator -->
                                <div data-u="navigator" class="jssorb01">
                                    <div data-u="prototype" style="width:12px;height:12px;"></div>
                                </div>
                                <!-- Thumbnail Navigator -->
                                <div data-u="thumbnavigator" class="jssort03" data-autocenter="1">
                                    <div class="thumbslider"></div>
                                    <!-- Thumbnail Item Skin Begin -->
                                    <div data-u="slides" style="cursor: pointer;">
                                        <div data-u="prototype" class="p">
                                            <div class="w">
                                                <div data-u="thumbnailtemplate" class="t"></div>
                                            </div>
                                            <div class="c"></div>
                                        </div>
                                    </div>
                                    <!-- Thumbnail Item Skin End -->
                                </div>
                                <!-- Arrow Navigator -->
                                <span data-u="arrowleft" class="jssora02l" data-autocenter="2"></span>
                                <span data-u="arrowright" class="jssora02r" data-autocenter="2"></span>
                            </div>
                        </div>
                    <?php endif; ?>
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
                                <a href="<?=$layout->link ?>" alt="<?=$this->escape($layout->author) ?>" title="<?=$this->escape($layout->author) ?>" target="_blank">
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
            <?php
            if (in_array($layout->key, $this->get('layouts'))): ?>
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

<script src="<?=$this->getVendorUrl('kartik-v/bootstrap-star-rating/js/star-rating.min.js') ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?=$this->getStaticUrl('js/jssor.slider/jssor.slider-21.1.5.min.js') ?>"></script>
<script>
jQuery(document).ready(function ($) {
    var jssor_1_options = {
        $AutoPlay: true,
        $ArrowNavigatorOptions: {
          $Class: $JssorArrowNavigator$
        },
        $BulletNavigatorOptions: {
          $Class: $JssorBulletNavigator$
        },
        $ThumbnailNavigatorOptions: {
          $Class: $JssorThumbnailNavigator$,
          $Cols: 9,
          $SpacingX: 3,
          $SpacingY: 3,
          $Align: 260
        }
    };

    var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);

    //responsive code begin
    //you can remove responsive code if you don't want the slider scales while window resizing
    function ScaleSlider() {
        var refSize = jssor_1_slider.$Elmt.parentNode.clientWidth;
        if (refSize) {
            refSize = Math.min(refSize, 600);
            jssor_1_slider.$ScaleWidth(refSize);
        }
        else {
            window.setTimeout(ScaleSlider, 90);
        }
    }
    ScaleSlider();
    $(window).bind("load", ScaleSlider);
    $(window).bind("resize", ScaleSlider);
    $(window).bind("orientationchange", ScaleSlider);
    //responsive code end
});
</script>
