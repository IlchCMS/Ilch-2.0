<?php
if ($this->get('receivers') != '') {
?>
<form class="form-horizontal" method="POST" action="">
<?php echo $this->getTokenField(); ?>
<table class="table table-hover">
    <colgroup>
        <col class="icon_width" />
        <col class="icon_width" />
        <col class="icon_width" />
        <col class="col-lg-2" />
        <col />
    </colgroup>
    <thead>
        <tr>
            <th><?=$this->getCheckAllCheckbox('check_receivers')?></th>
            <th></th>
            <th></th>
            <th><?php echo $this->getTrans('name'); ?></th>
            <th><?php echo $this->getTrans('email'); ?></th>
        </tr>
    </thead>
    <tbody>
<?php
        foreach ($this->get('receivers') as $receiver) {
?>
        <tr>
            <td><input value="<?=$receiver->getId()?>" type="checkbox" name="check_receivers[]" /></td>
            <td><?=$this->getEditIcon(array('action' => 'treat', 'id' => $receiver->getId()))?></td>
            <td><?=$this->getDeleteIcon(array('action' => 'delete', 'id' => $receiver->getId()))?></td>
<?php
            echo '<td>'.$this->escape($receiver->getName()).'</td>';
            echo '<td>'.$this->escape($receiver->getEmail()).'</td>';
            echo '</tr>';
        }
?>
    </tbody>
</table>
<?=$this->getListBar(array('delete' => 'delete'))?>
</form>
<?php
} else {
    echo $this->getTrans('noReceivers');
}
?>