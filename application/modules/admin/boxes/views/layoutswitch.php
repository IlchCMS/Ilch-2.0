<form action="get">
    <select class="form-control layoutInput" name="language">
    <?php foreach ($this->get('layouts') as $key => $name): ?>
        <?php $sel = ''; ?>
        <?php if ((!isset($_SESSION['layout']) && $key == $this->get('defaultLayout')) || (isset($_SESSION['layout']) && $_SESSION['layout'] == $key)): ?>
            <?php $sel = 'selected="selected"'; ?>
        <?php endif; ?>
        <option <?=$sel ?> value="<?=$key ?>"><?=$this->escape($name) ?></option>
    <?php endforeach; ?>
    </select>
</form>

<script>
$('.layoutInput').change (
    this,
    function () {
        top.location.href = '<?=$this->escape($this->getCurrentUrl(['ilch_layout' => '__LAYOUT_PLACEHOLDER__'], false)); ?>'
                .replace('__LAYOUT_PLACEHOLDER__', $(this).val());
    }
);
</script>
