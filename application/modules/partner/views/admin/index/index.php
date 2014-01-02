<?php
if ($this->get('partners') != '') {
?>
<table class="table table-hover">
    <colgroup>
        <col class="col-lg-1" />
    </colgroup>
    <thead>
        <tr>
            <th><?php echo $this->trans('treat'); ?></th>
            <th><?php echo $this->trans('name'); ?></th>
        </tr>
    </thead>
    <tbody>
<?php
        foreach ($this->get('partners') as $partner) {
            echo '<tr>
                    <td>
                    <a href="'.$this->url(array('action' => 'treat', 'id' => $partner->getId())).'" title="'.$this->trans('treat').'"><i class="fa fa-edit"></i></a> ';
?>
                <span class="deletePartner clickable fa fa-times-circle"
                              data-clickurl="<?php echo $this->url(array('action' => 'delete', 'id' => $partner->getId())); ?>"
                              data-toggle="modal"
                              data-target="#deleteModal"
                              data-modaltext="<?php echo $this->escape($this->trans('askIfDeletePartner', $this->escape($partner->getName()))); ?>"
                              title="<?php echo $this->trans('delete'); ?>"></span>
<?php
            echo '</td>';
            echo '<td>'.$this->escape($partner->getName()).'</td>';
            echo '</tr>';
        }
?>
    </tbody>
</table>
<?php
} else {
    echo $this->trans('noPartners');
}
?>

<script>
$('.deletePartner').on('click', function(event) {
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