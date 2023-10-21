<?php

/** @var \Ilch\View $this */
?>
<h1><?=($this->get('rank')) ? $this->getTrans('edit') : $this->getTrans('add') ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('title') ? 'has-error' : '' ?>">
        <label for="title" class="col-lg-2 control-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-lg-2">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?=($this->get('rank')) ? $this->escape($this->get('rank')->getTitle()) : '' ?>"
                   required />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('posts') ? 'has-error' : '' ?>">
        <label for="posts" class="col-lg-2 control-label">
            <?=$this->getTrans('posts') ?>:
        </label>
        <div class="col-lg-2">
            <input type="number"
                   class="form-control"
                   id="posts"
                   name="posts"
                   min="0"
                   value="<?=($this->get('rank')) ? $this->escape($this->get('rank')->getPosts()) : '' ?>"
                   required />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
