<legend><?php echo $this->trans('manageLink'); ?></legend>
<table class="table table-bordered table-striped table-responsive">
    <colgroup>
        <col class="col-lg-1" />
        <col class="col-lg-2" />
        <col class="col-lg-5" />
    </colgroup>
    <thead>
        <tr>
            <th><?php echo $this->trans('treat'); ?></th>
            <th><?php echo $this->trans('name'); ?></th>
            <th><?php echo $this->trans('description'); ?></th>
            <th><?php echo $this->trans('category'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if ($this->get('links') != '') {
            foreach ($this->get('links') as $link) {
                $getBanner = $this->escape($link->getBanner());
                if ($getBanner != '') {
                    $getBanner = '<a href='.$this->escape($link->getLink()).' target="_blank" rel="popover" data-img="'.$this->escape($link->getBanner()).'">'.$this->escape($link->getName()).' <i class="fa fa-picture-o"></i></a>';
                }else{
                    $getBanner = '<a href='.$this->escape($link->getLink()).' target="_blank">'.$this->escape($link->getName()).'</a>';
                }
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
                echo '</td>';
                echo '<td>'.$getBanner.'</td>';
                echo '<td>'.$this->escape($link->getDesc()).'</td>';
                echo '<td>'.$this->escape($link->getCatId()).'</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr>';
            echo '<td colspan="4">'.$this->trans('noLinks').'</td>';
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

$(function () {
    $('a[rel=popover]').popover({
        html: true,
        trigger: 'hover',
        placement: 'right',
        title: 'Banner Vorschau',
        content: function(){return '<img src="'+$(this).data('img') + '" />';}
    });
});
</script>
<style>
    .deleteLink {
        padding-left: 10px;
    }
    .popover{
        display:block !important;
        max-width: 500px!important;
        width:auto;
    }
</style>

