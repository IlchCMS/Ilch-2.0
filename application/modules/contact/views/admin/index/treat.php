<?php

/** @var \Ilch\View $this */

/** @var \Modules\Contact\Models\Receiver $receiver */
$receiver = $this->get('receiver');
?>
<h1><?=$this->getTrans($receiver->getId() ? 'edit' : 'add') ?></h1>
<form method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('name') ? ' has-error' : '' ?>">
        <label for="name" class="col-xl-2 col-form-label">
            <?=$this->getTrans('name') ?>:
        </label>
        <div class="col-xl-2">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   value="<?=$this->escape($this->originalInput('name', $receiver->getName())) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('email') ? ' has-error' : '' ?>">
        <label for="email" class="col-xl-2 col-form-label">
                <?=$this->getTrans('email') ?>:
        </label>
        <div class="col-xl-2">
            <input type="email"
                   class="form-control"
                   id="email"
                   name="email"
                   value="<?=$this->escape($this->originalInput('email', $receiver->getEmail())) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar($receiver->getId() ? 'updateButton' : 'addButton') ?>
</form>
