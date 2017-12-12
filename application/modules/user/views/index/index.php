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
                        <col class="col-lg-3" />
                        <col class="col-lg-3" />
                        <col class="col-lg-3" />
                        <col class="col-lg-3" />
                    </colgroup>
                    <thead>
                    <tr>
                        <th><?=$this->getTrans('userlistName') ?></th>
                        <th><?=$this->getTrans('userlistRegist') ?></th>
                        <th><?=$this->getTrans('userDateLastActivity') ?></th>
                        <th><?=$this->getTrans('userlistContact') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->get('userList') as $userlist): ?>
                            <?php $ilchDate = new Ilch\Date($userlist->getDateCreated()); ?>
                            <?php $ilchLastDate = (!empty($userlist->getDateLastActivity())) ? new Ilch\Date($userlist->getDateLastActivity()) : ''; ?>
                            <tr>
                                <td>
                                    <a href="<?=$this->getUrl(['controller' => 'profil', 'action' => 'index', 'user' => $userlist->getId()]) ?>" title="<?=$this->escape($userlist->getName()) ?>s <?=$this->getTrans('profile') ?>" class="user-link"><?=$this->escape($userlist->getName()) ?></a>
                                </td>
                                <td>
                                    <?=substr($this->getTrans($ilchDate->format('l')), 0, 2).', '.$ilchDate->format('d. ').substr($this->getTrans($ilchDate->format('F')), 0, 4).$ilchDate->format(' Y') ?>
                                </td>
                                <td>
                                    <?=(!empty($ilchLastDate)) ? substr($this->getTrans($ilchLastDate->format('l')), 0, 2).', '.$ilchLastDate->format('d. ').substr($this->getTrans($ilchLastDate->format('F')), 0, 4).$ilchLastDate->format(' Y') : ''; ?>
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
                                    <?php if ($this->escape($userlist->getSteam()) != ''): ?>
                                        <a href="https://steamcommunity.com/id/<?=$this->escape($userlist->getSteam()) ?>" target="_blank" class="fa fa-steam-square fa-lg user-link" title="<?=$this->getTrans('profileSteam') ?>"></a>
                                    <?php endif; ?>
                                    <?php if ($this->escape($userlist->getTwitch()) != ''): ?>
                                        <a href="https://www.twitch.tv/<?=$this->escape($userlist->getTwitch()) ?>" target="_blank" class="fa fa-twitch fa-lg user-link" title="<?=$this->getTrans('profileTwitch') ?>"></a>
                                    <?php endif; ?>
                                    <?php if ($this->escape($userlist->getTeamspeak()) != ''): ?>
                                        <a href="ts3server://<?=$this->escape($userlist->getTeamspeak()) ?>" target="_blank" title="<?=$this->getTrans('profileTeamspeak') ?>"><img src="<?=$this->getModuleUrl('static/images/teamspeak/teamspeak.svg') ?>" style="width:24px;height:24px;"></a>
                                    <?php endif; ?>
                                    <?php if ($this->escape($userlist->getDiscord()) != ''): ?>
                                        <a href="https://discord.gg/<?=$this->escape($userlist->getDiscord()) ?>" target="_blank" title="<?=$this->getTrans('profileDiscord') ?>"><img src="<?=$this->getModuleUrl('static/images/discord/discord.svg') ?>" style="width:24px;height:24px;"></a>
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