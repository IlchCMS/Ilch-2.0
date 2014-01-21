<legend><?php echo $this->getTrans('manage'); ?></legend>
<form class="form-horizontal" method="POST" action="">
<?=$this->getTokenField()?>
<div id="img-responsive">
    <ul class="nav nav-tabs">
        <li <?php if(!$this->getRequest()->getParam('showsetfree')) { echo 'class="active"'; } ?>>
            <a href="<?php echo $this->getUrl(array('controller' => 'index', 'action' => 'index')); ?>">
                <?php echo $this->getTrans('entrys'); ?>
            </a>
        </li>
        <?php if ($this->get('badge') > 0) : ?>
            <li <?php if($this->getRequest()->getParam('showsetfree')) { echo 'class="active"'; } ?>>
                <a href="<?php echo $this->getUrl(array('controller' => 'index', 'action' => 'index', 'showsetfree' => 1)); ?>">
                    <?php echo $this->getTrans('setfree'); ?><span class="badge"><?php echo $this->get('badge'); ?></span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
    <br />
    <div class="responsive panel bordered">
        <table class="table table-striped table-responsive">
            <colgroup>
                <col class="icon_width" />
                <?php
                    if($this->getRequest()->getParam('showsetfree')) {
                        echo '<col class="icon_width" />';
                    }
                ?>
                <col class="icon_width" />
                <col class="col-lg-2" />
                <col class="col-lg-2" />
                <col />
            </colgroup>
            <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_entries')?></th>
                    <?php
                        if($this->getRequest()->getParam('showsetfree')) {
                            echo '<th></th>';
                        }
                    ?>
                    <th></th>
                    <th><?php echo $this->getTrans('from'); ?></th>
                    <th><?php echo $this->getTrans('date'); ?></th>
                    <th><?php echo $this->getTrans('message'); ?></th>
                </tr>
            </thead>
            <?php foreach ($this->get('entries') as $entry) : ?>
            <tbody>
                <tr>
                    <td><input value="<?=$entry->getId()?>" type="checkbox" name="check_entries[]" /></td>
                    <?php
                        if($this->getRequest()->getParam('showsetfree')) {
                            echo '<td>';
                            $freeArray = array('action' => 'setfree', 'id' => $entry->getId());
            
                            if($this->get('badge') > 1) {
                                $freeArray = array('action' => 'setfree', 'id' => $entry->getId(), 'showsetfree' => 1);
                            }

                           echo '<a href="'.$this->getUrl($freeArray).'"><span class="fa fa-check-square-o text-success"></span></a>';
                           echo '</td>';
                        }
                        
                        $deleteArray = array('action' => 'del', 'id' => $entry->getId());

                        if($this->getRequest()->getParam('showsetfree') && $this->get('badge') > 1) {
                            $deleteArray = array('action' => 'del', 'id' => $entry->getId(), 'showsetfree' => 1);
                        }
                    ?>
                    </td>
                    <td><?=$this->getDeleteIcon($deleteArray)?></td>
                    <td>
                        <?php echo $this->escape($entry->getName()); ?>
                    </td>
                    <td>
                        <?php echo $this->escape($entry->getDateTime()); ?>
                    </td>
                    <td>
                        <?php echo $entry->getText(); ?>
                    </td>
                </tr>
            </tbody>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<?php
$actions = array('delete' => 'delete');

if($this->getRequest()->getParam('showsetfree')) {
    $actions = array('delete' => 'delete', 'setfree' => 'setfree');
}

echo $this->getListBar($actions);
?>