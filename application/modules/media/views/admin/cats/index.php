<legend><?php echo $this->getTrans('cats'); ?></legend>
<?php
if ($this->get('cats') != '') {
?>
<form class="form-horizontal" method="POST" action="">
<?=$this->getTokenField()?>
    <table class="table table-hover">
        <colgroup>
            <col class="icon_width" />
            <col class="icon_width" />
            <col class="icon_width" />
            <col class="col" />
        </colgroup>
        <thead>
            <tr>
                <th><?=$this->getCheckAllCheckbox('check_cats')?></th>
                <th></th>
                <th></th>
                <th><?php echo $this->getTrans('catTitle'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($this->get('cats') as $cat) {
            ?>
                <tr>
                    <td><input value="<?=$cat->getId()?>" type="checkbox" name="check_cats[]" /></td>
                    <td><?=$this->getEditIcon(array('action' => 'treat', 'id' => $cat->getId()))?></td>
                    <td><?=$this->getDeleteIcon(array('action' => 'delcat', 'id' => $cat->getId()))?></td>
                    <td><?=$cat->getCatName()?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?=$this->getListBar(array('delete' => 'delete'))?>
</form>
<?php
} else {
    echo $this->getTranslator()->trans('noCats');
}
?>
