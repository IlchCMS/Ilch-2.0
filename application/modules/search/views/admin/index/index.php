<?php

/** @var \Ilch\View $this */

/** @var array $modules */
$modules = $this->get('modules');
/** @var int $totalDocs */
$totalDocs = $this->get('totalDocs');
/** @var int $queueCount */
$queueCount = $this->get('queueCount');
/** @var string $serverVersion */
$serverVersion = $this->get('serverVersion');
/** @var bool $fulltextAvailable */
$fulltextAvailable = $this->get('fulltextAvailable');
?>
<h1><?=$this->getTrans('overview') ?></h1>
<div class="table-responsive">
    <table class="table table-hover">
        <tbody>
            <tr>
                <th><?=$this->getTrans('engine') ?></th>
                <td><?=$this->escape($serverVersion) ?>, <?=$this->getTrans($fulltextAvailable ? 'engineFulltext' : 'engineLike') ?></td>
            </tr>
            <tr>
                <th><?=$this->getTrans('totalDocuments') ?></th>
                <td><?=$totalDocs ?></td>
            </tr>
            <tr>
                <th><?=$this->getTrans('queuePending') ?></th>
                <td><?=$queueCount ?></td>
            </tr>
        </tbody>
    </table>
</div>
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col />
            <col class="col-xl-2" />
            <col class="col-xl-2" />
            <col class="col-xl-2" />
        </colgroup>
        <thead>
            <tr>
                <th><?=$this->getTrans('module') ?></th>
                <th class="text-center"><?=$this->getTrans('documents') ?></th>
                <th class="text-center"><?=$this->getTrans('frontier') ?></th>
                <th class="text-center"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($modules as $module): ?>
                <tr>
                    <td><?=$this->escape($module['name']) ?></td>
                    <td class="text-center"><?=$module['docs'] ?></td>
                    <td class="text-center"><?=$module['frontier'] ?></td>
                    <td class="text-center">
                        <form method="POST" action="<?=$this->getUrl(['action' => 'reindex']) ?>" class="d-inline"
                              onsubmit="return confirm('<?=$this->getTrans('reindexConfirm') ?>');">
                            <?=$this->getTokenField() ?>
                            <input type="hidden" name="module" value="<?=$this->escape($module['key']) ?>">
                            <button type="submit" class="btn btn-danger" title="<?=$this->getTrans('reindex') ?>">
                                <i class="fa-solid fa-rotate-right"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
