<style>
.group-image{
    max-width: 100px;
    height: auto;
}
</style>

<legend><?=$this->getTrans('manageWarOverview') ?></legend>
<?php if ($this->get('war') != ''): ?>
    <div id="filter-media">
        <div id="filter-panel" class="collapse filter-panel">
            <form class="form-horizontal" method="POST" action="">
                <?=$this->getTokenField() ?>
                <div class="form-group">
                    <label class="col-lg-2 control-label" for="pref-perpage"><?=$this->getTrans('showOnly') ?></label>
                    <div class="col-lg-2">
                        <select id="pref-perpage" class="form-control" name="filterLastNext">
                            <option value="0"><?=$this->getTrans('all') ?></option>
                            <option value="1"><?=$this->getTrans('warStatusOpen') ?></option>
                            <option value="2"><?=$this->getTrans('warStatusClose') ?></option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-default filter-col" name="filter" value="filter">
                        <span class="fa fa-search"></span><?=$this->getTrans('send') ?>
                    </button>
                </div>
            </form>
        </div>
        <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#filter-panel">
            <span class="fa fa-cogs"></span> <?=$this->getTrans('filter') ?>
        </button>
    </div>
    <?=$this->get('pagination')->getHtml($this, array()) ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField()?>
        <table class="table table-striped table-hover table-responsive">
            <colgroup>
                <col class="icon_width">
                <col class="icon_width">
                <col class="icon_width">
                <col class="col-lg-2">
                <col class="col-lg-2">
                <col class="col-lg-4">
                <col />
            </colgroup>
            <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_war') ?></th>
                    <th></th>
                    <th></th>
                    <th><?=$this->getTrans('enemyName') ?></th>
                    <th><?=$this->getTrans('groupName') ?></th>
                    <th><?=$this->getTrans('nextWarTime') ?></th>
                    <th><?=$this->getTrans('warStatus') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('war') as $war): ?>
                    <tr>
                        <td><input value="<?=$war->getId() ?>" type="checkbox" name="check_war[]" /></td>
                        <td><?=$this->getEditIcon(array('action' => 'treat', 'id' => $war->getId())) ?></td>
                        <td><?=$this->getDeleteIcon(array('action' => 'del', 'id' => $war->getId())) ?></td>
                        <td><?=$war->getWarEnemy() ?></td>
                        <td><?=$war->getWarGroup() ?></td>
                        <td><?=date('d.m.Y H:i', strtotime($war->getWarTime())) ?></td>
                        <td>
                            <?php if ($war->getWarStatus() == '1'): ?>
                                <?=$this->getTrans('warStatusOpen') ?>
                            <?php elseif ($war->getWarStatus() == '2'): ?>
                                <?=$this->getTrans('warStatusClose') ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?=$this->get('pagination')->getHtml($this, array()) ?>
        <?=$this->getListBar(array('delete' => 'delete')) ?>
    </form>
<?php else: ?>
    <?=$this->getTranslator()->trans('noWars') ?>
<?php endif; ?>
