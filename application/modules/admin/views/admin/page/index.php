<h1><?=$this->getTrans('manage') ?></h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="icon_width">
                <col class="icon_width">
                <col class="icon_width">
                <col>
                <?php if ($this->get('multilingual')): ?>
                    <col class="col-lg-1">
                <?php endif; ?>
            </colgroup>
            <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_pages') ?></th>
                    <th></th>
                    <th></th>
                    <th><?=$this->getTrans('pageTitle') ?></th>
                    <?php
                    if ($this->get('multilingual')) {
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
                <?php if (!empty($this->get('pages'))): ?>
                    <?php foreach ($this->get('pages') as $page): ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_pages', $page->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $page->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'delete', 'id' => $page->getId()]) ?></td>
                            <td>
                                <a target="_blank" href="<?=$this->getUrl().'/index.php/'.$this->escape($page->getPerma()) ?>"><?=$this->escape($page->getTitle()) ?></a>
                            </td>
                            <?php
                            if ($this->get('multilingual')) {
                                echo '<td class="text-right">';
                                foreach ($this->getTranslator()->getLocaleList() as $key => $value) {
                                    if ($key == $this->get('contentLanguage')) {
                                        continue;
                                    }

                                    if ($this->get('pageMapper')->getPageByIdLocale($page->getId(), $key) != null) {
                                        echo '<a href="'.$this->getUrl(['action' => 'treat', 'id' => $page->getId(), 'locale' => $key]).'"><i class="fa fa-edit"></i></a>';
                                    } else {
                                        echo '<a href="'.$this->getUrl(['action' => 'treat', 'id' => $page->getId(), 'locale' => $key]).'"><i class="fa fa-plus-circle"></i></a>';
                                    }
                                }
                                echo '</td>';
                            }
                            ?>
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
