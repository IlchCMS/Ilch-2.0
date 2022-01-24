<h1>
    <?php
    if ($this->get('category') != '') {
        echo $this->getTrans('menuActionEditCategory');
    } else {
        echo $this->getTrans('menuActionNewCategory');
    }
    ?>
</h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('name') ? 'has-error' : '' ?>">
        <label for="name" class="col-lg-2 control-label">
            <?=$this->getTrans('name') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   placeholder="Name"
                   value="<?=($this->get('category') != '') ? $this->escape($this->get('category')->getName()) : $this->escape($this->get('post')['name']) ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="desc" class="col-lg-2 control-label">
            <?=$this->getTrans('description') ?>:
        </label>
        <div class="col-lg-4">
            <textarea class="form-control"
                      id="desc"
                      name="desc"
                      cols="45"
                      rows="3"><?=($this->get('category') != '') ? $this->escape($this->get('category')->getDesc()) : $this->escape($this->get('post')['desc']) ?></textarea>
        </div>
    </div>
    <?=($this->get('category') != '') ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
</form>
