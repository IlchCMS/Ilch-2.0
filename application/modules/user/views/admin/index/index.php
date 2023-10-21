<h1><?=$this->getTrans('manage') ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'index']) ?>" <?php if (!$this->getRequest()->getParam('showsetfree') && !$this->getRequest()->getParam('showlocked') && !$this->getRequest()->getParam('showselectsdelete')) {
        echo 'class="nav-link active"';
    } ?>>
                <?=$this->getTrans('users') ?>
            </a>
        </li>
        <?php if ($this->get('badge') > 0): ?>
            <li class="nav-item">
                <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'index', 'showsetfree' => 1]) ?>" <?php if ($this->getRequest()->getParam('showsetfree')) {
        echo 'class="nav-link active"';
    } ?>>
                    <?=$this->getTrans('setfree') ?> <span class="badge"><?=$this->get('badge') ?></span>
                </a>
            </li>
        <?php endif; ?>
        <?php if ($this->get('badgeLocked') > 0): ?>
            <li class="nav-item">
                <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'index', 'showlocked' => 1]) ?>" <?php if ($this->getRequest()->getParam('showlocked')) {
        echo 'class="nav-link active"';
    } ?>>
                    <?=$this->getTrans('unlock') ?> <span class="badge"><?=$this->get('badgeLocked') ?></span>
                </a>
            </li>
        <?php endif; ?>
        <?php if ($this->get('badgeSelectsDelete') > 0): ?>
            <li class="nav-item">
                <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'index', 'showselectsdelete' => 1]) ?>" <?php if ($this->getRequest()->getParam('showselectsdelete')) {
        echo 'class="nav-link active"';
    } ?>>
                    <?=$this->getTrans('selectsdelete') ?> <span class="badge"><?=$this->get('badgeSelectsDelete') ?></span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
    <br />
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="icon_width">
                <col class="icon_width">
                <col class="icon_width">
                <col class="col-xl-2">
                <col class="col-xl-2">
                <col class="col-xl-2">
                <?php if ($this->getRequest()->getParam('showselectsdelete')): ?><col class="col-xl-2"><?php endif; ?>
                <col class="col-xl-2">
                <col>
            </colgroup>
            <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_users') ?></th>
                    <th></th>
                    <th></th>
                    <th><?=$this->getTrans('userName') ?></th>
                    <th><?=$this->getTrans('userEmail') ?></th>
                    <th><?=$this->getTrans('userDateCreated') ?></th>
                    <th><?=$this->getTrans('userDateLastActivity') ?></th>
                    <?php if ($this->getRequest()->getParam('showselectsdelete')): ?><th><?=$this->getTrans('selectsdeletetime') ?> <a class="badge" data-bs-toggle="modal" data-bs-target="#infoModal"><i class="fa-solid fa-info"></i></a></th><?php endif; ?>
                    <th><?=$this->getTrans('userGroups') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($this->get('userList') != ''):
                    foreach ($this->get('userList') as $user):
                        $groups = '';

                        foreach ($user->getGroups() as $group) {
                            if ($groups != '') {
                                $groups .= ', ';
                            }

                            $groups .= $group->getName();
                        }

                        if ($groups === '') {
                            $groups = $this->getTrans('noGroupsAssigned');
                        }

                        $dateConfirmed = $user->getDateConfirmed();

                        if ($dateConfirmed == '') {
                            $dateConfirmed = $this->getTrans('notConfirmedYet');
                        }

                        $dateLastActivity = $user->getDateLastActivity();

                        if ($dateLastActivity !== null && $dateLastActivity->getTimestamp() == 0) {
                            $dateLastActivity = $this->getTrans('neverLoggedIn');
                        }
                        ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_users', $user->getId()) ?></td>
                            <td>
                            <?php if ($this->getRequest()->getParam('showselectsdelete')): ?>
                                <a href="<?=$this->getUrl(['action' => 'selectsdelete', 'id' => $user->getId()], null, true) ?>" title="<?=$this->getTrans('deleteaccountreset') ?>"><i class="fa-solid fa-check text-success"></i></a>
                            <?php elseif ($this->getRequest()->getParam('showsetfree')): ?>
                                <a href="<?=$this->getUrl(['action' => 'setfree', 'id' => $user->getId()], null, true) ?>" title="<?=$this->getTrans('setfree') ?>"><i class="fa-solid fa-check text-success"></i></a>
                            <?php elseif ($this->getRequest()->getParam('showlocked')): ?>
                                <a href="<?=$this->getUrl(['action' => 'unlock', 'id' => $user->getId()], null, true) ?>" title="<?=$this->getTrans('unlock') ?>"><i class="fa-solid fa-check text-success"></i></a>
                            <?php else: ?>
                                <?=((($user->isAdmin() and $this->getUser()->isAdmin()) or !$user->isAdmin())?$this->getEditIcon(['action' => 'treat', 'id' => $user->getId()]):'') ?>
                            <?php endif; ?>
                            </td>
                            <td><?=((($user->isAdmin() and $this->getUser()->isAdmin()) or !$user->isAdmin())?$this->getDeleteIcon(['action' => 'delete', 'id' => $user->getId()]):'') ?></td>
                            <td><?=$this->escape($user->getName()) ?></td>
                            <td><?=$this->escape($user->getEmail()) ?></td>
                            <td><?=$this->escape($user->getDateCreated()) ?></td>
                            <td><?=$this->escape($user->getDateLastActivity()) ?></td>
                            <?php if ($this->getRequest()->getParam('showselectsdelete')): ?>
                            <?php
                            if ($this->get('timetodelete') > 0) {
                                $date = new \Ilch\Date();
                                $date->modify('-'.$this->get('timetodelete').' days');
                                $dateuser = new \Ilch\Date($user->getSelectsDelete());
                                $classadd = ($dateuser->getTimestamp() <= $date->getTimestamp() ? 'danger' : 'success');
                            } else {
                                $classadd = 'danger';
                            }
                            ?>
                            <td><p class="text-<?=$classadd ?>"><?=$this->escape($user->getSelectsDelete()) ?></p></td>
                            <?php endif; ?>
                            <td><?=$this->escape($groups) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7"><?=$this->getTrans('noUsersExist') ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?=$this->getListBar(['delete' => 'delete']) ?>
</form>
<?=$this->getDialog('infoModal', $this->getTrans('info'), $this->getTrans('selectsdeletetimeInfoText')) ?>
<?php if ($this->getRequest()->getParam('showselectsdelete')): ?>
<?=$this->get('pagination')->getHtml($this, ['action' => 'index', 'showselectsdelete' => 1]) ?>
<?php elseif ($this->getRequest()->getParam('showsetfree')): ?>
<?=$this->get('pagination')->getHtml($this, ['action' => 'index', 'showsetfree' => 1]) ?>
<?php elseif ($this->getRequest()->getParam('showlocked')): ?>
<?=$this->get('pagination')->getHtml($this, ['action' => 'index', 'showlocked' => 1]) ?>
<?php else: ?>
<?=$this->get('pagination')->getHtml($this, ['action' => 'index']) ?>
<?php endif; ?>
