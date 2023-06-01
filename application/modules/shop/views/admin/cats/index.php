<link href="<?=$this->getModuleUrl('static/css/shop_admin.css') ?>" rel="stylesheet">
<?php $itemsMapper = $this->get('itemsMapper'); ?>

<h1>
    <?=$this->getTrans('menuCats') ?>
    <a class="badge" data-toggle="modal" data-target="#infoModal">
        <i class="fa-solid fa-info"></i>
    </a>
</h1>
<?php if (!empty($this->get('cats'))) : ?>
    <form class="form-horizontal" id="catsIndexForm" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col>
                    <col class="icon_width">
                    <col class="icon_width">
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_cats') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('catTitle') ?></th>
                        <th><?=$this->getTrans('itemsCount') ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="sortable">
                    <?php foreach ($this->get('cats') as $cat) : ?>
                        <?php $countItems = count($itemsMapper->getShopItems(['cat_id' => $cat->getId()])); ?>
                        <tr id="<?=$this->escape($cat->getId()) ?>">
                            <td><?=$this->getDeleteCheckbox('check_cats', $cat->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $cat->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'delcat', 'id' => $cat->getId()]) ?></td>
                            <td><?=$this->escape($cat->getTitle()) ?></td>
                            <td align="center"><?=$countItems ?></td>
                            <td><i class="fa-solid fa-up-down"></i></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <input type="hidden" id="positions" name="positions" value="" />
        </div>
        <div class="content_savebox">
            <button type="submit" class="btn btn-default sortbtn" name="save" value="save">
                <?=$this->getTrans('saveSort') ?>
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
            function() {
                const items = $('#sortable tr');
                const serverIDs = [items.length];
                let index = 1;
                items.each(
                    function() {
                        serverIDs[index] = $(this).attr('id');
                        index++;
                    });
                $('#positions').val(serverIDs.join(","));
            }
        );
    </script>
<?php else : ?>
    <?=$this->getTrans('noCategory') ?>
<?php endif; ?>

<?=$this->getDialog('infoModal', $this->getTrans('info'), $this->getTrans('catInfoText')) ?>
