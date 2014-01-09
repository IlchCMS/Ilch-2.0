<?php 
    foreach ($this->get('impressum') as $impressum) {
        echo $this->escape($impressum->getParagraph()).'<br /><br />';  
        
        $getCompany = $this->escape($impressum->getCompany());
        if ($getCompany != '') {
        echo $this->escape($impressum->getCompany()).'<br />';            
        }
        
        echo $this->escape($impressum->getName()).'<br />';    
        echo $this->escape($impressum->getAdress()).'<br /><br />';    
        echo $this->escape($impressum->getCity()).'<br /><br />';
        echo 'Kontakt:<br />';
        
        $getPhone = $this->escape($impressum->getPhone());
        if ($getPhone != '') {
        echo $this->escape($impressum->getPhone()).'<br />';            
        }
        echo '<a href="'.$this->url(array('contact')).' title="">Formular</a><br /><br />';
        echo $impressum->getDisclaimer();    
    }
?>