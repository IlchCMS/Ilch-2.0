<legend><?=$this->getTrans('folderRights') ?></legend>
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col>
            <col class="col-lg-2">
            <col class="col-lg-2">
        </colgroup>
        <thead>
            <tr>
                <th><?=$this->getTrans('folder') ?></th>
                <th><?=$this->getTrans('required') ?></th>
                <th><?=$this->getTrans('available') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>"/"</td>
                <td class="text-success"><?=$this->getTrans('writable') ?></td>
                <td>
                    <?php if (is_writable(ROOT_PATH)): ?>
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
                    <?php if (is_writable(ROOT_PATH.'/.htaccess')): ?>
                        <span class="text-success"><?=$this->getTrans('writable') ?></span>
                    <?php else: ?>
                        <span class="text-danger"><?=$this->getTrans('notWritable') ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>"/updates/"</td>
                <td class="text-success"><?=$this->getTrans('writable') ?></td>
                <td>
                    <?php if (is_writable(ROOT_PATH.'/updates/')): ?>
                        <span class="text-success"><?=$this->getTrans('writable') ?></span>
                    <?php else: ?>
                        <span class="text-danger"><?=$this->getTrans('notWritable') ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>"/certificate/"</td>
                <td class="text-success"><?=$this->getTrans('writable') ?></td>
                <td>
                    <?php if (is_writable(ROOT_PATH.'/certificate/')): ?>
                        <span class="text-success"><?=$this->getTrans('writable') ?></span>
                    <?php else: ?>
                        <span class="text-danger"><?=$this->getTrans('notWritable') ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>"/backups/"</td>
                <td class="text-success"><?=$this->getTrans('writable') ?></td>
                <td>
                    <?php if (is_writable(ROOT_PATH.'/backups/')): ?>
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
                <td>"/application/modules/smilies/static/img/"</td>
                <td class="text-success"><?=$this->getTrans('writable') ?></td>
                <td>
                    <?php if (is_writable(APPLICATION_PATH.'/modules/smilies/static/img/')): ?>
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
            <?php if ($this->get('folderrights') != ''): ?>
                <?php foreach ($this->get('folderrights') as $folderright): ?>
                    <tr>
                        <td>"/application/modules/<?=$folderright->getKey() ?>/<?=$folderright->getFolder() ?>/"</td>
                        <td class="text-success"><?=$this->getTrans('writable') ?></td>
                        <td>
                            <?php if (is_writable(APPLICATION_PATH.'/modules/'.$folderright->getKey().'/'.$folderright->getFolder().'/')): ?>
                                <span class="text-success"><?=$this->getTrans('writable') ?></span>
                            <?php else: ?>
                                <span class="text-danger"><?=$this->getTrans('notWritable') ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
