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
            <th>Bearbeiten</th>
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

            echo ' <a class="deleteLink" href="'.$this->url(array('module' => 'page', 'controller' => 'index', 'action' => 'delete', 'id' => $page->getId())).'">
                        <i class="fa fa-times-circle"></i>
                    </a>
                    </td>
                    <td><a target="_blank" href="'.$this->url().'/index.php/'.$this->escape($page->getPerma()).'">'.$page->getTitle().'</a></td>
                </tr>';
        }
        ?>
    </tbody>
</table>
<?php
} else {
    echo 'Keine Seiten vorhanden';
}
?>
<style>
    .deleteLink {
        padding-left: 10px;
    }
</style>