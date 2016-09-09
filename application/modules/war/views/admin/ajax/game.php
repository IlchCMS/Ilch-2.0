<div id="dup">
    <div id="duplicater">
        <?php if ($this->get('games') != ''): ?>
            <?php foreach ($this->get('games') as $game): ?>
                <div class="form-group">
                    <label class="col-lg-2 control-label" for="textinput">
                        <?=$this->getTrans('warMapName') ?>
                        <?php if ($game->getId() != ''): ?>
                            <a id="<?=$game->getId() ?>"
                                class="btn btn-danger btn-sm"
                                href="javascript:void(0)"
                                onclick="del(event)">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        <?php endif; ?>
                    </label>
                    <div class="col-lg-4">
                        <input type="text"
                               class="form-control"
                               name="warMapPlayed[]"
                               placeholder="<?=$this->getTrans('warMapName') ?>"
                               value="<?=$game->getMap() ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label" for="textinput">
                        <?=$this->getTrans('warResult') ?>
                    </label>
                    <div class="col-lg-2">
                        <input type="number"
                               class="form-control"
                               name="warErgebnisGroup[]"
                               placeholder="<?=$this->getTrans('warResultWe') ?>"
                               value="<?=$game->getGroupPoints() ?>">
                    </div>
                    <div class="col-lg-2">
                        <input type="number"
                               class="form-control"
                               name="warErgebnisEnemy[]"
                               placeholder="<?=$this->getTrans('warResultEnemy') ?>"
                               value="<?=$game->getEnemyPoints() ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2" ></label>
                    <div class="col-lg-4">
                        <legend></legend>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="form-group">
                <label class="col-lg-2 control-label" for="textinput">
                    <?=$this->getTrans('warMapName') ?>
                </label>
                <div class="col-lg-4">
                    <input type="text"
                           class="form-control"
                           name="warMapPlayed[]"
                           placeholder="<?=$this->getTrans('warMapName') ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-2 control-label" for="textinput">
                    <?=$this->getTrans('warResult') ?>
                </label>
                <div class="col-lg-2">
                    <input type="text"
                           class="form-control"
                           name="warErgebnisGroup[]"
                           placeholder="<?=$this->getTrans('warResultWe') ?>">
                </div>
                <div class="col-lg-2">
                    <input type="text"
                           class="form-control"
                           name="warErgebnisEnemy[]"
                           placeholder="<?=$this->getTrans('warResultEnemy') ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-2" ></label>
                <div class="col-lg-4">
                    <legend></legend>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-2 control-label" for="textinput"></label>
    <div class="col-lg-2">
        <a id="button-duplicater" class="btn btn-default" onlick="duplicate()"><?=$this->getTrans('warMoreMaps') ?></a>
    </div>
    <div class="col-lg-2">
        <a id="button-remover" class="btn btn-default" onlick="remove()"><?=$this->getTrans('warRemoveMaps') ?></a>
    </div>
</div>

<script>
document.getElementById('button-duplicater').onclick = duplicate;
document.getElementById('button-remover').onclick = remove;

var i = 0;
var original = document.getElementById('duplicater');

function duplicate()
{
    var clone = original.cloneNode(true); // "deep" clone
    clone.id = "duplicetor" + ++i; // there can only be one element with an ID
    var dup = original.parentNode.appendChild(clone);
    $("#duplicater").after(dup);
}

function remove()
{
    var node = document.getElementById('dup');
    if (node.hasChildNodes()) {
        if (node.childNodes.length > '2') {
         node.removeChild(node.lastChild);
        }
    }
}

function del(event)
{
    var mapid = event.currentTarget.id;
    $('#games').load('<?=$this->getUrl('index.php/admin/war/ajax/del/id/'.$this->getRequest()->getParam('id').'/mapid/') ?>'+mapid);
}
</script>
