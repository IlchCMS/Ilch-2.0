<legend><?= $this->getTrans('menuSites') ?></legend>
<?php if ($this->get('pages') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="col" />
                    <?php
                    if ($this->get('multilingual')) {
                        echo '<col />';
                    }
                    ?>
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_pages') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('pageTitle') ?></th>
                        <?php if ($this->get('multilingual')) {
                            echo '<th class="text-right">';

                            foreach ($this->getTranslator()->getLocaleList() as $key => $value) {
                                if ($key == $this->get('contentLanguage')) {
                                    continue;
                                }

                                echo '<img src="'.$this->getStaticUrl('img/'.$key.'.png').'"> ';
                            }

                            echo '</th>';
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('pages') as $page) { ?>
                        <tr>
                            <td><input value="<?=$page->getId()?>" type="checkbox" name="check_pages[]" /></td>
                            <td><?=$this->getEditIcon(array('action' => 'treat', 'id' => $page->getId()))?></td>
                            <td><?=$this->getDeleteIcon(array('action' => 'delete', 'id' => $page->getId()))?></td>
                            <td>
                                <a target="_blank" href="<?=$this->getUrl().'/index.php/'.$this->escape($page->getPerma())?>"><?=$page->getTitle()?></a>
                            </td>
                        <?php if ($this->get('multilingual')) {
                            echo '<td class="text-right">';
                                foreach ($this->getTranslator()->getLocaleList() as $key => $value) {
                                    if ($key == $this->get('contentLanguage')) {
                                        continue;
                                    }

                                    if ($this->get('pageMapper')->getPageByIdLocale($page->getId(), $key) != null) {
                                        echo '<a href="'.$this->getUrl(array('action' => 'treat', 'id' => $page->getId(), 'locale' => $key)).'"><i class="fa fa-edit"></i></a>';
                                    } else {
                                        echo '<a href="'.$this->getUrl(array('action' => 'treat', 'id' => $page->getId(), 'locale' => $key)).'"><i class="fa fa-plus-circle"></i></a>';
                                    }
                                }

                            echo '</td>';
                        }

                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php $actions = array('delete' => 'delete') ?>
        <?=$this->getListBar($actions) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noPages') ?>
<?php endif; ?>
