<?php

$announcements = $this->get('announcements');
?>

<link href="<?=$this->getModuleUrl('static/css/birthday.css') ?>" rel="stylesheet">

<h1><?php echo $this->getTrans('settings'); ?></h1>

<table class="table table-striped">
    <tr>
        <th>Content</th><th>Action</th>        
    </tr>
    <?php
        $announcements->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);
        for ($announcements->rewind(); $announcements->valid(); $announcements->next())
        {
            $announcement = $announcements->current();
            $id = $announcement->getID();

            echo " <tr>
                    <td>" . substr($announcement->getContent(), 0, 100) . "</td>
                    <td><a  class='btn btn-success " . (($announcement->isActive()) ? 'disabled' : '') . "'
                            href='" . $this->getUrl(['controller' => 'index', 'action' => 'activate', 'id' => $id]) . "'>Annoucement Aktivieren</a>
                        <a  class='btn btn-warning'
                            href='" . $this->getUrl(['controller' => 'index', 'action' => 'edit', 'id' => $id]) . "'>Bearbeiten</a>
                        <a  class='btn btn-danger'
                            href='" . $this->getUrl(['controller' => 'index', 'action' => 'delete', 'id' => $id]) . "'>Löschen</a></td>
                   </tr>";
        }

        if($announcements->count() < 1)
        {
            echo "<tr><td class='text-center' colspan='2'>Keine Announcements vorhanden.</td></tr>";
        }

    ?>    
</table>
