<h1>
    <?=$this->getTrans('manage') ?>
    <a class="badge" data-toggle="modal" data-target="#infoModal">
        <i class="fa fa-info"></i>
    </a>
</h1>
<form class="form-horizontal" id="downloadsForm" method="POST" action="">
    <?=$this->getTokenField() ?>
    <?php if ($this->get('categorys') != ''): ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-lg-2">
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_cats') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('category') ?></th>
                        <th><?=$this->getTrans('links') ?></th>
                    </tr>
                </thead>
                <tbody id="sortableCat">
                    <?php
                    foreach ($this->get('categorys') as $category):
                        $getDesc = $this->escape($category->getDesc());
                        if ($getDesc != '') {
                            $getDesc = '&raquo; '.$this->escape($category->getDesc());
                        } else {
                            $getDesc = '';
                        }
                    ?>
                        <tr id="<?=$category->getId() ?>">
                            <td><?=$this->getDeleteCheckbox('check_cats', $category->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treatCat', 'id' => $category->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'deleteCat', 'id' => $category->getId()]) ?></td>
                            <td><a href="<?=$this->getUrl(['action' => 'index', 'cat_id' => $category->getId()]) ?>" title="<?=$this->escape($category->getName()) ?>"><?=$this->escape($category->getName()) ?></a><br><?=$getDesc ?></td>
                            <td style="vertical-align:middle"><a href="<?=$this->getUrl(['action' => 'index', 'cat_id' => $category->getId()]) ?>" title="<?=$this->escape($category->getName()) ?>"><?=$category->getLinksCount() ?></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <input type="hidden" id="hiddenMenuCat" name="hiddenMenuCat" value="" />
        </div>
        <br />
    <?php endif; ?>

    <?php if (!empty($this->get('links'))): ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_links') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('links') ?></th>
                    </tr>
                </thead>
                <tbody id="sortable">
                    <?php
                    foreach ($this->get('links') as $link):
                        $banner = $this->escape($link->getBanner());
                        $desc = $this->escape($link->getDesc());
                        if (!empty($desc)) {
                            $desc = '&raquo; '.$this->escape($link->getDesc());
                        } else {
                            $desc = '';
                        }

                        if (strncmp($banner, 'application', 11) === 0) {
                            $banner = '<img src="'.$this->getBaseUrl($banner).'">';
                        } elseif (!empty($banner)) {
                            $banner = '<img src="'.$this->escape($banner).'">';
                        } else {
                            $banner = $link->getName();
                        }
                    ?>
                        <tr id="<?=$link->getId() ?>">
                            <td><?=$this->getDeleteCheckbox('check_links', $link->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treatLink', 'id' => $link->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'deleteLink', 'id' => $link->getId()]) ?></td>
                            <td><a href="<?=$this->escape($link->getLink()) ?>" target="_blank" title="<?=$this->escape($link->getName()) ?>"><?=$banner ?></a><br /><?=$desc ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <input type="hidden" id="hiddenMenu" name="hiddenMenu" value="" />
        </div>
    <?php else: ?>
        <?=$this->getTrans('noLinks') ?>
    <?php endif; ?>

    <div class="content_savebox">
        <button type="submit" class="btn btn-default" name="save" value="save">
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

<?=$this->getDialog('infoModal', $this->getTrans('info'), $this->getTrans('linkInfoText')) ?>

<script>
$(function() {
$( "#sortable" ).sortable();
$( "#sortable" ).disableSelection();
$( "#sortableCat" ).sortable();
$( "#sortableCat" ).disableSelection();
});
$('#downloadsForm').submit (
    function () {
        var items = $("#sortable tr");
        var linkIDs = [items.length];
        var index = 0;
        items.each(
            function(intIndex) {
                linkIDs[index] = $(this).attr("id");
                index++;
            });
        $('#hiddenMenu').val(linkIDs.join(","));

        items = $("#sortableCat tr");
        linkIDs = [items.length];
        index = 0;
        items.each(
            function(intIndex) {
                linkIDs[index] = $(this).attr("id");
                index++;
            });
        $('#hiddenMenuCat').val(linkIDs.join(","));
    }
);
</script>
