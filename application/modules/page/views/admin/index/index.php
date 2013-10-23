<?php
if ($this->get('pages') != '') {
?>
<table class="table table-hover">
    <colgroup>
        <col class="col-lg-1">
        <col />
        <col class="col-lg-1">
        <col class="col-lg-1">
    </colgroup>
    <thead>
        <tr>
            <th>Editieren</th>
            <th><?php echo $this->trans('pageTitle'); ?></th>
            <th><?php echo $this->trans('pageAdress'); ?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($this->get('pages') as $page) {
            echo '<tr>
                    <td>';
            foreach ($this->getTranslator()->getLocaleList() as $key => $value) {
                echo '<a href="'.$this->url(array('module' => 'page', 'controller' => 'index', 'action' => 'change', 'id' => $page->getId(), 'locale' => $key)).'"><img src="'.$this->staticUrl('img/'.$key.'.png').'"></a> ';
            }

            echo '</td>
                    <td>'.$page->getTitle().'</td>
                    <td><a target="_blank" href="'.$this->url().'/index.php/'.$this->escape($page->getPerma()).'">Öffnen</a></td>
                    <td><a href="'.$this->url(array('module' => 'page', 'controller' => 'index', 'action' => 'delete', 'id' => $page->getId())).'">Löschen</a></td>
                </tr>';
        }
        ?>
    </tbody>
</table>
<?php
} else {
    echo 'Keine Seiten vorhanden';
}
