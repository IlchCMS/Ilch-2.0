<?php
$userMapper = $this->get('userMapper');
$groupMapper = $this->get('groupMapper');
?>

<h1><?=$this->getTrans('menuTeam') ?></h1>
<div class="teams">
    <?php if ($this->get('team')): ?>
    <?php $team = $this->get('team'); ?>
        <div class="row">
            <?php if ($team->getOptShow() == 1): ?>
                <div class="col-lg-12 team-name">
                    <?php if ($team->getImg() != ''): ?>
                        <img src="<?=$this->getBaseUrl().$team->getImg() ?>" alt="<?=$this->escape($team->getName()) ?>" title="<?=$this->escape($team->getName()) ?>" />
                    <?php else: ?>
                        <h3><?=$this->escape($team->getName()) ?></h3>
                    <?php endif; ?>
                </div>
                <div class="col-lg-12">
                    <?php
                    $groupList = $groupMapper->getUsersForGroup($team->getGroupId());
                    $leaderIds = explode(',', $team->getLeader());
                    $coLeaderIds = explode(',', $team->getCoLeader());
                    $groupList = array_unique(array_merge($leaderIds, $coLeaderIds, $groupList));
                    ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <colgroup>
                                <col class="col-lg-5" />
                                <col class="col-lg-3" />
                                <col />
                            </colgroup>
                            <tbody>
                            <?php foreach ($groupList as $userId): ?>
                                <?php $user = $userMapper->getUserById($userId); ?>
                                <?php if ($user AND $user->getConfirmed() == 1): ?>
                                    <tr>
                                        <td>
                                            <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]) ?>" title="<?=$this->escape($user->getName()) ?>s <?=$this->getTrans('profile') ?>">
                                                <?=$this->escape($user->getName()) ?>
                                            </a>
                                        </td>
                                        <td>
                                            <?php
                                            if (in_array($user->getId(), $leaderIds)) {
                                                echo $this->getTrans('leader');
                                            } elseif (in_array($user->getId(), $coLeaderIds)) {
                                                echo $this->getTrans('coLeader');
                                            } else {
                                                echo $this->getTrans('member');
                                            }
                                            ?>
                                        </td>
                                        <td class="contact-links">
                                            <?php if ($this->getUser() AND $this->getUser()->getId() != $this->escape($user->getId())): ?>
                                                <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'panel', 'action' => 'dialognew', 'id' => $user->getId()]) ?>" class="fa fa-comment" title="<?=$this->getTrans('privateMessage') ?>"></a>
                                            <?php endif; ?>
                                            <?php if ($user->getOptMail() == 1 AND $this->getUser() AND $this->getUser()->getId() != $user->getID()): ?>
                                                <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'mail', 'action' => 'index', 'user' => $user->getId()]) ?>" class="fa fa-envelope" title="<?=$this->getTrans('email') ?>"></a>
                                            <?php endif; ?>
                                            <?php if ($this->escape($user->getHomepage()) != ''): ?>
                                                <a href="<?=$this->escape($user->getHomepage()); ?>" class="fa fa-globe" title="<?=$this->getTrans('website') ?>"></a>
                                            <?php endif; ?>
                                            <?php if ($this->escape($user->getFacebook()) != ''): ?>
                                                <a href="https://www.facebook.com/<?=$this->escape($user->getFacebook()) ?>" target="_blank" class="fa fa-facebook" title="<?=$this->getTrans('profileFacebook') ?>"></a>
                                            <?php endif; ?>
                                            <?php if ($this->escape($user->getTwitter()) != ''): ?>
                                                <a href="https://twitter.com/<?=$this->escape($user->getTwitter()) ?>" target="_blank" class="fa fa-twitter" title="<?=$this->getTrans('profileTwitter') ?>"></a>
                                            <?php endif; ?>
                                            <?php if ($this->escape($user->getGoogle()) != ''): ?>
                                                <a href="https://plus.google.com/<?=$this->escape($user->getGoogle()) ?>" target="_blank" class="fa fa-google-plus" title="<?=$this->getTrans('profileGoogle') ?>"></a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>

                            <?php ($this->getUser()) ? $userId = $this->getUser()->getId() : $userId = 0 ?>
                            <?php  if ($team->getOptIn() == 1 AND (!in_array($userId, $groupList) OR $userId == 0)): ?>
                                <tr>
                                    <td colspan="3"><a href="<?=$this->getUrl(['action' => 'join', 'id' => $team->getId()]) ?>"><?=$this->getTrans('apply') ?></a></td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <?=$this->getTrans('noTeam') ?>
    <?php endif; ?>
</div>
