<?php
if ($this->get('receivers') != '') {
?>
<table class="table table-hover">
    <colgroup>
        <col class="col-xs-1" />
        <col class="col-xs-2" />
        <col class="col-xs-2" />
    </colgroup>
    <thead>
        <tr>
            <th><?php echo $this->trans('treat'); ?></th>
            <th><?php echo $this->trans('name'); ?></th>
            <th><?php echo $this->trans('email'); ?></th>
        </tr>
    </thead>
    <tbody>
<?php
        foreach ($this->get('receivers') as $receiver) {
            echo '<tr>
                    <td>
                    <a href="'.$this->url(array('action' => 'treat', 'id' => $receiver->getId())).'"><i class="fa fa-edit"></i></a> ';
?>
                <span class="deleteReceiver clickable fa fa-times-circle"
                              data-clickurl="<?php echo $this->url(array('action' => 'delete', 'id' => $receiver->getId())); ?>"
                              data-toggle="modal"
                              data-target="#deleteModal"
                              data-modaltext="<?php echo $this->escape($this->trans('askIfDeleteReceiver', $this->escape($receiver->getName()))); ?>"
                              title="<?php echo $this->trans('deleteReceiver'); ?>"></span>
<?php
            echo '</td>';
            echo '<td>'.$this->escape($receiver->getName()).'</td>';
            echo '<td>'.$this->escape($receiver->getEmail()).'</td>';
            echo '</tr>';
        }
?>
    </tbody>
</table>
<?php
} else {
    echo $this->trans('noReceivers');
}
?>

<script>
$('.deleteReceiver').on('click', function(event) {
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