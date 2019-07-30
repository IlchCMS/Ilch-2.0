<?php
$comment = $this->get('comment');
?>

<h1>Edit Comment</h1>
<form action="<?php echo $this->getUrl(['module' => 'bugtracker', 'controller' => 'index', 'action' => 'saveComment', 'comment-id' => $comment->getID()]); ?>" method="post">
    <?php echo $this->getTokenField(); ?>
    <div class="form-group">
        <label for="comment">Comment:</label>
        <textarea class='form-control ckeditor' name='content' toolbar='ilch_bbcode' required>
            <?php echo $comment->getContent();?>
        </textarea>
    </div>
    <div class="checkbox">
        <label>
            <input type="checkbox" name="intern-only" value="1" <?php echo (($comment->isInternOnly()) ? 'checked' : ''); ?> />Intern only
        </label>
    </div>
    <button type="submit" class="btn btn-success">Save</button>
</form>

