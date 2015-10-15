<legend><?=$this->getTrans('folderRights') ?></legend>
<table class="table table-hover">
    <colgroup>
        <col />
        <col class="col-lg-2">
        <col class="col-lg-2">
    </colgroup>
    <tr>
        <th><?=$this->getTrans('folder') ?></th>
        <th><?=$this->getTrans('required') ?></th>
        <th><?=$this->getTrans('available') ?></th>
    </tr>
    <tr>
        <td>"/application/"</td>
        <td class="text-success"><?=$this->getTrans('writable') ?></td>
        <td>
            <?php if (is_writable(CONFIG_PATH)): ?>
                <span class="text-success"><?=$this->getTrans('writable') ?></span>
            <?php else: ?>
                <span class="text-danger"><?=$this->getTrans('notWritable') ?></span>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td>"/application/modules/media/static/upload/"</td>
        <td class="text-success"><?=$this->getTrans('writable') ?></td>
        <td>
            <?php if (is_writable(APPLICATION_PATH.'/modules/media/static/upload/')): ?>
                <span class="text-success"><?=$this->getTrans('writable') ?></span>
            <?php else: ?>
                <span class="text-danger"><?=$this->getTrans('notWritable') ?></span>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td>"/application/modules/user/static/upload/avatar/"</td>
        <td class="text-success"><?=$this->getTrans('writable') ?></td>
        <td>
            <?php if (is_writable(APPLICATION_PATH.'/modules/user/static/upload/avatar/')): ?>
                <span class="text-success"><?=$this->getTrans('writable') ?></span>
            <?php else: ?>
                <span class="text-danger"><?=$this->getTrans('notWritable') ?></span>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td>"/application/modules/events/static/upload/image/"</td>
        <td class="text-success"><?=$this->getTrans('writable') ?></td>
        <td>
            <?php if (is_writable(APPLICATION_PATH.'/modules/events/static/upload/image/')): ?>
                <span class="text-success"><?=$this->getTrans('writable') ?></span>
            <?php else: ?>
                <span class="text-danger"><?=$this->getTrans('notWritable') ?></span>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td>"/.htaccess"</td>
        <td class="text-success"><?=$this->getTrans('writable') ?></td>
        <td>
            <?php if (is_writable(APPLICATION_PATH.'/../.htaccess')): ?>
                <span class="text-success"><?=$this->getTrans('writable') ?></span>
            <?php else: ?>
                <span class="text-danger"><?=$this->getTrans('notWritable') ?></span>
            <?php endif; ?>
        </td>
    </tr>
</table>
