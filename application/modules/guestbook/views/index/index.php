<p class="pull-right">
    <a href="<?php echo $this->getUrl(array('action' => 'newentry')); ?>"
       class="btn btn-small btn-primary"
       type="button" >
           <?php echo $this->getTrans('entry'); ?>
    </a>
</p>
<div id="img-responsive" class="responsive">
    <?php
        foreach ($this->get('entries') as $entry) :
    ?>
            <div class="responsive panel bordered">
                <table class="table table-striped table-responsive">
                    <colgroup>
                        <col class="col-lg-3">
                        <col />
                        <col />
                    </colgroup>
                    <tbody>
                        <tr>
                            <td>
                                <?php echo $this->getTrans('from'); ?>: <?php echo $this->escape($entry->getName()); ?>
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
                                <?php echo $this->getTrans('date'); ?>: <?php echo $this->escape($entry->getDatetime()); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="responsive panel-body">
                    <?php echo nl2br($this->getHtmlFromBBCode($this->escape($entry->getText()))); ?>
                </div>
            </div>
    <?php
        endforeach;
    ?>
</div>
