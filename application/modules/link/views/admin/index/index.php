<legend><?=$this->getTrans('manageLink') ?></legend>
<?php
$categories = $this->get('categorys');
$links = $this->get('links');
?>
<form class="form-horizontal" method="POST" action="">
    <?php if ($categories != ''): ?>
        <?=$this->getTokenField() ?>
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
                            <th><?=$this->getCheckAllCheckbox('check_cats')?></th>
                            <th></th>
                            <th></th>
                            <th><?php echo $this->getTrans('category'); ?></th>
                            <th style="text-align:center"><?php echo $this->getTrans('links'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->get('categorys') as $category) { ?>
                        <tr>
                                <td><input value="<?=$category->getId()?>" type="checkbox" name="check_cats[]" /></td>
                                <td><?=$this->getEditIcon(array('action' => 'treatCat', 'id' => $category->getId()))?></td>
                                <td><?=$this->getDeleteIcon(array('action' => 'deleteCat', 'id' => $category->getId()))?></td>
                        <?php
                                $getDesc = $this->escape($category->getDesc());

                                if ($getDesc != '') {
                                    $getDesc = '&raquo; '.$this->escape($category->getDesc());
                                }else{
                                    $getDesc = '';
                                }

                                echo '<td><a href='.$this->getUrl(array('action' => 'index', 'cat_id' => $category->getId())).' title="'.$this->escape($category->getName()).'">'.$this->escape($category->getName()).'</a><br>'.$getDesc.'</td>';    
                                echo '<td align="center" style="vertical-align:middle">'.$category->getLinksCount().'</td>';
                                echo '</tr>';
                            }
                        ?>
                    </tbody>
                </table>
                <br />
            </div>
    <?php endif; ?>
    <?php if ($links != ''): ?>
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
                            <th><?=$this->getCheckAllCheckbox('check_links')?></th>
                            <th></th>
                            <th></th>
                            <th><?php echo $this->getTrans('links'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($links as $link) { ?>
                        <tr>
                            <td><input value="<?=$link->getId()?>" type="checkbox" name="check_links[]" /></td>
                            <td><?=$this->getEditIcon(array('action' => 'treatLink', 'id' => $link->getId()))?></td>
                            <td><?=$this->getDeleteIcon(array('action' => 'deleteLink', 'id' => $link->getId()))?></td>
                            <?php
                            $getBanner = $this->escape($link->getBanner());
                            $getDesc = $this->escape($link->getDesc());

                            if (!empty($getDesc)) {
                                $getDesc = '&raquo; '.$this->escape($link->getDesc());
                            }else{
                                $getDesc = '';
                            }

                            if (!empty($getBanner)) {
                                $getBanner = '<img src="'.$getBanner.'">';
                            }else{
                                $getBanner = $this->escape($link->getName());
                            }

                            echo '<td><a href='.$this->escape($link->getLink()).' target="_blank" title="'.$this->escape($link->getName()).'">'.$getBanner.'</a><br />'.$getDesc.'</td>';    
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
    <?php else: ?>
        <?=$this->getTrans('noLinks') ?>
    <?php endif; ?>
    <?php $actions = array('delete' => 'delete') ?>
    <?=$this->getListBar($actions) ?>
</form>
