<link href="<?=$this->getModuleUrl('static/css/forum-style.css') ?>" rel="stylesheet">
<?php
$posts = $this->get('posts');
$topicpost = $this->get('post');
$readAccess = $this->get('readAccess');
$forum = $this->get('forum');
$adminAccess = null;
if ($this->getUser()) {
    $adminAccess = $this->getUser()->isAdmin();
}
?>

<?php if (is_in_array($readAccess, explode(',', $forum->getReadAccess())) || $adminAccess == true): ?>
    <div id="forum">
        <h3><?=$topicpost->getTopicTitle() ?></h3>
        <div class="topic-actions">
            <?php if($this->getUser()): ?>
                <?php if (is_in_array($readAccess, explode(',', $forum->getReplayAccess())) || $adminAccess == true): ?>
                    <div class="buttons">
                        <a href="<?=$this->getUrl(array('controller' => 'newpost', 'action' => 'index','topicid' => $this->getRequest()->getParam('topicid'))) ?>" class="btn btn-labeled bgblue">
                            <span class="btn-label">
                                <i class="fa fa-plus"></i>
                            </span><?=$this->getTrans('createNewPost') ?>
                        </a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <?php $_SESSION['redirect'] = $this->getRouter()->getQuery(); ?>
                <div class="buttons">
                    <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'login', 'action' => 'index')) ?>" class="btn btn-labeled bgblue">
                        <span class="btn-label">
                            <i class="fa fa-user"></i>
                        </span><?=$this->getTrans('loginPost') ?>
                    </a>
                </div>
            <?php endif; ?>
            <?=$this->get('pagination')->getHtml($this, array('action' => 'index', 'topicid' => $this->getRequest()->getParam('topicid'))); ?>
        </div>
        <?php foreach ($posts as $post): ?>
            <?php $date = new \Ilch\Date($post->getDateCreated()) ?>
            <div id="p3" class="post bg1">
                <div class="edit">
                    <?php if ($this->getUser()): ?>
                        <?php if ($this->getUser()->isAdmin()): ?>
                            <p class="edit-post">
                                <a href="<?=$this->getUrl(array('controller' => 'showposts', 'action' => 'delete', 'id' => $post->getId(), 'topicid' => $this->getRequest()->getParam('topicid'))) ?>" class="btn btn-xs btn-labeled bgblue">
                                    <span class="btn-label">
                                        <i class="fa fa-trash"></i>
                                    </span><?=$this->getTrans('delete') ?>
                                </a>
                            </p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="postbody">
                    <p class="author">
                        <a id="<?=$post->getId() ?>" href="#<?=$post->getId() ?>">
                            <img src="<?=$this->getModuleUrl('static/img/icon_post_target.png') ?>" alt="Post" title="Post" height="9" width="11">
                        </a>
                        by
                        <strong>
                            <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $post->getAutor()->getId())) ?>" style="color: #AA0000;" class="username-coloured">
                                <?=$post->getAutor()->getName() ?>
                            </a>
                        </strong>
                        »
                        <?=$date->format("d.m.Y H:i:s", true)?>
                    </p>
                    <div class="content"><?=nl2br($this->getHtmlFromBBCode($post->getText())) ?></div>
                    <div id="sig3" class="signature"><?=$post->getAutor()->getSignature() ?></div>
                </div>
                <dl class="postprofile" id="profile3">
                    <dt>
                        <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $post->getAutor()->getId())) ?>">
                            <img src="<?=$this->getBaseUrl($post->getAutor()->getAvatar()) ?>" alt="User avatar" height="100" width="100">
                        </a>
                        <br>
                        <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $post->getAutor()->getId())) ?>" style="color: #AA0000;" class="username-coloured">
                            <?=$post->getAutor()->getName() ?>
                        </a>
                    </dt>
                    <dd>Site Admin</dd>
                    <dd>&nbsp;</dd>
                    <dd><strong>Posts:</strong> 16</dd><dd><strong>Joined:</strong> <?=$post->getAutor()->getDateCreated() ?></dd>
                </dl>
            </div>
        <?php endforeach; ?>
        <div class="topic-actions">
            <?php if($this->getUser()): ?>
                <?php if (is_in_array($readAccess, explode(',', $forum->getReplayAccess())) || $adminAccess == true): ?>
                    <div class="buttons">
                        <a href="<?=$this->getUrl(array('controller' => 'newpost', 'action' => 'index','topicid' => $this->getRequest()->getParam('topicid'))) ?>" class="btn btn-labeled bgblue">
                            <span class="btn-label">
                                <i class="fa fa-plus"></i>
                            </span><?=$this->getTrans('createNewPost') ?>
                        </a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <?php $_SESSION['redirect'] = $this->getRouter()->getQuery(); ?>
                <div class="buttons">
                    <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'login', 'action' => 'index')) ?>" class="btn btn-labeled bgblue">
                        <span class="btn-label">
                            <i class="fa fa-user"></i>
                        </span><?=$this->getTrans('loginPost') ?>
                    </a>
                </div>
            <?php endif; ?>
            <?=$this->get('pagination')->getHtml($this, array('action' => 'index', 'topicid' => $this->getRequest()->getParam('topicid'))); ?>
        </div>
    </div>
<?php else: ?>
    <?php
    header("location: ".$this->getUrl(array('controller' => 'index', 'action' => 'index', 'access' => 'noaccess')));
    exit;
    ?>
<?php endif; ?>

<script>
$(document).ready(function(){
	$('a[href^="#"]').on('click',function (e) {
	    e.preventDefault();

	    var target = this.hash;
	    var $target = $(target);
        });
</script>