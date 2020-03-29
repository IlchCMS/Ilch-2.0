<?php
$adate = new \Ilch\Date(); 
$history = $this->get('history');

if ($history != '') {
    $getDate = new \Ilch\Date($history->getDate());
    $date = $getDate->format('d.m.Y', true);
}
?>

<link rel="stylesheet" href="<?=$this->getModuleUrl('static/css/history.css') ?>">
<link href="<?=$this->getStaticUrl('js/datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">

<h1>
    <?=($history != '') ? $this->getTrans('edit') : $this->getTrans('add') ?>
</h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('date') ? 'has-error' : '' ?>">
        <label for="date" class="col-lg-2 control-label">
            <?=$this->getTrans('date') ?>:
        </label>
        <div class="col-lg-2 input-group ilch-date date form_datetime">
            <input type="text"
                   class="form-control"
                   id="date"
                   name="date"
                   value="<?php if ($history != '') { echo $date; } else { echo $this->get('post')['date']; } ?>"
                   readonly>
            <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
            </span>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('title') ? 'has-error' : '' ?>">
        <label for="title" class="col-lg-2 control-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?php if ($history != '') { echo $this->escape($history->getTitle()); } else { echo $this->get('post')['title']; } ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('text') ? 'has-error' : '' ?>">
        <label for="ck_1" class="col-lg-2 control-label">
            <?=$this->getTrans('text') ?>:
        </label>
        <div class="col-lg-10">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="text"
                      toolbar="ilch_html"
                      rows="5"><?php if ($history != '') { echo $this->escape($history->getText()); } else { echo $this->get('post')['text']; } ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="type" class="col-lg-2 control-label">
            <?=$this->getTrans('symbol') ?>:
        </label>
        <div class="col-lg-2 input-group ilch-date">
            <span class="input-group-addon">
                <span id="chosensymbol" class="<?=($history != '') ? $this->escape($history->getType()) : $this->get('post')['symbol'] ?>"></span>
            </span>
            <input type="text"
                   class="form-control"
                   id="symbol"
                   name="symbol"
                   value="<?=($history != '') ? $this->escape($history->getType()) : $this->get('post')['symbol'] ?>"
                   readonly />
            <span class="input-group-addon">
                <span class="fas fa-mouse-pointer" data-toggle="modal" data-target="#symbolDialog"></span>
            </span>
        </div>
    </div>
    <div class="form-group">
        <label for="color" class="col-lg-2 control-label">
            <?=$this->getTrans('color') ?>:
        </label>
        <div class="col-lg-2 input-group ilch-date">
            <input class="form-control color {hash:true}"
                   id="color"
                   name="color"
                   value="<?php if ($history != '') { echo $history->getColor(); } else { echo '#75ce66'; } ?>">
            <span class="input-group-addon">
                <span class="fa fa-undo" onclick="document.getElementById('color').color.fromString('75ce66')"></span>
            </span>
        </div>
    </div>
    <?=($history != '') ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
</form>

<div class="modal fade" id="symbolDialog" tabindex="-1" role="dialog" aria-labelledby="symbolDialogTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="symbolDialogTitle"><?=$this->getTrans('chooseIcon') ?></h5>
                <button type="button" class="btn" id="noIcon" data-dismiss="modal"><?=$this->getTrans('noIcon') ?></button>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?=$this->getTrans('close') ?></button>
            </div>
        </div>
    </div>
</div>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
<script src="<?=$this->getStaticUrl('js/jscolor/jscolor.js') ?>"></script>
<script src="<?=$this->getStaticUrl('js/datetimepicker/js/bootstrap-datetimepicker.min.js') ?>" charset="UTF-8"></script>
<?php if (strncmp($this->getTranslator()->getLocale(), 'en', 2) !== 0): ?>
    <script src="<?=$this->getStaticUrl('js/datetimepicker/js/locales/bootstrap-datetimepicker.'.substr($this->getTranslator()->getLocale(), 0, 2).'.js') ?>" charset="UTF-8"></script>
<?php endif; ?>
<script>
$(document).ready(function() {
    $(".form_datetime").datetimepicker({
        format: "dd.mm.yyyy",
        autoclose: true,
        language: '<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>',
        minView: 2,
        todayHighlight: true
    });

    $("#symbolDialog").on('shown.bs.modal', function (e) {
        let content = JSON.parse(<?=json_encode(file_get_contents(ROOT_PATH.'/vendor/fortawesome/font-awesome/metadata/icons.json')) ?>);
        let icons = [];

        $.each(content, function(index, icon) {
            if (icon.styles == 'brands') {
                icons.push('fab fa-' + index);
            } else if (icon.styles == 'solid') {
                icons.push('fas fa-' + index);
            } else if (icon.styles == 'regular') {
                icons.push('far fa-' + index);
            }
        })

        for (var x = 0; x < icons.length;) {
            div = '<div class="row">';
            for (var y = x; y < x+6; y++) {
                div += '<div class="icon col-lg-2"><i id="'+icons[y]+'" class="faicon '+icons[y]+' fa-2x"></i></div>';
            }
            div += '</div>';
            x = y;

            $("#symbolDialog .modal-content .modal-body").append(div);
        }

        $(".faicon").click(function (e) {
            $("#symbol").val($(this).closest("i").attr('id'));
            $("#chosensymbol").attr("class", $(this).closest("i").attr('id'));
            $("#symbolDialog").modal('hide')
        });

        $("#noIcon").click(function (e) {
            $("#symbol").val("");
        });
    });
});
</script>
