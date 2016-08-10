<link href="<?=$this->getModuleUrl('static/css/layouts.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('manage') ?></legend>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
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
                    <img src="<?=$this->getStaticUrl('../application/layouts/'.$layout->getKey().'/config/screen.png') ?>" title="<?=$this->escape($layout->getName()) ?>" />
                </div>
                <div class="panel-footer">
                    <div class="clearfix">
                        <div class="pull-left">
                                <?php if ($this->get('defaultLayout') == $layout->getKey()): ?>
                                    <span class="btn btn-default disabled" title="<?=$this->getTrans('isDefault') ?>">
                                        <i class="fa fa-check fa-lg text-success"></i>
                                    </span>
                                <?php else: ?>
                                    <a href="<?=$this->getUrl(['action' => 'default', 'key' => $layout->getKey()]) ?>" class="btn btn-default" title="<?=$this->getTrans('setDefault') ?>">
                                        <i class="fa unchecked"></i>
                                    </a>
                                <?php endif; ?>

                        <?php if ($layout->getModulekey() != ''): ?>
                            <a href="<?=$this->getUrl(['module' => $layout->getModulekey(),'controller' => 'index', 'action' => 'index']) ?>" class="btn btn-default" title="<?=$this->getTrans('settings') ?>">
                                <i class="fa fa-cogs"></i>
                            </a>
                        <?php endif; ?>
                        </div>
                        <div class="pull-right">
                            <?php if ($this->get('defaultLayout') != $layout->getKey()): ?>
                                <span class="btn btn-default deleteLayout"
                                      data-clickurl="<?=$this->getUrl(['action' => 'delete', 'key' => $layout->getKey()]) ?>"
                                      data-toggle="modal"
                                      data-target="#deleteModal"
                                      data-modaltext="<?=$this->escape($this->getTrans('askIfDeleteLayout', $layout->getKey())) ?>"
                                      title="<?=$this->getTrans('delete') ?>">
                                    <i class="fa fa-trash-o fa-lg text-danger"></i>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</form>

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
