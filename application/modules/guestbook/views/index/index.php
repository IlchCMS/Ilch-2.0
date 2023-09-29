<h1>
    <?=$this->getTrans('menuGuestbook') ?>
    <div class="pull-right">
        <a href="<?=$this->getUrl(['action' => 'newentry']) ?>"><?=$this->getTrans('entry') ?></a>
    </div>
</h1>
<?php if (!empty($this->get('welcomeMessage'))) : ?>
<div class="card panel-default">
    <div class="card-body welcomeMessage"><?=$this->purify($this->get('welcomeMessage')) ?></div>
</div>
<?php endif; ?>
<?php foreach ($this->get('entries') as $entry): ?>
    <?php $date = new \Ilch\Date($entry->getDatetime()); ?>
    <div class="card panel-default">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6 col-xl-5">
                    <?=$this->getTrans('from') ?>: <?=$this->escape($entry->getName()) ?>
                </div>
                <div class="col-md-6 col-xl-3">
                    <?php if ($this->getUser() && $this->getUser()->isAdmin()) : ?>
                        <a target="_blank" href="mailto:<?=$this->escape($entry->getEmail()) ?>">
                            <i class="fa-solid fa-envelope"></i>
                        </a>
                    <?php endif; ?>
                    <?php if ($entry->getHomepage() != ''): ?>
                        <a target="_blank" rel="noopener" href="<?=$this->escape($entry->getHomepage()) ?>">
                            <i class="fa-solid fa-house"></i>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="col-md-12 col-xl-4">
                    <?=$this->getTrans('date') ?>: <?=$date->format('H:i d.m.Y', true) ?>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?=$this->alwaysPurify($entry->getText()) ?>
        </div>
    </div>
<?php endforeach; ?>
<?php if (empty($this->get('entries'))) : ?>
    <?=$this->getTrans('noEntries') ?>
<?php endif; ?>
<?=$this->get('pagination')->getHtml($this, ['action' => 'index']) ?>
