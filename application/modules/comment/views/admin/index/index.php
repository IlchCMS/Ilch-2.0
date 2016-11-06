<?php
$commentMapper = $this->get('commentMapper');
$modulesMapper = $this->get('modulesMapper');
$locale = $this->get('locale');
?>

<legend><?=$this->getTrans('manage') ?></legend>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col />
                <col class="col-lg-1" />
            </colgroup>
            <thead>
                <tr>
                    <th><?=$this->getTrans('commentModul') ?></th>
                    <th class="text-center"><?=$this->getTrans('comments') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($this->get('modules')): ?>
                    <?php foreach ($this->get('modules') as $module): ?>
                        <?php $modules = $modulesMapper->getModulesByKey($module, $locale); ?>
                        <?php $comments = $commentMapper->getCommentsLikeKey($module); ?>
                        <tr>
                            <td><a href="<?=$this->getUrl('admin/comment/index/show/key/'.$module) ?>"><?=$modules->getName() ?></a>
                            <td class="text-center"><?=count($comments) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2"><?=$this->getTrans('noComments') ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</form>
