<legend><?=$this->getTrans('manage') ?></legend>
<?php if ($this->get('privacys') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col />
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_privacys') ?></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('title') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('privacys') as $privacy): ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_privacys', $privacy->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $privacy->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $privacy->getId()]) ?></td>
                            <td>
                                <?php if ($privacy->getShow() == 1): ?>
                                    <a href="<?=$this->getUrl(['action' => 'update', 'id' => $privacy->getId()], null, true) ?>">
                                        <span class="fa fa-check-square-o text-info"></span>
                                    </a>
                                <?php else: ?>
                                    <a href="<?=$this->getUrl(['action' => 'update', 'id' => $privacy->getId()], null, true) ?>">
                                        <span class="fa fa-square-o text-info"></span>
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td><?=$privacy->getTitle() ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noPrivacy') ?>
<?php endif; ?>
