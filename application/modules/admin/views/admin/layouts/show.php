<link href="<?=$this->getModuleUrl('static/css/extsearch.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('js/star-rating/css/star-rating.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('menuLayout').' '.$this->getTrans('info') ?></legend>
<?php
$layoutsList = url_get_contents('http://ilch2.de/downloads/layouts/list.php');
$layouts = json_decode($layoutsList);

if (empty($layouts)) {
    echo $this->getTrans('noLayoutsAvailable');
    return;
}

foreach ($layouts as $layout): ?>
    <?php if ($layout->id == $this->getRequest()->getParam('id')): ?>
        <div id="layout">
            <div class="col-lg-2">
                <div class="col-lg-12">
                    <div class="thumbnail">
                        <span data-toggle="modal" data-target="#infoModal">
                            <img src="<?=$layout->thumb ?>" class="img-thumbnail" alt="<?=$this->escape($layout->name) ?>" title="<?=$this->escape($layout->name) ?>" />
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12">
                <div class="row">
                    <div class="col-lg-2 col-sm-3 col-xs-6">
                        <b><?=$this->getTrans('name') ?>:</b>
                    </div>
                    <div class="col-lg-10 col-sm-9 col-xs-6">
                        <?=$this->escape($layout->name) ?>
                    </div>
                    <div class="col-lg-2 col-sm-3 col-xs-6">
                        <b><?=$this->getTrans('version') ?>:</b>
                    </div>
                    <div class="col-lg-10 col-sm-9 col-xs-6">
                        <?=$layout->version ?>
                    </div>
                    <div class="col-lg-2 col-sm-3 col-xs-6">
                        <b><?=$this->getTrans('author') ?>:</b>
                    </div>
                    <div class="col-lg-10 col-sm-9 col-xs-6">
                        <?php if ($layout->link != ''): ?>
                            <a href="<?=$layout->link ?>" alt="<?=$this->escape($layout->author) ?>" title="<?=$this->escape($layout->author) ?>" target="_blank">
                                <i><?=$this->escape($layout->author) ?></i>
                            </a>
                        <?php else: ?>
                            <i><?=$this->escape($layout->author) ?></i>
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-2 col-sm-3 col-xs-6">
                        <b><?=$this->getTrans('hits') ?>:</b>
                    </div>
                    <div class="col-lg-10 col-sm-9 col-xs-6">
                        <?=$layout->hits ?>
                    </div>
                    <div class="col-lg-2 col-sm-3 col-xs-6">
                        <b><?=$this->getTrans('downloads') ?>:</b>
                    </div>
                    <div class="col-lg-10 col-sm-9 col-xs-6">
                        <?=$layout->downs ?>
                    </div>
                    <div class="col-lg-2 col-sm-3 col-xs-6">
                        <b><?=$this->getTrans('rating') ?>:</b>
                    </div>
                    <div class="col-lg-10 col-sm-9 col-xs-6">
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
                <div class="col-lg-12">
                    <b><?=$this->getTrans('desc') ?>:</b>
                </div>
                <div class="col-lg-12">
                    <?=$this->escape($layout->desc) ?>
                </div>
            </div>
        </div>

        <div class="content_savebox">
            <?php
            $filename = basename($layout->downloadLink);
            $filename = strstr($filename,'.',true);
            if (in_array($filename, $this->get('layouts'))): ?>
                <button class="btn disabled" title="<?=$this->getTrans('alreadyExists') ?>">
                    <i class="fa fa-check text-success"></i> <?=$this->getTrans('alreadyExists') ?>
                </button>
            <?php else: ?>
                <form method="POST" action="<?=$this->getUrl(['module' => 'admin', 'controller' => 'layouts', 'action' => 'search']) ?>">
                    <?=$this->getTokenField() ?>
                    <button type="submit" class="btn" name="url" value="<?=$layout->downloadLink ?>">
                        <i class="fa fa-download"></i> <?=$this->getTrans('download') ?>
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <?=$this->getDialog('infoModal', $this->escape($layout->name), '<center><img src="'.$layout->thumb.'" class="img-thumbnail" alt="'.$this->escape($layout->name).'" /></center>'); ?>
    <?php endif; ?>
<?php endforeach; ?>

<script src="<?=$this->getStaticUrl('js/star-rating/js/star-rating.js') ?>" type="text/javascript"></script>
