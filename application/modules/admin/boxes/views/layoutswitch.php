<form action="get">
    <select class="layoutInput form-control" name="language">
        <?php foreach ($this->get('layouts') as $layout): ?>
            <?php $name = basename($layout); ?>
            <?php $sel = ''; ?>
            <?php if ((!isset($_SESSION['layout']) && $name == 'default') || (isset($_SESSION['layout']) && $_SESSION['layout'] == $name)): ?>
                <?php $sel = 'selected="selected"'; ?>
            <?php endif; ?>

            <option <?=$sel ?> value="<?=$name ?>"><?=$this->escape($name) ?></option>
        <?php endforeach; ?>
    </select>
</form>

<script>
$('.layoutInput').change (
    this,
    function () {
        top.location.href = '<?=$this->getUrl(array('action' => 'index')); ?>/ilch_layout/'+$(this).val()
    }
);
</script>
