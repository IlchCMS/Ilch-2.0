<?php
/** @var \Ilch\View $this */

/** @var Modules\Vote\Models\Vote[]|null $votes */
$votes = $this->get('votes');

/** @var Modules\Vote\Mappers\Result $resultMapper */
$resultMapper = $this->get('resultMapper');
?>
<h1><?=$this->getTrans('manage') ?></h1>
<?php if ($votes) : ?>
<form method="POST">
    <?=$this->getTokenField() ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="icon_width" />
                <col class="icon_width" />
                <col class="icon_width" />
                <col class="icon_width" />
                <col class="icon_width" />
                <col />
                <col class="icon_width" />
            </colgroup>
            <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_vote') ?></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th><?=$this->getTrans('question') ?></th>
                    <th><?=$this->getTrans('reply') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($votes as $vote) : ?>
                <tr>
                    <td><label><input value="<?=$vote->getId() ?>" type="checkbox" name="check_vote[]" /></label></td>
                    <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $vote->getId()]) ?></td>
                    <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $vote->getId()]) ?></td>
                    <td>
                        <a href="<?=$this->getUrl(['action' => 'lock', 'id' => $vote->getId()], null, true) ?>" title="<?=$vote->getStatus() ? $this->getTrans('unlock') : $this->getTrans('lock') ?>">
                            <span class="fa-solid fa-lock<?=!$vote->getStatus() ? '-open' : '' ?>"></span>
                        </a>
                    </td>
                    <td>
                        <a href="<?=$this->getUrl(['action' => 'reset', 'id' => $vote->getId()], null, true) ?>" title="<?=$this->getTrans('reset') ?>">
                            <span class="fa-solid fa-arrows-rotate text-primary"></span>
                        </a>
                    </td>
                    <td><?=$this->escape($vote->getQuestion()) ?></td>
                    <td><?=count($resultMapper->getVoteRes($vote->getId()) ?? []) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?=$this->getListBar(['delete' => 'delete']) ?>
</form>
<?php else : ?>
    <?=$this->getTrans('noVote') ?>
<?php endif; ?>
