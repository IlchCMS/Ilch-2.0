<?php
$userMapper = $this->get('userMapper');
$groupMapper = $this->get('groupMapper');
$profileFieldsContentMapper = $this->get('profileFieldsContentMapper');
$profileIconFields = $this->get('profileIconFields');
$profileFieldsTranslation = $this->get('profileFieldsTranslation');
?>

<h1><?=$this->getTrans('menuTeams') ?></h1>
<div class="teams">
    <?php if ($this->get('teams')): ?>
        <div class="row">
            <?php foreach ($this->get('teams') as $teamlist): ?>
                <?php if ($teamlist->getOptShow() == 1): ?>
                    <div class="col-lg-12 team-name">
                        <a href="<?=$this->getUrl(['action' => 'team', 'id' => $teamlist->getId()]) ?>">
                        <?php if ($teamlist->getImg() != ''): ?>
                            <img src="<?=$this->getBaseUrl().$teamlist->getImg() ?>" alt="<?=$this->escape($teamlist->getName()) ?>" title="<?=$this->escape($teamlist->getName()) ?>" />
                        <?php else: ?>
                            <h3><?=$this->escape($teamlist->getName()) ?></h3>
                        <?php endif; ?>
                        </a>
                    </div>
                    <div class="col-lg-12">
                        <?php
                        $groupList = $groupMapper->getUsersForGroup($teamlist->getGroupId());
                        $leaderIds = explode(',', $teamlist->getLeader());
                        $coLeaderIds = explode(',', $teamlist->getCoLeader());
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

                                                                echo '<a href="'.$profileIconField->getAddition().$profileFieldContent->getValue().'" target="_blank" rel="noopener" class="fa '.$profileIconField->getIcon().'" title="'.$profileFieldName.'"></a>';
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
                                <?php  if ($teamlist->getOptIn() == 1 && (!in_array($userId, $groupList) || $userId == 0)): ?>
                                    <tr>
                                        <td colspan="3"><a href="<?=$this->getUrl(['action' => 'join', 'id' => $teamlist->getId()]) ?>"><?=$this->getTrans('apply') ?></a></td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <?=$this->getTrans('noTeams') ?>
    <?php endif; ?>
</div>
