<?php

use Ilch\Date;

$newsletter = $this->get('newsletter');
$date = new Date($newsletter->getDateCreated());
?>

<p class="small text-muted"><?=$date->format('l, d. F Y', true) ?></p>
<p>&nbsp;</p>
<p><b><?=$newsletter->getSubject() ?></b></p>
<div class="ck-content"><?=$this->purify($newsletter->getText()) ?></div>
