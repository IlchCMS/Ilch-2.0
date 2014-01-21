<?php
$shoutboxs = $this->get('shoutbox');

if (!empty($shoutboxs)) {
?>
<table class="table table-striped table-responsive">
        <?php foreach ($this->get('shoutbox') as $shoutbox): {
                echo '<tr>';         
                echo '<td><b>'.$this->escape($shoutbox->getName()).':</b> <span style="font-size:12px">'.$shoutbox->getTime().'</span></td>';  
                echo '</tr>';
                echo '<tr>';                
                echo '<td>'.$this->escape($shoutbox->getTextarea()).'</td>';  
                echo '</tr>';
            }
        endforeach; ?>
</table>
<?php
}  else {
    echo $this->getTrans('noEntrys');
} ?>