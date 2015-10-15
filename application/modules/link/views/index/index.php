<?php
$categories = $this->get('categorys');
$links = $this->get('links');
?>

<legend><?=$this->getTrans('menuLinks') ?></legend>
<?php if ($categories != ''): ?>
    <table class="table table-hover table-striped">
        <colgroup>
            <col class="col-lg-11">
            <col class="col-lg-1">
        </colgroup>
        <tr>
            <th><?=$this->getTrans('category') ?></th>
            <th style="text-align:center"><?=$this->getTrans('links') ?></th>
        </tr>
        <?php foreach ($this->get('categorys') as $category): ?>
            <tr>
                <?php if ($category->getDesc() != ''): ?>
                    <?php $getDesc = '&raquo; '.$this->escape($category->getDesc()); ?>
                <?php else: ?>
                    <?php $getDesc = ''; ?>
                <?php endif; ?>
                <td>
                    <a href="<?=$this->getUrl(array('action' => 'index', 'cat_id' => $category->getId())) ?>" title="<?=$this->escape($category->getName()) ?>"><?=$this->escape($category->getName()) ?></a><br />
                    <?=$getDesc ?>
                </td>
                <td align="center" style="vertical-align:middle"><?=$category->getLinksCount() ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br />
<?php endif; ?>

<table class="table table-hover table-striped">
    <colgroup>
        <col class="col-lg-11">
        <col class="col-lg-1">
    </colgroup>
    <tr>
        <th><?=$this->getTrans('links') ?></th>
        <th style="text-align:center"><?=$this->getTrans('hits') ?></th>
    </tr>
    <?php if ($links != ''): ?>
        <?php foreach ($this->get('links') as $link): ?>
            <tr>
                <?php $getBanner = $this->escape($link->getBanner()); ?>
                <?php $getDesc = $this->escape($link->getDesc()); ?>

                <?php 
                if (!empty($getDesc)) {
                    $getDesc = '&raquo; '.$this->escape($link->getDesc());
                }else{
                    $getDesc = '';
                }

                if (!empty($getBanner)) {
                    $getBanner = '<img src="'.$this->escape($link->getBanner()).'">';
                }else{
                    $getBanner = $this->escape($link->getName());
                }
                ?>
                <td>
                    <a href="<?=$this->getUrl(array('action' => 'redirect', 'link_id' => $link->getId())) ?>" target="_blank" title="<?=$this->escape($link->getName()) ?>"><?=$getBanner ?></a><br />
                    <?=$getDesc ?>
                </td>
                <td align="center" style="vertical-align:middle"><?=$this->escape($link->getHits()) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="2"><?=$this->getTrans('noLinks') ?></td>
        </tr>
    <?php endif; ?>
</table>
