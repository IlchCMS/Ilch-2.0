<legend><?=$this->getTrans('menuImprint') ?></legend>
<?php if ($this->get('imprintStyle') == 0): ?>
    <?php 
        foreach ($this->get('imprint') as $imprint) {
            echo '<b>'.$this->escape($imprint->getParagraph()).'</b><br /><br />';
            echo $this->escape($imprint->getName()).'<br />';
            echo $this->escape($imprint->getAddress()).'<br />';
            if ($this->escape($imprint->getAddressAdd()) != '') {
                echo $this->escape($imprint->getAddressAdd()).'<br />';
            }
            echo '<br />'.$this->escape($imprint->getCity()).'<br /><br />';
            echo '<b>'.$this->getTrans('contact').':</b><br />';

            if ($this->escape($imprint->getPhone()) != '') {
                echo $this->getTrans('phone').': '.$this->escape($imprint->getPhone()).'<br />';
            }
            if ($this->escape($imprint->getFax()) != '') {
                echo $this->getTrans('fax').': '.$this->escape($imprint->getFax()).'<br />';
            }
            if ($this->escape($imprint->getEmail()) != '') {
                $email = str_replace("@", "<span class=\"at-ilch\"></span>", $this->escape($imprint->getEmail()));
                $email = str_replace(".", "<span class=\"dot-ilch\"></span>", $email);
                echo $this->getTrans('email').': '.$email.'<br /><br />';
            }
            echo '<a href="'.$this->getUrl(['module'=> 'contact']).'" title="">'.$this->getTrans('form').'</a><br /><br />';
            echo $imprint->getDisclaimer();
        }
    ?>
<?php else: ?>
    <?php 
        foreach ($this->get('imprint') as $imprint) {
            echo '<b>'.$this->escape($imprint->getParagraph()).'</b><br /><br />';

            $getCompany = $this->escape($imprint->getCompany());
            if ($getCompany != '') {
                echo $this->escape($imprint->getCompany()).'<br />';
            }

            echo $this->escape($imprint->getName()).'<br />';
            echo $this->escape($imprint->getAddress()).'<br />';
            if ($this->escape($imprint->getAddressAdd()) != '') {
                echo $this->escape($imprint->getAddressAdd()).'<br />';
            }
            echo '<br />'.$this->escape($imprint->getCity()).'<br /><br />';
            echo '<b>'.$this->getTrans('contact').':</b><br />';

            if ($this->escape($imprint->getPhone()) != '') {
                echo $this->getTrans('phone').': '.$this->escape($imprint->getPhone()).'<br />';
            }
            if ($this->escape($imprint->getFax()) != '') {
                echo $this->getTrans('fax').': '.$this->escape($imprint->getFax()).'<br />';
            }
            if ($this->escape($imprint->getEmail()) != '') {
                $email = str_replace("@", "<span class=\"at-ilch\"></span>", $this->escape($imprint->getEmail()));
                $email = str_replace(".", "<span class=\"dot-ilch\"></span>", $email);
                echo $this->getTrans('email').': '.$email.'<br />';
            }
            echo '<br /><a href="'.$this->getUrl(['module'=> 'contact']).'" title="">'.$this->getTrans('form').'</a><br />';

            if ($this->escape($imprint->getRegistration()) OR $this->escape($imprint->getCommercialRegister()) OR $this->escape($imprint->getVatId()) OR $this->escape($imprint->getOther()) != '') {
                if ($this->escape($imprint->getRegistration()) != '') {
                    echo '<br />'.$this->getTrans('registration').': '.$this->escape($imprint->getRegistration());
                }
                if ($this->escape($imprint->getCommercialRegister()) != '') {
                    echo '<br />'.$this->getTrans('commercialRegister').': '.$this->escape($imprint->getCommercialRegister());
                }
                if ($this->escape($imprint->getVatId()) != '') {
                    echo '<br />'.$this->getTrans('vatId').': '.$this->escape($imprint->getVatId());
                }
                if ($this->escape($imprint->getOther()) != '') {
                    echo '<br /><br />'.$imprint->getOther();
                }
            }
            echo '<br />'.$imprint->getDisclaimer();
        }
    ?>
<?php endif; ?>

<style type="text/css">
.at-ilch:after {
    content:"\0040";
} 
.dot-ilch:after {
    content:"\002E";
} 
</style>
