<link href="<?=$this->getModuleUrl('static/css/article.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('settings') ?></legend>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName())) ?>">
    <?=$this->getTokenField() ?>   
    <div class="form-group">
        <label for="articlePerPage" class="col-lg-2 control-label">
            <?=$this->getTrans('articlePerPage') ?>:
        </label>
        <div class="col-lg-2">
            <div class="container">
                <div class="input-group spinner">
                    <input type="text" class="form-control" id="articlePerPage" name="articlePerPage" min="0" value="<?=$this->get('articlePerPage') ?>">
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
