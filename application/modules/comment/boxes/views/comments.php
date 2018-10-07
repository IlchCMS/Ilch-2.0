<?php
$comments = $this->get('comments');
?>

<link href="<?=$this->getBoxUrl('static/css/comment.css') ?>" rel="stylesheet">

<?php if (!empty($comments)): ?>
    <div class="article-box">
        <ul class="list-unstyled">
            <?php foreach ($comments as $comment): ?>
                <?php $date = new \Ilch\Date($comment->getDateCreated()); ?>
                <li class="ellipsis" style="line-height: 25px;">
                    <span class="ellipsis-item">
                        <a href="<?=$this->getUrl($comment->getKey()) ?>">
                            <?=substr($this->escape($comment->getText()), 0, 25) ?>
                        </a>
                        <br>
                        <small><?=$date->format("d.m.y - H:i", true) ?> <?=$this->getTrans('clock') ?></small>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php else: ?>
    <?=$this->getTrans('noComments') ?>
<?php endif; ?>
