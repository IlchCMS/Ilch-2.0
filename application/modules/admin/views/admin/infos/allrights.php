<h1><?=$this->getTrans('allRights') ?></h1>
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col>
            <col class="col-lg-2">
        </colgroup>
        <thead>
            <tr>
                <th><?=$this->getTrans('folder') ?></th>
                <th><?=$this->getTrans('available') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($this->get('results') as $result) : ?>
            <tr>
                <td><?=$result['path'] ?></td>
                <td>
                    <?php if ($result['writable']): ?>
                        <span class="text-success"><?=$this->getTrans('writable') ?></span>
                    <?php else: ?>
                        <span class="text-danger"><?=$this->getTrans('notWritable') ?></span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
