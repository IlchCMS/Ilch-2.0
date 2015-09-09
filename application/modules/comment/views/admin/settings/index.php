<link href="<?=$this->getModuleUrl('static/css/comment.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('settings') ?></legend>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName())) ?>">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="interleaving" class="col-lg-2 control-label">
            <?=$this->getTrans('interleaving') ?>:
        </label>
        <div class="col-lg-2">
            <div class="container">
                <div class="input-group spinner interleaving">
                    <input type="text" class="form-control" id="interleaving" name="interleaving" value="<?=$this->get('comment_interleaving') ?>">
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
    $('.interleaving .btn:first-of-type').on('click', function() {
        $('.interleaving input').val( parseInt($('.interleaving input').val(), 10) + 1);
    });
    $('.interleaving .btn:last-of-type').on('click', function() {
        $('.interleaving input').val( parseInt($('.interleaving input').val(), 10) - 1);
    });
})(jQuery);
</script>
