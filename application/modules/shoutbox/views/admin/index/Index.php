<legend><?php echo $this->trans('manageShoutbox'); ?></legend>
<?php
$shoutboxs = $this->get('shoutbox');

if (!empty($shoutboxs)) {
?>
<table class="table table-bordered table-striped table-responsive">
    <?php foreach ($this->get('shoutbox') as $shoutbox) : ?>
    <tr>
        <td><span class="deleteLink clickable fa fa-trash-o fa-1x text-danger"
                            data-clickurl="<?php echo $this->url(array('action' => 'delete', 'id' => $shoutbox->getId())); ?>"
                            data-toggle="modal"
                            data-target="#deleteModal"
                            data-modaltext="<?php echo $this->escape($this->trans('askIfDeleteShoutbox', $this->escape($shoutbox->getName()))); ?>"
                            title="<?php echo $this->trans('delete'); ?>"></span>
                            <b> <?php echo $this->escape($shoutbox->getName()); ?>:</b> <span style="font-size:12px"><?php echo $shoutbox->getTime(); ?></span></td>  
    </tr>
    <tr>             
        <td colspan="2"><?php echo $this->escape($shoutbox->getTextarea()); ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php
}  else {
    echo $this->trans('noEntrys');
} ?>

<script>
$('.deleteLink').on('click', function(event) {
    $('#modalButton').data('clickurl', $(this).data('clickurl'));
    $('#modalText').html($(this).data('modaltext'));
});

$('#modalButton').on('click', function(event) {
    window.location = $(this).data('clickurl');
});
</script>