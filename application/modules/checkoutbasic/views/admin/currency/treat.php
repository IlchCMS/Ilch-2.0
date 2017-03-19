<?php $currency = $this->get('currency'); ?>

<h1>
    <?php if ($this->getRequest()->getParam("id")) {
        echo $this->getTrans('edit');
    } else {
        echo $this->getTrans('add');
    }
    ?>
</h1>
<?php if (!empty($this->get('errors'))): ?>
    <div class="alert alert-danger" role="alert">
        <strong> <?=$this->getTrans('errorsOccured') ?>:</strong>
        <ul>
            <?php foreach ($this->get('errors') as $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=in_array('name', $this->get('errorFields')) ? 'has-error' : '' ?>">
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   placeholder="<?=$this->getTrans('name') ?>"
                   value="<?=$this->escape($currency->getName()) ?>" />
        </div>
    </div>
    <div class="form-group hidden">
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="id"
                   name="id"
                   value="<?=$this->escape($currency->getId()) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
