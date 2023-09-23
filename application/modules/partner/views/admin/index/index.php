<h1>
    <?=$this->getTrans('manage') ?>
    <a class="badge rounded-pill bg-secondary" data-bs-toggle="modal" data-bs-target="#infoModal">
        <i class="fa-solid fa-info"></i>
    </a>
</h1>
<?php if ($this->get('entries') != ''): ?>
    <form class="form-horizontal" id="partnerIndexForm" method="POST">
        <?=$this->getTokenField() ?>
        <ul class="nav nav-tabs">
            <li <?=(!$this->getRequest()->getParam('showsetfree')) ? 'class="active"' : '' ?>>
                <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'index']) ?>">
                    <?=$this->getTrans('entrys') ?>
                </a>
            </li>
            <?php if ($this->get('badge') > 0): ?>
                <li <?=($this->getRequest()->getParam('showsetfree')) ? 'class="active"' : '' ?>>
                    <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'index', 'showsetfree' => 1]) ?>">
                        <?=$this->getTrans('setfree') ?> <span class="badge"><?=$this->get('badge') ?></span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
        <br />
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <?php  if ($this->getRequest()->getParam('showsetfree')): ?>
                        <col class="icon_width">
                    <?php endif; ?>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-lg-2">
                    <col />
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_entries') ?></th>
                        <th></th>
                        <?php  if ($this->getRequest()->getParam('showsetfree')): ?>
                            <th></th>
                        <?php endif; ?>
                        <th></th>
                        <th><?=$this->getTrans('name') ?></th>
                        <th><?=$this->getTrans('banner') ?></th>
                    </tr>
                </thead>
                <tbody id="sortable">
                    <?php foreach ($this->get('entries') as $entry): ?>
                        <?php if (strncmp($entry->getBanner(), 'application', 11) === 0): ?>
                            <?php $banner = $this->getBaseUrl($entry->getBanner()); ?>
                        <?php else: ?>
                            <?php $banner = $entry->getBanner(); ?>
                        <?php endif; ?>
                        <tr id="<?=$entry->getId() ?>">
                            <td><?=$this->getDeleteCheckbox('check_entries', $entry->getId()) ?></td>
                            <?php if ($this->getRequest()->getParam('showsetfree')): ?>
                                <td>
                                    <?php
                                    $freeArray = ['action' => 'setfree', 'id' => $entry->getId()];
                                    if ($this->get('badge') > 1) {
                                        $freeArray = ['action' => 'setfree', 'id' => $entry->getId(), 'showsetfree' => 1];
                                    }
                                    echo '<a href="'.$this->getUrl($freeArray, null, true).'" title="'.$this->getTrans('setfree').'"><i class="fa-solid fa-check-square-o text-success"></i></a>';
                                    ?>
                                </td>
                            <?php endif; ?>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $entry->getId()]) ?></td>
                            <td>
                                <?php
                                $deleteArray = ['action' => 'del', 'id' => $entry->getId()];
                                if ($this->get('badge') > 1) {
                                    $deleteArray = ['action' => 'del', 'id' => $entry->getId(), 'showsetfree' => 1];
                                }
                                echo $this->getDeleteIcon($deleteArray);
                                ?>
                            </td>
                            <td><?=$this->escape($entry->getName()) ?></td>
                            <td><a href='<?=$this->escape($entry->getLink()) ?>' target="_blank" rel="noopener"><img src='<?=$banner ?>'></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <input type="hidden" id="positions" name="positions" value="" />
        </div>
        <?php
        $actions = ['delete' => 'delete'];

        if ($this->getRequest()->getParam('showsetfree')) {
            $actions += ['setfree' => 'setfree'];
            echo $this->getListBar($actions);
        }
        ?>
        <?php if (!$this->getRequest()->getParam('showsetfree')) : ?>
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
        <?php endif; ?>
    </form>

    <script>
        $(function() {
            $( "#sortable" ).sortable();
            $( "#sortable" ).disableSelection();
        });
        $('#partnerIndexForm').submit (
            function () {
                var items = $("#sortable tr");
                var partnerIDs = [items.length];
                var index = 0;
                items.each(
                    function(intIndex) {
                        partnerIDs[index] = $(this).attr("id");
                        index++;
                    });
                $('#positions').val(partnerIDs.join(","));
            }
        );
    </script>
<?php else: ?>
    <?=$this->getTrans('noPartners') ?>
<?php endif; ?>

<?=$this->getDialog('infoModal', $this->getTrans('info'), $this->getTrans('partnerInfoText')) ?>
