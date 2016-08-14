<legend><?=$this->getTrans('manage') ?></legend>
<?php if ($this->get('boxes') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col>
                    <?php if ($this->get('multilingual')): ?>
                        <col>
                    <?php endif; ?>
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_boxes') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('boxTitle') ?></th>
                        <?php if ($this->get('multilingual')): ?>
                            <th class="text-right">
                                <?php foreach ($this->getTranslator()->getLocaleList() as $key => $value): ?>
                                    <?php if ($key == $this->get('contentLanguage')): ?>
                                        <?php continue; ?>
                                    <?php endif; ?>

                                    <img src="<?=$this->getStaticUrl('img/'.$key.'.png') ?>">
                                <?php endforeach; ?>
                            </th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('boxes') as $box): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="check_boxes[]" value="<?=$box->getId() ?>" />
                            </td>
                            <td>
                                <a href="<?=$this->getUrl(['action' => 'treat', 'id' => $box->getId()]) ?>">
                                    <i class="fa fa-edit text-success"></i>
                                </a>
                            </td>
                            <td>
                                <?=$this->getDeleteIcon(['action' => 'delete', 'id' => $box->getId()]) ?>
                            </td>
                            <td>
                                <?php if ($box->getTitle() !== ''): ?>
                                    <?=$box->getTitle() ?>
                                <?php else: ?>
                                    <?=$this->getTrans('noTitleBox') ?>
                                <?php endif; ?>
                            </td>
                            <?php if ($this->get('multilingual')): ?>
                                <td class="text-right">
                                    <?php foreach ($this->getTranslator()->getLocaleList() as $key => $value): ?>
                                        <?php if ($key == $this->get('contentLanguage')): ?>
                                            <?php continue; ?>
                                        <?php endif; ?>

                                        <?php if ($this->get('boxMapper')->getBoxByIdLocale($box->getId(), $key) != null): ?>
                                            <a href="<?=$this->getUrl(['action' => 'treat', 'id' => $box->getId(), 'locale' => $key]) ?>"><i class="fa fa-edit"></i></a>
                                        <?php else: ?>
                                            <a href="<?=$this->getUrl(['action' => 'treat', 'id' => $box->getId(), 'locale' => $key]) ?>"><i class="fa fa-plus-circle"></i></a>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noBoxes') ?>
<?php endif; ?>

<script>
$('.deleteBox').on('click', function(event) {
    $('#modalButton').data('clickurl', $(this).data('clickurl'));
    $('#modalText').html($(this).data('modaltext'));
});

$('#modalButton').on('click', function(event) {
    window.location = $(this).data('clickurl');
});
</script>
