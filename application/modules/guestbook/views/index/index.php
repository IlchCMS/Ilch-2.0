<?php $entries = $this->get('entries'); ?>
<legend><?php echo $this->trans('guestbook'); ?></legend>
<p class="pull-right">
    <a href="<?php echo $this->url(array('module' => 'guestbook', 'controller' => 'index', 'action' => 'newentry')); ?>"
       class="btn btn-small btn-primary"
       type="button" >
           <?php echo $this->trans('entry'); ?>
    </a>
</p>
<div id="img-responsive" class="responsive">
    <?php
        if (!empty($entries)) :
            foreach ($this->get('entries') as $entry) :
    ?>
            <div class="responsive panel bordered">
                <table class="table table-bordered table-striped table-responsive">
                    <colgroup>
                        <col class="col-lg-3">
                        <col />
                        <col />
                    </colgroup>
                    <tbody>
                        <tr>
                            <td>
                                <?php echo $this->trans('from'); ?>: <?php echo $this->escape($entry->getName()); ?>
                            </td>
                            <td>
                                <a target="_blank" href="//<?php echo $this->escape($entry->getHomepage()); ?>">
                                    <span class="glyphicon glyphicon-home"></span>
                                </a>
                                <a target="_blank" href="mailto:<?php echo $this->escape($entry->getEmail()); ?>">
                                    <span class="glyphicon glyphicon-envelope"></span>
                                </a>
                            </td>
                            <td>
                                <?php echo $this->trans('date'); ?>: <?php echo $this->escape($entry->getDatetime()); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="responsive panel-body">
                    <?php echo $entry->getText(); ?>
                </div>
            </div>
    <?php
            endforeach;
        endif;
    ?>
</div>
