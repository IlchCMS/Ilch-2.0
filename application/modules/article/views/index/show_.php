<?php
$comments = $this->get('comments');
$article = $this->get('article');
$commentMapper = new \Modules\Comment\Mappers\Comment();
$content = str_replace('[PREVIEWSTOP]', '', $article->getContent());
$image = $article->getArticleImage();
$imageSource = $article->getArticleImageSource();
$preview = $this->getRequest()->getParam('preview');
echo '<h4>'.$article->getTitle().'</h4>';
if (!empty($image)) {
    echo '<figure><img class="article_image" src="'.$this->getBaseUrl($image).'"/>';
    if (!empty($imageSource)) {
        echo '<figcaption class="article_image_source">'.$this->getTrans('articleImageSource').': '.$imageSource.'</figcaption><figure>';
    }
}
echo '<br />';

echo $content;
echo '<hr />';
if ($article->getAuthorId() != ''){
    $userMapper = new \Modules\User\Mappers\User();
    $user = $userMapper->getUserById($article->getAuthorId());
    if ($user != ''){
        echo $this->getTrans('author').': <a href="'.$this->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId())).'">'.$this->escape($user->getName()).'</a>';
        echo '<hr />';
    }
}
if(empty($preview))
{
echo '<div id="comment"><h5>'.$this->getTrans('comments').' ('.count($comments).')</h5></div>';

    if($this->getUser())
    {
    ?>
    <form action="" class="form-horizontal" method="POST">
        <?=$this->getTokenField() ?>
        <div class="form-group">
            <div class="col-lg-12">
                <textarea class="form-control"
                        name="article_comment_text"
                          required></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12">
                <input type="submit"
                       name="saveEntry"
                       class="pull-right btn" 
                       value="<?php echo $this->getTrans('submit'); ?>" />
            </div>
        </div>
    </form>
	<section id="blog">
	<div class="article">
    <div class="row">
	<div class="comments">					
    <?php
    }
	
    $userMapper = new \Modules\User\Mappers\User();
    foreach($comments as $comment){
		$user = $userMapper->getUserById($comment->getUserId());
				echo '<div class="media media-comments">';
				echo '<a class="pull-left" href="#"><img src="http://lorempixel.com/70/70/" alt=""></a>';
				echo '<div class="media-body">';
				echo '<h5 class="media-heading"># <b>{ZAHL}</b> von <a href="'.$this->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId())).'">'.$this->escape($user->getName()).'</a>';
				echo '<span><a href="'.$this->getUrl(array('module' => 'comment', 'action' => 'index', 'id' => $comment->getId(), 'ida' => $article->getId())).'"><i class="fa fa-comment-o"></i> Reply</a></span></h5>';
				echo '<p class="date">May 30th, 2014</p>';
				echo '<p>'.nl2br($this->escape($comment->getText())).'</p>';
				echo '</div></div>';
		$fk_comments = $commentMapper->getCommentsByFKId($comment->getId());
		foreach($fk_comments as $fk_comment){
				$user = $userMapper->getUserById($fk_comment->getUserId());
				echo '<div class="media media-comments reply">';
				echo '<a class="pull-left" href="#"><img src="http://lorempixel.com/70/70/" alt=""></a>';
				echo '<div class="media-body">';
				echo '<h5 class="media-heading"># <b>{ZAHL}</b> von <a href="'.$this->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId())).'">'.$this->escape($user->getName()).'</a>';
				echo '<span><a href="'.$this->getUrl(array('module' => 'comment', 'action' => 'index', 'id' => $fk_comment->getId(), 'ida' => $article->getId())).'"><i class="fa fa-comment-o"></i> Reply</a></span></h5>';
				echo '<p class="date">May 30th, 2014</p>';
				echo '<p>'.nl2br($this->escape($fk_comment->getText())).'</p>';
				echo '</div></div>';
		}
    }
	echo '</div></div></div></section>';
	
	
}
