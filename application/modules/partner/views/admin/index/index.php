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
                <col class="col-xs-1">
                <col class="col-xs-2">
                <col />
            </colgroup>
            <thead>
                <tr>
                    <th><?php echo $this->trans('treat'); ?></th>
                    <th><?php echo $this->trans('name'); ?></th>
                    <th><?php echo $this->trans('banner'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('entries') as $entry) : ?>
                    <tr>
                        <td>
                        <?php if($this->getRequest()->getParam('showsetfree')) {
                            $freeArray = array('module' => 'partner', 'controller' => 'index', 'action' => 'setfree', 'id' => $entry->getId());

                            if($this->get('badge') > 1) {
                                $freeArray = array('action' => 'setfree', 'id' => $entry->getId(), 'showsetfree' => 1);
                            }
                        ?>
                            <a href="<?php echo $this->url($freeArray).'" title="'.$this->trans('setfree'); ?>"><i class="fa fa-check text-success"></i></a>
                        <?php }else{ ?>
                            <a href="<?php echo $this->url(array('action' => 'treat', 'id' => $entry->getId())).'" title="'.$this->trans('treat'); ?>"><i class="fa fa-edit"></i></a>
                        <?php } ?>
                            <?php
                                $deleteArray = array('action' => 'del', 'id' => $entry->getId());

                                if($this->getRequest()->getParam('showsetfree') && $this->get('badge') > 1) {
                                    $deleteArray = array('action' => 'del', 'id' => $entry->getId(), 'showsetfree' => 1);
                                }
                            ?>
                        <span class="deleteLink clickable fa fa-trash-o fa-1x text-danger"
                                      data-clickurl="<?php echo $this->url(array('action' => 'delete', 'id' => $entry->getId())); ?>"
                                      data-toggle="modal"
                                      data-target="#deleteModal"
                                      data-modaltext="<?php echo $this->escape($this->trans('askIfDeletePartner', $this->escape($entry->getName()))); ?>"
                                      title="<?php echo $this->trans('delete'); ?>"></span>
                        </td>
                        <td>
                            <?php echo $this->escape($entry->getName()); ?>
                        </td>
                        <td>
                            <a href='<?php echo $this->escape($entry->getLink()); ?>' target="_blank"><img src='<?php echo $this->escape($entry->getBanner()); ?>'></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
$('.deleteLink').on('click', function(event) {
    $('#modalButton').data('clickurl', $(this).data('clickurl'));
    $('#modalText').html($(this).data('modaltext'));
});

$('#modalButton').on('click', function(event) {
    window.location = $(this).data('clickurl');
});
</script>
<style>
    .deleteLink {
        padding-left: 10px;
    }
</style>
