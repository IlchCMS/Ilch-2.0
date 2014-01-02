<legend><?php echo $this->trans('managenewentry'); ?></legend>
<?php if (is_array($this->get('entries')) && count($this->get('entries')) > 0) : ?>
    <div id="img-responsive" class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th><?php echo $this->trans('name'); ?></th>
                    <th><?php echo $this->trans('link'); ?></th>
                    <th><?php echo $this->trans('banner'); ?></th>
                    <th><?php echo $this->trans('setfree'); ?></th>
                    <th><?php echo $this->trans('delete'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('entries') as $entry) : ?>
                    <tr>
                        <td><?php echo $this->escape($entry->getName()); ?></td>
                        <td><a href="<?php echo $entry->getLink(); ?>" target="_blank"><?php echo $entry->getLink(); ?></a></td>
                        <td><img src="<?php echo $entry->getBanner(); ?>"></td>
                        <td><a href="<?php echo $this->url(array('module' => 'partner', 'controller' => 'index', 'action' => 'setfree', 'id' => $this->escape($entry->getId()))); ?>">
                            <span class="item_delete" title="<?php echo $this->trans('setfree'); ?>"><i class="fa fa-check"></i></span></a>
                        </td>
                        <td>
                            <a href="<?php echo $this->url(array('module' => 'partner', 'controller' => 'index', 'action' => 'delete', 'id' => $this->escape($entry->getId()))); ?>">
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