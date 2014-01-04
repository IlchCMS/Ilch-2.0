<legend><?php echo $this->trans('managePartner'); ?></legend>
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
                    <?php echo $this->trans('setfree'); ?> <span class="badge"><?php echo $this->get('badge'); ?></span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
    <br />
    <div class="responsive panel bordered">
        <table class="table table-bordered table-striped table-responsive">
            <colgroup>
                <col class="col-lg-1">
                <col class="col-lg-2">
                <col />
            </colgroup>
            <thead>
                <tr>
                    <th><?php echo $this->trans('treat'); ?></th>
                    <th><?php echo $this->trans('name'); ?></th>
                    <th><?php echo $this->trans('banner'); ?></th>
                </tr>
            </thead>
            <?php foreach ($this->get('entries') as $entry) : ?>
            <tbody>
                <tr>
                    <td>
                    <?php if($this->getRequest()->getParam('showsetfree')) {
                        $freeArray = array('module' => 'partner', 'controller' => 'index', 'action' => 'setfree', 'id' => $entry->getId());

                        if($this->get('badge') > 1) {
                            $freeArray = array('action' => 'setfree', 'id' => $entry->getId(), 'showsetfree' => 1);
                        }
                    ?>
                        <a href="<?php echo $this->url($freeArray).'" title="'.$this->trans('setfree'); ?>"><i class="fa fa-check"></i></a>
                    <?php }else{ ?>
                        <a href="<?php echo $this->url(array('action' => 'treat', 'id' => $entry->getId())).'" title="'.$this->trans('treat'); ?>"><i class="fa fa-edit"></i></a>
                    <?php } ?>
                        <?php
                            $deleteArray = array('action' => 'del', 'id' => $entry->getId());

                            if($this->getRequest()->getParam('showsetfree') && $this->get('badge') > 1) {
                                $deleteArray = array('action' => 'del', 'id' => $entry->getId(), 'showsetfree' => 1);
                            }
                        ?>
                        <a href="<?php echo $this->url($deleteArray).'" title="'.$this->trans('delete'); ?>"><i class="fa fa-times-circle"></i></a>
                    </td>
                    <td>
                        <?php echo $this->escape($entry->getName()); ?>
                    </td>
                    <td>
                        <a href='<?php echo $this->escape($entry->getLink()); ?>' target="_blank"><img src='<?php echo $this->escape($entry->getBanner()); ?>'></a>
                    </td>
                </tr>
            </tbody>
            <?php endforeach; ?>
        </table>
    </div>
</div>
