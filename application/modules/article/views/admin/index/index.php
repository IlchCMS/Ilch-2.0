<?php
$articleMapper = $this->get('articleMapper');
$categoryMapper = $this->get('categoryMapper');
?>

<legend><?=$this->getTrans('manage') ?></legend>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="icon_width">
                <col class="icon_width">
                <col class="icon_width">
                <col class="col-lg-2">
                <col>
                <?php if ($this->get('multilingual')): ?>
                    <col class="col-lg-1">
                <?php endif; ?>
            </colgroup>
            <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_articles') ?></th>
                    <th></th>
                    <th></th>
                    <th><?=$this->getTrans('cats') ?></th>
                    <th><?=$this->getTrans('title') ?></th>
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
                <?php if (!empty($this->get('articles'))): ?>
                    <?php foreach ($this->get('articles') as $article): ?>
                        <?php $articlesCats = $categoryMapper->getCategoryById($article->getCatId()); ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_articles', $article->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $article->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'delete', 'id' => $article->getId()]) ?></td>
                            <td><a target="_blank" href="<?=$this->getUrl().'/index.php/article/cats/show/id/'.$articlesCats->getId() ?>"><?=$this->escape($articlesCats->getName()) ?></a></td>
                            <td><a target="_blank" href="<?=$this->getUrl().'/index.php/'.$this->escape($article->getPerma()) ?>"><?=$this->escape($article->getTitle()) ?></a></td>
                            <?php if ($this->get('multilingual')): ?>
                                <td class="text-right">
                                    <?php foreach ($this->getTranslator()->getLocaleList() as $key => $value): ?>
                                        <?php if ($key == $this->get('contentLanguage')): ?>
                                            <?php continue; ?>
                                        <?php endif; ?>

                                        <?php if ($articleMapper->getArticleByIdLocale($article->getId(), $key)): ?>
                                            <a href="<?=$this->getUrl(['action' => 'treat', 'id' => $article->getId(), 'locale' => $key]) ?>"><i class="fa fa-edit"></i></a>
                                        <?php else: ?>
                                            <a href="<?=$this->getUrl(['action' => 'treat', 'id' => $article->getId(), 'locale' => $key]) ?>"><i class="fa fa-plus-circle"></i></a>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?=($this->get('multilingual')) ? '6' : '5' ?>"><?=$this->getTrans('noArticles') ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?=$this->getListBar(['delete' => 'delete']) ?>
</form>
