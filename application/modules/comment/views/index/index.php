<?php
$comments = $this->get('comments');
$commentMapper = new \Modules\Comment\Mappers\Comment();
$preview = $this->getRequest()->getParam('preview');
?>

<link href="<?=$this->getModuleUrl('../comment/static/css/comment.css') ?>" rel="stylesheet">

<?php if(!empty($comments)): ?>
    <section class="comment-list">
        <?php foreach($comments as $comment): ?>
            <?php if($this->getRequest()->getParam('id') == $comment->getId()): ?>
                <?php $fk_comments = $commentMapper->getCommentsByFKId($comment->getId()); ?>
                <?php $date = new \Ilch\Date($comment->getDateCreated()); ?>
                <?php $userMapper = new \Modules\User\Mappers\User(); ?>
                <?php $user = $userMapper->getUserById($comment->getUserId()); ?>
                <?php $commentDate = new \Ilch\Date($comment->getDateCreated()); ?>
                <article class="row" id="<?=$comment->getId() ?>">
                    <div class="col-md-2 col-sm-2 hidden-xs">
                        <figure class="thumbnail">
                            <a href="<?=$this->getUrl('user/profil/index/user/'.$user->getId()) ?>"><img class="img-responsive" src="<?=$this->getUrl().'/'.$user->getAvatar() ?>" alt="<?=$this->escape($user->getName()) ?>"></a>
                        </figure>
                    </div>
                    <div class="col-md-10 col-sm-10">
                        <div class="panel panel-default arrow left">
                            <div class="panel-bodylist">
                                <header class="text-left">
                                    <div class="comment-user">
                                        <i class="fa fa-user"></i> <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId())) ?>"><?=$this->escape($user->getName()) ?></a>
                                    </div>
                                    <time class="comment-date"><i class="fa fa-clock-o"></i> <?=$commentDate->format("d.m.Y - H:i", true) ?></time>
                                </header>
                                <div class="comment-post"><p><?=nl2br($this->escape($comment->getText())) ?></p></div>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endif; ?>
        <?php endforeach; ?>
    </section>
    <?php if(empty($preview)): ?>
        <?php $userMapper = new \Modules\User\Mappers\User(); ?>
        <?php $nowDate = new \Ilch\Date(); ?>
        <div class="row">
            <div class="col-md-12">
                <h3 class="page-header" id="comment"><?=$this->getTrans('menuComments') ?> (<?=count($fk_comments) ?>)</h3>
                <?php if($this->getUser()): ?>            
                    <form action="" class="form-horizontal" method="POST">
                        <?=$this->getTokenField() ?>
                        <section class="comment-list">
                            <article class="row">
                                <div class="col-md-2 col-sm-2 hidden-xs">
                                    <figure class="thumbnail">
                                        <a href="<?=$this->getUrl('user/profil/index/user/'.$this->getUser()->getId()) ?>"><img class="img-responsive" src="<?=$this->getUrl().'/'.$this->getUser()->getAvatar() ?>" alt="<?=$this->getUser()->getName() ?>"></a>
                                    </figure>
                                </div>
                                <div class="col-md-10 col-sm-10">
                                    <div class="panel panel-default arrow left">
                                        <div class="panel-body">
                                            <header class="text-left">
                                                <div class="comment-user"><i class="fa fa-user"></i> <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $this->getUser()->getId())) ?>"><?=$this->getUser()->getName() ?></a></div>
                                                <time class="comment-date"><i class="fa fa-clock-o"></i> <?=$nowDate->format("d.m.Y - H:i", true) ?></time>
                                            </header>
                                            <div class="comment-post">
                                                <p>                                                
                                                    <textarea class="form-control"
                                                              accesskey=""
                                                              name="comment_comment_text"
                                                              required></textarea>                                             
                                                </p>
                                            </div>
                                            <p class="text-right submit">
                                                <input type="submit"
                                                       name="saveEntry"
                                                       class="btn" 
                                                       value="<?=$this->getTrans('submit') ?>" />
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </section>
                    </form>
                <?php endif; ?>

                <section class="comment-list">
                    <?php foreach($comments as $comment): ?>
                        <?php if($this->getRequest()->getParam('id') == $comment->getFKId()): ?>
                            <?php $date = new \Ilch\Date($comment->getDateCreated()); ?>
                            <?php $userMapper = new \Modules\User\Mappers\User(); ?>
                            <?php $user = $userMapper->getUserById($comment->getUserId()); ?>
                            <?php $commentDate = new \Ilch\Date($comment->getDateCreated()); ?>
                            <article class="row" id="<?=$comment->getId() ?>">
                                <div class="col-md-2 col-sm-2 hidden-xs">
                                    <figure class="thumbnail">
                                        <a href="<?=$this->getUrl('user/profil/index/user/'.$user->getId()) ?>"><img class="img-responsive" src="<?=$this->getUrl().'/'.$user->getAvatar() ?>" alt="<?=$this->escape($user->getName()) ?>"></a>
                                    </figure>
                                </div>
                                <div class="col-md-10 col-sm-10">
                                    <div class="panel panel-default arrow left">
                                        <div class="panel-bodylist">
                                            <header class="text-left">
                                                <div class="comment-user">
                                                    <i class="fa fa-user"></i> <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId())) ?>"><?=$this->escape($user->getName()) ?></a>
                                                </div>
                                                <time class="comment-date"><i class="fa fa-clock-o"></i> <?=$commentDate->format("d.m.Y - H:i", true) ?></time>
                                            </header>		
                                            <div class="comment-post"><p><?=nl2br($this->escape($comment->getText())) ?></p></div>
                                            <p class="text-right"><a href="<?=$this->getUrl(array('module' => 'comment', 'action' => 'index', 'id' => $comment->getId())) ?>" class="btn btn-default btn-sm"><i class="fa fa-reply"></i> <?=$this->getTrans('reply') ?></a></p>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </section>  
            </div>
        </div>
    <?php endif; ?> 
<?php endif; ?>
