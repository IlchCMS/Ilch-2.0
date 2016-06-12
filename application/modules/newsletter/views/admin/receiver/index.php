<legend><?=$this->getTrans('receiver') ?></legend>
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col class="col-lg-2" />
            <col />
        </colgroup>
        <thead>
            <tr>
                <th><?=$this->getTrans('userName') ?></th>
                <th><?=$this->getTrans('userEmail') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if ($this->get('emails') != ''): ?>
                <?php foreach ($this->get('userList') as $user): ?>
                    <tr>
                        <td><?=$user['name']; ?></td>
                        <td><?=($user['email'] === '') ? 'NotRegistUser' : $user['email']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2"><?=$this->getTrans('noEmails') ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
