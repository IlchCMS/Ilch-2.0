<?php
$modulesList = url_get_contents('http://ilch2.de/downloads/modules/list.php');
$modulesOnUpdateServer = json_decode($modulesList);
$versionsOfModules = $this->get('versionsOfModules');
?>

<link href="<?=$this->getModuleUrl('static/css/extsearch.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('search') ?></legend>
<?php
if (empty($modulesOnUpdateServer)) {
    echo $this->getTrans('noModulesAvailable');
    return;
}
?>

<div id="modules" class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col class="col-lg-2">
            <col>
        </colgroup>
        <thead>
            <tr>
                <th><?=$this->getTrans('modules') ?></th>
                <th><?=$this->getTrans('desc') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($modulesOnUpdateServer as $moduleOnUpdateServer):  ?>
                <?php
                if (!empty($moduleOnUpdateServer->phpextensions)) {
                    $extensionCheck = [];
                    foreach ($moduleOnUpdateServer->phpextensions as $extension) {
                        $extensionCheck[] = extension_loaded($extension);
                    }

                    $phpExtensions = array_combine($moduleOnUpdateServer->phpextensions, $extensionCheck);
                    foreach ($phpExtensions as $key => $value) {
                        if ($value == true) {
                            $phpExtension[] = '<font color="#3c763d">'.$key.'</font>';
                        } else {
                            $phpExtension[] = '<font color="#a94442">'.$key.'</font>';
                        }
                    }

                    $phpExtension = implode(", ", $phpExtension);
                }
                ?>
                <tr>
                    <td>
                        <a href="<?=$this->getUrl(['action' => 'show', 'id' => $moduleOnUpdateServer->id]); ?>" title="<?=$this->getTrans('info') ?>"><?=$this->escape($moduleOnUpdateServer->name) ?></a>
                        <br />
                        <small>
                            <?=$this->getTrans('author') ?>: 
                            <?php if ($moduleOnUpdateServer->link != ''): ?>
                                <a href="<?=$moduleOnUpdateServer->link ?>" alt="<?=$this->escape($moduleOnUpdateServer->author) ?>" title="<?=$this->escape($moduleOnUpdateServer->author) ?>" target="_blank"><i><?=$this->escape($moduleOnUpdateServer->author) ?></i></a>
                            <?php else: ?>
                                <i><?=$this->escape($moduleOnUpdateServer->author) ?></i>
                            <?php endif; ?>
                        </small>
                        <br /><br />
                        <?php
                        $filename = basename($moduleOnUpdateServer->downloadLink);
                        $filename = strstr($filename,'.',true);
                        if (!empty($moduleOnUpdateServer->phpextensions) AND in_array(false, $extensionCheck)): ?>
                            <button class="btn disabled"
                                    title="<?=$this->getTrans('phpExtensionError') ?>">
                                <i class="fa fa-download"></i>
                            </button>
                        <?php elseif (version_compare(phpversion(), $moduleOnUpdateServer->phpVersion, '<')): ?>
                            <button class="btn disabled"
                                    title="<?=$this->getTrans('phpVersionError') ?>">
                                <i class="fa fa-download"></i>
                            </button>
                        <?php elseif (version_compare(VERSION, $moduleOnUpdateServer->ilchCore, '<')): ?>
                            <button class="btn disabled"
                                    title="<?=$this->getTrans('ilchCoreError') ?>">
                                <i class="fa fa-download"></i>
                            </button>
                        <?php elseif (in_array($filename, $this->get('modules')) && $moduleOnUpdateServer->version <= $versionsOfModules[$moduleOnUpdateServer->key]['version']): ?>
                            <button class="btn disabled"
                                    title="<?=$this->getTrans('alreadyExists') ?>">
                                <i class="fa fa-check text-success"></i>
                            </button>
                        <?php elseif (in_array($filename, $this->get('modules')) && $moduleOnUpdateServer->version > $versionsOfModules[$moduleOnUpdateServer->key]['version']): ?>
                            <form method="POST" action="<?=$this->getUrl(['action' => 'update']) ?>">
                                <?=$this->getTokenField() ?>
                                <button type="submit"
                                        class="btn btn-default"
                                        name="url"
                                        value="<?=$moduleOnUpdateServer->downloadLink ?>"
                                        title="<?=$this->getTrans('moduleUpdate') ?>">
                                    <i class="fa fa-refresh"></i>
                                </button>
                            </form>
                        <?php else: ?>
                            <form method="POST" action="">
                                <?=$this->getTokenField() ?>
                                <button type="submit"
                                        class="btn btn-default"
                                        name="url"
                                        value="<?=$moduleOnUpdateServer->downloadLink ?>"
                                        title="<?=$this->getTrans('moduleDownload') ?>">
                                    <i class="fa fa-download"></i>
                                </button>
                            </form>
                        <?php endif; ?>

                        <a href="<?=$this->getUrl(['action' => 'show', 'id' => $moduleOnUpdateServer->id]); ?>" title="<?=$this->getTrans('info') ?>">
                            <span class="btn btn-default">
                                <i class="fa fa-info text-info"></i>
                            </span>
                        </a>
                    </td>
                    <td><?=$moduleOnUpdateServer->desc ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
