<h1><?=$this->getTrans('menuLinks') ?></h1>
<?php if ($this->get('categorys') != ''): ?>
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
            <?php foreach ($this->get('categorys') as $category): ?>
                <tr>
                    <?php
                    if ($category->getDesc() != '') {
                        $getDesc = '&raquo; '.$this->escape($category->getDesc());
                    } else {
                        $getDesc = '';
                    }
                    ?>
                    <td>
                        <a href="<?=$this->getUrl(['action' => 'index', 'cat_id' => $category->getId()]) ?>" title="<?=$this->escape($category->getName()) ?>"><?=$this->escape($category->getName()) ?></a><br />
                        <?=$getDesc ?>
                    </td>
                    <td align="center" style="vertical-align:middle"><?=$category->getLinksCount() ?></td>
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
        <tr>
            <th><?=$this->getTrans('links') ?></th>
            <th class="text-center"><?=$this->getTrans('hits') ?></th>
        </tr>
        <?php if (!empty($this->get('links'))): ?>
            <?php foreach ($this->get('links') as $link): ?>
                <tr>
                    <?php
                    $banner = $this->escape($link->getBanner());
                    $desc = $this->escape($link->getDesc());
                    if (!empty($desc)) {
                        $desc = '&raquo; '.$this->escape($link->getDesc());
                    } else {
                        $desc = '';
                    }

                    if (strncmp($banner, 'application', 11) === 0) {
                        $banner = '<img src="'.$this->getBaseUrl($banner).'">';
                    } elseif (!empty($banner)) {
                        $banner = '<img src="'.$this->escape($banner).'">';
                    } else {
                        $banner = $this->escape($link->getName());
                    }
                    ?>
                    <td>
                        <a href="<?=$this->getUrl(['action' => 'redirect', 'link_id' => $link->getId()]) ?>" target="_blank" title="<?=$this->escape($link->getName()) ?>"><?=$banner ?></a><br />
                        <?=$desc ?>
                    </td>
                    <td class="text-center" style="vertical-align:middle"><?=$this->escape($link->getHits()) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="2"><?=$this->getTrans('noLinks') ?></td>
            </tr>
        <?php endif; ?>
    </table>
</div>
