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
<form action="" method="POST">
    <?php echo $this->getTokenField(); ?>
    <div class="ilch_form_group">
        <textarea class="ilch_full_width"
                  name="article_comment_text"
                  required></textarea>
    </div>
    <div class="ilch_form_group">
        <input type="submit"
           name="saveEntry"
           class="ilch_pull_right ilch_btn" 
           value="<?php echo $this->getTrans('submit'); ?>" />
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
