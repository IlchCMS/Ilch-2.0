<h1><?=$this->getTrans('phpExtensions') ?></h1>
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col>
            <col class="col-xl-2">
            <col class="col-xl-2">
        </colgroup>
        <thead>
            <tr>
                <th><?=$this->getTrans('phpExtensions') ?></th>
                <th><?=$this->getTrans('required') ?></th>
                <th><?=$this->getTrans('available') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>gd</td>
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
                <td>cURL</td>
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
                <td>Multibyte String (mbstring)</td>
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
                <td>MySQLi (mysqli)</td>
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
                <td>OpenSSL</td>
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
                <td>Zip</td>
                <td class="text-success"><?=$this->getTrans('existing') ?>
                <td>
                    <?php if (extension_loaded('zip')): ?>
                        <span class="text-success"><?=$this->getTrans('existing') ?></span>
                    <?php else: ?>
                        <span class="text-danger"><?=$this->getTrans('missing') ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php if ($this->get('phpExtensions') != ''): ?>
                <?php foreach ($this->get('phpExtensions') as $phpExtension): ?>
                    <tr>
                        <td><?=$phpExtension->getExtension() ?></td>
                        <td class="text-success"><?=$this->getTrans('existing') ?></td>
                        <td>
                            <?php if (extension_loaded($phpExtension->getExtension())): ?>
                                <span class="text-success"><?=$this->getTrans('existing') ?></span>
                            <?php else: ?>
                                <span class="text-danger"><?=$this->getTrans('missing') ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
