<?php

/** @var \Ilch\View $this */

/** @var Modules\Vote\Mappers\Vote $voteMapper */
$voteMapper = $this->get('voteMapper');
/** @var Modules\Vote\Mappers\Result $resultMapper */
$resultMapper = $this->get('resultMapper');
/** @var Modules\Vote\Mappers\Ip $ipMapper */
$ipMapper = $this->get('ipMapper');

/** @var Modules\Vote\Models\Vote[]|null $votes */
$votes = $this->get('votes');

/** @var array $groupIds */
$groupIds = $this->get('readAccess');

/** @var string $clientIP */
if (!isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $clientIP = $_SERVER['REMOTE_ADDR'];
} else {
    $clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
}
?>

<link href="<?=$this->getBoxUrl('static/css/vote.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('css/bootstrap-progressbar-3.3.4.min.css') ?>" rel="stylesheet">

<?php if ($votes) :
    $userId = null;

    if ($this->getUser()) {
        $userId = $this->getUser()->getId();
    }
    ?>
    <?php foreach ($votes as $groupVote) : ?>
            <div class="row">
                <div class="col-lg-12">
                    <form action="<?=$this->getUrl(['module' => 'vote']) ?>" class="form-horizontal" method="POST">
                        <?=$this->getTokenField() ?>
                        <div class="card border-primary">
                            <div class="card-header bg-primary">
                                <h6 class="card-title"><?=$this->escape($groupVote->getQuestion()) ?></h6>
                            </div>
                            <?php
                            $voteRes = $resultMapper->getVoteRes($groupVote->getId());
                            $ip = $ipMapper->getIP($groupVote->getId(), $clientIP);
                            $votedUser = $ipMapper->getVotedUser($groupVote->getId(), $userId);
                            if ($groupVote->getGroups() == 'all') {
                                $groupIds[] = 'all';
                            }
                            ?>
                            <?php if ($ip || $votedUser || $groupVote->getStatus() != 0 || !is_in_array(explode(',', $groupVote->getGroups()), array_merge($groupIds, ($groupVote->getGroups() == 'all' ? ['all'] : [])))) : ?>
                                <div class="vote-body">
                                    <div class="list-group">
                                        <?php foreach ($voteRes as $voteResModel) : ?>
                                            <?php $result = $resultMapper->getResultByIdAndReply($groupVote->getId(), $voteResModel->getReply()); ?>
                                            <?php $totalResult = $resultMapper->getResultById($groupVote->getId()); ?>
                                            <?php if ($result != 0 && $totalResult != 0) : ?>
                                                <?php $percent = $resultMapper->getPercent($result, $totalResult); ?>
                                            <?php else : ?>
                                                <?php $percent = 0; ?>
                                            <?php endif; ?>
                                            <div class="list-group-item">
                                                <?=$this->escape($voteResModel->getReply()) ?> (<?=$result ?>)
                                                <div class="radio">
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$percent ?>"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php else : ?>
                                <div class="vote-body">
                                    <div class="list-group">
                                        <input type="hidden" name="id" value="<?=$groupVote->getId() ?>" />
                                        <?php foreach ($voteRes as $voteResModel) : ?>
                                            <div class="list-group-item">
                                                <?php if ($groupVote->getMultipleReply()) : ?>
                                                    <div class="checkbox">
                                                        <label for="box_<?=$this->escape($voteResModel->getReply()) ?>">
                                                            <input type="checkbox"
                                                                   name="reply[]"
                                                                   id="box_<?=$this->escape($voteResModel->getReply()) ?>"
                                                                   value="<?=$this->escape($voteResModel->getReply()) ?>"> <?=$this->escape($voteResModel->getReply()) ?>
                                                        </label>
                                                    </div>
                                                <?php else : ?>
                                                    <div class="radio">
                                                        <label for="box_<?=$this->escape($voteResModel->getReply()) ?>">
                                                            <input type="radio"
                                                                   name="reply[]"
                                                                   id="box_<?=$this->escape($voteResModel->getReply()) ?>"
                                                                   value="<?=$this->escape($voteResModel->getReply()) ?>" /> <?=$this->escape($voteResModel->getReply()) ?>
                                                        </label>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                        <div class="card-footer">
                                            <?=$this->getSaveBar('voteButton', 'Vote') ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
    <?php endforeach; ?>
<?php else : ?>
    <?=$this->getTrans('noVote') ?>
<?php endif; ?>

<script src="<?=$this->getStaticUrl('js/bootstrap-progressbar.js') ?>"></script>
<script>
$(document).ready(function() {
    $('.progress .progress-bar').progressbar();
});
</script>
