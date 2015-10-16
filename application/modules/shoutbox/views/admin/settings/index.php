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
                <div class="input-group spinner">
                    <input type="text" class="form-control" id="limit" name="limit" min="1" value="<?=$this->get('limit') ?>">
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
                <div class="input-group spinner">
                    <input type="text" class="form-control" id="maxwordlength" name="maxwordlength" min="1" value="<?=$this->get('maxwordlength') ?>">
                    <div class="input-group-btn-vertical">
                        <span class="btn btn-default"><i class="fa fa-caret-up"></i></span>
                        <span class="btn btn-default"><i class="fa fa-caret-down"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="maxtextlength" class="col-lg-2 control-label">
            <?=$this->getTrans('maximumTextLength') ?>:
        </label>
        <div class="col-lg-2">
            <div class="container">
                <div class="input-group spinner">
                    <input type="text" class="form-control" id="maxwordlength" name="maxtextlength" min="1" value="<?=$this->get('maxtextlength') ?>">
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
$(function() {
    $('.spinner .btn:first-of-type').on('click', function() {
        var btn = $(this);
        var input = btn.closest('.spinner').find('input');
        if (input.attr('max') == undefined || parseInt(input.val()) < parseInt(input.attr('max'))) {
            input.val(parseInt(input.val(), 10) + 1);
        } else {
            btn.next("disabled", true);
        }
    });
    $('.spinner .btn:last-of-type').on('click', function() {
        var btn = $(this);
        var input = btn.closest('.spinner').find('input');
        if (input.attr('min') == undefined || parseInt(input.val()) > parseInt(input.attr('min'))) {
            input.val(parseInt(input.val(), 10) - 1);
        } else {
            btn.prev("disabled", true);
        }
    });
})
</script>
