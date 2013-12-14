<legend><?php echo $this->trans('manage'); ?></legend>
<?php if (is_array($this->get('entries')) && count($this->get('entries')) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th><?php echo $this->trans('from'); ?></th>
                    <th><?php echo $this->trans('text'); ?></th>
                    <th><?php echo $this->trans('delete'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('entries') as $entry) : ?>
                    <tr>
                        <td><?php echo $this->escape($entry->getName()); ?></td>
                        <td><?php echo $entry->getText(); ?></td>
                        <td><a href="<?php echo $this->url(array('module' => 'guestbook', 'controller' => 'index', 'action' => 'del', 'id' => $this->escape($entry->getId()))); ?>">
                            <span class="item_delete" title="<?php echo $this->trans('delete'); ?>"><i class="fa fa-times-circle"></i></span></a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>






