<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName(), 'id' => 1)) ?>">
 <?=$this->getTokenField() ?>
    <legend><?=$this->getTrans('manage') ?></legend>
    <?php if($this->get('imprintStyle') == '0'): ?>
        <div class="form-group">
            <label for="rank" class="col-lg-2 control-label">
                <?=$this->getTrans('paragraph') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="paragraph"
                       id="paragraph"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getParagraph()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="name" class="col-lg-2 control-label">
                <?=$this->getTrans('name') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="name"
                       id="name"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getName()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="address" class="col-lg-2 control-label">
                <?=$this->getTrans('address') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="address"
                       id="address"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getAddress()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="addressadd" class="col-lg-2 control-label">
                <?=$this->getTrans('addressadd') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="addressadd"
                       id="addressadd"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getAddressAdd()); } ?>" />
            </div>
        </div>
        <br />
        <div class="form-group">
            <label for="city" class="col-lg-2 control-label">
                <?=$this->getTrans('city') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="city"
                       id="city"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getCity()); } ?>" />
            </div>
        </div>
        <br />
        <div class="form-group">
            <label for="phone" class="col-lg-2 control-label">
                <?=$this->getTrans('phone') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="phone"
                       id="phone"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getPhone()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="fax" class="col-lg-2 control-label">
                <?=$this->getTrans('fax') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="fax"
                       id="fax"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getFax()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-lg-2 control-label">
                <?=$this->getTrans('email') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="email"
                       id="email"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getEmail()); } ?>" />
            </div>
        </div>
        <br />
        <div class="form-group">
            <label for="disclaimer" class="col-lg-2 control-label">
                <?=$this->getTrans('disclaimer') ?>:
            </label>
            <div class="col-lg-12">
                <textarea class="form-control ckeditor"
                          id="ck_1"
                          toolbar="ilch_html"
                          name="disclaimer" 
                          cols="60"
                          rows="5"><?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getDisclaimer()); } ?></textarea>
            </div>
        </div>
    <?php else: ?>
        <div class="form-group">
            <label for="paragraph" class="col-lg-2 control-label">
                <?=$this->getTrans('paragraph') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="paragraph"
                       id="paragraph"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getParagraph()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="company" class="col-lg-2 control-label">
                <?=$this->getTrans('company') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="company"
                       id="company"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getCompany()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="name" class="col-lg-2 control-label">
                <?=$this->getTrans('name') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="name"
                       id="name"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getName()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="address" class="col-lg-2 control-label">
                <?=$this->getTrans('address') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="address"
                       id="address"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getAddress()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="addressadd" class="col-lg-2 control-label">
                <?=$this->getTrans('addressadd') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="addressadd"
                       id="addressadd"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getAddressAdd()); } ?>" />
            </div>
        </div>
        <br />
        <div class="form-group">
            <label for="city" class="col-lg-2 control-label">
                <?=$this->getTrans('city') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="city"
                       id="city"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getCity()); } ?>" />
            </div>
        </div>
        <br />
        <div class="form-group">
            <label for="phone" class="col-lg-2 control-label">
                <?=$this->getTrans('phone') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="phone"
                       id="phone"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getPhone()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="fax" class="col-lg-2 control-label">
                <?=$this->getTrans('fax') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="fax"
                       id="fax"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getFax()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-lg-2 control-label">
                <?=$this->getTrans('email') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="email"
                       id="email"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getEmail()); } ?>" />
            </div>
        </div>
        <br />
        <div class="form-group">
            <label for="registration" class="col-lg-2 control-label">
                <?=$this->getTrans('registration') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="registration"
                       id="registration"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getRegistration()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="commercialregister" class="col-lg-2 control-label">
                <?=$this->getTrans('commercialRegister') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="commercialregister"
                       id="commercialregister"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getCommercialRegister()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="vatid" class="col-lg-2 control-label">
                <?=$this->getTrans('vatId') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="vatid"
                       id="vatid"
                       value="<?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getVatId()); } ?>" />
            </div>
        </div>
        <br />
        <div class="form-group">
            <label for="other" class="col-lg-2 control-label">
                <?=$this->getTrans('other') ?>:
            </label>
            <div class="col-lg-12">
               <textarea class="form-control ckeditor"
                          id="ck_2"
                          toolbar="ilch_html"
                          name="other" 
                          cols="60" 
                          rows="5"><?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getOther()); } ?></textarea>
            </div>
        </div>
        <br />
        <div class="form-group">
            <label for="disclaimer" class="col-lg-2 control-label">
                <?=$this->getTrans('disclaimer') ?>:
            </label>
            <div class="col-lg-12">
                <textarea class="form-control ckeditor"
                          id="ck_3"
                          toolbar="ilch_html"
                          name="disclaimer" 
                          cols="60" 
                          rows="5"><?php if ($this->get('imprint') != '') { echo $this->escape($this->get('imprint')->getDisclaimer()); } ?></textarea>
            </div>
        </div>
    <?php endif; ?>
    <?=$this->getSaveBar('updateButton')?>
</form>
