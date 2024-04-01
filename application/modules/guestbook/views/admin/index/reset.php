<h1><?=$this->getTrans('reset') ?></h1>
<div class="row mb-3">
    <div class="container">
        <a href="<?=$this->getUrl(['action' => 'reset'], null, true) ?>" class="btn btn-danger active delete_button" role="button" aria-pressed="true"><?=$this->getTrans('resetall') ?></a>
    </div>
</div>
