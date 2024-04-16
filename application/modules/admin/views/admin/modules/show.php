<?php

/** @var \Ilch\View $this */

/** @var string $coreVersion */
$coreVersion = $this->get('coreVersion');
/** @var array $versionsOfModules */
$versionsOfModules = $this->get('versionsOfModules');

$modulesList = url_get_contents($this->get('updateserver') . 'modules.php');
$modules = json_decode($modulesList, true);
?>

<link href="<?=$this->getModuleUrl('static/css/extsearch.css') ?>" rel="stylesheet">
<link href="<?=$this->getVendorUrl('kartik-v/bootstrap-star-rating/css/star-rating.min.css') ?>" rel="stylesheet">

<?php
if (empty($modules)) {
    echo $this->getTrans('noModulesAvailable');
    return;
}

foreach ($modules as $module) : ?>
    <?php if ($module['id'] == $this->getRequest()->getParam('id')) : ?>
        <?php
        $moduleModel = new \Modules\Admin\Models\Module();
        $moduleModel->setByArray($module);

        $hasPHPExtension = $moduleModel->hasPHPExtension();
        $hasDependencies = $moduleModel->checkOwnDependencies();

        $phpExtension = [];
        if (count($moduleModel->getPHPExtension())) {
            foreach ($moduleModel->getPHPExtension() as $extension => $state) {
                if ($state) {
                    $phpExtension[] = '<font color="#3c763d">' . $extension . '</font>';
                } else {
                    $phpExtension[] = '<font color="#a94442">' . $extension . '</font>';
                }
            }
        }
        $phpExtension = implode(', ', $phpExtension);

        $dependency = [];
        if (count($moduleModel->getDepends())) {
            foreach ($moduleModel->getDepends() as $key => $value) {
                if ($moduleModel[$module->getKey()]->dependsCheck[$key]) {
                    $dependency[] = '<font color="#3c763d">' . $key . ' ' . str_replace(',', '', $value) . '</font><br />';
                } else {
                    $dependency[] = '<font color="#a94442">' . $key . ' ' . str_replace(',', '', $value) . '</font><br />';
                }
            }
        }
        $dependency = implode(', ', $dependency);

        if ($moduleModel->hasPHPVersion()) {
            $phpVersion = '<font color="#3c763d">' . $moduleModel->getPHPVersion() . '</font>';
        } else {
            $phpVersion = '<font color="#a94442">' . $moduleModel->getPHPVersion() . '</font>';
        }

        if ($moduleModel->hasCoreVersion()) {
            $ilchCore = '<font color="#3c763d">' . $moduleModel->getIlchCore() . '</font>';
        } else {
            $ilchCore = '<font color="#a94442">' . $moduleModel->getIlchCore() . '</font>';
        }
        ?>
        <div id="module" class="tab-content">

            <?php if (!empty($module['thumbs'])) : ?>
                <div id="module-search-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner" role="listbox">
                        <?php $itemI = 0; ?>
                        <?php foreach ($module['thumbs'] as $thumb) : ?>
                            <div class="item <?=$itemI == 0 ? 'active' : '' ?>">
                                <img src="<?=$this->get('updateserver') . 'modules/images/' . $module['id'] . '/' . $thumb['img'] ?>" alt="<?=$this->escape($moduleModel->getName()) ?>">
                                <div class="carousel-caption">
                                    <?php if ($thumb['desc'] != '') : ?>
                                        <?=$this->escape($thumb['desc']) ?>
                                    <?php else : ?>
                                        <?=$this->escape($moduleModel->getName()) ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php $itemI++; ?>
                        <?php endforeach; ?>
                    </div>

                    <?php if (count($module['thumbs']) > 1) : ?>
                        <a class="left carousel-control" href="#module-search-carousel" role="button" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="right carousel-control" href="#module-search-carousel" role="button" data-slide="next">
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
                            <?=$this->escape($moduleModel->getName()) ?>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <b><?=$this->getTrans('version') ?>:</b>
                        </div>
                        <div class="col-sm-9 col-xs-6">
                            <?=$moduleModel->getVersion() ?>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <b><?=$this->getTrans('author') ?>:</b>
                        </div>
                        <div class="col-sm-9 col-xs-6">
                            <?php if ($moduleModel->getLink() != '') : ?>
                                <a href="<?=$moduleModel->getLink() ?>" alt="<?=$this->escape($moduleModel->getAuthor()) ?>" title="<?=$this->escape($moduleModel->getAuthor()) ?>" target="_blank" rel="noopener">
                                    <i><?=$this->escape($moduleModel->getAuthor()) ?></i>
                                </a>
                            <?php else : ?>
                                <i><?=$this->escape($moduleModel->getAuthor()) ?></i>
                            <?php endif; ?>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <b><?=$this->getTrans('hits') ?>:</b>
                        </div>
                        <div class="col-sm-9 col-xs-6">
                            <?=$module['hits'] ?>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <b><?=$this->getTrans('downloads') ?>:</b>
                        </div>
                        <div class="col-sm-9 col-xs-6">
                            <?=$module['downs'] ?>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <label for="rating" class="control-label"><b><?=$this->getTrans('rating') ?>:</b></label>
                        </div>
                        <div class="col-sm-9 col-xs-6">
                            <span title="<?=$module['rating'] ?> <?php
                            if ($module['rating'] == 1) {
                                echo $this->getTrans('star');
                            } else {
                                echo $this->getTrans('stars');
                            }
                            ?>">
                                <input id="rating" name="rating" type="number" class="rating" value="<?=$module['rating'] ?>">
                            </span>
                        </div>
                    </div>
                    <br />
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
                        <div class="col-sm-3 col-xs-6">
                            <b><?=$this->getTrans('phpVersion') ?>:</b>
                        </div>
                        <div class="col-sm-9 col-xs-6">
                            <?=$phpVersion ?>
                        </div>
                        <?php if (!empty($phpExtension)) : ?>
                            <div class="col-sm-3 col-xs-6">
                                <b><?=$this->getTrans('phpExtensions') ?>:</b>
                            </div>
                            <div class="col-sm-9 col-xs-6">
                                <?=$phpExtension ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($dependency)) : ?>
                            <div class="col-sm-3 col-xs-6">
                                <b><?=$this->getTrans('dependencies') ?>:</b>
                            </div>
                            <div class="col-sm-9 col-xs-6">
                                <?=$dependency ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <br />
                    <div class="col-xs-12">
                        <b><?=$this->getTrans('desc') ?>:</b>
                    </div>
                    <div class="col-xs-12">
                        <?=$this->escape($module['desc']) ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="changelog">
                <div class="col-xs-12">
                    <?php
                    if (!empty($module['changelog'])) {
                        echo $module['changelog'];
                    } else {
                        echo $this->getTrans('noChangelog');
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="content_savebox">
            <?php if (!$hasPHPExtension) : ?>
                <button class="btn btn-default disabled" title="<?=$this->getTrans('phpExtensionError') ?>">
                    <i class="fa-solid fa-download"></i> <?=$this->getTrans('download') ?>
                </button>
            <?php elseif (!$moduleModel->hasPHPVersion()) : ?>
                <button class="btn btn-default disabled" title="<?=$this->getTrans('phpVersionError') ?>">
                    <i class="fa-solid fa-download"></i> <?=$this->getTrans('download') ?>
                </button>
            <?php elseif (!$moduleModel->hasCoreVersion($this->get('coreVersion'))) : ?>
                <button class="btn btn-default disabled" title="<?=$this->getTrans('ilchCoreError') ?>">
                    <i class="fa-solid fa-download"></i> <?=$this->getTrans('download') ?>
                </button>
            <?php elseif (!$hasDependencies) : ?>
                <button class="btn btn-default disabled" title="<?=$this->getTrans('dependencyError') ?>">
                    <i class="fa-solid fa-download"></i> <?=$this->getTrans('download') ?>
                </button>
            <?php elseif (in_array($moduleModel->getKey(), $this->get('modules'))) : ?>
                <button class="btn btn-default disabled" title="<?=$this->getTrans('alreadyExists') ?>">
                    <i class="fa-solid fa-check text-success"></i> <?=$this->getTrans('alreadyExists') ?>
                </button>
            <?php else : ?>
                <form method="POST" action="<?=$this->getUrl(['module' => 'admin', 'controller' => 'modules', 'action' => 'search', 'key' => $moduleModel->getKey()]) ?>">
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
</script>
