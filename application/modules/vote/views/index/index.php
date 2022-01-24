<?php
$vote = $this->get('vote');
$voteMapper = $this->get('voteMapper');
$resultMapper = $this->get('resultMapper');
$ipMapper = $this->get('ipMapper');
$userMapper = $this->get('userMapper');

if (!isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $clientIP = $_SERVER['REMOTE_ADDR'];
} else {
    $clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
}
?>

<link href="<?=$this->getModuleUrl('static/css/vote.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('css/bootstrap-progressbar-3.3.4.min.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('menuVote') ?></h1>
<?php if ($vote != ''):
    $userId = null;
    $groupIds = [];
    $admin = false;
    $i = 0;

    if ($this->getUser()) {
        $admin = $this->getUser()->isAdmin();
        $userId = $this->getUser()->getId();
        $user = $userMapper->getUserById($userId);

        foreach ($user->getGroups() as $groups) {
            $groupIds[] = $groups->getId();
        }
    } else {
        $groupIds = [3];
    }
    ?>
    <?php foreach ($vote as $groupVote): ?>
        <?php if (is_in_array($this->get('readAccess'), explode(',', $groupVote->getReadAccess())) || $admin == true): ?>
            <div class="row">
                <div class="col-lg-12">
                    <form class="form-horizontal" method="POST">
                        <?=$this->getTokenField() ?>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h4 class="panel-title"><?=$this->escape($groupVote->getQuestion()) ?></h4>
                            </div>
                            <?php $voteRes = $resultMapper->getVoteRes($groupVote->getId()); ?>
                            <?php $ip = $ipMapper->getIP($groupVote->getId(), $clientIP); ?>
                            <?php $votedUser = $ipMapper->getVotedUser($groupVote->getId(), $userId); ?>
                            <?php (in_array('0', explode(',', $groupVote->getGroups()))) ? $groupIds[] = 0 : ''; ?>
                            <?php if ($ip != '' || $votedUser != '' || $groupVote->getStatus() != 0 || !is_in_array(explode(',', $groupVote->getGroups()), $groupIds)): ?>
                                <div class="vote-body">
                                    <div class="list-group">
                                        <?php foreach ($voteRes as $voteRes): ?>
                                            <?php $result = $resultMapper->getResultByIdAndReply($groupVote->getId(), $voteRes->getReply()); ?>
                                            <?php $totalResult = $resultMapper->getResultById($groupVote->getId()); ?>
                                            <?php if ($result != 0 && $totalResult != 0): ?>
                                                <?php $percent = $resultMapper->getPercent($result, $totalResult); ?>
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

<script src="<?=$this->getStaticUrl('js/bootstrap-progressbar.js') ?>"></script>
<script>
$(document).ready(function() {
    $('.progress .progress-bar').progressbar();
});
</script>
