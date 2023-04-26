<?php
$typeArray = ['profileFieldField', 'profileFieldCat', 'profileFieldIcon', 'profileFieldRadio', 'profileFieldCheck', 'profileFieldDrop', 'profileFieldDate'];
$iconArray = ['fa-regular fa-square', 'fa fa-list', 'fa-regular fa-face-smile', 'fa-regular fa-circle-check', 'fa-regular fa-square-check', 'far fa-caret-square-down', 'fa-regular fa-calendar-days'];
?>
<link href="<?=$this->getModuleUrl('static/css/profile-fields.css') ?>" rel="stylesheet">
<h1>
    <?=$this->getTrans('menuProfileFields') ?>
    <a class="badge" data-toggle="modal" data-target="#infoModal">
        <i class="fa-solid fa-info"></i>
    </a>
</h1>
<form class="form-horizontal" id="catsIndexForm" method="POST">
    <?=$this->getTokenField() ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="icon_width" />
                <col class="icon_width" />
                <col class="icon_width" />
                <col class="icon_width" />
                <col />
                <col />
                <col class="icon_width" />
            </colgroup>
            <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_users') ?></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th><?=$this->getTrans('profileFieldName') ?></th>
                    <th><?=$this->getTrans('profileFieldType') ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="sortable">
                <?php
                $profileFields = $this->get('profileFields');
                $profileFieldsTranslation = $this->get('profileFieldsTranslation');

                foreach ($profileFields as $profileField):
                ?>
                <tr id="<?=$profileField->getId() ?>">

                    <?php if ($profileField->getHidden() == 0): ?>
                        <td><?=$this->getDeleteCheckbox('check_users', $profileField->getId()) ?></td>
                        <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $profileField->getId()]) ?></td>
                        <td><?=$this->getDeleteIcon(['action' => 'delete', 'id' => $profileField->getId()]) ?></td>
                    <?php else: ?>
                        <td colspan="3"></td>
                    <?php endif; ?>
                    <td>
                        <?php if ($profileField->getShow() == 1): ?>
                            <a href="<?=$this->getUrl(['action' => 'update', 'id' => $profileField->getId()], null, true) ?>">
                                <span class="fa-regular fa-square-check text-info"></span>
                            </a>
                        <?php else: ?>
                            <a href="<?=$this->getUrl(['action' => 'update', 'id' => $profileField->getId()], null, true) ?>">
                                <span class="fa-regular fa-square text-info"></span>
                            </a>
                        <?php endif; ?>
                    </td>
                    <?php
                    $found = false;

                    foreach ($profileFieldsTranslation as $profileFieldTrans) {
                        if ($profileField->getId() == $profileFieldTrans->getFieldId()) {
                            $profileFieldName = $profileFieldTrans->getName();
                            $found = true;
                            break;
                        }
                    }

                    if (!$found) {
                        $profileFieldName = $profileField->getKey();
                    }
                    ?>

                    <?php if ($profileField->getType() != 1) : ?>
                        <td><?=$this->escape($profileFieldName) ?></td>
                        <td><i class="<?=$iconArray[$profileField->getType()] ?>"></i>&nbsp;&nbsp;<?=$this->getTrans($typeArray[$profileField->getType()]) ?></td>
                    <?php else: ?>
                        <td><b><?=$this->escape($profileFieldName) ?></b></td>
                        <td><b><i class="<?=$iconArray[$profileField->getType()] ?>"></i>&nbsp;&nbsp;<?=$this->getTrans($typeArray[$profileField->getType()]) ?></b></td>
                    <?php endif; ?>
                        <td><i class="fa-solid fa-up-down fa-xs"></i></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <input type="hidden" id="hiddenMenu" name="hiddenMenu" value="" />
    </div>

    <div class="content_savebox">
        <button type="submit" class="btn btn-default sortbtn" name="save" value="save">
            <?=$this->getTrans('saveButton') ?>
        </button>
        <input type="hidden" class="content_savebox_hidden" name="action" value="" />
        <div class="btn-group dropup">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <?=$this->getTrans('selected') ?> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu listChooser" role="menu">
                <li><a href="#" data-hiddenkey="delete"><?=$this->getTrans('delete') ?></a></li>
            </ul>
        </div>
    </div>
</form>

<?=$this->getDialog('infoModal', $this->getTrans('info'), $this->getTrans('profileFieldInfoText')) ?>

<script>
$(function() {
	let sortableSelector = $('#sortable');

	sortableSelector.sortable({
		opacity: .75,
		placeholder: 'placeholder',
		helper: function(e, tr) {
			const $originals = tr.children();
			const $helper = tr.clone();
			$helper.children().each(function(index) {
				$(this).width($originals.eq(index).width()+16);
			});
			return $helper;
		},
		update: function () {
			$('.sortbtn').addClass('save_button');
		}
	});
	sortableSelector.disableSelection();
});
$('#catsIndexForm').submit (
    function () {
        var items = $("#sortable tr");
        var catIDs = [items.length];
        var index = 0;
        items.each(
            function(intIndex) {
                catIDs[index] = $(this).attr("id");
                index++;
            });
        $('#hiddenMenu').val(catIDs.join(","));
    }
);
</script>
