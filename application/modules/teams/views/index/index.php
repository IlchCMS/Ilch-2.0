<?php

/** @var \Ilch\View $this */

/** @var \Modules\User\Mappers\ProfileFieldsContent $profileFieldsContentMapper */
$profileFieldsContentMapper = $this->get('profileFieldsContentMapper');
/** @var \Modules\User\Mappers\ProfileFields[]|null $profileIconFields */
$profileIconFields = $this->get('profileIconFields');
/** @var \Modules\User\Mappers\ProfileFieldsTranslation[]|null $profileFieldsTranslation */
$profileFieldsTranslation = $this->get('profileFieldsTranslation');

/** @var \Modules\Teams\Models\Teams[] $teams */
$teams = $this->get('teams');
/** @var array $teamData */
$teamData = $this->get('teamData');
?>
<h1><?=$this->getTrans('menuTeams') ?></h1>
<div class="teams">
    <?php foreach ($teams ?? [] as $team) : ?>
        <?php if ($team->getOptShow() == 1) : ?>
            <?php $data = $teamData[$team->getId()]; ?>
            <div class="row">
                <div class="col-xl-12 team-name">
                    <?php if ($team->getImg() != '') : ?>
                        <img src="<?=$this->getBaseUrl() . $team->getImg() ?>" alt="<?=$this->escape($team->getName()) ?>" title="<?=$this->escape($team->getName()) ?>" />
                    <?php else : ?>
                        <h3><?=$this->escape($team->getName()) ?></h3>
                    <?php endif; ?>
                </div>
                <div class="col-xl-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <colgroup>
                                <col class="col-xl-5" />
                                <col class="col-xl-3" />
                                <col />
                            </colgroup>
                            <tbody>
                            <?php foreach ($data['users'] as $user) : ?>
                                <?php $profileFieldsContent = $profileFieldsContentMapper->getProfileFieldContentByUserId($user->getId()); ?>
                                <tr>
                                    <td>
                                        <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]) ?>" title="<?=$this->escape($user->getName()) ?> <?=$this->getTrans('profile') ?>">
                                            <?=$this->escape($user->getName()) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php if (in_array($user->getId(), $data['leaderIds'])) : ?>
                                            <?=$this->getTrans('leader') ?>
                                        <?php elseif (in_array($user->getId(), $data['coLeaderIds'])) : ?>
                                            <?=$this->getTrans('coLeader') ?>
                                        <?php else : ?>
                                            <?=$this->getTrans('member') ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="contact-links">
                                        <?php if ($this->getUser() && $this->getUser()->getId() != $user->getId()) : ?>
                                            <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'panel', 'action' => 'dialognew', 'id' => $user->getId()]) ?>" class="fa-solid fa-comment" title="<?=$this->getTrans('privateMessage') ?>"></a>
                                        <?php endif; ?>
                                        <?php if ($user->getOptMail() == 1 && $this->getUser() && $this->getUser()->getId() != $user->getId()) : ?>
                                            <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'mail', 'action' => 'index', 'user' => $user->getId()]) ?>" class="fa-solid fa-envelope" title="<?=$this->getTrans('email') ?>"></a>
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

                                                        echo '<a href="' . $profileIconField->getAddition() . $profileFieldContent->getValue() . '" target="_blank" rel="noopener" class="' . $profileIconField->getIcon() . '" title="' . $profileFieldName . '"></a>';
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            <?php $userId = $this->getUser() ? $this->getUser()->getId() : 0; ?>
                            <?php if ($team->getOptIn() == 1 && (!in_array($userId, $data['allIds']) || $userId == 0)) : ?>
                                <tr>
                                    <td colspan="3"><a href="<?=$this->getUrl(['action' => 'join', 'id' => $team->getId()]) ?>"><?=$this->getTrans('apply') ?></a></td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php $pagination = $data['pagination']; ?>
                    <?php if ($pagination->getRows() > $pagination->getRowsPerPage()) : ?>
                        <div class="text-center mt-2">
                            <?php echo $pagination->getHtml($this, ['action' => 'index'], 'page_' . $team->getId()); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
