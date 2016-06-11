<legend><?=$this->getTrans('receiver') ?></legend>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
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
                <?php foreach ($this->get('userList') as $user): ?>
                    <tr>
                        <td><?=$user['name']; ?></td>
                        <td><?=($user['email'] === '') ? 'NotRegistUser' : $user['email']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</form>
