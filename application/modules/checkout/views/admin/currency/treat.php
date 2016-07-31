<?php
    $currency = $this->get('currency');
?>

<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
    <?php if ($this->getRequest()->getParam("id") == 0) : ?>
        <legend><?=$this->getTrans('addCurrency') ?></legend>
    <?php else: ?>
        <legend><?=$this->getTrans('treatCurrency') ?></legend>
    <?php endif; ?>
    <div class="form-group">
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="name"
                   id="name"
                   placeholder="<?=$this->getTrans('name') ?>"
                   value="<?=$this->escape($currency->getName()) ?>" />
        </div>
    </div>
    <div class="form-group hidden">
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="id"
                   id="id"
                   value="<?=$this->escape($currency->getId())?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
