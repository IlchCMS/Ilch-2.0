<?php $userMapper = $this->get('userMapper'); ?>

<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('menuUserList') ?></h1>
<?=$this->get('pagination')->getHtml($this, ['action' => 'index']) ?>
<div class="userlist">
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <colgroup>
                        <col class="col-lg-6" />
                        <col class="col-lg-3" />
                        <col class="col-lg-3" />
                    </colgroup>
                    <thead>
                    <tr>
                        <th><?=$this->getTrans('userlistName') ?></th>
                        <th><?=$this->getTrans('userlistRegist') ?></th>
                        <th><?=$this->getTrans('userlistContact') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->get('userList') as $userlist): ?>
                            <?php $ilchDate =  new Ilch\Date($userlist->getDateCreated()); ?>
                            <tr>
                                <td>
                                    <a href="<?=$this->getUrl(['controller' => 'profil', 'action' => 'index', 'user' => $userlist->getId()]) ?>" title="<?=$this->escape($userlist->getName()) ?>s <?=$this->getTrans('profile') ?>" class="user-link"><?=$this->escape($userlist->getName()) ?></a>
                                </td>
                                <td>
                                    <?=substr($this->getTrans($ilchDate->format('l')), 0, 2).', '.$ilchDate->format('d. ').substr($this->getTrans($ilchDate->format('F')), 0, 4).$ilchDate->format(' Y') ?>
                                </td>
                                <td>
                                    <?php if ($this->getUser() AND $this->getUser()->getId() != $this->escape($userlist->getID())): ?>
                                        <a href="<?=$this->getUrl(['controller' => 'panel', 'action' => 'dialognew', 'id' => $userlist->getId()]) ?>" class="fa fa-comment fa-lg user-link" title="<?=$this->getTrans('privateMessage') ?>"></a>
                                    <?php endif; ?>
                                    <?php if ($userlist->getOptMail() == 1 AND $this->getUser() AND $this->getUser()->getId() != $userlist->getID()): ?>
                                        <a href="<?=$this->getUrl(['controller' => 'mail', 'action' => 'index', 'user' => $userlist->getId()]) ?>" class="fa fa-envelope fa-lg user-link" title="<?=$this->getTrans('email') ?>"></a>
                                    <?php endif; ?>
                                    <?php if ($this->escape($userlist->getHomepage()) != ''): ?>
                                        <a href="<?=$userMapper->getHomepage($this->escape($userlist->getHomepage())) ?>" target="_blank" class="fa fa-globe fa-lg user-link" title="<?=$this->getTrans('website') ?>"></a>
                                    <?php endif; ?>
                                    <?php if ($this->escape($userlist->getFacebook()) != ''): ?>
                                        <a href="https://www.facebook.com/<?=$this->escape($userlist->getFacebook()) ?>" target="_blank" class="fa fa-facebook fa-lg user-link" title="<?=$this->getTrans('profileFacebook') ?>"></a>
                                    <?php endif; ?>
                                    <?php if ($this->escape($userlist->getTwitter()) != ''): ?>
                                        <a href="https://twitter.com/<?=$this->escape($userlist->getTwitter()) ?>" target="_blank" class="fa fa-twitter fa-lg user-link" title="<?=$this->getTrans('profileTwitter') ?>"></a>
                                    <?php endif; ?>
                                    <?php if ($this->escape($userlist->getGoogle()) != ''): ?>
                                        <a href="https://plus.google.com/<?=$this->escape($userlist->getGoogle()) ?>" target="_blank" class="fa fa-google-plus fa-lg user-link" title="<?=$this->getTrans('profileGoogle') ?>"></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?=$this->get('pagination')->getHtml($this, ['action' => 'index']) ?>