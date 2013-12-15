<legend><?php echo $this->trans('guestbook'); ?></legend>
<p class="pull-right">
    <a href="<?php echo $this->url(array('module' => 'guestbook', 'controller' => 'index', 'action' => 'newentry')); ?>" class="btn btn-small btn-primary" type="button" ><?php echo $this->trans('entry'); ?></a>
</p>
<div class="responsive">
    <?php if (is_array($this->get('entries')) && count($this->get('entries')) > 0) : ?>
        <?php foreach ($this->get('entries') as $entry) : ?>
            <div class="responsive panel bordered ">
                <table class="table table-bordered table-striped table-responsive">
                    <tbody>
                        <tr>
                            <td><?php echo $this->trans('from'); ?>: <?php echo $this->escape($entry->getName()); ?></td>
                            <td><a target="_blank" href="//<?php echo $this->escape($entry->getHomepage()); ?>"><span class="glyphicon glyphicon-home"></span></a>
                                <a target="_blank" href="mailto:<?php echo $this->escape($entry->getEmail()); ?>"><span class="glyphicon glyphicon-envelope"></span></a></td>
                            <td><?php echo $this->trans('date'); ?>: <?php echo $this->escape($entry->getDatetime()); ?></td>
                        </tr>
                    </tbody>
                </table>
                <div class="responsive panel-body"><?php echo $entry->getText(); ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
