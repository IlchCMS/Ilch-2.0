<?php

/** @var \Ilch\View $this */

/** @var \Modules\Forum\Models\ForumItem $forum */
$forum = $this->get('forum');
/** @var \Modules\Forum\Models\ForumItem $cat */
$cat = $this->get('cat');

$adminAccess = null;
if ($this->getUser()) {
    $adminAccess = $this->getUser()->isAdmin();
}
?>

<link href="<?=$this->getModuleUrl('static/css/forum.css') ?>" rel="stylesheet">

<?php if ($adminAccess || $forum->getCreateAccess()) : ?>
    <div id="forum">
        <h1>
            <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'index']) ?>"><?=$this->getTrans('forum') ?></a>
            <i class="fa-solid fa-chevron-right"></i> <a href="<?=$this->getUrl(['controller' => 'showcat', 'action' => 'index', 'id' => $cat->getId()]) ?>"><?=$cat->getTitle() ?></a>
            <i class="fa-solid fa-chevron-right"></i> <a href="<?=$this->getUrl(['controller' => 'showtopics', 'action' => 'index', 'forumid' => $forum->getId()]) ?>"><?=$forum->getTitle() ?></a>
            <i class="fa-solid fa-chevron-right"></i> <?=$this->getTrans('newTopicTitle') ?>
        </h1>
        <div class="row">
            <div class="col-lg-12">
                <div class="new-post-head ilch-head">
                    <?=$this->getTrans('createNewTopic') ?>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="new-topic ilch-bg ilch-border">
                    <form class="form-horizontal" method="POST">
                        <?=$this->getTokenField() ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group <?=$this->validation()->hasError('topicTitle') ? 'has-error' : '' ?>">
                                    <label for="topicTitle" class="col-lg-2 control-label">
                                        <?=$this->getTrans('topicTitle') ?>
                                    </label>
                                    <?php if ($forum->getPrefix() != '') : ?>
                                        <?php $prefix = explode(',', $forum->getPrefix()); ?>
                                        <?php array_unshift($prefix, ''); ?>
                                        <div class="col-lg-2 prefix">
                                            <select class="form-control" id="topicPrefix" name="topicPrefix">
                                                <?php foreach ($prefix as $key => $value) : ?>
                                                    <?php $selected = ''; ?>
                                                    <?php if ($key == $this->originalInput('topicPrefix')) : ?>
                                                        <?php $selected = 'selected="selected"'; ?>
                                                    <?php endif; ?>

                                                    <option <?=$selected ?> value="<?=$key ?>"><?=$this->escape($value) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    <?php endif; ?>
                                    <div class="col-lg-5">
                                        <input type="text"
                                               class="form-control"
                                               id="topicTitle"
                                               name="topicTitle"
                                               value="<?=$this->originalInput('topicTitle') ?>" />
                                    </div>
                                </div>
                                <div class="form-group <?=$this->validation()->hasError('text') ? 'has-error' : '' ?>">
                                    <label class="col-lg-2 control-label">
                                        <?=$this->getTrans('text') ?>
                                    </label>
                                    <div class="col-lg-10">
                                    <textarea class="form-control ckeditor"
                                              id="ck_1"
                                              name="text"
                                              toolbar="ilch_html_frontend"><?=$this->originalInput('text') ?></textarea>
                                    </div>
                                </div>
                                <?php if ($this->getUser()->isAdmin()) : ?>
                                    <div class="form-group">
                                        <div class="col-lg-2 control-label">
                                            <?=$this->getTrans('forumOptions') ?>
                                        </div>
                                        <div class="col-lg-10">
                                            <input type="checkbox"
                                                   id="fix"
                                                   name="fix"
                                                   value="1"
                                                   <?=($this->originalInput('fix')) ? 'checked' : '' ?> />
                                            <label for="fix">
                                                <?=$this->getTrans('forumTypeFixed') ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-8">
                                        <input type="submit"
                                               class="btn btn-primary"
                                               name="saveNewTopic"
                                               value="<?=$this->getTrans('add') ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php else : ?>
    <?php
    header('location: ' . $this->getUrl(['controller' => 'index', 'action' => 'index', 'access' => 'noaccess']));
    exit;
    ?>
<?php endif; ?>
