<?php
$newsletter = $this->get('newsletter');
$date = new \Ilch\Date($newsletter->getDateCreated());
?>

<p class="small text-muted"><?=$date->format("l, d. F Y", true) ?></p>
<p>&nbsp;</p>
<p><b><?=$newsletter->getSubject() ?></b></p>
<p><?=$newsletter->getText() ?></p>