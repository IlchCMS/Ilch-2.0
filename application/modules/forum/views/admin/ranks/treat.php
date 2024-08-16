<?php

/** @var \Ilch\View $this */

/** @var \Modules\Forum\Models\Rank|null $rank */
$rank = $this->get('rank');
?>
<h1><?=($rank) ? $this->getTrans('edit') : $this->getTrans('add') ?></h1>
<form method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('title') ? ' has-error' : '' ?>">
        <label for="title" class="col-xl-2 col-form-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-xl-2">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?=($rank) ? $this->escape($rank->getTitle()) : '' ?>"
                   required />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('posts') ? ' has-error' : '' ?>">
        <label for="posts" class="col-xl-2 col-form-label">
            <?=$this->getTrans('posts') ?>:
        </label>
        <div class="col-xl-2">
            <input type="number"
                   class="form-control"
                   id="posts"
                   name="posts"
                   min="0"
                   value="<?=($rank) ? $this->escape($rank->getPosts()) : '' ?>"
                   required />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
