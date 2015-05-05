<?php
$comments = $this->get('comments');
$commentMapper = new \Modules\Comment\Mappers\Comment();
if(!empty($comments)){
	echo '<section id="blog"><div class="article"><div class="row"><div class="comments">';
	foreach($comments as $comment){
		if($this->getRequest()->getParam('id') == $comment->getId()){
			$date = new \Ilch\Date($comment->getDateCreated());
			$userMapper = new \Modules\User\Mappers\User();
			$user = $userMapper->getUserById($comment->getUserId());
			echo '<div class="media media-comments">';
			echo '<a class="pull-left" href="#"><img src="http://lorempixel.com/70/70/" alt=""></a>';
			echo '<div class="media-body">';
			echo '<h5 class="media-heading"># <b>{ZAHL}</b> von <a href="'.$this->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId())).'">'.$this->escape($user->getName()).'</a>';
			echo '<p class="date">May 30th, 2014</p>';
			echo '<p>'.nl2br($this->escape($comment->getText())).'</p>';
			echo '</div></div>';
		}
	}
	echo '</div></div></div></section>';
	if($this->getUser()){
    ?>
		<form action="" class="form-horizontal" method="POST">
			<?=$this->getTokenField() ?>
			<div class="form-group">
				<div class="col-lg-12">
					<textarea class="form-control"
							name="comment_comment_text"
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
    <?php
    }
	echo '<section id="blog"><div class="article"><div class="row"><div class="comments">';
	foreach($comments as $comment){
		if($this->getRequest()->getParam('id') == $comment->getFKId()){
			$date = new \Ilch\Date($comment->getDateCreated());
			$userMapper = new \Modules\User\Mappers\User();
			$user = $userMapper->getUserById($comment->getUserId());
			echo '<div class="media media-comments">';
			echo '<a class="pull-left" href="#"><img src="http://lorempixel.com/70/70/" alt=""></a>';
			echo '<div class="media-body">';
			echo '<h5 class="media-heading"># <b>{ZAHL}</b> von <a href="'.$this->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId())).'">'.$this->escape($user->getName()).'</a>';
			echo '<span><a href="'.$this->getUrl(array('module' => 'comment', 'action' => 'index', 'id' => $comment->getId())).'"><i class="fa fa-comment-o"></i> Reply</a></span></h5>';
			echo '<p class="date">May 30th, 2014</p>';
			echo '<p>'.nl2br($this->escape($comment->getText())).'</p>';
			echo '</div></div>';
		}
	}
	echo '</div></div></div></section>';
}
