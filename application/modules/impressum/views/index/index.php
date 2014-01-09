<?php 
    foreach ($this->get('impressum') as $impressum) {
        echo $this->escape($impressum->getParagraph()).'<br /><br />';  
        
        $getCompany = $this->escape($impressum->getCompany());
        if ($getCompany != '') {
        echo $this->escape($impressum->getCompany()).'<br />';            
        }
        
        echo $this->escape($impressum->getName()).'<br />';    
        echo $this->escape($impressum->getAddress()).'<br /><br />';    
        echo $this->escape($impressum->getCity()).'<br /><br />';
        echo '<b>'.$this->trans('contact').':</b><br />';
        
        $getPhone = $this->escape($impressum->getPhone());
        if ($getPhone != '') {
        echo $this->escape($impressum->getPhone()).'<br />';            
        }
        echo '<a href="'.$this->url(array('module'=> 'contact')).'" title="">'.$this->trans('form').'</a><br /><br /><br />';
        echo $impressum->getDisclaimer();    
    }
?>