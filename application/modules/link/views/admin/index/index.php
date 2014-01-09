<legend><?php echo $this->trans('manageLink'); ?></legend>
<?php
$categories = $this->get('categorys');
$links = $this->get('links');

if (!empty($categories)) {
?>
<table class="table table-bordered table-striped table-responsive">
    <colgroup>
        <col class="col-lg-1" />
<<<<<<< HEAD
        <col class="col-lg-2" />
        <col class="col-lg-5" />
=======
        <col />
        <col class="col-lg-1" />
>>>>>>> e4d3202f6ff91046d083383cf0ef3af3bb1f594c
    </colgroup>
    <thead>
        <tr>
            <th><?php echo $this->trans('treat'); ?></th>
            <th><?php echo $this->trans('category'); ?></th>
            <th style="text-align:center"><?php echo $this->trans('links'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($this->get('categorys') as $category) {
                echo '<tr>
                        <td>
                        <a href="'.$this->url(array('action' => 'treatCat', 'id' => $category->getId())).'" title="'.$this->trans('treat').'"><i class="fa fa-edit"></i></a> ';
        ?>
                    <span class="deleteLink clickable fa fa-trash-o fa-1x text-danger"
                                  data-clickurl="<?php echo $this->url(array('action' => 'deleteCat', 'id' => $category->getId())); ?>"
                                  data-toggle="modal"
                                  data-target="#deleteModal"
                                  data-modaltext="<?php echo $this->escape($this->trans('askIfDeleteCat', $this->escape($category->getName()))); ?>"
                                  title="<?php echo $this->trans('delete'); ?>"></span>
        <?php
                echo '</td>';
                $getDesc = $this->escape($category->getDesc());
                
                if ($getDesc != '') {
                    $getDesc = '&raquo; '.$this->escape($category->getDesc());
                }else{
                    $getDesc = '';
                }
                
                echo '<td><a href='.$this->url(array('action' => 'index', 'cat_id' => $category->getId())).' title="'.$this->escape($category->getName()).'">'.$this->escape($category->getName()).'</a><br>'.$getDesc.'</td>';    
                echo '<td align="center" style="vertical-align:middle">'.$category->getLinksCount().'</td>';
                echo '</tr>';
            }
        ?>
    </tbody>
</table>
<br />
<?php } ?>


<table class="table table-bordered table-striped table-responsive">
    <colgroup>
        <col class="col-lg-1" />
        <col />
    </colgroup>
    <thead>
        <tr>
            <th><?php echo $this->trans('treat'); ?></th>
            <th><?php echo $this->trans('links'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($links)) {
            foreach ($this->get('links') as $link) {
                echo '<tr>
                        <td>
                        <a href="'.$this->url(array('action' => 'treatLink', 'id' => $link->getId())).'" title="'.$this->trans('treat').'"><i class="fa fa-edit"></i></a> ';
        ?>
                    <span class="deleteLink clickable fa fa-trash-o fa-1x text-danger"
                                  data-clickurl="<?php echo $this->url(array('action' => 'deleteLink', 'id' => $link->getId())); ?>"
                                  data-toggle="modal"
                                  data-target="#deleteModal"
                                  data-modaltext="<?php echo $this->escape($this->trans('askIfDeleteLink', $this->escape($link->getName()))); ?>"
                                  title="<?php echo $this->trans('delete'); ?>"></span>
        <?php
                echo '</td>';
                $getBanner = $this->escape($link->getBanner());
                $getDesc = $this->escape($link->getDesc());
                
                if (!empty($getDesc)) {
                    $getDesc = '&raquo; '.$this->escape($link->getDesc());
                }else{
                    $getDesc = '';
                }
                
                if (!empty($getBanner)) {
                    $getBanner = '<img src="'.$this->escape($link->getBanner()).'">';
                }else{
                    $getBanner = $this->escape($link->getName());
                }
                
                echo '<td><a href='.$this->escape($link->getLink()).' target="_blank" title="'.$this->escape($link->getName()).'">'.$getBanner.'</a><br />'.$getDesc.'</td>';    
                echo '</tr>';
            }

        }  else {
            echo '<td colspan="2">'.$this->trans('noLinks').'</td>';
        } ?>
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

