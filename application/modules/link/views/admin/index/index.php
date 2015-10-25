<legend><?=$this->getTrans('manage') ?></legend>
<form class="form-horizontal" method="POST" action="">
    <?php if ($this->get('categorys') != ''): ?>
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="col-lg-2">
                    <col />
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_cats') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('category') ?></th>
                        <th><?=$this->getTrans('links') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('categorys') as $category): ?>
                        <?php $getDesc = $this->escape($category->getDesc()); ?>
                        <?php if ($getDesc != ''): ?>
                            <?php $getDesc = '&raquo; '.$this->escape($category->getDesc()); ?>
                        <?php else: ?>
                            <?php $getDesc = ''; ?>
                        <?php endif; ?>
                        <tr>
                            <td><input value="<?=$category->getId()?>" type="checkbox" name="check_cats[]" /></td>
                            <td><?=$this->getEditIcon(array('action' => 'treatCat', 'id' => $category->getId())) ?></td>
                            <td><?=$this->getDeleteIcon(array('action' => 'deleteCat', 'id' => $category->getId())) ?></td>
                            <td><a href="<?=$this->getUrl(array('action' => 'index', 'cat_id' => $category->getId())) ?>" title="<?=$this->escape($category->getName()) ?>"><?=$this->escape($category->getName()) ?></a><br><?=$getDesc ?></td>
                            <td style="vertical-align:middle"><?=$category->getLinksCount() ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <br />
    <?php endif; ?>

    <?php if ($this->get('links') != ''): ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col />
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_links') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('links') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('links') as $link): ?>
                        <?php $getBanner = $this->escape($link->getBanner()); ?>
                        <?php $getDesc = $this->escape($link->getDesc()); ?>
                        <?php if (!empty($getDesc)): ?>
                            <?php $getDesc = '&raquo; '.$this->escape($link->getDesc()); ?>
                        <?php else: ?>
                            <?php $getDesc = ''; ?>
                        <?php endif; ?>

                        <?php if (!empty($getBanner)): ?>
                            <?php $getBanner = '<img src="'.$getBanner.'">'; ?>
                        <?php else: ?>
                            <?php $getBanner = $this->escape($link->getName()); ?>
                        <?php endif; ?>
                        <tr>
                            <td><input value="<?=$link->getId()?>" type="checkbox" name="check_links[]" /></td>
                            <td><?=$this->getEditIcon(array('action' => 'treatLink', 'id' => $link->getId())) ?></td>
                            <td><?=$this->getDeleteIcon(array('action' => 'deleteLink', 'id' => $link->getId())) ?></td>
                            <td><a href="<?=$this->escape($link->getLink()) ?>" target="_blank" title="<?=$this->escape($link->getName()) ?>"><?=$getBanner ?></a><br /><?=$getDesc ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <?=$this->getTrans('noLinks') ?>
    <?php endif; ?>

    <?=$this->getListBar(array('delete' => 'delete')) ?>
</form>
