<h1><?=$this->getTrans('manage') ?></h1>
<?php if ($this->validation()->hasErrors()): ?>
    <div class="alert alert-danger" role="alert">
        <strong> <?=$this->getTrans('errorsOccured') ?>:</strong>
        <ul>
            <?php foreach ($this->validation()->getErrorMessages() as $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName(), 'id' => 1]) ?>">
    <?=$this->getTokenField() ?>
        <div class="form-group">
            <label for="paragraph" class="col-lg-2 control-label">
                <?=$this->getTrans('paragraph') ?>:
            </label>
            <div class="col-lg-4">
                <input type="text"
                       class="form-control"
                       id="paragraph"
                       name="paragraph"
                       value="<?= ($this->originalInput('paragraph') != '') ? $this->originalInput('paragraph') : $this->escape($this->get('imprint')->getParagraph()) ?>" />
            </div>
        </div>
    <?php if ($this->get('imprintStyle') == '1'): ?>
        <div class="form-group">
            <label for="company" class="col-lg-2 control-label">
                <?=$this->getTrans('company') ?>:
            </label>
            <div class="col-lg-4">
                <input type="text"
                       class="form-control"
                       id="company"
                       name="company"
                       value="<?= ($this->originalInput('company') != '') ? $this->originalInput('company') : $this->escape($this->get('imprint')->getCompany()) ?>" />
            </div>
        </div>
    <?php endif; ?>
        <div class="form-group">
            <label for="name" class="col-lg-2 control-label">
                <?=$this->getTrans('name') ?>:
            </label>
            <div class="col-lg-4">
                <input type="text"
                       class="form-control"
                       id="name"
                       name="name"
                       value="<?= ($this->originalInput('name') != '') ? $this->originalInput('name') : $this->escape($this->get('imprint')->getName()) ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="address" class="col-lg-2 control-label">
                <?=$this->getTrans('address') ?>:
            </label>
            <div class="col-lg-4">
                <input type="text"
                       class="form-control"
                       id="address"
                       name="address"
                       value="<?= ($this->originalInput('address') != '') ? $this->originalInput('address') : $this->escape($this->get('imprint')->getAddress()) ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="addressadd" class="col-lg-2 control-label">
                <?=$this->getTrans('addressadd') ?>:
            </label>
            <div class="col-lg-4">
                <input type="text"
                       class="form-control"
                       id="addressadd"
                       name="addressadd"
                       value="<?= ($this->originalInput('addressadd') != '') ? $this->originalInput('addressadd') : $this->escape($this->get('imprint')->getAddressAdd()) ?>" />
            </div>
        </div>
        <br />
        <div class="form-group">
            <label for="city" class="col-lg-2 control-label">
                <?=$this->getTrans('city') ?>:
            </label>
            <div class="col-lg-4">
                <input type="text"
                       class="form-control"
                       id="city"
                       name="city"
                       value="<?= ($this->originalInput('city') != '') ? $this->originalInput('city') : $this->escape($this->get('imprint')->getCity()) ?>" />
            </div>
        </div>
        <br />
        <div class="form-group">
            <label for="phone" class="col-lg-2 control-label">
                <?=$this->getTrans('phone') ?>:
            </label>
            <div class="col-lg-4">
                <input type="text"
                       class="form-control"
                       id="phone"
                       name="phone"
                       value="<?= ($this->originalInput('phone') != '') ? $this->originalInput('phone') : $this->escape($this->get('imprint')->getPhone()) ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="fax" class="col-lg-2 control-label">
                <?=$this->getTrans('fax') ?>:
            </label>
            <div class="col-lg-4">
                <input type="text"
                       class="form-control"
                       id="fax"
                       name="fax"
                       value="<?= ($this->originalInput('fax') != '') ? $this->originalInput('fax') : $this->escape($this->get('imprint')->getFax()) ?>" />
            </div>
        </div>
        <div class="form-group <?=$this->validation()->hasError('email') ? 'has-error' : '' ?>">
            <label for="email" class="col-lg-2 control-label">
                <?=$this->getTrans('email') ?>:
            </label>
            <div class="col-lg-4">
                <input type="text"
                       class="form-control"
                       id="email"
                       name="email"
                       value="<?= ($this->originalInput('email') != '') ? $this->originalInput('email') : $this->escape($this->get('imprint')->getEmail()) ?>" />
            </div>
        </div>
        <br />
    <?php if ($this->get('imprintStyle') == '1'): ?>
        <div class="form-group">
            <label for="registration" class="col-lg-2 control-label">
                <?=$this->getTrans('registration') ?>:
            </label>
            <div class="col-lg-4">
                <input type="text"
                       class="form-control"
                       id="registration"
                       name="registration"
                       value="<?= ($this->originalInput('registration') != '') ? $this->originalInput('registration') : $this->escape($this->get('imprint')->getRegistration()) ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="commercialregister" class="col-lg-2 control-label">
                <?=$this->getTrans('commercialRegister') ?>:
            </label>
            <div class="col-lg-4">
                <input type="text"
                       class="form-control"
                       id="commercialregister"
                       name="commercialregister"
                       value="<?= ($this->originalInput('commercialregister') != '') ? $this->originalInput('commercialregister') : $this->escape($this->get('imprint')->getCommercialRegister()) ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="vatid" class="col-lg-2 control-label">
                <?=$this->getTrans('vatId') ?>:
            </label>
            <div class="col-lg-4">
                <input type="text"
                       class="form-control"
                       id="vatid"
                       name="vatid"
                       value="<?= ($this->originalInput('vatid') != '') ? $this->originalInput('vatid') : $this->escape($this->get('imprint')->getVatId()) ?>" />
            </div>
        </div>
        <br />
        <div class="form-group">
            <label for="ck_2" class="col-lg-2 control-label">
                <?=$this->getTrans('other') ?>:
            </label>
            <div class="col-lg-12">
               <textarea class="form-control ckeditor"
                         id="ck_2"
                         name="other" 
                         toolbar="ilch_html"
                         cols="60" 
                         rows="5"><?= ($this->originalInput('other') != '') ? $this->originalInput('other') : $this->escape($this->get('imprint')->getOther()) ?></textarea>
            </div>
        </div>
        <br />
    <?php endif; ?>
        <div class="form-group">
            <label for="ck_3" class="col-lg-2 control-label">
                <?=$this->getTrans('disclaimer') ?>:
            </label>
            <div class="col-lg-12">
                <textarea class="form-control ckeditor"
                          id="ck_3"
                          name="disclaimer" 
                          toolbar="ilch_html"
                          cols="60" 
                          rows="5"><?= ($this->originalInput('disclaimer') != '') ? $this->originalInput('disclaimer') : $this->escape($this->get('imprint')->getDisclaimer()) ?></textarea>
            </div>
        </div>
    <?=$this->getSaveBar('updateButton') ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
