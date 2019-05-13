<h1><?=$this->getTrans('deleteaccount') ?></h1>
<div class="form-group">
    <b>
    <?php if ($this->get('delete_time') > 0): ?>
    <?=$this->getTrans('deleteaccountwithdayInfoText', $this->get('delete_time')) ?>
    <?php else: ?>
    <?=$this->getTrans('deleteaccountInfoText') ?>
    <?php endif; ?>
    </b><br><br>
    <a href="<?=$this->getUrl(['action' => 'deleteaccount'], null, true) ?>" class="delete_button btn btn-danger btn-lg active" role="button" aria-pressed="true">
        <?=$this->getTrans('delete') ?>
    </a>
</div>
<script>
var deleteEntry = <?=json_encode($this->getTrans('deleteaccountquestion')) ?>;
</script>