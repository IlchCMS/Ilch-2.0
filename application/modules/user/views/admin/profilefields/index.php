<table class="table table-hover table-striped">
    <colgroup>
        <col class="icon_width" />
        <col class="icon_width" />
        <col class="icon_width" />
        <col />
    </colgroup>
    <thead>
        <tr>
            <th><?=$this->getCheckAllCheckbox('check_users') ?></th>
            <th></th>
            <th></th>
            <th><?=$this->getTrans('profileFieldName') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $profileFields = $this->get('profileFields');

        foreach ($profileFields as $profileField) :
        ?>
        <tr>
            <td>
                <input value="<?=$profileField->getId()?>" type="checkbox" name="check_users[]" />
            </td>
            <td>
                <?=$this->getEditIcon(['action' => 'treat', 'id' => $profileField->getId()]) ?>
            </td>
            <td>
                <?=$this->getDeleteIcon(['action' => 'delete', 'id' => $profileField->getId()]) ?>
            </td>
            <td><?=$this->escape($profileField->getName()) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
