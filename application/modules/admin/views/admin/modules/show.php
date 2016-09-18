<?php
$modulesList = url_get_contents('http://ilch2.de/downloads/modules/list.php');
$modules = json_decode($modulesList);
?>

<link href="<?=$this->getModuleUrl('static/css/extsearch.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('js/star-rating/css/star-rating.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('js/jssor.slider/jssor.slider.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('menuModules').' '.$this->getTrans('info') ?></legend>
<?php
if (empty($modules)) {
    echo $this->getTrans('noModulesAvailable');
    return;
}

foreach ($modules as $module): ?>
    <?php if ($module->id == $this->getRequest()->getParam('id')): ?>
        <?php
        if (!empty($module->phpExtensions)) {
            $extensionCheck = [];
            foreach ($module->phpExtensions as $extension) {
                $extensionCheck[] = extension_loaded($extension);
            }

            $phpExtensions = array_combine($module->phpExtensions, $extensionCheck);
            foreach ($phpExtensions as $key => $value) {
                if ($value == true) {
                    $phpExtension[] = '<font color="#3c763d">'.$key.'</font>';
                } else {
                    $phpExtension[] = '<font color="#a94442">'.$key.'</font>';
                }
            }

            $phpExtension = implode(", ", $phpExtension);
        }

        if (version_compare(phpversion(), $module->phpVersion, '>=')) {
            $phpVersion = '<font color="#3c763d">'.$module->phpVersion.'</font>';
        } else {
            $phpVersion = '<font color="#a94442">'.$module->phpVersion.'</font>';
        }

        if (version_compare($this->get('coreVersion'), $module->ilchCore, '>=')) {
            $ilchCore = '<font color="#3c763d">'.$module->ilchCore.'</font>';
        } else {
            $ilchCore = '<font color="#a94442">'.$module->ilchCore.'</font>';
        }
        ?>
        <div id="module">
            <div class="col-lg-6 col-xs-12">
                <div class="row">
                    <?php if (!empty($module->thumbs)): ?>
                        <div class="col-xs-12">
                            <div id="jssor_1" class="slider">
                                <div data-u="slides" class="slides">
                                    <?php foreach ($module->thumbs as $thumb): ?>
                                        <div data-p="112.50" style="display: none;">
                                            <img data-u="image" src="<?=$thumb->img ?>" />
                                            <img data-u="thumb" src="<?=$thumb->img ?>" />
                                            <div data-u="caption" data-t="5" class="caption">
                                                <?php if ($thumb->desc != ''): ?>
                                                    <?=$this->escape($thumb->desc) ?>
                                                <?php else: ?>
                                                    <?=$this->escape($module->name) ?>
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

                    <div class="col-sm-3 col-xs-6">
                        <b><?=$this->getTrans('name') ?>:</b>
                    </div>
                    <div class="col-sm-9 col-xs-6">
                        <?=$this->escape($module->name) ?>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <b><?=$this->getTrans('version') ?>:</b>
                    </div>
                    <div class="col-sm-9 col-xs-6">
                        <?=$module->version ?>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <b><?=$this->getTrans('author') ?>:</b>
                    </div>
                    <div class="col-sm-9 col-xs-6">
                        <?php if ($module->link != ''): ?>
                            <a href="<?=$module->link ?>" alt="<?=$this->escape($module->author) ?>" title="<?=$this->escape($module->author) ?>" target="_blank">
                                <i><?=$this->escape($module->author) ?></i>
                            </a>
                        <?php else: ?>
                            <i><?=$this->escape($module->author) ?></i>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <b><?=$this->getTrans('hits') ?>:</b>
                    </div>
                    <div class="col-sm-9 col-xs-6">
                        <?=$module->hits ?>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <b><?=$this->getTrans('downloads') ?>:</b>
                    </div>
                    <div class="col-sm-9 col-xs-6">
                        <?=$module->downs ?>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <b><?=$this->getTrans('rating') ?>:</b>
                    </div>
                    <div class="col-sm-9 col-xs-6">
                        <span title="<?=$module->rating ?> <?php if ($module->rating == 1) { echo $this->getTrans('star'); } else { echo $this->getTrans('stars'); } ?>">
                            <input type="number"
                                   class="rating"
                                   value="<?=$module->rating ?>"
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
                        <b><?=$this->getTrans('requirements') ?></b>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <b><?=$this->getTrans('ilchCoreVersion') ?>:</b>
                    </div>
                    <div class="col-sm-9 col-xs-6">
                        <?=$ilchCore ?>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <b><?=$this->getTrans('phpVersion') ?>:</b>
                    </div>
                    <div class="col-sm-9 col-xs-6">
                        <?=$phpVersion ?>
                    </div>
                    <?php if (!empty($module->phpExtensions)): ?>
                        <div class="col-sm-3 col-xs-6">
                            <b><?=$this->getTrans('phpExtensions') ?>:</b>
                        </div>
                        <div class="col-sm-9 col-xs-6">
                            <?=$phpExtension ?>
                        </div>
                    <?php endif; ?>
                </div>
                <br />
                <div class="col-xs-12">
                    <b><?=$this->getTrans('desc') ?>:</b>
                </div>
                <div class="col-xs-12">
                    <?=$this->escape($module->desc) ?>
                </div>
            </div>
        </div>

        <div class="content_savebox">
            <?php
            $filename = basename($module->downloadLink);
            $filename = strstr($filename,'.',true);
            if (!empty($module->phpextensions) AND in_array(false, $extensionCheck)): ?>
                <button class="btn disabled" title="<?=$this->getTrans('phpExtensionError') ?>">
                    <i class="fa fa-download"></i> <?=$this->getTrans('download') ?>
                </button>
            <?php elseif (in_array($filename, $this->get('modules'))): ?>
                <button class="btn disabled" title="<?=$this->getTrans('alreadyExists') ?>">
                    <i class="fa fa-check text-success"></i> <?=$this->getTrans('alreadyExists') ?>
                </button>
            <?php else: ?>
                <form method="POST" action="<?=$this->getUrl(['module' => 'admin', 'controller' => 'modules', 'action' => 'search']) ?>">
                    <?=$this->getTokenField() ?>
                    <button type="submit" class="btn" name="url" value="<?=$module->downloadLink ?>">
                        <i class="fa fa-download"></i> <?=$this->getTrans('download') ?>
                    </button>
                </form>
            <?php endif; ?>
        </div>
    <?php endif; ?>
<?php endforeach; ?>

<script src="<?=$this->getStaticUrl('js/star-rating/js/star-rating.js') ?>" type="text/javascript"></script>
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
