<?php
$forum = $this->get('forum');
$readAccess = $this->get('readAccess');

$adminAccess = null;
if ($this->getUser()) {
    $adminAccess = $this->getUser()->isAdmin();
}
?>

<link href="<?=$this->getModuleUrl('static/css/forum-style.css') ?>" rel="stylesheet">

<?php if (is_in_array($readAccess, explode(',', $forum->getCreateAccess())) || $adminAccess == true): ?>
    <h3 class="blue-header col-lg-12"><?=$this->getTrans('createNewTopic') ?></h3>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="topicTitleInput" class="col-lg-2 control-label">
                        <?=$this->getTrans('topicTitle') ?>:
                    </label>
                    <div class="col-lg-8">
                        <input type="text"
                               class="form-control"
                               id="topicTitleInput"
                               name="topicTitle"
                               value="" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">
                        <?=$this->getTrans('text') ?>*
                    </label>
                    <div class="col-lg-8">
                        <textarea class="form-control ckeditor"
                                  id="ck_1"
                                  name="text"
                                  toolbar="ilch_bbcode"></textarea>
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
                                           value="1" /> <?=$this->getTrans('yes') ?>
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
                               class="btn"
                               name="saveNewTopic"
                               value="<?=$this->getTrans('add') ?>" />
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php else: ?>
    <?php
    header("location: ".$this->getUrl(['controller' => 'index', 'action' => 'index', 'access' => 'noaccess']));
    exit;
    ?>
<?php endif; ?>

<?=$this->getDialog('smiliesModal', $this->getTrans('smilies'), '<iframe frameborder="0"></iframe>'); ?>
