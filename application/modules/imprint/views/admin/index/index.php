<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName(), 'id' => 1)) ?>">
 <?=$this->getTokenField() ?>
    <legend><?=$this->getTrans('manageImprint') ?></legend>
    <?php if($this->get('imprintStyle') == '0'): ?>
        <div class="form-group">
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="paragraph"
                       id="paragraph"
                       placeholder="<?php echo $this->getTrans('paragraph'); ?>"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getParagraph()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="name"
                       id="name"
                       placeholder="<?php echo $this->getTrans('name'); ?>"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getName()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="address"
                       id="address"
                       placeholder="<?php echo $this->getTrans('address'); ?>"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getAddress()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="addressadd"
                       id="addressadd"
                       placeholder="<?php echo $this->getTrans('addressadd'); ?>"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getAddressAdd()); } ?>" />
            </div>
        </div>
        <br />
        <div class="form-group">
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="city"
                       id="city"
                       placeholder="<?php echo $this->getTrans('city'); ?>"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getCity()); } ?>" />
            </div>
        </div>
        <br />
        <div class="form-group">
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="phone"
                       id="phone"
                       placeholder="<?php echo $this->getTrans('phone'); ?>"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getPhone()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="fax"
                       id="fax"
                       placeholder="<?php echo $this->getTrans('fax'); ?>"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getFax()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="email"
                       id="email"
                       placeholder="<?php echo $this->getTrans('email'); ?>"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getEmail()); } ?>" />
            </div>
        </div>
        <br />
        <div class="form-group">
            <div class="col-lg-12">
                <textarea class="form-control"
                          id="ilch_html"
                          name="disclaimer" 
                          cols="60"
                          rows="5"><?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getDisclaimer()); } ?></textarea>
            </div>
        </div>
    <?php else: ?>
        <div class="form-group">
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="paragraph"
                       id="paragraph"
                       placeholder="<?php echo $this->getTrans('paragraph'); ?>"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getParagraph()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="company"
                       id="company"
                       placeholder="<?php echo $this->getTrans('company'); ?>"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getCompany()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="name"
                       id="name"
                       placeholder="<?php echo $this->getTrans('name'); ?>"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getName()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="address"
                       id="address"
                       placeholder="<?php echo $this->getTrans('address'); ?>"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getAddress()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="addressadd"
                       id="addressadd"
                       placeholder="<?php echo $this->getTrans('addressadd'); ?>"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getAddressAdd()); } ?>" />
            </div>
        </div>
        <br />
        <div class="form-group">
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="city"
                       id="city"
                       placeholder="<?php echo $this->getTrans('city'); ?>"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getCity()); } ?>" />
            </div>
        </div>
        <br />
        <div class="form-group">
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="phone"
                       id="phone"
                       placeholder="<?php echo $this->getTrans('phone'); ?>"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getPhone()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="fax"
                       id="fax"
                       placeholder="<?php echo $this->getTrans('fax'); ?>"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getFax()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="email"
                       id="email"
                       placeholder="<?php echo $this->getTrans('email'); ?>"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getEmail()); } ?>" />
            </div>
        </div>
        <br />
        <div class="form-group">
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="registration"
                       id="registration"
                       placeholder="<?php echo $this->getTrans('registration'); ?>"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getRegistration()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="commercialregister"
                       id="commercialregister"
                       placeholder="<?php echo $this->getTrans('commercialRegister'); ?>"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getCommercialRegister()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="vatid"
                       id="vatid"
                       placeholder="<?php echo $this->getTrans('vatId'); ?>"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getVatId()); } ?>" />
            </div>
        </div>
        <br />
        <div class="form-group">
            <div class="col-lg-12">
               <textarea class="form-control"
                          id="ilch_html"
                          name="other" 
                          cols="60" 
                          rows="5"><?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getOther()); } ?></textarea>
            </div>
        </div>
        <br />
        <div class="form-group">
            <div class="col-lg-12">
                <textarea class="form-control"
                          id="ilch_html"
                          name="disclaimer" 
                          cols="60" 
                          rows="5"><?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getDisclaimer()); } ?></textarea>
            </div>
        </div>
    <?php endif; ?>
    <?=$this->getSaveBar('updateButton')?>
</form>
