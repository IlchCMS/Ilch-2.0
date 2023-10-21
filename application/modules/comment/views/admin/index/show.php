<?php
$userMapper = $this->get('userMapper');
$modulesMapper = $this->get('modulesMapper');
$locale = $this->get('locale');
$modules = $modulesMapper->getModulesByKey($this->getRequest()->getParam('key'), $locale);
?>

<h1><?=$modules->getName() ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="icon_width" />
                <col class="icon_width" />
                <col class="col-xl-1" />
                <col class="col-xl-1" />
                <col class="col-xl-1" />
                <col />
            </colgroup>
            <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_comments') ?></th>
                    <th></th>
                    <th><?=$this->getTrans('commentDateTime') ?></th>
                    <th><?=$this->getTrans('commentFrom') ?></th>
                    <th><?=$this->getTrans('commentLink') ?></th>
                    <th><?=$this->getTrans('commentText') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($this->get('comments')): ?>
                    <?php foreach ($this->get('comments') as $comment): ?>
                        <?php
                        $user = $userMapper->getUserById($comment->getUserId());
                        if (!$user) {
                            $user = $userMapper->getDummyUser();
                        }
                        ?>
                        <?php $date = new \Ilch\Date($comment->getDateCreated()) ?>
                        <?php $commentKey = preg_replace("#[/].*#", "", $comment->getKey()); ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_comments', $comment->getId()) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'delete', 'key' => $commentKey, 'id' => $comment->getId()]) ?></td>
                            <td><?=$date->format("d.m.Y H:i", true) ?></td>
                            <td><a href="<?=$this->getUrl('user/profil/index/user/'.$user->getId()) ?>" target="_blank"><?=$this->escape($user->getName()) ?></a></td>
                            <td><a href="<?=$this->getUrl($comment->getKey()) ?>#comment_<?=$comment->getId() ?>" target="_blank"><?=$modules->getName() ?></a></td>
                            <td><?=nl2br($this->escape($comment->getText())) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6"><?=$this->getTrans('noComments') ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?=$this->getListBar(['delete' => 'delete']) ?>
</form>
