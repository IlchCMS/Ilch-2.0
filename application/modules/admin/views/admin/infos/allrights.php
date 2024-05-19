<h1><?=$this->getTrans('allRights') ?> <a href="<?=$this->getUrl(['controller' => 'infos', 'action' => 'allrights'], null, true) ?>" title="<?=$this->getTrans('refreshAllRights') ?>"><i class="fa-solid fa-arrows-rotate"></i></a></h1>
<?php if (!empty($this->get('results'))) : ?>
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col>
            <col class="col-xl-2">
            <col class="col-xl-2">
            <col class="col-xl-2">
            <col class="col-xl-2">
            <col class="col-xl-2">
        </colgroup>
        <thead>
        <tr>
            <th><?=$this->getTrans('path') ?></th>
            <th><?=$this->getTrans('fileOwner') ?></th>
            <th><?=$this->getTrans('fileGroup') ?></th>
            <th><?=$this->getTrans('chmod') ?></th>
            <th><?=$this->getTrans('available') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->get('results') as $result) : ?>
            <tr>
                <td><?=$result['path'] ?></td>
                <td><?=$result['owner'] ?></td>
                <td><?=$result['group'] ?></td>
                <td><?=$result['chmod'] ?></td>
                <td>
                    <?php if ($result['writable']): ?>
                        <span class="text-success"><?=$this->getTrans('writable') ?></span>
                    <?php else: ?>
                        <span class="text-danger"><?=$this->getTrans('notWritable') ?></span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php else : ?>
    <?=$this->getTrans('allRightsFailure') ?>
<?php endif; ?>
