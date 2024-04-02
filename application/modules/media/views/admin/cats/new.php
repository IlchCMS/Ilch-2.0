<style>
div.input-group:last-child span.input-group-text-remove {
    display: none;
}

div.input-group span.input-group-text-remove {
    cursor: pointer;
}

div.input-group {
    margin-bottom: 3px;
    width: 55% !important;
}
</style>

<h1><?=$this->getTrans('newCat') ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3 form-group-options">
        <div class="input-group col-12">
            <input type="text" class="form-control" name="title_option[]" placeholder="<?=$this->getTrans('catTitle') ?>">
            <span class="input-group-text input-group-text-remove">
                <span class="fa fa-times"></span>
            </span>
        </div>
    </div>
    <?=$this->getSaveBar('saveButton') ?>
</form>

<script>
$(function() {
    $(document).on('focus', 'div.form-group-options div.input-group:last-child input', function() {
        var sInputGroupHtml = $(this).parent().html();
        var sInputGroupClasses = $(this).parent().attr('class');
        $(this).parent().parent().append('<div class="'+sInputGroupClasses+'">'+sInputGroupHtml+'</div>');
    });

    $(document).on('click', 'div.form-group-options .input-group-text-remove', function() {
        $(this).parent().remove();
    });
});
</script>
