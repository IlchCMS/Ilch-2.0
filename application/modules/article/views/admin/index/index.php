<?php
if ($this->get('articles') != '') {
?>
<table class="table table-hover">
    <colgroup>
        <col class="col-xs-1">
        <col />
        <?php
            if ($this->get('multilingual')) {
                echo '<col />';
            }
        ?>
    </colgroup>
    <thead>
        <tr>
            <th><?php echo $this->getTranslator()->trans('treat'); ?></th>
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
            echo '<tr>
                    <td>';
             echo '<a href="'.$this->url(array('action' => 'treat', 'id' => $article->getId())).'"><i class="fa fa-edit"></i></a> ';
            ?>
                <span class="deleteArticle clickable fa fa-times-circle"
                              data-clickurl="<?php echo $this->url(array('module' => 'article', 'controller' => 'index', 'action' => 'delete', 'id' => $article->getId())); ?>"
                              data-toggle="modal"
                              data-target="#deleteModal"
                              data-modaltext="<?php echo $this->escape($this->trans('askIfDeleteArticle', $article->getTitle())); ?>"
                              title="<?php echo $this->trans('deleteArticle'); ?>"></span>
            <?php
            echo '</td>';
            echo '<td>';

            if ($article->getTitle() !== '') {
                echo '<a target="_blank" href="'.$this->url().'/index.php/'.$this->escape($article->getPerma()).'">'.$article->getTitle().'</a>';
            } else {
                echo 'Kein Datensatz für Sprache vorhanden';
            }
            echo '</td>';

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
<?php
} else {
    echo $this->getTranslator()->trans('noArticles');
}
?>

<script>
$('.deleteArticle').on('click', function(event) {
    $('#modalButton').data('clickurl', $(this).data('clickurl'));
    $('#modalText').html($(this).data('modaltext'));
});

$('#modalButton').on('click', function(event) {
    window.location = $(this).data('clickurl');
});
</script>
<style>
    .deleteLink {
        padding-left: 10px;
    }
</style>