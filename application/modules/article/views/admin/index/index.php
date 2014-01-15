<?php
if ($this->get('articles') != '') {
?>
<form class="form-horizontal" method="POST" action="">
<?=$this->getTokenField()?>
<table class="table table-hover">
    <colgroup>
        <col class="icon_width" />
        <col class="icon_width" />
        <col class="icon_width" />
        <col />
        <?php
            if ($this->get('multilingual')) {
                echo '<col />';
            }
        ?>
    </colgroup>
    <thead>
        <tr>
            <th><?=$this->getCheckAllCheckbox('check_articles')?></th>
            <th></th>
            <th></th>
            <th><?php echo $this->trans('articleTitle'); ?></th>
            <?php
                if ($this->get('multilingual')) {
                    echo '<th class="text-right">';

                    foreach ($this->getTranslator()->getLocaleList() as $key => $value) {
                        if ($key == $this->get('contentLanguage')) {
                            continue;
                        }

                        echo '<img src="'.$this->staticUrl('img/'.$key.'.png').'"> ';
                    }

                    echo '</th>';
                }
            ?>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($this->get('articles') as $article) {
        ?>
            <tr>
                <td><input value="<?=$article->getId()?>" type="checkbox" name="check_articles[]" /></td>
                <td><?=$this->getEditIcon(array('action' => 'treat', 'id' => $article->getId()))?></td>
                <td><?=$this->getDeleteIcon(array('action' => 'delete', 'id' => $article->getId()))?></td>
                <td>
                    <a target="_blank" href="<?=$this->url().'/index.php/'.$this->escape($article->getPerma())?>"><?=$article->getTitle()?></a>
                </td>
        <?php
            if ($this->get('multilingual')) {
                echo '<td class="text-right">';
                    foreach ($this->getTranslator()->getLocaleList() as $key => $value) {
                        if ($key == $this->get('contentLanguage')) {
                            continue;
                        }
                        
                        if ($this->get('articleMapper')->getArticleByIdLocale($article->getId(), $key) != null) {
                            echo '<a href="'.$this->url(array('action' => 'treat', 'id' => $article->getId(), 'locale' => $key)).'"><i class="fa fa-edit"></i></a>';
                        } else {
                            echo '<a href="'.$this->url(array('action' => 'treat', 'id' => $article->getId(), 'locale' => $key)).'"><i class="fa fa-plus-circle"></i></a>';
                        }
                            
                    }

                echo '</td>';
            }

            echo '</tr>';
        }
        ?>
    </tbody>
</table>
<?=$this->getListBar(array('delete' => 'delete'))?>
</form>
<?php
} else {
    echo $this->getTranslator()->trans('noArticles');
}
?>