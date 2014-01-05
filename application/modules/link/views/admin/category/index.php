<legend><?php echo $this->trans('manageCategory'); ?></legend>
<table class="table table-bordered table-striped table-responsive">
    <colgroup>
        <col class="col-lg-1" />
        <col class="col-lg-2" />
    </colgroup>
    <thead>
        <tr>
            <th><?php echo $this->trans('treat'); ?></th>
            <th><?php echo $this->trans('name'); ?></th>
            <th><?php echo $this->trans('description'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if ($this->get('categorys') != '') { 
            foreach ($this->get('categorys') as $link) {
                echo '<tr>
                        <td>
                        <a href="'.$this->url(array('action' => 'treat', 'id' => $link->getId())).'" title="'.$this->trans('treat').'"><i class="fa fa-edit"></i></a> ';
        ?>
                    <span class="deleteLink clickable fa fa-times-circle"
                                  data-clickurl="<?php echo $this->url(array('action' => 'delete', 'id' => $link->getId())); ?>"
                                  data-toggle="modal"
                                  data-target="#deleteModal"
                                  data-modaltext="<?php echo $this->escape($this->trans('askIfDeleteLink', $this->escape($link->getName()))); ?>"
                                  title="<?php echo $this->trans('delete'); ?>"></span>
        <?php
                echo '</td>';echo '<td>'.$this->escape($link->getName()).'</td>';
                echo '<td>'.$this->escape($link->getDesc()).'</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr>';
            echo '<td colspan="4">'.$this->trans('noCategory').'</td>';
            echo '</tr>';
        }
?>
    </tbody>
</table>

<script>
$('.deleteLink').on('click', function(event) {
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