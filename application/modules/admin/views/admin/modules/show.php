<?php
$modulesList = url_get_contents($this->get('updateserver').'modules.php');
$modules = json_decode($modulesList);
$versionsOfModules = $this->get('versionsOfModules');
$coreVersion = $this->get('coreVersion');
?>

<link href="<?=$this->getModuleUrl('static/css/extsearch.css') ?>" rel="stylesheet">
<link href="<?=$this->getVendorUrl('kartik-v/bootstrap-star-rating/css/star-rating.min.css') ?>" rel="stylesheet">

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

        if (!empty($module->depends)) {
            $dependencyCheck = [];
            foreach ($module->depends as $key => $value) {
                $parsed = explode(',', $value);
                $dependencyCheck[$key] = ['condition' => str_replace(',','', $value), 'result' => version_compare($versionsOfModules[$key]['version'], $parsed[1], $parsed[0])];
            }

            foreach ($dependencyCheck as $key => $value) {
                if ($value['result'] == true) {
                    $dependency[] = '<font color="#3c763d">'.$key.' '.$value['condition'].'</font>';
                } else {
                    $dependency[] = '<font color="#a94442">'.$key.' '.$value['condition'].'</font>';
                }
            }

            $dependency = implode(", ", $dependency);
        }

        if (version_compare(phpversion(), $module->phpVersion, '>=')) {
            $phpVersion = '<font color="#3c763d">'.$module->phpVersion.'</font>';
        } else {
            $phpVersion = '<font color="#a94442">'.$module->phpVersion.'</font>';
        }

        if (version_compare($coreVersion, $module->ilchCore, '>=')) {
            $ilchCore = '<font color="#3c763d">'.$module->ilchCore.'</font>';
        } else {
            $ilchCore = '<font color="#a94442">'.$module->ilchCore.'</font>';
        }
        ?>
        <div id="module" class="tab-content">

            <?php if (!empty($module->thumbs)): ?>
                <div id="module-search-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner" role="listbox">
                        <?php $itemI = 0; ?>
                        <?php foreach ($module->thumbs as $thumb): ?>
                            <div class="item <?=$itemI == 0 ? 'active' : '' ?>">
                                <img src="<?=$this->get('updateserver').'modules/images/'.$module->id.'/'.$thumb->img ?>" alt="<?=$this->escape($module->name) ?>">
                                <div class="carousel-caption">
                                    <?php if ($thumb->desc != ''): ?>
                                        <?=$this->escape($thumb->desc) ?>
                                    <?php else: ?>
                                        <?=$this->escape($module->name) ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php $itemI++; ?>
                        <?php endforeach; ?>
                    </div>

                    <?php if(count($module->thumbs) > 1): ?>
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
                        <?php if (!empty($module->phpExtensions)): ?>
                            <div class="col-sm-3 col-xs-6">
                                <b><?=$this->getTrans('phpExtensions') ?>:</b>
                            </div>
                            <div class="col-sm-9 col-xs-6">
                                <?=$phpExtension ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($module->depends)): ?>
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
                        <?=$this->escape($module->desc) ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="changelog">
                <div class="col-xs-12">
                    <?php if (!empty($module->changelog)) {
                        echo $module->changelog;
                    } else {
                        echo $this->getTrans('noChangelog');
                    } ?>
                </div>
            </div>
        </div>

        <div class="content_savebox">
            <?php if (!empty($module->phpextensions) AND in_array(false, $extensionCheck)): ?>
                <button class="btn btn-default disabled" title="<?=$this->getTrans('phpExtensionError') ?>">
                    <i class="fa fa-download"></i> <?=$this->getTrans('download') ?>
                </button>
            <?php elseif (!version_compare(phpversion(), $module->phpVersion, '>=')): ?>
                <button class="btn btn-default disabled" title="<?=$this->getTrans('phpVersionError') ?>">
                    <i class="fa fa-download"></i> <?=$this->getTrans('download') ?>
                </button>
            <?php elseif (!version_compare($coreVersion, $module->ilchCore, '>=')): ?>
                <button class="btn btn-default disabled" title="<?=$this->getTrans('ilchCoreError') ?>">
                    <i class="fa fa-download"></i> <?=$this->getTrans('download') ?>
                </button>
            <?php elseif (!empty($dependencyCheck)): ?>
                <button class="btn btn-default disabled" title="<?=$this->getTrans('dependencyError') ?>">
                    <i class="fa fa-download"></i> <?=$this->getTrans('download') ?>
                </button>
            <?php elseif (in_array($module->key, $this->get('modules'))): ?>
                <button class="btn btn-default disabled" title="<?=$this->getTrans('alreadyExists') ?>">
                    <i class="fa fa-check text-success"></i> <?=$this->getTrans('alreadyExists') ?>
                </button>
            <?php else: ?>
                <form method="POST" action="<?=$this->getUrl(['module' => 'admin', 'controller' => 'modules', 'action' => 'search', 'key' => $module->key]) ?>">
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
<script type="text/javascript">
$(document).ready(function(){
    $('#module-search-carousel').carousel();
});
</script>

