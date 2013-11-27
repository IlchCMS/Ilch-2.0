<?php
if ($this->get('pages') != '') {
?>
<table class="table table-hover">
    <colgroup>
        <col class="col-lg-1">
        <col />
    </colgroup>
    <thead>
        <tr>
            <th><?php echo $this->getTranslator()->trans('treat'); ?></th>
            <th><?php echo $this->trans('pageTitle'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($this->get('pages') as $page) {
            echo '<tr>
                    <td>';
            foreach ($this->getTranslator()->getLocaleList() as $key => $value) {
                echo '<a href="'.$this->url(array('module' => 'page', 'controller' => 'index', 'action' => 'treat', 'id' => $page->getId(), 'locale' => $key)).'"><img src="'.$this->staticUrl('img/'.$key.'.png').'"></a> ';
            }
            
            ?>
                <span class="deletePage clickable fa fa-times-circle"
                              data-clickurl="<?php echo $this->url(array('module' => 'page', 'controller' => 'index', 'action' => 'delete', 'id' => $page->getId())); ?>"
                              data-toggle="modal"
                              data-target="#deleteModal"
                              data-modaltext="<?php echo $this->escape($this->trans('askIfDeletePage', $page->getTitle())); ?>"
                              title="<?php echo $this->trans('deletePage'); ?>"></span>
            <?php
            echo '</td>
                  <td><a target="_blank" href="'.$this->url().'/index.php/'.$this->escape($page->getPerma()).'">'.$page->getTitle().'</a></td>
                </tr>';
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