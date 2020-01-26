<?php
$userMapper = $this->get('userMapper');
$groupMapper = $this->get('groupMapper');
$profileFieldsContentMapper = $this->get('profileFieldsContentMapper');
$profileIconFields = $this->get('profileIconFields');
$profileFieldsTranslation = $this->get('profileFieldsTranslation');
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
                                <?php if ($user && $user->getConfirmed() == 1): ?>
                                    <?php $profileFieldsContent = $profileFieldsContentMapper->getProfileFieldContentByUserId($user->getId()); ?>
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
                                            <?php if ($this->getUser() && $this->getUser()->getId() != $this->escape($user->getId())): ?>
                                                <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'panel', 'action' => 'dialognew', 'id' => $user->getId()]) ?>" class="fa fa-comment" title="<?=$this->getTrans('privateMessage') ?>"></a>
                                            <?php endif; ?>
                                            <?php if ($user->getOptMail() == 1 && $this->getUser() && $this->getUser()->getId() != $user->getID()): ?>
                                                <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'mail', 'action' => 'index', 'user' => $user->getId()]) ?>" class="fa fa-envelope" title="<?=$this->getTrans('email') ?>"></a>
                                            <?php endif; ?>

                                            <?php foreach ($profileIconFields as $profileIconField) {
                                                if ($profileIconField->getShow()) {
                                                    foreach ($profileFieldsContent as $profileFieldContent) {
                                                        if ($profileFieldContent->getValue() && $profileIconField->getId() == $profileFieldContent->getFieldId()) {
                                                            $profileFieldName = $profileIconField->getKey();
                                                            foreach ($profileFieldsTranslation as $profileFieldTrans) {
                                                                if ($profileIconField->getId() == $profileFieldTrans->getFieldId()) {
                                                                    $profileFieldName = $profileFieldTrans->getName();
                                                                    break;
                                                                }
                                                            }

                                                            echo '<a href="'.$profileIconField->getAddition().$profileFieldContent->getValue().'" target="_blank" class="fa '.$profileIconField->getIcon().'" title="'.$profileFieldName.'"></a>';
                                                            break;
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>

                            <?php ($this->getUser()) ? $userId = $this->getUser()->getId() : $userId = 0 ?>
                            <?php  if ($team->getOptIn() == 1 && (!in_array($userId, $groupList) || $userId == 0)): ?>
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
