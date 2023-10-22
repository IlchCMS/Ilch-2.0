<h1>
    <?php
    if ($this->get('receiver') != '') {
        echo $this->getTrans('edit');
    } else {
        echo $this->getTrans('add');
    }
    ?>
</h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3 <?=$this->validation()->hasError('name') ? 'has-error' : '' ?>">
        <label for="name" class="col-xl-2 control-label">
            <?=$this->getTrans('name') ?>:
        </label>
        <div class="col-xl-2">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   value="<?=($this->get('receiver') != '') ? $this->escape($this->get('receiver')->getName()) : $this->escape($this->originalInput('name')) ?>" />
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('email') ? 'has-error' : '' ?>">
        <label for="email" class="col-xl-2 control-label">
                <?=$this->getTrans('email') ?>:
        </label>
        <div class="col-xl-2">
            <input type="email"
                   class="form-control"
                   id="email"
                   name="email"
                   value="<?=($this->get('receiver') != '') ? $this->escape($this->get('receiver')->getEmail()) : $this->escape($this->originalInput('email')) ?>" />
        </div>
    </div>
    <?=($this->get('receiver') != '') ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
</form>
