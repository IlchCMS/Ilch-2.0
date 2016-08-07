<style>
div.input-group-option:last-child span.input-group-addon-remove{
    display: none;
}
div.input-group-option:last-child input.form-control{
    border-bottom-right-radius: 3px;
    border-top-right-radius: 3px;
}
div.input-group-option span.input-group-addon-remove{
    cursor: pointer;
}
div.input-group-option{
    margin-bottom: 3px;
}
</style>

<legend><?=$this->getTrans('newCat') ?></legend>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group form-group-options">
        <div class="input-group input-group-option col-lg-6 col-md-6 col-xs-12">
            <input type="text" class="form-control" name="title_option[]" placeholder="<?=$this->getTrans('catTitle') ?>">
            <span class="input-group-addon input-group-addon-remove">
                <span class="fa fa-times"></span>
            </span>
        </div>
    </div>
    <?=$this->getSaveBar('saveButton') ?>
</form>

<script>
$(function() {
    $(document).on('focus', 'div.form-group-options div.input-group-option:last-child input', function() {
        var sInputGroupHtml = $(this).parent().html();
        var sInputGroupClasses = $(this).parent().attr('class');
        $(this).parent().parent().append('<div class="'+sInputGroupClasses+'">'+sInputGroupHtml+'</div>');
    });

    $(document).on('click', 'div.form-group-options .input-group-addon-remove', function() {
        $(this).parent().remove();
    });
});
</script>
