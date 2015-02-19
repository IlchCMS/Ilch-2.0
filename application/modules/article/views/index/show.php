<?php
$comments = $this->get('comments');
$article = $this->get('article');
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
if(empty($preview))
{
echo '<h5>'.$this->getTrans('comments').' ('.count($comments).')</h5>';

    if($this->getUser())
    {
    ?>
    <form action="" class="form-horizontal" method="POST">
        <?php echo $this->getTokenField(); ?>
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
    <?php
    }

    $userMapper = new \Modules\User\Mappers\User();

    foreach($comments as $comment)
    {
        $user = $userMapper->getUserById($comment->getUserId());
        echo '<a href="'.$this->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId())).'">'.$this->escape($user->getName()).'</a>';
        echo '<br /><hr />';
        echo nl2br($this->escape($comment->getText()));
        echo '<br /><br />';
    }
}
