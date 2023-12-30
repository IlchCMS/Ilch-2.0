<h1><?=$this->getTrans('manage') ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="icon_width">
                <col class="icon_width">
                <col class="icon_width">
                <col>
                <?php if ($this->get('multilingual')) : ?>
                    <col class="col-lg-1">
                <?php endif; ?>
            </colgroup>
            <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_pages') ?></th>
                    <th>
                        <a href="<?=$this->getUrl($this->get('sorter')->getUrlArray('id')) ?>" title=""><?=$this->get('sorter')->getArrowHtml('id') ?></a>
                    </th>
                    <th></th>
                    <th>
                        <a href="<?=$this->getUrl($this->get('sorter')->getUrlArray('title')) ?>" title="<?=$this->getTrans('pageTitle') ?>"><?=$this->get('sorter')->getArrowHtml('title') ?> <?=$this->getTrans('pageTitle') ?></a>&nbsp;
                    </th>
                    <?php if ($this->get('multilingual')) : ?>
                        <th class="text-right">
                            <?php foreach ($this->getTranslator()->getLocaleList() as $key => $value) : ?>
                                <?php if ($key == $this->get('contentLanguage')) : ?>
                                    <?php continue; ?>
                                <?php endif; ?>

                                <img src="<?=$this->getStaticUrl('img/lang/' . $key . '.png') ?>" alt="<?=$this->getTrans('multilingualContent') ?>" title="<?=$this->getTrans('multilingualContent') ?>">
                            <?php endforeach; ?>
                        </th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($this->get('pages'))) : ?>
                    <?php foreach ($this->get('pages') as $page) : ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_pages', $page->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $page->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'delete', 'id' => $page->getId()]) ?></td>
                            <td>
                                <a target="_blank" href="<?=$this->getUrl().'index.php/'.$this->escape($page->getPerma()) ?>"><?=$this->escape($page->getTitle()) ?></a>
                            </td>
                            <?php if ($this->get('multilingual')) : ?>
                                <td class="text-right">
                                    <?php foreach ($this->getTranslator()->getLocaleList() as $key => $value) : ?>
                                        <?php if ($key == $this->get('contentLanguage')) : ?>
                                            <?php continue; ?>
                                        <?php endif; ?>

                                        <?php if ($this->get('pageMapper')->getPageByIdLocale($page->getId(), $key) != null) : ?>
                                            <a href="<?=$this->getUrl(['action' => 'treat', 'id' => $page->getId(), 'locale' => $key]) ?>" title="<?=$this->getTrans('editContentLanguage') ?>"><i class="fa-regular fa-pen-to-square"></i></a>
                                        <?php else: ?>
                                            <a href="<?=$this->getUrl(['action' => 'treat', 'id' => $page->getId(), 'locale' => $key]) ?>" title="<?=$this->getTrans('addContentLanguage') ?>"><i class="fa-solid fa-circle-plus"></i></a>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?=($this->get('multilingual')) ? '5' : '4' ?>"><?=$this->getTrans('noPages') ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?=$this->getListBar(['delete' => 'delete']) ?>
</form>
