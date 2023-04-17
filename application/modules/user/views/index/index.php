<?php
$userMapper = $this->get('userMapper');
$profileFieldsContentMapper = $this->get('profileFieldsContentMapper');
$profileIconFields = $this->get('profileIconFields');
$profileFieldsTranslation = $this->get('profileFieldsTranslation');
$group = $this->get('group');
$groupText = (!empty($group)) ? ' ('.$this->getTrans('group').': '.$this->escape($group->getName()).')' : '';
$userGroupList_allowed = $this->get('userGroupList_allowed');
$userAvatarList_allowed = $this->get('userAvatarList_allowed');

?>

    <link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

    <h1><?=$this->getTrans('menuUserList').$groupText ?></h1>
<?=$this->get('pagination')->getHtml($this, ['action' => 'index']) ?>
    <div class="userlist">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <?php if ($userAvatarList_allowed == 1): ?>
                            <th><?=$this->getTrans('userAvatars') ?></th>
                            <?php endif; ?>
                            <th><?=$this->getTrans('userlistName') ?></th>
                            <th><?=$this->getTrans('userlistRegist') ?></th>
                            <th><?=$this->getTrans('userDateLastActivity') ?></th>
                            <th><?=$this->getTrans('userlistContact') ?></th>
                            <?php if ($userGroupList_allowed == 1): ?>
                            <th><?=$this->getTrans('userGroups') ?></th>
                            <?php endif; ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($this->get('userList') as $userlist):

                            // Gruppenliste laden um mit Komma trennen
                            $groups = '';

                            foreach ($userlist->getGroups() as $group) {
                                if ($groups != '') {
                                    $groups .= ', ';
                                }

                                $groups .= $group->getName();
                            }?>
                            <!-- Laden Ende -->


                            <?php $ilchDate = new Ilch\Date($userlist->getDateCreated()); ?>
                            <?php $ilchLastDate = (!empty($userlist->getDateLastActivity())) ? new Ilch\Date($userlist->getDateLastActivity()) : ''; ?>
                            <?php $profileFieldsContent = $profileFieldsContentMapper->getProfileFieldContentByUserId($userlist->getId()); ?>
                            <tr>
                                <?php
                                if ($userAvatarList_allowed == true):
                                ?>
                                <td>
                                    <img class="profile-image" src="<?=$this->getBaseUrl().$this->escape($userlist->getAvatar()) ?>" title="<?=$this->escape($userlist->getName()) ?>">
                                </td>
                                <?php endif; ?>
                                <td>
                                    <a href="<?=$this->getUrl(['controller' => 'profil', 'action' => 'index', 'user' => $userlist->getId()]) ?>" title="<?=$this->escape($userlist->getName()) ?>s <?=$this->getTrans('profile') ?>" class="user-link"><?=$this->escape($userlist->getName()) ?></a>
                                </td>
                                <td>
                                    <?=substr($this->getTrans($ilchDate->format('l')), 0, 2).', '.$ilchDate->format('d. ').$this->getTrans($ilchDate->format('M')).$ilchDate->format(' Y') ?>
                                </td>
                                <td>
                                    <?=(!empty($ilchLastDate)) ? substr($this->getTrans($ilchLastDate->format('l')), 0, 2).', '.$ilchLastDate->format('d. ').$this->getTrans($ilchLastDate->format('M')).$ilchLastDate->format(' Y') : '' ?>
                                </td>
                                <td>
                                    <?php if ($this->getUser() && $this->getUser()->getId() != $this->escape($userlist->getID())): ?>
                                        <a href="<?=$this->getUrl(['controller' => 'panel', 'action' => 'dialognew', 'id' => $userlist->getId()]) ?>" class="fa-solid fa-comment fa-lg user-link" title="<?=$this->getTrans('privateMessage') ?>"></a>
                                    <?php endif; ?>
                                    <?php if ($userlist->getOptMail() == 1 && $this->getUser() && $this->getUser()->getId() != $userlist->getID()): ?>
                                        <a href="<?=$this->getUrl(['controller' => 'mail', 'action' => 'index', 'user' => $userlist->getId()]) ?>" class="fa-solid fa-envelope fa-lg user-link" title="<?=$this->getTrans('email') ?>"></a>
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

                                            echo '<a href="'.$profileIconField->getAddition().$profileFieldContent->getValue().'" target="_blank" rel="noopener" class="'.$profileIconField->getIcon().' fa-lg user-link" title="'.$profileFieldName.'"></a>';
                                            break;
                                        }
                                    }
                                }
                            }
                                    ?>
                                </td>
                                <?php
                                if ($userGroupList_allowed == true):
                                ?>
                                <td>
                                    <?=$this->escape($groups) ?>
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?=$this->get('pagination')->getHtml($this, ['action' => 'index']) ?>