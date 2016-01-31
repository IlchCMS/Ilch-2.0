<table class="table table-hover">
    <thead>
        <tr>
            <th></th>
            <th><?=$this->getTrans('required') ?></th>
            <th><?=$this->getTrans('available') ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?=$this->getTrans('phpVersion') ?></td>
            <td class="text-success">>= 5.4.0</td>
            <td class="<?php if (version_compare(phpversion(), '5.4.0', '>=')): ?>
                            text-success
                        <?php else: ?>
                            text-danger
                        <?php endif; ?>">
                <?=$this->get('phpVersion') ?>
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
            <td>"/application/modules/user/static/upload/gallery/"</td>
            <td class="text-success"><?=$this->getTrans('writable') ?></td>
            <td>
                <?php if (is_writable(APPLICATION_PATH.'/modules/user/static/upload/gallery/')): ?>
                    <span class="text-success"><?=$this->getTrans('writable') ?></span>
                <?php else: ?>
                    <span class="text-danger"><?=$this->getTrans('notWritable') ?></span>
                <?php endif; ?>
            </td>
        </tr>
    </tbody>
</table>
