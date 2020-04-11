<link href="<?=$this->getModuleUrl('static/css/extsearch.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('manage') ?></h1>
<?php
$layoutsList = url_get_contents($this->get('updateserver'));
$layoutsOnUpdateServer = json_decode($layoutsList);
$versionsOfLayouts = $this->get('versionsOfLayouts');
$cacheFilename = ROOT_PATH.'/cache/'.md5($this->get('updateserver')).'.cache';
$cacheFileDate = null;
if (file_exists($cacheFilename)) {
    $cacheFileDate = new \Ilch\Date(date('Y-m-d H:i:s.', filemtime($cacheFilename)));
}
$coreVersion = $this->get('coreVersion');
$modulesNotInstalled = $this->get('modulesNotInstalled');
?>
<p><a href="<?=$this->getUrl(['action' => 'refreshurl', 'from' => 'index']) ?>" class="btn btn-primary"><?=$this->getTrans('updateNow') ?></a> <?=(!empty($cacheFileDate)) ? '<span class="small">'.$this->getTrans('lastUpdateOn').' '.$this->getTrans($cacheFileDate->format('l', true)).$cacheFileDate->format(', d. ', true).$this->getTrans($cacheFileDate->format('F', true)).$cacheFileDate->format(' Y H:i', true).'</span>' : $this->getTrans('lastUpdateOn').': '.$this->getTrans('lastUpdateUnknown') ?></p>

<?php foreach ($this->get('layouts') as $layout): ?>
    <div id="layouts" class="col-lg-3 col-sm-6">
        <div class="panel panel-ilch">
            <div class="panel-heading">
                <div class="clearfix">
                    <div class="pull-left">
                        <b><?=$this->escape($layout->getName()) ?></b>
                    </div>
                    <div class="pull-right">
                        <?php if ($layout->getLink() != ''): ?>
                            <a href="<?=$layout->getLink() ?>" alt="<?=$this->escape($layout->getAuthor()) ?>" title="<?=$this->escape($layout->getAuthor()) ?>" target="_blank" rel="noopener">
                                <i><?=$this->escape($layout->getAuthor()) ?></i>
                            </a>
                        <?php else: ?>
                            <i><?=$this->escape($layout->getAuthor()) ?></i>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <span data-toggle="modal"
                      data-target="#infoModal<?=$layout->getKey() ?>"
                      title="<?=$this->getTrans('info') ?>">
                    <img src="<?=$this->getStaticUrl('../application/layouts/'.$layout->getKey().'/config/screen.png') ?>" alt="<?=$this->escape($layout->getName()) ?>" title="<?=$this->escape($layout->getName()) ?>" />
                </span>
                <?=($layout->getOfficial()) ? '<span class="ilch-official">ilch</span>' : '' ?>
            </div>
            <div class="panel-footer">
                <div class="clearfix">
                    <div class="pull-left">
                        <?php
                        $ilchCoreTooOld = version_compare($coreVersion, $layout->getIlchCore(), '<');
                        $moduleNotInstalled = ($layout->getModulekey() != '' && isset($modulesNotInstalled[$layout->getModulekey()]));
                        $layoutOnUpdateServerFound = null;
                        $layoutsOnUpdateServer = (empty($layoutsOnUpdateServer)) ? [] : $layoutsOnUpdateServer;
                        foreach ($layoutsOnUpdateServer as $layoutOnUpdateServer) {
                            if ($layoutOnUpdateServer->key == $layout->getKey()) {
                                $layoutOnUpdateServerFound = $layoutOnUpdateServer;
                                break;
                            }
                        }

                        if (!empty($layoutOnUpdateServerFound) && version_compare($versionsOfLayouts[$layoutOnUpdateServerFound->key], $layoutOnUpdateServerFound->version, '<')): ?>
                            <?php if (version_compare($coreVersion, $layoutOnUpdateServerFound->ilchCore, '<')): ?>
                                <button class="btn disabled"
                                        title="<?=$this->getTrans('ilchCoreError') ?>">
                                    <i class="fas fa-sync"></i>
                                </button>
                            <?php else: ?>
                                <form method="POST" action="<?=$this->getUrl(['action' => 'update', 'key' => $layoutOnUpdateServerFound->key, 'version' => $versionsOfLayouts[$layoutOnUpdateServerFound->key], 'newVersion' => $layoutOnUpdateServerFound->version, 'from' => 'index']) ?>">
                                    <?=$this->getTokenField() ?>
                                    <button type="submit"
                                            class="btn btn-default"
                                            title="<?=$this->getTrans('layoutUpdate') ?>">
                                        <i class="fas fa-sync"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if ($this->get('defaultLayout') === $layout->getKey()): ?>
                                <span class="btn disabled" title="<?=$this->getTrans('isDefault') ?>">
                                    <i class="fas fa-check text-success"></i>
                                </span>
                            <?php else: ?>
                                <?php if ($ilchCoreTooOld) : ?>
                                    <button class="btn btn-default unchecked disabled"
                                            title="<?=$this->getTrans('ilchCoreError') ?>">
                                        <i class="fa"></i>
                                    </button>
                                <?php elseif ($moduleNotInstalled) : ?>
                                    <button class="btn btn-default unchecked disabled"
                                            title="<?=$this->getTrans('layoutModuleNotInstalled') ?>">
                                        <i class="fa"></i>
                                    </button>
                                <?php else: ?>
                                    <a href="<?=$this->getUrl(['action' => 'default', 'key' => $layout->getKey()], null, true) ?>" class="btn btn-default unchecked" title="<?=$this->getTrans('setDefault') ?>">
                                        <i class="fa"></i>
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($ilchCoreTooOld) : ?>
                            <button class="btn btn-default disabled"
                                    title="<?=$this->getTrans('ilchCoreError') ?>">
                                <i class="fas fa-cogs"></i>
                            </button>
                        <?php elseif ($moduleNotInstalled) : ?>
                            <button class="btn btn-default disabled"
                                    title="<?=$this->getTrans('layoutModuleNotInstalled') ?>">
                                <i class="fas fa-cogs"></i>
                            </button>
                        <?php else: ?>
                            <?php if ($layout->getModulekey() != ''): ?>
                                <a href="<?=$this->getUrl(['module' => $layout->getModulekey(),'controller' => 'index', 'action' => 'index']) ?>" class="btn btn-default" title="<?=$this->getTrans('settings') ?>">
                                    <i class="fas fa-cogs"></i>
                                </a>
                            <?php elseif (!empty($layout->getSettings())): ?>
                                <a href="<?=$this->getUrl(['action' => 'advSettingsShow', 'layoutKey' => $layout->getKey()]) ?>" class="btn btn-default" title="<?=$this->getTrans('settings') ?>">
                                    <i class="fas fa-cogs"></i>
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <div class="pull-right">
                        <span class="btn btn-default"
                              data-toggle="modal"
                              data-target="#infoModal<?=$layout->getKey() ?>"
                              title="<?=$this->getTrans('info') ?>">
                            <i class="fas fa-info text-info"></i>
                        </span>
                        <?php if ($this->get('defaultLayout') !== $layout->getKey()): ?>
                            <span class="btn btn-default deleteLayout"
                                  data-clickurl="<?=$this->getUrl(['action' => 'delete', 'key' => $layout->getKey()], null, true) ?>"
                                  data-toggle="modal"
                                  data-target="#deleteModal"
                                  data-modaltext="<?=$this->escape($this->getTrans('askIfDeleteLayout', $layout->getKey())) ?>"
                                  title="<?=$this->getTrans('delete') ?>">
                                <i class="far fa-trash-alt text-danger"></i>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    if ($layout->getLink() != '') {
        $screen = '<a href="'.$layout->getLink().'" alt="'.$this->escape($layout->getAuthor()).'" title="'.$this->escape($layout->getAuthor()).'" target="_blank" rel="noopener">
                   <img src="'.$this->getStaticUrl('../application/layouts/'.$layout->getKey().'/config/screen.png').'" class="img-thumbnail" alt="'.$this->escape($layout->getName()).'" title="'.$this->escape($layout->getName()).'" />
                   </a>';
        $author = '<a href="'.$layout->getLink().'" alt="'.$this->escape($layout->getAuthor()).'" title="'.$this->escape($layout->getAuthor()).'" target="_blank" rel="noopener">'.$this->escape($layout->getAuthor()).'</a>';
    } else {
        $screen = '<img src="'.$this->getStaticUrl('../application/layouts/'.$layout->getKey().'/config/screen.png').'" alt="'.$this->escape($layout->getName()).'" title="'.$this->escape($layout->getName()).'" />';
        $author = $this->escape($layout->getAuthor());
    }
    
    $layoutInfo = '<center>'.$screen.'</center><br />
                   <b>'.$this->getTrans('name').':</b> '.$this->escape($layout->getName()).'<br />
                   <b>'.$this->getTrans('version').':</b> '.$this->escape($layout->getVersion()).'<br />
                   <b>'.$this->getTrans('author').':</b> '.$author.'<br /><br />
                   <b>'.$this->getTrans('requirements').':</b><br />
                   <b>'.$this->getTrans('ilchCoreVersion').':</b> '.$this->escape($layout->getIlchCore()).'<br /><br />
                   <b>'.$this->getTrans('desc').':</b><br />'.$this->escape($layout->getDesc());
    ?>
    <?=$this->getDialog('infoModal'.$layout->getKey(), $this->getTrans('menuLayout').' '.$this->getTrans('info'), $layoutInfo) ?>
<?php endforeach; ?>

<?=$this->getDialog('deleteModal', $this->getTrans('delete'), $this->getTrans('needAcknowledgement'), 1) ?>
<script>
$('.deleteLayout').on('click', function(event) {
    $('#modalButton').data('clickurl', $(this).data('clickurl'));
    $('#modalText').html($(this).data('modaltext'));
});

$('#modalButton').on('click', function(event) {
    window.location = $(this).data('clickurl');
});
</script>
