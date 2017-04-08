<?php
$vote = $this->get('vote');
$userMapper = new Modules\User\Mappers\User();
$voteMapper = new Modules\Vote\Mappers\Vote();
$resultMapper = new \Modules\Vote\Mappers\Result();
$ipMapper = new \Modules\Vote\Mappers\Ip();

if (!isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $clientIP = $_SERVER['REMOTE_ADDR'];
} else {
    $clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
}
?>

<link href="<?=$this->getBoxUrl('static/css/vote.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('css/bootstrap-progressbar-3.3.4.css') ?>" rel="stylesheet">

<?php if ($vote != '' ):
    $userId = null;
    $groupIds = [0];
    $admin = FALSE;
    $i = '';

    if ($this->getUser()) {
        $admin = $this->getUser()->isAdmin();
        $userId = $this->getUser()->getId();
        $user = $userMapper->getUserById($userId);

        $groupIds = [];
        foreach ($user->getGroups() as $groups) {
            $groupIds[] = $groups->getId();
        }
    }
    ?>
    <?php foreach ($vote as $groupVote): ?>
        <?php if(in_array($groupVote->getGroup(), $groupIds) || $admin == TRUE): ?>
            <div class="row">
                <div class="col-lg-12">
                    <form action="<?=$this->getUrl(['module' => 'vote']) ?>" class="form-horizontal" method="POST">
                        <?=$this->getTokenField() ?>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h4 class="panel-title"><?=$this->escape($groupVote->getQuestion()) ?></h4>
                            </div>
                            <?php $voteRes = $resultMapper->getVoteRes($groupVote->getId()); ?>
                            <?php $ip = $ipMapper->getIP($groupVote->getId(), $clientIP); ?>
                            <?php if ($ip != '' OR $groupVote->getStatus() != 0): ?>
                                <div class="vote-body">
                                    <div class="list-group">
                                        <?php foreach ($voteRes as $voteRes): ?>
                                            <?php $result = $resultMapper->getResultByIdAndReply($groupVote->getId(), $voteRes->getReply()); ?>
                                            <?php $totalResult = $resultMapper->getResultById($groupVote->getId()); ?>
                                            <?php if ($result != 0 AND $totalResult != 0): ?>
                                                <?php $percent = $resultMapper->getPercent($result, $totalResult); ?>>
                                            <?php else: ?>
                                                <?php $percent = 0; ?>
                                            <?php endif; ?>
                                            <div class="list-group-item">
                                                <?=$this->escape($voteRes->getReply()) ?> (<?=$result ?>)
                                                <div class="radio">
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$percent ?>"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="vote-body">
                                    <div class="list-group">
                                        <?php foreach ($voteRes as $voteRes): ?>
                                            <div class="list-group-item">
                                                <div class="radio">
                                                    <label for="box_<?=$this->escape($voteRes->getReply()) ?>">
                                                        <input type="radio"
                                                               name="reply"
                                                               id="box_<?=$this->escape($voteRes->getReply()) ?>"
                                                               value="<?=$this->escape($voteRes->getReply()) ?>" /> <?=$this->escape($voteRes->getReply()) ?>
                                                    </label>
                                                    <input type="hidden" name="id" value="<?=$groupVote->getId() ?>" />
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                        <div class="panel-footer">
                                            <?=$this->getSaveBar('voteButton', 'Vote') ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
            <?php $i++; ?>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if ($i < '1'): ?>
        <?=$this->getTrans('noVote') ?>
    <?php endif; ?>
<?php else: ?>
    <?=$this->getTrans('noVote') ?>
<?php endif; ?>

<script type="text/javascript" src="<?=$this->getStaticUrl('js/bootstrap-progressbar.js') ?>"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.progress .progress-bar').progressbar();
});
</script>
