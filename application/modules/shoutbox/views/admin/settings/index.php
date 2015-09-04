<link href="<?=$this->getModuleUrl('static/css/shoutbox.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('settings') ?></legend>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName())) ?>">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="limit" class="col-lg-2 control-label">
            <?=$this->getTrans('numberOfMessagesDisplayed') ?>:
        </label>
        <div class="col-lg-2">
            <div class="container">
                <div class="input-group spinner limit">
                    <input type="text" class="form-control" id="limit" name="limit" value="<?=$this->get('limit') ?>">
                    <div class="input-group-btn-vertical">
                        <span class="btn btn-default"><i class="fa fa-caret-up"></i></span>
                        <span class="btn btn-default"><i class="fa fa-caret-down"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="maxwordlength" class="col-lg-2 control-label">
            <?=$this->getTrans('maximumWordLength') ?>:
        </label>
        <div class="col-lg-2">
            <div class="container">
                <div class="input-group spinner maxwordlength">
                    <input type="text" class="form-control" id="maxwordlength" name="maxwordlength" value="<?=$this->get('maxwordlength') ?>">
                    <div class="input-group-btn-vertical">
                        <span class="btn btn-default"><i class="fa fa-caret-up"></i></span>
                        <span class="btn btn-default"><i class="fa fa-caret-down"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<script type="text/javascript">
(function ($) {
    $('.limit .btn:first-of-type').on('click', function() {
        $('.limit input').val( parseInt($('.limit input').val(), 10) + 1);
    });
    $('.limit .btn:last-of-type').on('click', function() {
        $('.limit input').val( parseInt($('.limit input').val(), 10) - 1);
    });

    $('.maxwordlength .btn:first-of-type').on('click', function() {
        $('.maxwordlength input').val( parseInt($('.maxwordlength input').val(), 10) + 1);
    });
    $('.maxwordlength .btn:last-of-type').on('click', function() {
        $('.maxwordlength input').val( parseInt($('.maxwordlength input').val(), 10) - 1);
    });
})(jQuery);
</script>
