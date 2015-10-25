<link href="<?=$this->getBaseUrl('application/modules/war/static/css/style.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('menuGroups') ?></legend>
<?php if ($this->get('groups') != ''): ?>
    <?=$this->get('pagination')->getHtml($this, array()) ?>
    <div id="war_index">
        <?php foreach ($this->get('groups') as $group): ?>
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-xs-12 col-md-3 text-center">
                        <a href="<?=$this->getUrl(array('controller' => 'group', 'action' => 'show', 'id' => $group->getId())) ?>">
                            <img src="<?=$this->getBaseUrl($group->getGroupImage()) ?>" alt="<?=$group->getGroupName() ?>" class="thumbnail img-responsive" />
                        </a>
                    </div>
                    <div class="col-xs-12 col-md-9 section-box">
                        <h4>
                            <a href="<?=$this->getUrl(array('controller' => 'group', 'action' => 'show', 'id' => $group->getId()))?>"><?=$group->getGroupName() ?></a>
                        </h4>
                        <p>...</p>
                        <hr />
                        <div class="row rating-desc">
                            <div class="col-md-12">
                                <span>WIN</span>(36)<span class="separator">|</span>
                                <span>LOOS</span>(100)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?=$this->get('pagination')->getHtml($this, array()) ?>
<?php else: ?>
    <?=$this->getTranslator()->trans('noGroup') ?>
<?php endif; ?>
