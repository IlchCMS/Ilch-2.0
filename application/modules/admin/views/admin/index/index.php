<?php
if ($this->getUser()->getFirstName() != '') {
    $name = $this->getUser()->getFirstName().' '.$this->getUser()->getLastName();
} else {
    $name = $this->getUser()->getName();
}

$ilchNewsList = url_get_contents('http://ilch2.de/ilchNews.php');
$ilchNews = json_decode($ilchNewsList);
?>

<h3><?=$this->getTrans('welcomeBack', $this->escape($name)) ?> !</h3>
<?=$this->getTrans('welcomeBackDescripton') ?>
<br /><br /><br />
<div class="row">
    <?php if ($this->get('guestbookEntries') or $this->get('partnerEntries')): ?>
        <div class="col-lg-6 col-md-6">
            <legend><?=$this->getTrans('awaitingUnlocking') ?></legend>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <colgroup>
                        <col class="col-lg-2">
                        <col>
                    </colgroup>
                    <thead>
                        <th><?=$this->getTrans('modules') ?></th>
                        <th><?=$this->getTrans('number') ?></th>
                    </thead>
                    <tbody>
                        <?php if ($this->get('guestbookEntries')): ?>
                            <tr>
                                <td><a href="<?=$this->getUrl(['module' => 'guestbook', 'controller' => 'index', 'action' => 'index', 'showsetfree' => 1]) ?>"><?=$this->get('moduleLocales')['guestbook']->getName() ?></a></td>
                                <td><?=count($this->get('guestbookEntries')) ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php if ($this->get('partnerEntries')): ?>
                            <tr>
                                <td><a href="<?=$this->getUrl(['module' => 'partner', 'controller' => 'index', 'action' => 'index', 'showsetfree' => 1]) ?>"><?=$this->get('moduleLocales')['partner']->getName() ?></a></td>
                                <td><?=count($this->get('partnerEntries')) ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php if ($this->get('usersNotConfirmed')) : ?>
                            <tr>
                                <td><a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'index', 'action' => 'index', 'showsetfree' => 1)) ?>"><?=$this->get('moduleLocales')['user']->getName() ?></a></td>
                                <td><?=count($this->get('usersNotConfirmed')) ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
    <div class="col-lg-6 col-md-6">
        <legend>
            <?=$this->getTrans('system') ?>
            <?php if ($this->get('foundNewVersions')): ?>
                <span class="label label-danger"><?=$this->getTrans('notUpToDate') ?></span>
            <?php elseif ($this->get('curlErrorOccured')): ?>
                <span class="label label-warning"><?=$this->getTrans('versionQueryFailed') ?></span>
            <?php else: ?>
                <span class="label label-success"><?=$this->getTrans('upToDate') ?></span>
            <?php endif; ?>
        </legend>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="col-lg-2">
                    <col />
                </colgroup>
                <thead>
                    <th></th>
                    <th><?=$this->getTrans('version') ?></th>
                </thead>
                <tbody>
                    <tr>
                        <td><?=$this->getTrans('installedVersion') ?></td>
                        <td><?=VERSION ?></td>
                    </tr>
                    <tr>
                        <td><?=$this->getTrans('serverVersion') ?></td>
                        <td>
                            <?php if ($this->get('newVersion')): ?>
                                <?=$this->get('newVersion') ?>
                            <?php elseif ($this->get('curlErrorOccured')): ?>
                                <?=$this->getTrans('versionNA') ?>
                            <?php else: ?>
                                <?=VERSION ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php if ($this->get('foundNewVersions')): ?>
                        <tr>
                            <td></td>
                            <td>
                                <!-- TODO: Remove this message when the update function is going live. -->
                                Update function is not yet running in current stage of development.<br />
                                <a href="<?=$this->getUrl(['controller' => 'update', 'action' => 'index']) ?>"><?=$this->getTrans('updateNow') ?></a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if (!empty($ilchNews)): ?>
            <legend>ilch <?=$this->getTrans('news') ?></legend>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <colgroup>
                        <col class="col-lg-2">
                        <col>
                    </colgroup>
                    <thead>
                        <th><?=$this->getTrans('date') ?></th>
                        <th><?=$this->getTrans('title') ?></th>
                    </thead>
                    <tbody>
                        <?php foreach ($ilchNews as $news): ?>
                            <?php $date = new \Ilch\Date($news->date); ?>
                            <tr>
                                <td><?=$date->format("d.m.Y", true) ?></td>
                                <td><a href="<?=$news->link ?>" target="_blank" title="<?=$this->escape($news->title) ?>"><?=$this->escape($news->title) ?></a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
