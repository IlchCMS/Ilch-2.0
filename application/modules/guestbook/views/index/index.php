<a href="<?php echo $this->getUrl(array('action' => 'newentry')); ?>">
       <?php echo $this->getTrans('entry'); ?>
</a>
<br /><br />
<?php
    foreach ($this->get('entries') as $entry) :
?>
    <table>
        <colgroup>
            <col style="width: 200px;" />
            <col style="width: 100px;" />
            <col />
        </colgroup>
        <tbody>
            <tr>
                <td>
                    <?php echo $this->getTrans('from'); ?>: <?php echo $this->escape($entry->getName()); ?>
                </td>
                <td>
                    <a target="_blank" href="<?php echo $this->escape($entry->getHomepage()); ?>">
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
            <tr>
                <td colspan="2"><?php echo nl2br($this->getHtmlFromBBCode($this->escape($entry->getText()))); ?></td>
            </tr>
        </tbody>
    </table>
    <br /><br />
<?php
    endforeach;

    echo $this->get('pagination')->getHtml($this, array('action' => 'index'));
?>
