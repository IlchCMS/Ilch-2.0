<?php
if ($this->get('boxes') != '') {
?>
<table class="table table-hover">
    <colgroup>
        <col class="col-lg-1">
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
            <th><?php echo $this->trans('boxTitle'); ?></th>
            <?php
                if ($this->get('multilingual')) {
                    echo '<th class="text-right">';

                    foreach ($this->getTranslator()->getLocaleList() as $key => $value) {
                        if ($key == $this->getTranslator()->getLocale()) {
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
        foreach ($this->get('boxes') as $box) {
            echo '<tr>
                    <td>';
             echo '<a href="'.$this->url(array('action' => 'treat', 'id' => $box->getId())).'"><i class="fa fa-edit"></i></a> ';
            ?>
                <span class="deleteBox clickable fa fa-times-circle"
                              data-clickurl="<?php echo $this->url(array('module' => 'box', 'controller' => 'index', 'action' => 'delete', 'id' => $box->getId())); ?>"
                              data-toggle="modal"
                              data-target="#deleteModal"
                              data-modaltext="<?php echo $this->escape($this->trans('askIfDeleteBox', $box->getTitle())); ?>"
                              title="<?php echo $this->trans('deleteBox'); ?>"></span>
            <?php
            echo '</td>';
            echo '<td>';

            if ($box->getTitle() !== '') {
                echo $box->getTitle();
            } else {
                echo 'Kein Datensatz für Sprache vorhanden';
            }
            echo '</td>';
            if ($this->get('multilingual')) {
                echo '<td class="text-right">';
                    foreach ($this->getTranslator()->getLocaleList() as $key => $value) {
                        if ($key == $this->getTranslator()->getLocale()) {
                            continue;
                        }
                        echo '<a href="'.$this->url(array('action' => 'treat', 'id' => $box->getId(), 'locale' => $key)).'">Edit</a>';
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
    echo $this->getTranslator()->trans('noBoxes');
}
?>

<script>
$('.deleteBox').on('click', function(event) {
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