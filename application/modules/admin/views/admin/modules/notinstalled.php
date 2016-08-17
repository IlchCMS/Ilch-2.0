<legend><?=$this->getTrans('modulesNotInstalled') ?></legend>
<?php if ($this->get('modulesNotInstalled') != ''): ?>
    <div class="table-responsive">
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
                <?php foreach ($this->get('modulesNotInstalled') as $module): ?>
                    <?php $content = $module->getContentForLocale($this->getTranslator()->getLocale()); ?>
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
                            <a href="<?=$this->getUrl(['action' => 'install', 'key' => $module->getKey()], null, true) ?>" class="btn btn-default" title="<?=$this->getTrans('installModule') ?>">
                                <i class="fa fa-save"></i>
                            </a>
                            <span class="btn btn-default"
                                  data-toggle="modal"
                                  data-target="#infoModal<?=$module->getKey() ?>"
                                  title="<?=$this->getTrans('info') ?>">
                                <i class="fa fa-info text-info"></i>
                            </span>
                            <a href="<?=$this->getUrl(['action' => 'delete', 'key' => $module->getKey()], null, true) ?>" class="btn btn-default" title="<?=$this->getTrans('delete') ?>">
                                <i class="fa fa-trash-o text-warning"></i>
                            </a>
                        </td>
                        <td>
                            <?php if (!empty($content['description'])): ?>
                                <?=$content['description'] ?>
                            <?php endif; ?>
                        </td>
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
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <?=$this->getTrans('noNotInstalledModules') ?>
<?php endif; ?>
