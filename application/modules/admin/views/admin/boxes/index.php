<?php
if ($this->get('boxes') != '') {
?>
<form class="form-horizontal" method="POST" action="">
<?php echo $this->getTokenField(); ?>
<table class="table table-hover">
    <colgroup>
        <col class="icon_width">
        <col class="icon_width">
        <col class="icon_width">
        <col />
        <?php
            if ($this->get('multilingual')) {
                echo '<col />';
            }
        ?>
    </colgroup>
    <thead>
        <tr>
            <th><?=$this->getCheckAllCheckbox('check_boxes')?></th>
            <th></th>
            <th></th>
            <th><?php echo $this->getTrans('boxTitle'); ?></th>
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
        <?php
        foreach ($this->get('boxes') as $box) {
            echo '<tr>
                    <td>
                        <input value="'.$box->getId().'" type="checkbox" name="check_boxes[]" />
                    </td>
                    <td>
                        <a href="'.$this->getUrl(array('action' => 'treat', 'id' => $box->getId())).'">
                            <i class="fa fa-edit"></i>
                        </a>
                    </td>
                    <td>
                        '.$this->getDeleteIcon(array('action' => 'delete', 'id' => $box->getId())).'
                    </td>
                    <td>';

            if ($box->getTitle() !== '') {
                echo $box->getTitle();
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
                        
                        if ($this->get('boxMapper')->getBoxByIdLocale($box->getId(), $key) != null) {
                            echo '<a href="'.$this->getUrl(array('action' => 'treat', 'id' => $box->getId(), 'locale' => $key)).'"><i class="fa fa-edit"></i></a>';
                        } else {
                            echo '<a href="'.$this->getUrl(array('action' => 'treat', 'id' => $box->getId(), 'locale' => $key)).'"><i class="fa fa-plus-circle"></i></a>';
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