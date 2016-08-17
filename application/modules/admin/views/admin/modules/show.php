<link href="<?=$this->getModuleUrl('static/css/extsearch.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('js/star-rating/css/star-rating.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('menuModules').' '.$this->getTrans('info') ?></legend>
<?php
$modulesList = url_get_contents('http://ilch2.de/downloads/modules/list.php');
$modules = json_decode($modulesList);

if (empty($modules)) {
    echo $this->getTrans('noModulesAvailable');
    return;
}

foreach ($modules as $module): ?>
    <?php if ($module->id == $this->getRequest()->getParam('id')): ?>
        <div id="module">
            <div class="col-lg-6 col-sm-12">
                <div class="row">
                    <div class="col-lg-2 col-sm-3 col-xs-6">
                        <b><?=$this->getTrans('name') ?>:</b>
                    </div>
                    <div class="col-lg-10 col-sm-9 col-xs-6">
                        <?=$this->escape($module->name) ?>
                    </div>
                    <div class="col-lg-2 col-sm-3 col-xs-6">
                        <b><?=$this->getTrans('version') ?>:</b>
                    </div>
                    <div class="col-lg-10 col-sm-9 col-xs-6">
                        <?=$module->version ?>
                    </div>
                    <div class="col-lg-2 col-sm-3 col-xs-6">
                        <b><?=$this->getTrans('author') ?>:</b>
                    </div>
                    <div class="col-lg-10 col-sm-9 col-xs-6">
                        <?php if ($module->link != ''): ?>
                            <a href="<?=$module->link ?>" alt="<?=$this->escape($module->author) ?>" title="<?=$this->escape($module->author) ?>" target="_blank">
                                <i><?=$this->escape($module->author) ?></i>
                            </a>
                        <?php else: ?>
                            <i><?=$this->escape($module->author) ?></i>
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-2 col-sm-3 col-xs-6">
                        <b><?=$this->getTrans('hits') ?>:</b>
                    </div>
                    <div class="col-lg-10 col-sm-9 col-xs-6">
                        <?=$module->hits ?>
                    </div>
                    <div class="col-lg-2 col-sm-3 col-xs-6">
                        <b><?=$this->getTrans('downloads') ?>:</b>
                    </div>
                    <div class="col-lg-10 col-sm-9 col-xs-6">
                        <?=$module->downs ?>
                    </div>
                    <div class="col-lg-2 col-sm-3 col-xs-6">
                        <b><?=$this->getTrans('rating') ?>:</b>
                    </div>
                    <div class="col-lg-10 col-sm-9 col-xs-6">
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
                <div class="col-lg-12">
                    <b><?=$this->getTrans('desc') ?>:</b>
                </div>
                <div class="col-lg-12">
                    <?=$this->escape($module->desc) ?>
                </div>
            </div>
        </div>

        <div class="content_savebox">
            <?php
            $filename = basename($module->downloadLink);
            $filename = strstr($filename,'.',true);
            if (in_array($filename, $this->get('modules'))): ?>
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
