<?php
$comments = $this->get('comments');
$article = $this->get('article');
$content = str_replace('[PREVIEWSTOP]', '', $article->getContent());
echo '<h4>'.$article->getTitle().'</h4>';
echo '<br />';

echo $content;
echo '<hr />';
echo '<h5>'.$this->getTrans('comments').' ('.count($comments).')</h5>';

if($this->getUser())
{
?>
<form action="" method="POST" class="form-horizontal">
    <?php echo $this->getTokenField(); ?>
    <div class="form-group">
        <div class="col-lg-12">
            <textarea class="form-control" name="article_comment_text" required></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-10 control-label"></label>
        <div class="col-lg-2">
           <input type="submit" 
               name="saveEntry" 
               class="btn btn-small btn-primary pull-right" 
               value="<?php echo $this->getTrans('submit'); ?>" />
        </div>
    </div>
</form>
<?php
}

$userMapper = new \User\Mappers\User();

foreach($comments as $comment)
{
    $user = $userMapper->getUserById($comment->getUserId());
    echo '<a href="'.$this->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId())).'">'.$this->escape($user->getName()).'</a>';
    echo '<br /><hr />';
    echo nl2br($this->escape($comment->getText()));
    echo '<br /><br />';
}
