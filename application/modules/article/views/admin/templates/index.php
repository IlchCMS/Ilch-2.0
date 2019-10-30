<h1><?=$this->getTrans('manageTemplates') ?></h1>
<?php if (!empty($this->get('articles'))): ?>
    <form class="form-horizontal" method="POST">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col />
                    <?php if ($this->get('multilingual')): ?>
                        <col class="col-lg-1">
                    <?php endif; ?>
                </colgroup>
                <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_articles') ?></th>
                    <th></th>
                    <th></th>
                    <th><?=$this->getTrans('title') ?></th>
                    <?php if ($this->get('multilingual')): ?>
                        <th class="text-right">
                            <?php foreach ($this->getTranslator()->getLocaleList() as $key => $value): ?>
                                <?php if ($key == $this->get('contentLanguage')): ?>
                                    <?php continue; ?>
                                <?php endif; ?>

                                <img src="<?=$this->getStaticUrl('img/lang/'.$key.'.png') ?>" alt="<?=$this->getTrans('language').' '.$key ?>">
                            <?php endforeach; ?>
                        </th>
                    <?php endif; ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($this->get('articles') as $article): ?>
                    <tr>
                        <td><?=$this->getDeleteCheckbox('check_articles', $article->getId()) ?></td>
                        <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $article->getId()]) ?></td>
                        <td><?=$this->getDeleteIcon(['action' => 'delete', 'id' => $article->getId()]) ?></td>
                        <td><?=$this->escape($article->getTitle()) ?></td>
                        <?php if ($this->get('multilingual')): ?>
                            <td class="text-right">
                                <?php foreach ($this->getTranslator()->getLocaleList() as $key => $value): ?>
                                    <?php if ($key == $this->get('contentLanguage')): ?>
                                        <?php continue; ?>
                                    <?php endif; ?>

                                    <?php if ($this->get('templateMapper')->getTemplateByIdLocale($article->getId(), $key)): ?>
                                        <a href="<?=$this->getUrl(['action' => 'treat', 'id' => $article->getId(), 'locale' => $key]) ?>"><i class="fa fa-edit"></i></a>
                                    <?php else: ?>
                                        <a href="<?=$this->getUrl(['action' => 'treat', 'id' => $article->getId(), 'locale' => $key]) ?>"><i class="fa fa-plus-circle"></i></a>
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
    <?=$this->get('pagination')->getHtml($this, ['action' => 'index']) ?>
<?php else : ?>
    <?=$this->getTrans('noTemplates') ?>
<?php endif; ?>
