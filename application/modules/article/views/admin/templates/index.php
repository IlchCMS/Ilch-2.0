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
                    <col />
                </colgroup>
                <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_articles') ?></th>
                    <th></th>
                    <th></th>
                    <th><?=$this->getTrans('title') ?></th>
                    <th><?=$this->getTrans('language') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($this->get('articles') as $article): ?>
                    <tr>
                        <td><?=$this->getDeleteCheckbox('check_articles', $article->getId()) ?></td>
                        <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $article->getId()]) ?></td>
                        <td><?=$this->getDeleteIcon(['action' => 'delete', 'id' => $article->getId()]) ?></td>
                        <td><?=$this->escape($article->getTitle()) ?></td>
                        <td><?=$this->escape($article->getLocale()) ?></td>
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
