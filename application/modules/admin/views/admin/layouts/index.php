<link href="<?=$this->getModuleUrl('static/css/extsearch.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('manage') ?></legend>
<?php
$layoutsList = url_get_contents('http://ilch2.de/downloads/layouts/list.php');
$layoutsOnUpdateServer = json_decode($layoutsList);
$versionsOfLayouts = $this->get('versionsOfLayouts');
?>

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
                            <a href="<?=$layout->getLink() ?>" alt="<?=$this->escape($layout->getAuthor()) ?>" title="<?=$this->escape($layout->getAuthor()) ?>" target="_blank">
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
            </div>
            <div class="panel-footer">
                <div class="clearfix">
                    <div class="pull-left">
                        <?php
                        $layoutOnUpdateServerFound = null;
                        $filename = '';
                        foreach ($layoutsOnUpdateServer as $layoutOnUpdateServer) {
                            if ($layoutOnUpdateServer->key == $layout->getKey()) {
                                $filename = basename($layoutOnUpdateServer->downloadLink);
                                $filename = strstr($filename,'.',true);
                                $layoutOnUpdateServerFound = $layoutOnUpdateServer;
                                break;
                            }
                        }

                        if (!empty($layoutOnUpdateServerFound) && version_compare($versionsOfLayouts[$layoutOnUpdateServerFound->key], $layoutOnUpdateServerFound->version, '<')): ?>
                                <form method="POST" action="<?=$this->getUrl(['action' => 'update', 'version' => $versionsOfLayouts[$layoutOnUpdateServerFound->key], 'from' => 'index']) ?>">
                                    <?=$this->getTokenField() ?>
                                    <button type="submit"
                                            class="btn btn-default"
                                            name="url"
                                            value="<?=$layoutOnUpdateServerFound->downloadLink ?>"
                                            title="<?=$this->getTrans('layoutUpdate') ?>">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                </form>
                        <?php else: ?>
                            <?php if ($this->get('defaultLayout') == $layout->getKey()): ?>
                                <span class="btn disabled" title="<?=$this->getTrans('isDefault') ?>">
                                    <i class="fa fa-check text-success"></i>
                                </span>
                            <?php else: ?>
                                <a href="<?=$this->getUrl(['action' => 'default', 'key' => $layout->getKey()]) ?>" class="btn btn-default" title="<?=$this->getTrans('setDefault') ?>">
                                    <i class="fa unchecked"></i>
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>

                    <?php if ($layout->getModulekey() != ''): ?>
                        <a href="<?=$this->getUrl(['module' => $layout->getModulekey(),'controller' => 'index', 'action' => 'index']) ?>" class="btn btn-default" title="<?=$this->getTrans('settings') ?>">
                            <i class="fa fa-cogs"></i>
                        </a>
                    <?php endif; ?>
                    </div>
                    <div class="pull-right">
                        <span class="btn btn-default"
                              data-toggle="modal"
                              data-target="#infoModal<?=$layout->getKey() ?>"
                              title="<?=$this->getTrans('info') ?>">
                            <i class="fa fa-info text-info"></i>
                        </span>
                        <?php if ($this->get('defaultLayout') != $layout->getKey()): ?>
                            <span class="btn btn-default deleteLayout"
                                  data-clickurl="<?=$this->getUrl(['action' => 'delete', 'key' => $layout->getKey()]) ?>"
                                  data-toggle="modal"
                                  data-target="#deleteModal"
                                  data-modaltext="<?=$this->escape($this->getTrans('askIfDeleteLayout', $layout->getKey())) ?>"
                                  title="<?=$this->getTrans('delete') ?>">
                                <i class="fa fa-trash-o text-danger"></i>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    if ($layout->getLink() != '') {
        $screen = '<a href="'.$layout->getLink().'" alt="'.$this->escape($layout->getAuthor()).'" title="'.$this->escape($layout->getAuthor()).'" target="_blank">
                   <img src="'.$this->getStaticUrl('../application/layouts/'.$layout->getKey().'/config/screen.png').'" class="img-thumbnail" alt="'.$this->escape($layout->getName()).'" title="'.$this->escape($layout->getName()).'" />
                   </a>';
        $author = '<a href="'.$layout->getLink().'" alt="'.$this->escape($layout->getAuthor()).'" title="'.$this->escape($layout->getAuthor()).'" target="_blank">'.$this->escape($layout->getAuthor()).'</a>';
    } else {
        $screen = '<img src="'.$this->getStaticUrl('../application/layouts/'.$layout->getKey().'/config/screen.png').'" alt="'.$this->escape($layout->getName()).'" title="'.$this->escape($layout->getName()).'" />';
        $author = $this->escape($layout->getAuthor());
    }
    
    $layoutInfo = '<center>'.$screen.'</center><br />
                   <b>'.$this->getTrans('name').':</b> '.$this->escape($layout->getName()).'<br />
                   <b>'.$this->getTrans('version').':</b> '.$this->escape($layout->getVersion()).'<br />
                   <b>'.$this->getTrans('author').':</b> '.$author.'<br /><br />
                   <b>'.$this->getTrans('desc').':</b><br />'.$this->escape($layout->getDesc());
    ?>
    <?=$this->getDialog('infoModal'.$layout->getKey(), $this->getTrans('menuLayout').' '.$this->getTrans('info'), $layoutInfo); ?>
<?php endforeach; ?>

<?=$this->getDialog('deleteModal', $this->getTrans('delete'), $this->getTrans('needAcknowledgement'), 1); ?>
<script>
$('.deleteLayout').on('click', function(event) {
    $('#modalButton').data('clickurl', $(this).data('clickurl'));
    $('#modalText').html($(this).data('modaltext'));
});

$('#modalButton').on('click', function(event) {
    window.location = $(this).data('clickurl');
});
</script>
