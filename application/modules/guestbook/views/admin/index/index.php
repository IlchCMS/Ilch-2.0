<legend><?php echo $this->trans('manage'); ?></legend>
<div id="img-responsive">
    <ul class="nav nav-tabs">
        <li <?php if(!$this->getRequest()->getParam('showsetfree')) { echo 'class="active"'; } ?>>
            <a href="<?php echo $this->url(array('controller' => 'index', 'action' => 'index')); ?>">
                <?php echo $this->trans('entrys'); ?>
            </a>
        </li>
        <?php if ($this->get('badge') > 0) : ?>
            <li <?php if($this->getRequest()->getParam('showsetfree')) { echo 'class="active"'; } ?>>
                <a href="<?php echo $this->url(array('controller' => 'index', 'action' => 'index', 'showsetfree' => 1)); ?>">
                    <?php echo $this->trans('setfree'); ?><span class="badge"><?php echo $this->get('badge'); ?></span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
    <br />
    <?php foreach ($this->get('entries') as $entry) : ?>
        <div class="responsive panel bordered">
            <table class="table table-bordered table-striped table-responsive">
                <colgroup>
                    <col class="col-lg-3">
                    <col />
                    <col />
                    <?php
                        if($this->getRequest()->getParam('showsetfree')) {
                            echo '<col />';
                        }
                    ?>
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
                            <?php
                                echo $this->trans('delete');
                                $deleteArray = array('action' => 'del', 'id' => $entry->getId());

                                if($this->getRequest()->getParam('showsetfree') && $this->get('badge') > 1) {
                                    $deleteArray = array('action' => 'del', 'id' => $entry->getId(), 'showsetfree' => 1);
                                }
                            ?>
                            <a href="<?php echo $this->url($deleteArray); ?>">
                                <span title="<?php echo $this->trans('delete'); ?>">
                                    <i class="fa fa-times-circle"></i>
                                </span>
                            </a>
                        </td>
                        <?php if($this->getRequest()->getParam('showsetfree')) { ?>
                        <td>
                            <?php echo $this->trans('setfree'); ?>
                            <a href="<?php echo $this->url(array('module' => 'guestbook', 'controller' => 'index', 'action' => 'setfree', 'id' => $this->escape($entry->getId()))); ?>">
                                <span title="<?php echo $this->trans('setfree'); ?>">
                                    <i class="fa fa-check"></i></span>
                            </a>
                        </td>
                        <?php } ?>
                    </tr>
                </tbody>
            </table>
            <div class="responsive panel-body">
                <?php echo $entry->getText(); ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
