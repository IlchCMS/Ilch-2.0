<legend><?php echo $this->trans('manage'); ?></legend>
<div class="table-responsive">
    <ul class="nav nav-tabs">
        <li>
            <a href="<?php echo $this->url(array('module' => 'guestbook', 'controller' => 'index', 'action' => 'index')); ?>"><?php echo $this->trans('entrys'); ?></a>
        </li>
            <li  class="active">
                <a href="<?php echo $this->url(array('module' => 'guestbook', 'controller' => 'index', 'action' => 'shownew')); ?>">
                    <span class="badge pull-right"><?php echo $this->get('badge'); ?></span><?php echo $this->trans('setfree'); ?></a>
            </li>
    </ul>
<?php if (is_array($this->get('entries')) && count($this->get('entries')) > 0) : ?>
    <?php $entries = $this->get('entries'); ?>
                                    <?php foreach ($entries as $entry) : ?>
    
            <div class="responsive panel bordered">
                <table class="table table-hover table-striped">
                    <colgroup>
                        <col class="col-lg-3">
                        <col />
                        <col />
                        <col />
                    </colgroup>
                    <tbody>
                        <tr>
                            <td>
                                <?php echo $this->trans('from'); ?>: <?php echo $this->escape($entry->getName()); ?>
                            </td>
                            <td>
                                <?php echo $this->trans('date'); ?>: <?php echo $this->escape($entry->getDatetime()); ?>
                            </td>
                            <td>
                                <?php echo $this->trans('delete'); ?>
                                <a href="<?php echo $this->url(array('module' => 'guestbook', 'controller' => 'index', 'action' => 'delspam', 'id' => $this->escape($entry->getId()))); ?>">
                                    <span title="<?php echo $this->trans('delete'); ?>">
                                        <i class="fa fa-times-circle"></i>
                                    </span>
                                </a>
                            </td>
                            <td>
                                <?php echo $this->trans('setfree'); ?>
                                <a href="<?php echo $this->url(array('module' => 'guestbook', 'controller' => 'index', 'action' => 'setfree', 'id' => $this->escape($entry->getId()))); ?>">
                                    <span title="<?php echo $this->trans('setfree'); ?>">
                                        <i class="fa fa-check"></i></span>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="responsive panel-body">
                    <?php echo $entry->getText(); ?>
                </div>
            </div>
    <?php endforeach; ?>
<?php endif; ?>