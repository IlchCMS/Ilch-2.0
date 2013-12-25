<?php
if ($this->get('boxes') != '') {
?>
<table class="table table-hover">
    <colgroup>
        <col class="col-lg-1">
        <col />
    </colgroup>
    <thead>
        <tr>
            <th><?php echo $this->getTranslator()->trans('treat'); ?></th>
            <th><?php echo $this->trans('boxTitle'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($this->get('boxes') as $box) {
            echo '<tr>
                    <td>';
            foreach ($this->getTranslator()->getLocaleList() as $key => $value) {
                echo '<a href="'.$this->url(array('module' => 'box', 'controller' => 'index', 'action' => 'treat', 'id' => $box->getId(), 'locale' => $key)).'"><img src="'.$this->staticUrl('img/'.$key.'.png').'"></a> ';
            }
            
            ?>
                <span class="deleteBox clickable fa fa-times-circle"
                              data-clickurl="<?php echo $this->url(array('module' => 'box', 'controller' => 'index', 'action' => 'delete', 'id' => $box->getId())); ?>"
                              data-toggle="modal"
                              data-target="#deleteModal"
                              data-modaltext="<?php echo $this->escape($this->trans('askIfDeleteBox', $box->getTitle())); ?>"
                              title="<?php echo $this->trans('deleteBox'); ?>"></span>
            <?php
            echo '</td>
                  <td>'.$this->escape($box->getTitle()).'</td>
                </tr>';
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