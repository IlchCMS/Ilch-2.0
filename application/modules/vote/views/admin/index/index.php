<h1><?=$this->getTrans('manage') ?></h1>
<?php if ($this->get('vote') != ''): ?>
    <form class="form-horizontal" method="POST">
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
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_vote') ?></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('question') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('vote') as $vote): ?>
                        <tr>
                            <td><input value="<?=$vote->getId() ?>" type="checkbox" name="check_vote[]" /></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $vote->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $vote->getId()]) ?></td>
                            <td>
                                <?php if ($vote->getStatus() == 1): ?>
                                    <a href="<?=$this->getUrl(['action' => 'lock', 'id' => $vote->getId()], null, true) ?>" title="<?=$this->getTrans('unlock') ?>">
                                        <span class="fa fa-lock"></span>
                                    </a>
                                <?php else: ?>
                                    <a href="<?=$this->getUrl(['action' => 'lock', 'id' => $vote->getId()], null, true) ?>" title="<?=$this->getTrans('lock') ?>">
                                        <span class="fa fa-unlock te"></span>
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?=$this->getUrl(['action' => 'reset', 'id' => $vote->getId()], null, true) ?>" title="<?=$this->getTrans('reset') ?>">
                                    <span class="fa fa-refresh text-primary"></span>
                                </a>
                            </td>
                            <td><?=$this->escape($vote->getQuestion()) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noVote') ?>
<?php endif; ?>
