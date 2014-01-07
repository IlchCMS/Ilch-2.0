<?php
if ($this->get('pages') != '') {
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
            <th><?php echo $this->trans('pageTitle'); ?></th>
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
        foreach ($this->get('pages') as $page) {
            echo '<tr>
                    <td>';
             echo '<a href="'.$this->url(array('action' => 'treat', 'id' => $page->getId())).'"><i class="fa fa-edit"></i></a> ';
            ?>
                <span class="deletePage clickable fa fa-times-circle"
                              data-clickurl="<?php echo $this->url(array('module' => 'page', 'controller' => 'index', 'action' => 'delete', 'id' => $page->getId())); ?>"
                              data-toggle="modal"
                              data-target="#deleteModal"
                              data-modaltext="<?php echo $this->escape($this->trans('askIfDeletePage', $page->getTitle())); ?>"
                              title="<?php echo $this->trans('deletePage'); ?>"></span>
            <?php
            echo '</td>';
            echo '<td>';

            if ($page->getTitle() !== '') {
                echo '<a target="_blank" href="'.$this->url().'/index.php/'.$this->escape($page->getPerma()).'">'.$page->getTitle().'</a>';
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
                        
                        if ($this->get('pageMapper')->getPageByIdLocale($page->getId(), $key) != null) {
                            echo '<a href="'.$this->url(array('action' => 'treat', 'id' => $page->getId(), 'locale' => $key)).'"><i class="fa fa-edit"></i></a>';
                        } else {
                            echo '<a href="'.$this->url(array('action' => 'treat', 'id' => $page->getId(), 'locale' => $key)).'"><i class="fa fa-plus-circle"></i></a>';
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
    echo $this->getTranslator()->trans('noPages');
}
?>

<script>
$('.deletePage').on('click', function(event) {
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