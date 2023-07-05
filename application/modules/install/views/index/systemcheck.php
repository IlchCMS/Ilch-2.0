<?php

/** @var \Ilch\View $this */

/** @var array $errors */
$errors = $this->get('errors')
?>
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th><a href="" title="<?=$this->getTrans('checkReload') ?>"><i class="fa-solid fa-arrows-rotate"></i></a></th>
                <th><?=$this->getTrans('required') ?></th>
                <th><?=$this->getTrans('available') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?=$this->getTrans('phpVersion') ?></td>
                <td class="text-success">>= <?=$this->get('phpVersion') ?></td>
                <td class="<?=!($errors['phpVersion'] ?? false) ? 'text-success' : 'text-danger' ?>">
                    <?=PHP_VERSION ?>
                </td>
            </tr>
            <tr>
                <td><?=$this->getTrans('dbVersion') ?> (<?=$this->get('dbServerInfo') ?>)</td>
                <td class="text-success">>= <?=$this->get('requiredVersion') ?></td>
                <td class="<?=!($errors['dbVersion'] ?? false) ? 'text-success' : 'text-danger' ?>">
                    <?=$this->get('dbVersion') ?>
                </td>
            </tr>
            <tr>
                <td>PHP-<?=$this->getTrans('extension') ?> cURL</td>
                <td class="text-success"><?=$this->getTrans('existing') ?>
                <td>
                    <span class="<?=!($errors['curlExtension'] ?? false) ? 'text-success' : 'text-danger' ?>"><?=$this->getTrans(!($errors['curlExtension'] ?? false) ? 'existing' : 'missing') ?></span>
                </td>
            </tr>
            <tr>
                <td>PHP-<?=$this->getTrans('extension') ?> gd</td>
                <td class="text-success"><?=$this->getTrans('existing') ?>
                <td>
                    <span class="<?=!($errors['gdExtension'] ?? false) ? 'text-success' : 'text-danger' ?>"><?=$this->getTrans(!($errors['gdExtension'] ?? false) ? 'existing' : 'missing') ?></span>
                </td>
            </tr>
            <tr>
                <td>PHP-<?=$this->getTrans('extension') ?> Multibyte String (mbstring)</td>
                <td class="text-success"><?=$this->getTrans('existing') ?>
                <td>
                    <span class="<?=!($errors['mbstringExtension'] ?? false) ? 'text-success' : 'text-danger' ?>"><?=$this->getTrans(!($errors['mbstringExtension'] ?? false) ? 'existing' : 'missing') ?></span>
                </td>
            </tr>
            <tr>
                <td>PHP-<?=$this->getTrans('extension') ?> MySQLi (mysqli)</td>
                <td class="text-success"><?=$this->getTrans('existing') ?>
                <td>
                    <span class="<?=!($errors['mysqliExtension'] ?? false) ? 'text-success' : 'text-danger' ?>"><?=$this->getTrans(!($errors['mysqliExtension'] ?? false) ? 'existing' : 'missing') ?></span>
                </td>
            </tr>
            <tr>
                <td>PHP-<?=$this->getTrans('extension') ?> OpenSSL</td>
                <td class="text-success"><?=$this->getTrans('existing') ?>
                <td>
                    <span class="<?=!($errors['opensslExtension'] ?? false) ? 'text-success' : 'text-danger' ?>"><?=$this->getTrans(!($errors['opensslExtension'] ?? false) ? 'existing' : 'missing') ?></span>
                </td>
            </tr>
            <tr>
                <td>PHP-<?=$this->getTrans('extension') ?> Zip</td>
                <td class="text-success"><?=$this->getTrans('existing') ?>
                <td>
                    <span class="<?=!($errors['zipExtension'] ?? false) ? 'text-success' : 'text-danger' ?>"><?=$this->getTrans(!($errors['zipExtension'] ?? false) ? 'existing' : 'missing') ?></span>
                </td>
            </tr>
            <tr>
                <td><?=$this->getTrans('certificate') ?></td>
                <td class="text-success"><?=$this->getTrans('valid') ?></td>
                <td>
                    <?php if (!($errors['opensslExtension'] ?? false)) : ?>
                        <?php if (!($errors['missingCert'] ?? false)) : ?>
                            <?php if (!($errors['expiredCert'] ?? false)) : ?>
                                <span class="text-success"><?=$this->getTrans('valid') ?></span>
                            <?php else : ?>
                                <span class="text-danger"><?=$this->getTrans('expired') ?></span>
                            <?php endif; ?>
                        <?php else : ?>
                            <span class="text-danger"><?=$this->getTrans('missing') ?></span>
                        <?php endif; ?>
                    <?php else : ?>
                        <span class="text-danger"><?=$this->getTrans('unknown') ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>"/"</td>
                <td class="text-success"><?=$this->getTrans('writable') ?></td>
                <td>
                    <span class="<?=!($errors['writableRootPath'] ?? false) ? 'text-success' : 'text-danger' ?>"><?=$this->getTrans(!($errors['writableRootPath'] ?? false) ? 'writable' : 'notWritable') ?></span>
                </td>
            </tr>
            <tr>
                <td>"/.htaccess"</td>
                <td class="text-success"><?=$this->getTrans('writable') ?></td>
                <td>
                    <span class="<?=!($errors['writableHtaccess'] ?? false) ? 'text-success' : 'text-danger' ?>"><?=$this->getTrans(!($errors['writableHtaccess'] ?? false) ? 'writable' : 'notWritable') ?></span>
                </td>
            </tr>
            <tr>
                <td>"/updates/"</td>
                <td class="text-success"><?=$this->getTrans('writable') ?></td>
                <td>
                    <span class="<?=!($errors['writableUpdates'] ?? false) ? 'text-success' : 'text-danger' ?>"><?=$this->getTrans(!($errors['writableUpdates'] ?? false) ? 'writable' : 'notWritable') ?></span>
                </td>
            </tr>
            <tr>
                <td>"/certificate/"</td>
                <td class="text-success"><?=$this->getTrans('writable') ?></td>
                <td>
                    <span class="<?=!($errors['writableCertificate'] ?? false) ? 'text-success' : 'text-danger' ?>"><?=$this->getTrans(!($errors['writableCertificate'] ?? false) ? 'writable' : 'notWritable') ?></span>
                </td>
            </tr>
            <tr>
                <td>"/backups/"</td>
                <td class="text-success"><?=$this->getTrans('writable') ?></td>
                <td>
                    <span class="<?=!($errors['writableBackups'] ?? false) ? 'text-success' : 'text-danger' ?>"><?=$this->getTrans(!($errors['writableBackups'] ?? false) ? 'writable' : 'notWritable') ?></span>
                </td>
            </tr>
            <tr>
                <td>"/application/"</td>
                <td class="text-success"><?=$this->getTrans('writable') ?></td>
                <td>
                    <span class="<?=!($errors['writableConfig'] ?? false) ? 'text-success' : 'text-danger' ?>"><?=$this->getTrans(!($errors['writableConfig'] ?? false) ? 'writable' : 'notWritable') ?></span>
                </td>
            </tr>
            <tr>
                <td>"/application/modules/media/static/upload/"</td>
                <td class="text-success"><?=$this->getTrans('writable') ?></td>
                <td>
                    <span class="<?=!($errors['writableMedia'] ?? false) ? 'text-success' : 'text-danger' ?>"><?=$this->getTrans(!($errors['writableMedia'] ?? false) ? 'writable' : 'notWritable') ?></span>
                </td>
            </tr>
            <tr>
                <td>"/application/modules/user/static/upload/avatar/"</td>
                <td class="text-success"><?=$this->getTrans('writable') ?></td>
                <td>
                    <span class="<?=!($errors['writableAvatar'] ?? false) ? 'text-success' : 'text-danger' ?>"><?=$this->getTrans(!($errors['writableAvatar'] ?? false) ? 'writable' : 'notWritable') ?></span>
                </td>
            </tr>
            <tr>
                <td>"/application/modules/user/static/upload/gallery/"</td>
                <td class="text-success"><?=$this->getTrans('writable') ?></td>
                <td>
                    <span class="<?=!($errors['writableGallery'] ?? false) ? 'text-success' : 'text-danger' ?>"><?=$this->getTrans(!($errors['writableGallery'] ?? false) ? 'writable' : 'notWritable') ?></span>
                </td>
            </tr>
        </tbody>
    </table>
</div>
