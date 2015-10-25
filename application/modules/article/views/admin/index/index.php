<?php
$categoryMapper = new \Modules\Article\Mappers\Category();
?>

<legend><?=$this->getTrans('menuArticle') ?></legend>
<?php if ($this->get('articles') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="col-lg-2">
                    <col />
                    <?php if ($this->get('multilingual')): ?>
                        <col />
                    <?php endif; ?>
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_articles')?></th>
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
                    <?php foreach ($this->get('articles') as $article): ?>
                        <?php $articlesCats = $categoryMapper->getCategoryById($article->getCatId()); ?>
                        <tr>
                            <td><input value="<?=$article->getId() ?>" type="checkbox" name="check_articles[]" /></td>
                            <td><?=$this->getEditIcon(array('action' => 'treat', 'id' => $article->getId())) ?></td>
                            <td><?=$this->getDeleteIcon(array('action' => 'delete', 'id' => $article->getId())) ?></td>
                            <td><a target="_blank" href="<?=$this->getUrl().'/index.php/article/cats/show/id/'.$articlesCats->getId() ?>"><?=$articlesCats->getName() ?></a></td>
                            <td><a target="_blank" href="<?=$this->getUrl().'/index.php/'.$this->escape($article->getPerma()) ?>"><?=$article->getTitle() ?></a></td>
                            <?php if ($this->get('multilingual')): ?>
                                <td class="text-right">
                                    <?php foreach ($this->getTranslator()->getLocaleList() as $key => $value): ?>
                                        <?php if ($key == $this->get('contentLanguage')): ?>
                                            <?php continue; ?>
                                        <?php endif; ?>

                                        <?php if ($this->get('articleMapper')->getArticleByIdLocale($article->getId(), $key) != null): ?>
                                            <a href="<?=$this->getUrl(array('action' => 'treat', 'id' => $article->getId(), 'locale' => $key)) ?>"><i class="fa fa-edit"></i></a>
                                        <?php else: ?>
                                            <a href="<?=$this->getUrl(array('action' => 'treat', 'id' => $article->getId(), 'locale' => $key)) ?>"><i class="fa fa-plus-circle"></i></a>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(array('delete' => 'delete'))?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noArticles') ?>
<?php endif; ?>
