<?php

/** @var \Ilch\View $this */

/** @var Modules\Vote\Mappers\Result $resultMapper */
$resultMapper = $this->get('resultMapper');

/** @var Modules\Vote\Models\Vote[]|null $votes */
$votes = $this->get('votes');

/** @var array $groupIds */
$groupIds = $this->get('readAccess');

/** @var string $clientIP */
$clientIP = $this->get('clientIP');
?>

<link href="<?=$this->getBoxUrl('static/css/vote.css') ?>" rel="stylesheet">

<?php if ($votes) :
    foreach ($votes as $groupVote) :
        $voteRes = $resultMapper->getVoteRes($groupVote->getId());
        if ($voteRes) :
            $canVote = $groupVote->canVote($clientIP, $this->getUser(), $groupIds);
            ?>
            <div class="row">
                <div class="col-xl-12">
                    <form action="<?=$this->getUrl(['module' => 'vote']) ?>" method="POST">
                        <?=$this->getTokenField() ?>
                        <div class="card border-primary">
                            <div class="card-header bg-primary">
                                <h6 class="card-title"><?=$this->escape($groupVote->getQuestion()) ?></h6>
                            </div>

                            <div class="vote-body">
                                <div class="list-group">
                                <input type="hidden" name="id" value="<?=$groupVote->getId() ?>" />
                                <?php foreach ($voteRes as $voteResModel) : ?>
                                    <div class="list-group-item">
                                    <?php if (!$canVote) : ?>
                                        <?php $percent = $voteResModel->getPercent($resultMapper->getResultById($groupVote->getId())); ?>
                                        <?php $barLabel = $this->escape($voteResModel->getReply()) . ' (' . $voteResModel->getResult() . ')'; ?>
                                        <?=$barLabel ?>
                                        <div class="radio">
                                            <div class="progress" role="progressbar" aria-label="<?=$barLabel ?>" aria-valuenow="<?=$percent ?>" aria-valuemin="0" aria-valuemax="100">
                                                <div class="progress-bar" style="width: <?=$percent ?>%"></div>
                                            </div>
                                        </div>
                                    <?php else : ?>
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
                                    <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                                </div>
                            </div>
                            <?php if ($canVote) : ?>
                            <div class="card-footer">
                                <?=$this->getSaveBar('voteButton', 'Vote') ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php else : ?>
    <?=$this->getTrans('noVote') ?>
<?php endif; ?>
