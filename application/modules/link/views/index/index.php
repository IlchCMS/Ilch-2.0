<?php

/** @var \Ilch\View $this */

/** @var \Modules\Link\Models\Category[]|null $categorys */
$categorys = $this->get('categorys');

/** @var \Modules\Link\Models\Link[]|null $links */
$links = $this->get('links');
?>
<h1><?=$this->getTrans('menuLinks') ?></h1>
<?php if ($categorys) : ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col>
                <col class="col-xl-1">
            </colgroup>
            <tr>
                <th><?=$this->getTrans('category') ?></th>
                <th class="text-center"><?=$this->getTrans('links') ?></th>
            </tr>
            <?php foreach ($categorys as $category) : ?>
            <tr>
                <?php
                $getDesc = $this->escape($category->getDesc());
                if ($getDesc != '') {
                    $getDesc = '&raquo; ' . $getDesc;
                }
                ?>
                <td>
                    <a href="<?=$this->getUrl(['action' => 'index', 'cat_id' => $category->getId()]) ?>" title="<?=$this->escape($category->getName()) ?>"><?=$this->escape($category->getName()) ?></a><br />
                    <?=$getDesc ?>
                </td>
                <td style="text-align: center; vertical-align:middle"><?=$category->getLinksCount() ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <br />
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col>
            <col class="col-xl-1">
        </colgroup>
        <thead>
            <tr>
                <th><?=$this->getTrans('links') ?></th>
                <th class="text-center"><?=$this->getTrans('hits') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($links) : ?>
            <?php foreach ($links as $link) : ?>
            <tr>
                <?php
                $banner = $this->escape($link->getBanner());
                $desc = $this->escape($link->getDesc());
                if (!empty($desc)) {
                    $desc = '&raquo; ' . $this->escape($link->getDesc());
                } else {
                    $desc = '';
                }

                if (strncmp($banner, 'application', 11) === 0) {
                    $banner = '<img src="' . $this->getBaseUrl($banner) . '">';
                } elseif (!empty($banner)) {
                    $banner = '<img src="' . $this->escape($banner) . '">';
                } else {
                    $banner = $this->escape($link->getName());
                }
                ?>
                <td>
                    <a href="<?=$this->getUrl(['action' => 'redirect', 'link_id' => $link->getId()]) ?>" target="_blank" title="<?=$this->escape($link->getName()) ?>"><?=$banner ?><br />
                    <?=$desc ?></a>
                </td>
                <td class="text-center" style="vertical-align:middle"><?=$this->escape($link->getHits()) ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="2"><?=$this->getTrans('noLinks') ?></td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
