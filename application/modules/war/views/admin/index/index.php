<legend><?php echo $this->getTrans('manageWarOverview'); ?></legend>
<?php
if ($this->get('war') != '') {
?>
<div id="filter-media" >
    <div id="filter-panel" class="collapse filter-panel">
        <form class="form-horizontal" method="POST" action="">
            <?=$this->getTokenField()?>
            <div class="form-group">
                <label class="col-lg-2 control-label" for="pref-perpage">Show only:</label>
                <div class="col-lg-2">
                    <select id="pref-perpage" class="form-control" name="filterLastNext">
                        <option value="0">Alle</option>
                        <option value="1"><?=$this->getTrans('warStatusOpen')?></option>
                        <option value="2"><?=$this->getTrans('warStatusClose')?></option>
                    </select>     
                </div>
            </div>
            <div class="form-group">    
                <button type="submit" class="btn btn-default filter-col" name="filter" value="filter">
                    <span class="fa fa-search"></span><?=$this->getTrans('send')?>
                </button>  
            </div>
        </form>
    </div>    
    <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#filter-panel">
        <span class="fa fa-cogs"></span> Filter
    </button>
</div>
<form class="form-horizontal" method="POST" action="">
<?=$this->getTokenField()?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
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
                    <th><?=$this->getCheckAllCheckbox('check_war')?></th>
                    <th></th>
                    <th></th>
                    <th><?php echo $this->getTrans('enemyName'); ?></th>
                    <th><?php echo $this->getTrans('groupName'); ?></th>
                    <th><?php echo $this->getTrans('nextWarTime'); ?></th>
                    <th><?php echo $this->getTrans('warStatus'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('war') as $war) : ?>
                    <tr>
                        <td><input value="<?=$war->getId()?>" type="checkbox" name="check_war[]" /></td>
                        <td>
                            <?php echo $this->getEditIcon(array('action' => 'treat', 'id' => $war->getId())); ?>
                        </td>
                        <td>
                            <?php $deleteArray = array('action' => 'del', 'id' => $war->getId()); ?>
                            <?=$this->getDeleteIcon($deleteArray)?>
                        </td>
                        <td>
                            <?php echo $this->escape($war->getWarEnemy()); ?>
                        </td>
                        <td>
                            <?php echo $this->escape($war->getWarGroup()); ?>
                        </td>
                        <td>
                            <?php echo $this->escape($war->getWarTime()); ?>
                        </td>
                        <td>
                            <?php if ($this->escape($war->getWarStatus() == '1')){
                                echo $this->getTrans('warStatusOpen');
                            } elseif ($this->escape($war->getWarStatus() == '2')) {
                                echo $this->getTrans('warStatusClose');
                            } ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
    $actions = array('delete' => 'delete', 'warClose' => $this->getTrans('warClose'), 'warOpen' => $this->getTrans('warOpen'));

    echo $this->getListBar($actions);
    ?>
</form>
<?php
} else {
    echo $this->getTranslator()->trans('noWars');
}
?>
<style>
    .group-image{
        max-width: 100px;
        height: auto;
    }
</style>
