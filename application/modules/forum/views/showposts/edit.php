<?php

/** @var \Ilch\View $this */

/** @var \Modules\Forum\Models\ForumItem $forum */
$forum = $this->get('forum');
/** @var \Modules\Forum\Models\ForumTopic $topic */
$topic = $this->get('topic');
/** @var \Modules\Forum\Models\ForumPost $post */
$post = $this->get('post');
?>

<link href="<?=$this->getModuleUrl('static/css/forum.css') ?>" rel="stylesheet">

<div id="forum">
    <div class="row">
        <div class="col-lg-12">
            <div class="new-post-head ilch-head">
                <?=$this->getTrans('editPost') ?>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="new-topic ilch-bg ilch-border">
                <form class="form-horizontal" method="POST">
                    <?=$this->getTokenField() ?>
                    <?php if ($this->get('isFirstPost')) : ?>
                    <div class="row mb-3 <?=$this->validation()->hasError('topicTitle') ? 'has-error' : '' ?>">
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
                                        <?php if ($key == $topic->getTopicPrefix()) : ?>
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
                                   value="<?=$this->escape($topic->getTopicTitle()) ?>" />
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="row mb-3 <?=$this->validation()->hasError('text') ? 'has-error' : '' ?>">
                        <label class="col-lg-2 control-label">
                            <?=$this->getTrans('text') ?>
                        </label>
                        <div class="col-lg-10">
                            <textarea class="form-control ckeditor"
                                      id="ck_1"
                                      name="text"
                                      toolbar="ilch_html_frontend"><?=$this->escape($post->getText()) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="offset-lg-2 col-lg-8">
                            <input type="submit"
                                   class="btn btn-sm btn-primary"
                                   name="editPost"
                                   value="<?=$this->getTrans('edit') ?>" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
