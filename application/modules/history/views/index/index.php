<?php
    $historys = $this->get('historys');
?>

<table class="table table-striped table-responsive">
    <tbody>
        <?php if (!empty($historys)) {
            foreach ($this->get('historys') as $history) {
                echo '<tr>';        
                echo '<th>'.$this->escape($history->getTitle()).' am '.$this->escape($history->getDate()).'</th>';    
                echo '</tr>';
                echo '<tr>';        
                echo '<td>'.nl2br($this->getHtmlFromBBCode($this->escape($history->getText()))).'</td>';
                echo '</tr>';               
            }
        }  else {
            echo '<tr><td>'.$this->getTrans('noHistorys').'</td></tr>';
        } ?>
    </tbody>
</table>
