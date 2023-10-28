<link href="<?=$this->getBaseUrl('application/modules/war/static/css/jquery-editable-select.min.css') ?>" rel="stylesheet">
<script src="<?=$this->getBaseUrl('application/modules/war/static/js/jquery-editable-select.min.js') ?>"></script>
<div id="dup">
<?php $index = 0; ?>
<?php foreach ($this->get('games') as $game): ?>
    <div id="duplicator<?=$index++ ?>">
        <?php if ($game->getId()): ?>
        <input type="hidden" name="warGameIds[]" value="<?=$game->getId() ?>">
        <?php endif; ?>
        <div class=" row mb-3 ">
            <label class="col-xl-2 control-label" for="warMapPlayed[]">
                <?=$this->getTrans('warMapName') ?>
                <?php if ($game->getId()): ?>
                <a id="<?=$game->getId() ?>"
                   class="btn btn-danger btn-sm"
                   href="javascript:void(0)"
                   onclick="del(<?=$game->getId() ?>)">
                    <i class="fa-regular fa-trash-can"></i>
                </a>
                <?php endif; ?>
            </label>
            <div class="col-xl-4">
                <select class="chosen-select form-control"
                        id="warMapPlayed[]"
                        name="warMapPlayed[]"
                        data-placeholder="<?=$this->getTrans('warMapName') ?>">
                    <?php foreach ($this->get('gamesmaps') ?? [] as $maps): ?>
                        <option value="<?=$maps->getId() ?>" <?=$game->getMap() == $maps->getId() ? 'selected=""' : '' ?>><?=$this->escape($maps->getName()) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row mb-3 ">
            <label class="col-xl-2 control-label" for="warErgebnis[]">
                <?=$this->getTrans('warResult') ?>
            </label>
            <div class="col-xl-2">
                <input type="number"
                       class="form-control"
                       id="warErgebnisGroup[]"
                       name="warErgebnisGroup[]"
                       placeholder="<?=$this->getTrans('warResultWe') ?>"
                       value="<?=$game->getGroupPoints() ?>">
            </div>
            <div class="col-xl-2">
                <input type="number"
                       class="form-control"
                       id="warErgebnisEnemy[]"
                       name="warErgebnisEnemy[]"
                       placeholder="<?=$this->getTrans('warResultEnemy') ?>"
                       value="<?=$game->getEnemyPoints() ?>">
            </div>
        </div>
        <div class="row mb-3 ">
            <label class="col-xl-2"></label>
            <div class="col-xl-4">
                <h1></h1>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>
<div class="row mb-3 ">
    <label class="col-xl-2 control-label" for="textinput"></label>
    <div class="col-xl-2">
        <a id="button-duplicater" class="btn btn-outline-secondary"><?=$this->getTrans('warMoreMaps') ?></a>
    </div>
    <div class="col-xl-2">
        <a id="button-remover" class="btn btn-outline-secondary"><?=$this->getTrans('warRemoveMaps') ?></a>
    </div>
</div>

<script>
    document.getElementById('button-duplicater').onclick = duplicate;
    document.getElementById('button-remover').onclick = remove;
    document.getElementById('button-remover').onclick = remove;

    let i = <?=$index?>;
    let original = document.getElementById('duplicator0');

    function duplicate() {
        let clone = original.cloneNode(true); // "deep" clone
        clone.id = "duplicator" + i++; // there can only be one element with an ID
        original.parentNode.appendChild(clone);

        // delete a possible hidden input "warGameIds" for the clone as otherwise the original would
        // get overwritten in the database.
        let allWarGameId = document.getElementsByName('warGameIds[]');
        if (allWarGameId.length !== 0) {
            let lastWarGameId = allWarGameId[allWarGameId.length - 1];
            lastWarGameId.parentNode.removeChild(lastWarGameId);
        }

        let warGamedel = document.getElementsByName('warGamedel[]');
        if (warGamedel.length !== 0) {
            let lastWarGameId = warGamedel[warGamedel.length - 1];
            lastWarGameId.parentNode.removeChild(lastWarGameId);
        }

        let warMapPlayed = document.getElementsByName('warMapPlayed[]');
        if (warMapPlayed.length !== 0) {
            //warMapPlayed[warMapPlayed.length - 1].editableSelect();
        }

        let warErgebnisGroup = document.getElementsByName('warErgebnisGroup[]');
        if (warErgebnisGroup.length !== 0) {
            warErgebnisGroup[warErgebnisGroup.length - 1].value = '';
        }

        let warErgebnisEnemy = document.getElementsByName('warErgebnisEnemy[]');
        if (warErgebnisEnemy.length !== 0) {
            warErgebnisEnemy[warErgebnisEnemy.length - 1].value = '';
        }
    }

    function remove() {
        let node = document.getElementById('dup');
        if (node.hasChildNodes()) {
            if (node.childNodes.length > '2') {
                node.removeChild(node.lastChild);
            }
        }
    }

    function del(mapid) {
        $('#games').load('<?=$this->getUrl('index.php/admin/war/ajax/del/id/' . $this->getRequest()->getParam('id') . '/mapid/') ?>' + mapid);
    }
</script>
