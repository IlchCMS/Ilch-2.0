<h1><?=$this->getTrans('reset') ?></h1>
<div class="row mb-3">
    <a href="<?=$this->getUrl(['action' => 'reset'], null, true) ?>" class="btn btn-danger btn-xl active delete_button" role="button" aria-pressed="true"><?=$this->getTrans('resetall') ?></a>
</div>
