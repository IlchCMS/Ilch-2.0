<?php 
$image = $this->get('image');
?>
<div id="gallery">
    <div class="row">
        <div class="col-md-6">
            <a href="<?php echo $this->getUrl().'/'.$image->getImageId(); ?>">
                <img class="thumbnail" src="<?php echo $this->getUrl().'/'.$image->getImageId(); ?>"/>
            </a>
        </div>
        <div class="col-md-6">
            <h3>Title</h3>
            <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
        </div>
    </div>
</div>
<?php
$comments = $this->get('comments');
if($this->getUser())
{
    echo '<hr />';
    echo '<h5>'.$this->getTrans('comments').' ('.count($comments).')</h5>';
?>
<form action="" class="form-horizontal" method="POST">
    <?php echo $this->getTokenField(); ?>
    <div class="form-group">
        <div class="col-lg-12">
            <textarea class="form-control"
                    name="gallery_comment_text"
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

$userMapper = new \User\Mappers\User();

foreach($comments as $comment)
{
    $user = $userMapper->getUserById($comment->getUserId());
    echo '<a href="'.$this->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId())).'">'.$this->escape($user->getName()).'</a>';
    echo '<br /><hr />';
    echo nl2br($this->escape($comment->getText()));
    echo '<br /><br />';
}
