<legend><?php echo $this->trans('manage'); ?></legend>
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <tbody>
            <tr>
                <td>
                    <a href="<?php echo $this->url(array('module' => 'guestbook', 'controller' => 'index', 'action' => 'show')); ?>">
                    <span class="clickable fa fa-edit" title="Edit"></span></a></td>
                <td><?php echo $this->trans('manageentry'); ?></td>
            </tr>
        </tbody>
    </table>
</div>