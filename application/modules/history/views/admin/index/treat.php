<?php

/** @var \Ilch\View $this */

/** @var \Modules\History\Models\History $history */
$history = $this->get('history');
?>
<link rel="stylesheet" href="<?=$this->getModuleUrl('static/css/history.css') ?>">
<link href="<?=$this->getStaticUrl('js/tempus-dominus/dist/css/tempus-dominus.min.css') ?>" rel="stylesheet">

<h1>
    <?=($history->getId()) ? $this->getTrans('edit') : $this->getTrans('add') ?>
</h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="row mb-3 <?=$this->validation()->hasError('date') ? 'has-error' : '' ?>">
        <label for="date" class="col-lg-2 control-label">
            <?=$this->getTrans('date') ?>:
        </label>
        <div id="date" class="col-lg-2 input-group ilch-date date form_datetime">
            <?php
            $getDate = new \Ilch\Date($history->getDate() ?? 'now');
            ?>
            <input type="text"
                   class="form-control"
                   id="date"
                   name="date"
                   value="<?=$this->originalInput('date', $getDate->format('d.m.Y', true)) ?>"
                   readonly>
            <span class="input-group-text">
                <span class="fa-solid fa-calendar"></span>
            </span>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('title') ? 'has-error' : '' ?>">
        <label for="title" class="col-xl-2 control-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?=$this->originalInput('title', $history->getTitle()) ?>" />
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('text') ? 'has-error' : '' ?>">
        <label for="ck_1" class="col-xl-2 control-label">
            <?=$this->getTrans('text') ?>:
        </label>
        <div class="col-xl-10">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="text"
                      toolbar="ilch_html"
                      rows="5"><?=$this->originalInput('text', $history->getText()) ?></textarea>
        </div>
    </div>
    <div class="row mb-3">
        <label for="symbol" class="col-xl-2 control-label">
            <?=$this->getTrans('symbol') ?>:
        </label>
        <div class="col-xl-2 input-group ilch-date">
            <span class="input-group-text">
                <span id="chosensymbol" class="<?=$this->originalInput('symbol', $history->getType())  ?>"></span>
            </span>
            <input type="text"
                   class="form-control"
                   id="symbol"
                   name="symbol"
                   value="<?=$this->originalInput('symbol', $history->getType()) ?>"
                   disabled />
            <span class="input-group-text">
                <span class="fa-solid fa-mouse-pointer" data-bs-toggle="modal" data-bs-target="#symbolDialog"></span>
            </span>
        </div>
    </div>
    <div class="row mb-3">
        <label for="color" class="col-xl-2 control-label">
            <?=$this->getTrans('color') ?>:
        </label>
        <div class="col-xl-2 input-group ilch-date">
            <input class="form-control color {hash:true}"
                   id="color"
                   name="color"
                   value="<?=$this->originalInput('color', $history->getColor()) ? : '#75ce66' ?>">
            <span class="input-group-text">
                <span class="fa-solid fa-undo" onclick="document.getElementById('color').color.fromString('75ce66')"></span>
            </span>
        </div>
    </div>
    <?=($history->getId()) ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
</form>

<div class="modal fade" id="symbolDialog" tabindex="-1" role="dialog" aria-labelledby="symbolDialogTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header row">
                <h5 class="modal-title" id="symbolDialogTitle"><?=$this->getTrans('chooseIcon') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="col-6"><button type="button" class="btn btn-outline-secondary" id="noIcon" data-bs-dismiss="modal"><?=$this->getTrans('noIcon') ?></button></div>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?=$this->getTrans('close') ?></button>
            </div>
        </div>
    </div>
</div>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe style="border:0;"></iframe>') ?>
<script src="<?=$this->getStaticUrl('js/jscolor/jscolor.js') ?>"></script>
<script src="<?=$this->getStaticUrl('js/popper/dist/umd/popper.min.js') ?>" charset="UTF-8"></script>
<script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/js/tempus-dominus.min.js') ?>" charset="UTF-8"></script>
<?php if (strncmp($this->getTranslator()->getLocale(), 'en', 2) !== 0): ?>
    <script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/locales/'.substr($this->getTranslator()->getLocale(), 0, 2).'.js') ?>" charset="UTF-8"></script>
<?php endif; ?>
<script>
$(document).ready(function() {
    new tempusDominus.TempusDominus(document.getElementById('date'), {
        display: {
            calendarWeeks: true,
            buttons: {
                today: true,
                close: true
            },
            components: {
                clock: false
            }
        },
        localization: {
            locale: "<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>",
            startOfTheWeek: 1,
            format: "dd.MM.yyyy"
        }
    });

        // $(".form_datetime").datetimepicker({
            // format: "dd.mm.yyyy",
            // autoclose: true,
            // language: '<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>',
            // minView: 2,
            // todayHighlight: true
        // });

    $("#symbolDialog").on('shown.bs.modal', function () {
        let content = JSON.parse(<?=json_encode(file_get_contents(ROOT_PATH . '/vendor/fortawesome/font-awesome/metadata/icons.json')) ?>);
        let icons = [];

        $.each(content, function(index, icon) {
            if (~icon.styles.indexOf('brands')) {
                icons.push('fa-brands fa-' + index);
            } else {
                if (~icon.styles.indexOf('solid')) {
                    icons.push('fa-solid fa-' + index);
                }

                if (~icon.styles.indexOf('regular')) {
                    icons.push('fa-regular fa-' + index);
                }
            }
        })

        let div;
        let x;
        for (x = 0; x < icons.length;) {
            let y;
            div = '<div class="row">';
            for (y = x; y < x + 6; y++) {
                div += '<div class="icon col-xl-2"><i id="' + icons[y] + '" class="faicon ' + icons[y] + ' fa-2x"></i></div>';
            }
            div += '</div>';
            x = y;

            $("#symbolDialog .modal-content .modal-body").append(div);
        }

        $(".faicon").click(function () {
            $("#symbol").val($(this).closest("i").attr('id'));
            $("#chosensymbol").attr("class", $(this).closest("i").attr('id'));
            $("#symbolDialog").modal('hide')
        });

        $("#noIcon").click(function () {
            $("#symbol").val("");
        });
    });
});
</script>
