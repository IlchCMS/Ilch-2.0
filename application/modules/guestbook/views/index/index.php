<h1>
    <?=$this->getTrans('menuGuestbook') ?>
    <div class="pull-right">
        <a href="<?=$this->getUrl(['action' => 'newentry']) ?>"><?=$this->getTrans('entry') ?></a>
    </div>
</h1>
<?php if (!empty($this->get('welcomeMessage'))) : ?>
<div class="panel panel-default">
    <div class="panel-body welcomeMessage"><?=nl2br($this->getHtmlFromBBCode($this->escape($this->get('welcomeMessage')))) ?></div>
</div>
<?php endif; ?>
<?php foreach ($this->get('entries') as $entry): ?>
    <?php $date = new \Ilch\Date($entry->getDatetime()); ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-sm-6 col-lg-5">
                    <?=$this->getTrans('from'); ?>: <?=$this->escape($entry->getName()) ?>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <?php if ($this->getUser() && $this->getUser()->isAdmin()) : ?>
                        <a target="_blank" href="mailto:<?=$this->escape($entry->getEmail()) ?>">
                            <i class="fa fa-envelope"></i>
                        </a>
                    <?php endif; ?>
                    <?php if ($entry->getHomepage() != ''): ?>
                        <a target="_blank" href="<?=$this->escape($entry->getHomepage()) ?>">
                            <i class="fa fa-home"></i>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="col-sm-12 col-lg-4">
                    <?=$this->getTrans('date') ?>: <?=$date->format("H:i d.m.Y", true) ?>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <?=nl2br($this->getHtmlFromBBCode($this->escape($entry->getText()))) ?>
        </div>
    </div>
<?php endforeach; ?>
<?php if (empty($this->get('entries'))) : ?>
    <?=$this->getTrans('noEntries') ?>
<?php endif; ?>
<?=$this->get('pagination')->getHtml($this, ['action' => 'index']) ?>
