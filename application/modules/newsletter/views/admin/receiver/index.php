<h1><?=$this->getTrans('receiver') ?></h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <?php if ($this->get('emails') != ''): ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-xl-2">
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_entries') ?></th>
                        <th></th>
                        <th><?=$this->getTrans('userName') ?></th>
                        <th><?=$this->getTrans('userEmail') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('userList') as $user): ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_entries', $user['email']) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'delete', 'selector' => $user['selector']]) ?> </td>
                            <td><?=$this->escape($user['name']) ?></td>
                            <td><?=($user['email'] === '') ? 'NotRegistUser' : $this->escape($user['email']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    <?php else: ?>
        <?=$this->getTrans('noEmails') ?>
    <?php endif; ?>
</form>
