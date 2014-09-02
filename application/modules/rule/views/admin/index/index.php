<legend><?php echo $this->getTrans('manageRule'); ?></legend>
<form class="form-horizontal" method="POST" action="">
<?=$this->getTokenField()?>
    <div class="responsive panel bordered">
        <table class="table table-striped table-responsive">
            <colgroup>
                <col class="icon_width">
                <col class="icon_width">
                <col class="icon_width">
                <col class="icon_width">
                <col class="col-lg-2">
                <col />
            </colgroup>
            <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_entries')?></th>
                    <th></th>
                    <th></th>
                    <th>§</th>
                    <th><?php echo $this->getTrans('title'); ?></th>
                    <th><?php echo $this->getTrans('text'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('entries') as $entry) : ?>
                    <tr>
                        <td><input value="<?=$entry->getId()?>" type="checkbox" name="check_entries[]" /></td>
                        <td>
                            <?php echo $this->getEditIcon(array('action' => 'treat', 'id' => $entry->getId())); ?>
                        </td>
                        <td>
                            <?php $deleteArray = array('action' => 'del', 'id' => $entry->getId()); ?>
                            <?=$this->getDeleteIcon($deleteArray)?>
                        </td>
                        <td>
                            <?php echo $this->escape($entry->getParagraph()); ?>
                        </td>
                        <td>
                            <?php echo $this->escape($entry->getTitle()); ?>
                        </td>
                        <td>
                            <?php echo nl2br($this->getHtmlFromBBCode($this->escape($entry->getText()))); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
    $actions = array('delete' => 'delete');

    echo $this->getListBar($actions);
    ?>
</form>