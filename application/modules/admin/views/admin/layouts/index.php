<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName())) ?>">
    <?=$this->getTokenField() ?>
    <legend><?=$this->getTrans('layouts') ?></legend>
        <?php $i = 0; ?>
            <?php foreach ($this->get('layouts') as $layout): ?>
                <?php if ($i !== 0 && $i % 3 == 0): ?>
                    </div>
                    <br />
                <?php endif; ?>

                <?php if ($i % 3 == 0): ?>
                    <div class="row">
                <?php endif; ?>

                <div class="col-lg-4">
                    <div class="thumbnail">
                        <img src="<?=$this->getStaticUrl('../application/layouts/'.$layout->getKey().'/config/screen.png') ?>" />
                        <div class="caption">
                            <div class="clearfix">
                                <span class="pull-left">
                                    <h3 style="margin-top: 0px;"><?=$this->escape($layout->getName()) ?></h3>
                                </span>
                                <span class="pull-right">
                                    <p style="margin-top: 4px;">
                                        <?php if($layout->getLink() != ''): ?>
                                        <a href="<?=$layout->getLink() ?>" alt="<?=$this->escape($layout->getAuthor()) ?>" title="<?=$this->escape($layout->getAuthor()) ?>" target="_blank">
                                                <i><?=$this->escape($layout->getAuthor()) ?></i>
                                            </a>
                                        <?php else: ?>
                                            <i><?=$this->escape($layout->getAuthor()) ?></i>
                                        <?php endif; ?>
                                    </p>
                                </span>                                
                            </div>
                            <p><?=$this->escape($layout->getDesc()) ?></p>
                            <p>
                                <a title="<?php if ($this->get('defaultLayout') == $layout->getKey()) { echo $this->getTrans('isDefault'); } else { echo $this->getTrans('setDefault'); } ?>"
                                   href="<?=$this->getUrl(array('action' => 'default', 'key' => $layout->getKey())) ?>">
                                    <?php if ($this->get('defaultLayout') == $layout->getKey()): ?>
                                        <i class="fa fa-check-square-o fa-2x text-success"></i>
                                    <?php else: ?>
                                        <i class="fa fa-square-o fa-2x"></i>
                                    <?php endif; ?>
                                </a>
                                <?php if($layout->getModulekey() != ''): ?>
                                    <a class="fa-2x" href="<?=$this->getUrl(array('module' => $layout->getModulekey(),'controller' => 'index', 'action' => 'index')) ?>">
                                        <i class="fa fa fa-cogs"></i> Settings
                                    </a>
                                <?php endif; ?>

                                <span class="deleteLayout clickable fa fa-trash-o fa-2x text-danger pull-right"
                                      data-clickurl="<?=$this->getUrl(array('action' => 'delete', 'key' => $layout->getKey())) ?>"
                                      data-toggle="modal"
                                      data-target="#deleteModal"
                                      data-modaltext="<?=$this->escape($this->getTrans('askIfDeleteLayout', $layout->getKey())) ?>"
                                      title="<?=$this->getTrans('delete') ?>">
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <?php $i++; ?>
            <?php endforeach; ?>
        </div>
    </div>
</form>

<script>
$('.deleteLayout').on('click', function(event) {
    $('#modalButton').data('clickurl', $(this).data('clickurl'));
    $('#modalText').html($(this).data('modaltext'));
});

$('#modalButton').on('click', function(event) {
    window.location = $(this).data('clickurl');
});
</script>
