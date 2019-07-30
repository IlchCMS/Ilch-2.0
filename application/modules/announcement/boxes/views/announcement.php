<?php
    $announcement = $this->get('announcement');
?>
<div class="row">
    <div class="col-md-12">
        <h3>Anouncements</h3>
        <p><?php echo (($announcement == null) ? 'Kein Announcement vorhanden!' : $announcement->getContent()) ?></p>
    </div>
</div>