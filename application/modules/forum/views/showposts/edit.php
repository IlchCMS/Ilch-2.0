<?php

/** @var \Ilch\View $this */

/** @var \Modules\Forum\Models\ForumItem $forum */
$forum = $this->get('forum');
/** @var \Modules\Forum\Models\ForumTopic $topic */
$topic = $this->get('topic');
/** @var \Modules\Forum\Models\ForumPost $post */
$post = $this->get('post');
/** @var \Modules\Forum\Models\Prefix[] $prefixes */
$prefixes = $this->get('prefixes');
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
                    <div class="form-group <?=$this->validation()->hasError('topicTitle') ? 'has-error' : '' ?>">
                        <label for="topicTitle" class="col-lg-2 control-label">
                            <?=$this->getTrans('topicTitle') ?>
                        </label>
                        <?php if ($forum->getPrefix() != '') : ?>
                            <?php $prefixIds = explode(',', $forum->getPrefix()); ?>
                            <?php array_unshift($prefixIds, ''); ?>
                            <div class="col-lg-2 prefix">
                                <select class="form-control" id="topicPrefix" name="topicPrefix">
                                    <?php foreach ($prefixIds as $prefixId) : ?>
                                        <?php $selected = ''; ?>
                                        <?php if ($prefixId == $topic->getTopicPrefix()->getId()) : ?>
                                            <?php $selected = 'selected="selected"'; ?>
                                        <?php endif; ?>
                                        <?php if ($prefixId) : ?>
                                            <option <?=$selected ?> value="<?=$prefixId ?>"><?=$this->escape($prefixes[$prefixId]->getPrefix()) ?></option>
                                        <?php else : ?>
                                            <option <?=$selected ?> value="0"></option>
                                        <?php endif; ?>
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
                    <div class="form-group <?=$this->validation()->hasError('text') ? 'has-error' : '' ?>">
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
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-8">
                            <input type="submit"
                                   class="btn btn-primary"
                                   name="editPost"
                                   value="<?=$this->getTrans('edit') ?>" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
