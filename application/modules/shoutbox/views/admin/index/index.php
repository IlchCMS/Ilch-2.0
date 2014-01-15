<legend><?php echo $this->trans('manageShoutbox'); ?></legend>
<?php
$shoutboxs = $this->get('shoutbox');

if (!empty($shoutboxs)) {
?>
<form class="form-horizontal" method="POST" action="">
<?=$this->getTokenField()?>
<table class="table table-striped table-responsive">
        <colgroup>
            <col class="icon_width" />
            <col class="icon_width" />
            <col class="col-lg-2" />
            <col class="col-lg-2" />
            <col />
        </colgroup>
        <tr>
            <th><?=$this->getCheckAllCheckbox('check_entries')?></th>
            <th></th>
            <th><?php echo $this->trans('from'); ?></th>
            <th><?php echo $this->trans('date'); ?></th>
            <th><?php echo $this->trans('message'); ?></th>
        </tr>
    <?php foreach ($this->get('shoutbox') as $shoutbox) : ?>
    <tr>
        <td>
            <input value="<?=$shoutbox->getId()?>" type="checkbox" name="check_entries[]" />
        </td>
        <td>
            <?=$this->getDeleteIcon(array('action' => 'delete', 'id' => $shoutbox->getId()))?>
        </td>
        <td>
            <?php echo $this->escape($shoutbox->getName()); ?>
        </td>
        <td>
            <?php echo $shoutbox->getTime(); ?>
        </td>
        <td>
            <?php echo $this->escape($shoutbox->getTextarea()); ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?=$this->getListBar(array('delete' => 'delete'))?>
</form>
<?php
}  else {
    echo $this->trans('noEntrys');
} ?>