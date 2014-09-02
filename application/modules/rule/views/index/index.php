<?php
    $rules = $this->get('rules');
?>

<table class="table table-striped table-responsive">
    <tbody>
        <?php if (!empty($rules)) {
            foreach ($this->get('rules') as $rule) {
                echo '<tr>';        
                echo '<th>§'.$this->escape($rule->getParagraph()).'. '.$this->escape($rule->getTitle()).'</th>';    
                echo '</tr>';
                echo '<tr>';        
                echo '<td>'.nl2br($this->getHtmlFromBBCode($this->escape($rule->getText()))).'</td>';
                echo '</tr>';               
            }
        } else {
            echo '<tr><td>'.$this->getTrans('noRules').'</td></tr>';
        } ?>
    </tbody>
</table>
