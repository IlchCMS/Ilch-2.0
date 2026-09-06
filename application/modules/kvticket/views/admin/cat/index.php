<form method="POST" action="">
    <?=$this->getTokenField() ?>

    <?php if ($this->get('cats')): ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col />
                    <col class="col-xl-1" />
                    <col class="col-xl-1" />
                </colgroup>
                <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_tickets') ?></th>
                    <th></th>
                    <th></th>
                    <th><?=$this->getTrans('cat') ?></th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('cats') as $cat): ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_tickets', $cat->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $cat->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $cat->getId()]) ?></td>
                            <td><?=$this->escape($cat->getTitle()) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <tr>
            <td colspan="4"><?=$this->getTrans('noCats') ?></td>
        </tr>
    <?php endif; ?>

    <?php if ($this->get('cats')): ?>
        <div class="content_savebox">
            <input type="hidden" class="content_savebox_hidden" name="action" value="" />
            <div class="btn-group dropup">
                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <?=$this->getTrans('selected') ?>
                </button>
                <ul class="dropdown-menu listChooser" role="menu">
                    <li><a class="dropdown-item" href="#" data-hiddenkey="delete"><?=$this->getTrans('delete') ?></a></li>
                </ul>
            </div>
        </div>
    <?php endif; ?>
</form>
