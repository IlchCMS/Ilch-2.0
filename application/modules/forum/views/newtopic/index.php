<?php $forum = $this->get('forum'); ?>
<?php $readAccess = $this->get('readAccess'); ?>
<?php

$adminAccess = null;
if($this->getUser()){
    $adminAccess = $this->getUser()->isAdmin();
}
     ?>
<?php if (is_in_array($readAccess, explode(',', $forum->getCreateAccess())) || $adminAccess == true): ?>
<link href="<?=$this->getModuleUrl('static/css/forum-style.css') ?>" rel="stylesheet">
<form class="form-horizontal" method="POST" action="">
        <div id="">
            <div class="row">
                <div class="col-md-12">
                    <?=$this->getTokenField() ?>
                    <div class="form-group">
                        <label for="topicTitleInput" class="col-lg-2 control-label">
                            <?=$this->getTrans('topicTitle') ?>:
                        </label>
                        <div class="col-lg-8">
                            <input class="form-control"
                                   type="text"
                                   name="topicTitle"
                                   id="topicTitleInput"
                                   value="" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">
                            <?=$this->getTrans('text'); ?>*
                        </label>
                        <div class="col-lg-8">
                            <textarea id="ck_1"
                                      class="form-control ckeditor"
                                      toolbar="ilch_bbcode"
                                      name="text">
                            </textarea>
                        </div>
                    </div>
                    <?php if ($this->getUser()->isAdmin()): ?>
                        <div class="form-group">
                            <label for="forumTypeFixed" class="col-lg-2 control-label">
                                <?=$this->getTrans('forumTypeFixed') ?>:
                            </label>
                            <div class="col-lg-2">
                                <div class="radio">
                                    <label>
                                        <input type="radio"
                                               name="type"
                                               value="1"
                                                /> <?=$this->getTrans('yes') ?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio"
                                               name="type"
                                               value="0"
                                               checked="checked" /> <?=$this->getTrans('no') ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-8">
                            <input type="submit"
                                   name="saveNewTopic"
                                   class="btn"
                                   value="<?php echo $this->getTrans('add'); ?>" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </form>
<?php else: ?>
    <?php
    header("location: ".$this->getUrl(array('controller' => 'index', 'action' => 'index', 'access' => 'noaccess')));
    exit;
    ?>
<?php endif; ?>
