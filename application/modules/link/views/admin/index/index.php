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
                    <?php
                    foreach ($this->get('categorys') as $category):
                        $getDesc = $this->escape($category->getDesc());
                        if ($getDesc != '') {
                            $getDesc = '&raquo; '.$this->escape($category->getDesc());
                        } else {
                            $getDesc = '';
                        }
                    ?>
                        <tr>
                            <td><input value="<?=$category->getId()?>" type="checkbox" name="check_cats[]" /></td>
                            <td><?=$this->getEditIcon(['action' => 'treatCat', 'id' => $category->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'deleteCat', 'id' => $category->getId()]) ?></td>
                            <td><a href="<?=$this->getUrl(['action' => 'index', 'cat_id' => $category->getId()]) ?>" title="<?=$this->escape($category->getName()) ?>"><?=$this->escape($category->getName()) ?></a><br><?=$getDesc ?></td>
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
                    <?php
                    foreach ($this->get('links') as $link):
                        $getBanner = $this->escape($link->getBanner());
                        $getDesc = $this->escape($link->getDesc());
                        if (!empty($getDesc)) {
                            $getDesc = '&raquo; '.$this->escape($link->getDesc());
                        } else {
                            $getDesc = '';
                        }

                        if (!empty($getBanner)) {
                            $getBanner = '<img src="'.$getBanner.'">';
                        } else {
                            $getBanner = $this->escape($link->getName());
                        }
                    ?>
                        <tr>
                            <td><input value="<?=$link->getId()?>" type="checkbox" name="check_links[]" /></td>
                            <td><?=$this->getEditIcon(['action' => 'treatLink', 'id' => $link->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'deleteLink', 'id' => $link->getId()]) ?></td>
                            <td><a href="<?=$this->escape($link->getLink()) ?>" target="_blank" title="<?=$this->escape($link->getName()) ?>"><?=$getBanner ?></a><br /><?=$getDesc ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <?=$this->getTrans('noLinks') ?>
    <?php endif; ?>

    <?=$this->getListBar(['delete' => 'delete']) ?>
</form>
