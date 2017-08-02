<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th><?php if (!empty($this->get('errors'))) { echo '<a href="" title="'.$this->getTrans('checkReload').'"><i class="fa fa-refresh"></i></a>'; } ?></th>
                <th><?=$this->getTrans('required') ?></th>
                <th><?=$this->getTrans('available') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?=$this->getTrans('phpVersion') ?></td>
                <td class="text-success">>= 5.6.0</td>
                <td class="<?php if (version_compare(phpversion(), '5.6.0', '>=')): ?>
                                text-success
                            <?php else: ?>
                                text-danger
                            <?php endif; ?>">
                    <?=$this->get('phpVersion') ?>
                </td>
            </tr>
            <tr>
                <td>PHP-<?=$this->getTrans('extension') ?> cURL</td>
                <td class="text-success"><?=$this->getTrans('existing') ?>
                <td>
                    <?php if (extension_loaded('curl')): ?>
                        <span class="text-success"><?=$this->getTrans('existing') ?></span>
                    <?php else: ?>
                        <span class="text-danger"><?=$this->getTrans('missing') ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>PHP-<?=$this->getTrans('extension') ?> gd</td>
                <td class="text-success"><?=$this->getTrans('existing') ?>
                <td>
                    <?php if (extension_loaded('gd')): ?>
                        <span class="text-success"><?=$this->getTrans('existing') ?></span>
                    <?php else: ?>
                        <span class="text-danger"><?=$this->getTrans('missing') ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>PHP-<?=$this->getTrans('extension') ?> Multibyte String (mbstring)</td>
                <td class="text-success"><?=$this->getTrans('existing') ?>
                <td>
                    <?php if (extension_loaded('mbstring')): ?>
                        <span class="text-success"><?=$this->getTrans('existing') ?></span>
                    <?php else: ?>
                        <span class="text-danger"><?=$this->getTrans('missing') ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>PHP-<?=$this->getTrans('extension') ?> MySQLi (mysqli)</td>
                <td class="text-success"><?=$this->getTrans('existing') ?>
                <td>
                    <?php if (extension_loaded('mysqli')): ?>
                        <span class="text-success"><?=$this->getTrans('existing') ?></span>
                    <?php else: ?>
                        <span class="text-danger"><?=$this->getTrans('missing') ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>PHP-<?=$this->getTrans('extension') ?> OpenSSL</td>
                <td class="text-success"><?=$this->getTrans('existing') ?>
                <td>
                    <?php if (extension_loaded('openssl')): ?>
                        <span class="text-success"><?=$this->getTrans('existing') ?></span>
                    <?php else: ?>
                        <span class="text-danger"><?=$this->getTrans('missing') ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>PHP-<?=$this->getTrans('extension') ?> Zip</td>
                <td class="text-success"><?=$this->getTrans('existing') ?>
                <td>
                    <?php if (extension_loaded('zip')): ?>
                        <span class="text-success"><?=$this->getTrans('existing') ?></span>
                    <?php else: ?>
                        <span class="text-danger"><?=$this->getTrans('missing') ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><?=$this->getTrans('certificate') ?></td>
                <td class="text-success"><?=$this->getTrans('valid') ?></td>
                <td>
                    <?php if (file_exists(ROOT_PATH.'/certificate/Certificate.crt')): ?>
                        <?php if (extension_loaded('openssl')): ?>
                            <?php $public_key = file_get_contents(ROOT_PATH.'/certificate/Certificate.crt');
                            $certinfo = openssl_x509_parse($public_key);
                            $validTo = $certinfo['validTo_time_t'];
                            if ($validTo < time()): ?>
                                <span class="text-danger"><?=$this->getTrans('expired') ?></span>
                            <?php else: ?>
                                <span class="text-success"><?=$this->getTrans('valid') ?></span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-danger"><?=$this->getTrans('unknown') ?></span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="text-danger"><?=$this->getTrans('missing') ?></span>
                    <?php endif; ?>
                </td>
            </tr>
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
                    <?php if (is_writable(APPLICATION_PATH)): ?>
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
        </tbody>
    </table>
</div>
