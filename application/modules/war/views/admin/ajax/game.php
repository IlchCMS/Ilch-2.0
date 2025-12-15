<?php

/** @var \Ilch\View $this */
?>
<div id="dup">
<?php $index = 0; ?>
<?php
/** @var \Modules\War\Models\Games $game */
foreach ($this->get('games') as $game) : ?>
    <div id="duplicator<?=$index++ ?>">
        <?php if ($game->getId()) : ?>
        <input type="hidden" name="warGameIds[]" value="<?=$game->getId() ?>">
        <?php endif; ?>
        <div class=" row mb-3 ">
            <label class="col-xl-2 col-form-label" for="warMapPlayed[]">
                <?=$this->getTrans('warMapName') ?>
                <?php if ($game->getId()) : ?>
                <a id="<?=$game->getId() ?>"
                   class="btn btn-danger btn-sm"
                   href="javascript:void(0)"
                   onclick="del(<?=$game->getId() ?>)">
                    <i class="fa-regular fa-trash-can"></i>
                </a>
                <?php endif; ?>
            </label>
            <div class="col-xl-4">
                <select class="form-control"
                        id="warMapPlayed[]"
                        name="warMapPlayed[]"
                        data-placeholder="<?=$this->getTrans('warMapName') ?>">
                    <option value="" <?=$game->getMap() == 0 ? 'selected=""' : '' ?>><?=$this->getTrans('choose') ?></option>
                    <?php
                    /** @var \Modules\War\Models\Maps $maps */
                    foreach ($this->get('gamesmaps') ?? [] as $maps) : ?>
                        <option value="<?=$maps->getId() ?>" <?=$game->getMap() == $maps->getId() ? 'selected=""' : '' ?>><?=$this->escape($maps->getName()) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row mb-3 ">
            <label class="col-xl-2 col-form-label" for="warResultGroups[]">
                <label for="warResultEnemys[]">
                    <?=$this->getTrans('warResult') ?>
                </label>
            </label>
            <div class="col-xl-2">
                <input type="number"
                       class="form-control"
                       id="warResultGroups[]"
                       name="warResultGroups[]"
                       placeholder="<?=$this->getTrans('warResultWe') ?>"
                       value="<?=$game->getGroupPoints() ?>">
            </div>
            <div class="col-xl-2">
                <input type="number"
                       class="form-control"
                       id="warResultEnemys[]"
                       name="warResultEnemys[]"
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
    <label class="col-xl-2 col-form-label" for="textinput"></label>
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
        }

        let warResultGroups = document.getElementsByName('warResultGroups[]');
        if (warResultGroups.length !== 0) {
            warResultGroups[warResultGroups.length - 1].value = '';
        }

        let warResultEnemys = document.getElementsByName('warResultEnemys[]');
        if (warResultEnemys.length !== 0) {
            warResultEnemys[warResultEnemys.length - 1].value = '';
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
