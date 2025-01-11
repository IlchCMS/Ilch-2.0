<?php

/** @var \Ilch\View $this */

/** @var \Modules\Imprint\Models\Imprint $imprint */
$imprint = $this->get('imprint');
?>
<h1><?=$this->getTrans('menuImprint') ?></h1>
<div class="ck-content"><?=$this->purify($imprint->getImprint()) ?></div>
