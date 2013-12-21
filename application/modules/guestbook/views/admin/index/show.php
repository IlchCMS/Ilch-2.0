<?php $entries = $this->get('entries'); ?>
<legend><?php echo $this->trans('manage'); ?></legend>
<?php if (!empty($entries)) : ?>
    <div id="img-responsive" class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th><?php echo $this->trans('from'); ?></th>
                    <th><?php echo $this->trans('text'); ?></th>
                    <th><?php echo $this->trans('delete'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($entries as $entry) : ?>
                    <tr>
                        <td><?php echo $this->escape($entry->getName()); ?></td>
                        <td><?php echo $entry->getText(); ?></td>
                        <td>
                            <a href="<?php echo $this->url(array('action' => 'del', 'id' => $this->escape($entry->getId()))); ?>">
                                <span class="item_delete" title="<?php echo $this->trans('delete'); ?>">
                                    <i class="fa fa-times-circle"></i>
                                </span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>






