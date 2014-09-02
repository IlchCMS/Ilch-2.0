<?php
    $shoutbox = $this->get('shoutbox');
?>

<table class="table table-striped table-responsive">
    <tbody>
        <?php if (!empty($shoutbox)) {
            foreach ($this->get('shoutbox') as $shoutbox) {
                echo '<tr>';         
                echo '<td><b>'.$this->escape($shoutbox->getName()).':</b> <span style="font-size:12px">'.$shoutbox->getTime().'</span></td>';  
                echo '</tr>';
                echo '<tr>';                
                echo '<td>'.$this->escape($shoutbox->getTextarea()).'</td>';  
                echo '</tr>';
            }
        } else {
            echo '<tr><td>'.$this->getTrans('noEntrys').'</td></tr>';
        } ?>
    </tbody>
</table>