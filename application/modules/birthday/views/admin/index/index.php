<link href="<?=$this->getModuleUrl('static/css/birthday.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('settings') ?></legend>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName())) ?>">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="entrySettings" class="col-lg-2 control-label">
            <?=$this->getTrans('numberOfBirthsdayShow') ?>:
        </label>
        <div class="col-lg-2 input-group">
            <div class="container">
                <div class="input-group spinner">
                    <input class="form-control"
                           type="text"
                           id="entrySettings"
                           name="entrySettings"
                           min="1"
                           value="<?=$this->get('setShow') ?>">
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

<script language="JavaScript" type="text/javascript">
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
