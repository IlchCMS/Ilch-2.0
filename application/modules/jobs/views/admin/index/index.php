<legend><?=$this->getTrans('manage') ?></legend>
<?php if ($this->get('jobs') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField()?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-lg-2">
                    <col />
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_entries') ?></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('title') ?></th>
                        <th><?=$this->getTrans('email') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('jobs') as $jobs) : ?>
                        <tr>
                            <td><input type="checkbox" name="check_entries[]" value="<?=$jobs->getId() ?>" /></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $jobs->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $jobs->getId()]) ?></td>
                            <td>
                                <?php if ($jobs->getShow() == 1): ?>
                                    <a href="<?=$this->getUrl(['action' => 'update', 'id' => $jobs->getId()], null, true) ?>">
                                        <span class="fa fa-check-square-o text-info"></span>
                                    </a>
                                <?php else: ?>
                                    <a href="<?=$this->getUrl(['action' => 'update', 'id' => $jobs->getId()], null, true) ?>">
                                        <span class="fa fa-square-o text-info"></span>
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td><?=$this->escape($jobs->getTitle()) ?></td>
                            <td><?=$this->escape($jobs->getEmail()) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noJobs') ?>
<?php endif; ?>
