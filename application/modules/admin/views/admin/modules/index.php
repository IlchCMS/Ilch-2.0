<?php
$modulesList = url_get_contents('http://ilch2.de/downloads/modules/list.php');
$modulesOnUpdateServer = json_decode($modulesList);
$versionsOfModules = $this->get('versionsOfModules');
?>

<link href="<?=$this->getModuleUrl('static/css/extsearch.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('modulesInstalled') ?></legend>
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
            <?php foreach ($this->get('modules') as $module):
                $content = $module->getContentForLocale($this->getTranslator()->getLocale());
                if ($this->getUser()->hasAccess('module_'.$module->getKey()) && !$module->getSystemModule()): ?>
                    <tr>
                        <td>
                            <?=$content['name'] ?>
                            <br />
                            <small>
                                <?=$this->getTrans('author') ?>:
                                <?php if ($module->getLink() != ''): ?>
                                    <a href="<?=$module->getLink() ?>" alt="<?=$this->escape($module->getAuthor()) ?>" title="<?=$this->escape($module->getAuthor()) ?>" target="_blank">
                                        <i><?=$this->escape($module->getAuthor()) ?></i>
                                    </a>
                                <?php else: ?>
                                    <i><?=$this->escape($module->getAuthor()) ?></i>
                                <?php endif; ?>
                            </small>
                            <br /><br />
                            <a href="<?=$this->getUrl(['module' => $module->getKey(), 'controller' => 'index', 'action' => 'index']) ?>" class="btn btn-default" title="<?=$this->getTrans('administrate') ?>">
                                <i class="fa fa-pencil text-success"></i>
                            </a>
                            <span class="btn btn-default"
                                  data-toggle="modal"
                                  data-target="#infoModal<?=$module->getKey() ?>"
                                  title="<?=$this->getTrans('info') ?>">
                                <i class="fa fa-info text-info"></i>
                            </span>
                            <?php if (!isset($config->isSystemModule)): ?>
                                <a href="<?=$this->getUrl(['action' => 'uninstall', 'key' => $module->getKey()], null, true) ?>" class="btn btn-default" title="<?=$this->getTrans('uninstall') ?>">
                                   <i class="fa fa-trash-o text-warning"></i>
                                </a>
                            <?php endif; ?>
                            <?php
                            $moduleOnUpdateServerFound = null;
                            $filename = '';
                            foreach ($modulesOnUpdateServer as $moduleOnUpdateServer) {
                                if ($moduleOnUpdateServer->key == $module->getKey()) {
                                    $filename = basename($moduleOnUpdateServer->downloadLink);
                                    $filename = strstr($filename,'.',true);
                                    $moduleOnUpdateServerFound = $moduleOnUpdateServer;
                                    break;
                                }
                            }
                            if (!empty($moduleOnUpdateServerFound) && version_compare($versionsOfModules[$moduleOnUpdateServerFound->key]['version'], $moduleOnUpdateServerFound->version, '<')): ?>
                                <form method="POST" action="<?=$this->getUrl(['action' => 'update']) ?>">
                                    <?=$this->getTokenField() ?>
                                    <button type="submit"
                                            class="btn btn-default"
                                            name="url"
                                            value="<?=$moduleOnUpdateServerFound->downloadLink ?>"
                                            title="<?=$this->getTrans('moduleUpdate') ?>">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                        <td><?=$content['description'] ?></td>
                    </tr>

                    <?php
                    if ($module->getLink() != '') {
                        $author = '<a href="'.$module->getLink().'" alt="'.$this->escape($module->getAuthor()).'" title="'.$this->escape($module->getAuthor()).'" target="_blank">'.$this->escape($module->getAuthor()).'</a>';
                    } else {
                        $author = $this->escape($module->getAuthor());
                    }
                    $moduleInfo = '<b>'.$this->getTrans('name').':</b> '.$content['name'].'<br />
                                   <b>'.$this->getTrans('version').':</b> '.$this->escape($module->getVersion()).'<br />
                                   <b>'.$this->getTrans('author').':</b> '.$author.'<br /><br />
                                   <b>'.$this->getTrans('desc').':</b><br />'.$content['description'];
                    ?>
                    <?=$this->getDialog('infoModal'.$module->getKey(), $this->getTrans('menuModules').' '.$this->getTrans('info'), $moduleInfo); ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
