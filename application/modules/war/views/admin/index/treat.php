<link href="<?=$this->getStaticUrl('js/datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">

<h1>
    <?php if ($this->getRequest()->getParam('id') == ''): ?>
        <?=$this->getTrans('menuActionNewWar') ?>
    <?php else: ?>
        <?=$this->getTrans('manageWar') ?>
    <?php endif; ?>
</h1>

<?php if ($this->get('group') != '' and $this->get('enemy') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="form-group <?=$this->validation()->hasError('warEnemy') ? 'has-error' : '' ?>">
            <label for="warEnemy" class="col-lg-2 control-label">
                <?=$this->getTrans('warEnemy') ?>:
            </label>
            <div class="col-lg-4">
                <select class="form-control" id="warEnemy" name="warEnemy">
                    <optgroup label="<?=$this->getTrans('enemysName') ?>">
                        <?php foreach ($this->get('enemy') as $enemy): ?>
                            <?php $selected = ''; ?>
                            <?php if ($this->get('war') != '' and $this->get('war')->getWarEnemy() == $enemy->getId()): ?>
                                <?php $selected = 'selected="selected"'; ?>
                            <?php endif; ?>
                            <option <?=$selected ?> value="<?=$enemy->getId() ?>"><?=$this->escape($enemy->getEnemyName()) ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="form-group <?=$this->validation()->hasError('warGroup') ? 'has-error' : '' ?>">
            <label for="warGroup" class="col-lg-2 control-label">
                <?=$this->getTrans('warGroup') ?>:
            </label>
            <div class="col-lg-4">
                <select class="form-control" id="warGroup" name="warGroup">
                    <optgroup label="<?=$this->getTrans('groupsName') ?>">
                        <?php foreach ($this->get('group') as $group): ?>
                            <?php $selected = ''; ?>
                            <?php if ($this->get('war') != '' and $this->get('war')->getWarGroup() == $group->getId()): ?>
                                <?php $selected = 'selected="selected"'; ?>
                            <?php endif; ?>
                            <option <?=$selected ?> value="<?=$group->getId() ?>"><?=$this->escape($group->getGroupName()) ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="form-group <?=$this->validation()->hasError('warTime') ? 'has-error' : '' ?>">
            <label for="warTimeInput" class="col-md-2 control-label">
                <?=$this->getTrans('warTime') ?>:
            </label>
            <div class="input-group ilch-date date form_datetime col-lg-4">
                <input type="text"
                       class="form-control"
                       id="warTimeInput"
                       name="warTime"
                       size="16"
                       value="<?php if ($this->get('war') != '') { echo $this->get('war')->getWarTime(); } ?>"
                       readonly>
                <span class="input-group-addon">
                    <span class="fa fa-calendar"></span>
                </span>
            </div>
        </div>
        <div class="form-group <?=$this->validation()->hasError('warMap') ? 'has-error' : '' ?>">
            <label for="warMapInput" class="col-lg-2 control-label">
                <?=$this->getTrans('warMap') ?>:
            </label>
            <div class="col-lg-4">
                <?php
                $value = "";
                if ($this->originalInput('warMap') != '') {
                    $value = $this->originalInput('warMap');
                } elseif ($this->get('war') != '') {
                    $value = $this->get('war')->getWarMaps();
                }
                ?>
                <input type="text"
                       class="form-control"
                       id="warMapInput"
                       name="warMap"
                       value="<?=$value ?>" />
            </div>
        </div>
        <div class="form-group <?=$this->validation()->hasError('warServer') ? 'has-error' : '' ?>">
            <label for="warServerInput" class="col-lg-2 control-label">
                <?=$this->getTrans('warServer') ?>:
            </label>
            <div class="col-lg-4">
                <?php
                $value = "";
                if ($this->originalInput('warServer') != '') {
                    $value = $this->originalInput('warServer');
                } elseif ($this->get('war') != '') {
                    $value = $this->get('war')->getWarServer();
                }
                ?>
                <input type="text"
                       class="form-control"
                       id="warServerInput"
                       name="warServer"
                       value="<?=$value ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="warPasswordInput" class="col-lg-2 control-label">
                <?=$this->getTrans('warPassword') ?>:
            </label>
            <div class="col-lg-4">
                <?php
                $value = "";
                if ($this->originalInput('warPassword') != '') {
                    $value = $this->originalInput('warPassword');
                } elseif ($this->get('war') != '') {
                    $value = $this->get('war')->getWarPassword();
                }
                ?>
                <input type="text"
                       class="form-control"
                       id="warPasswordInput"
                       name="warPassword"
                       value="<?=$value ?>" />
            </div>
        </div>
        <div class="form-group <?=$this->validation()->hasError('warXonx') ? 'has-error' : '' ?>">
            <label for="warXonx" class="col-lg-2 control-label">
                <?=$this->getTrans('warXonx') ?>:
            </label>
            <div class="col-lg-2">
                <select class="form-control" id="warXonx" name="warXonx" onchange="diasableXonx()">
                    <optgroup label="<?=$this->getTrans('warXonx') ?>">
                        <option value="neu"><?=$this->getTrans('new') ?></option>
                        <?php if ($this->get('warOptXonx') != ''): ?>
                            <?php foreach ($this->get('warOptXonx') as $opt): ?>
                                <?php $selected = ''; ?>
                                <?php if ($this->get('war') != '' and $this->get('war')->getWarXonx() == $opt->getWarXonx()): ?>
                                    <?php $selected = 'selected="selected"'; ?>
                                <?php endif; ?>
                                <option <?=$selected ?> value="<?=$opt->getWarXonx() ?>"><?=$this->escape($opt->getWarXonx()) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </optgroup>
                </select>
            </div>
            <div class="col-lg-2">
                <input type="text"
                       class="form-control"
                       style=""
                       id="warXonxNew"
                       name="warXonxNew"
                       value="" />
            </div>
        </div>
        <div class="form-group <?=$this->validation()->hasError('warGame') ? 'has-error' : '' ?>">
            <label for="warGame" class="col-lg-2 control-label">
                <?=$this->getTrans('warGame'); ?>:
            </label>
            <div class="col-lg-2">
                <select class="form-control" id="warGame" name="warGame" onchange="diasableGame()">
                    <optgroup label="<?=$this->getTrans('warGame') ?>">
                        <option value="neu"><?=$this->getTrans('warNew') ?></option>
                        <?php if ($this->get('warOptGame') != ''): ?>
                            <?php foreach ($this->get('warOptGame') as $opt): ?>
                                <?php $selected = ''; ?>
                                <?php if ($this->get('war') != '' and $this->get('war')->getWarGame() == $opt->getWarGame()): ?>
                                    <?php $selected = 'selected="selected"'; ?>
                                <?php endif; ?>
                                <option <?=$selected ?> value="<?=$opt->getWarGame() ?>"><?=$this->escape($opt->getWarGame()) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </optgroup>
                </select>
            </div>
            <div class="col-lg-2">
                <input type="text"
                       class="form-control"
                       style=""
                       id="warGameNew"
                       name="warGameNew"
                       value="" />
            </div>
        </div>
        <div class="form-group <?=$this->validation()->hasError('warMatchtype') ? 'has-error' : '' ?>">
            <label for="warMatchtype" class="col-lg-2 control-label">
                <?=$this->getTrans('warMatchtype') ?>:
            </label>
            <div class="col-lg-2">
                <select class="form-control" id="warMatchtype" name="warMatchtype" onchange="diasableMatchtype()">
                    <optgroup label="<?=$this->getTrans('warMatchtype') ?>">
                        <option value="neu"><?=$this->getTrans('new') ?></option>
                        <?php if ($this->get('warOptMatchtype') != ''): ?>
                            <?php foreach ($this->get('warOptMatchtype') as $opt): ?>
                                <?php $selected = ''; ?>
                                <?php if ($this->get('war') != '' and $this->get('war')->getWarMatchtype() == $opt->getWarMatchtype()): ?>
                                    <?php $selected = 'selected="selected"'; ?>
                                <?php endif; ?>
                                <option <?=$selected ?> value="<?=$opt->getWarMatchtype() ?>"><?=$this->escape($opt->getWarMatchtype()) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </optgroup>
                </select>
            </div>
            <div class="col-lg-2">
                <input type="text"
                       class="form-control"
                       style=""
                       id="warMatchtypeNew"
                       name="warMatchtypeNew"
                       value="" />
            </div>
        </div>
        <?php if ($this->getRequest()->getParam('id')): ?>
            <h1><?=$this->getTrans('warResult') ?></h1>
            <div id="games"></div>
        <?php else: ?>
            <h1><?=$this->getTrans('warResultInfo') ?></h1>
            <div class="form-group">
                <div class="col-lg-2">
                    <?=$this->getTrans('warResultInfo') ?>:
                </div>
                <div class="col-lg-4">
                    <span><?=$this->getTrans('warResultInfoText') ?></span>
                </div>
            </div>
        <?php endif; ?>
        <h1><?=$this->getTrans('warReport') ?></h1>
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-8">
                <?php
                $value = "";
                if ($this->originalInput('warReport') != '') {
                    $value = $this->originalInput('warReport');
                } elseif ($this->get('war') != '') {
                    $value = $this->get('war')->getWarReport();
                }
                ?>
                <textarea class="form-control ckeditor"
                          id="ck_1"
                          name="warReport"
                          toolbar="ilch_html"><?=$value ?></textarea>
            </div>
        </div>
        <h1><?=$this->getTrans('warStatus') ?></h1>
        <div class="form-group">
            <label for="warStatus" class="col-lg-2 control-label">
                <?=$this->getTrans('warStatus') ?>:
            </label>
            <div class="col-lg-4">
                <select class="form-control" id="warStatus" name="warStatus">
                    <optgroup label="<?=$this->getTrans('warStatus') ?>">
                        <option <?php if ($this->get('war') != '' && $this->get('war')->getWarStatus() == '1'): ?>
                                    <?='selected="selected"'; ?>
                                <?php endif; ?> value="1"><?=$this->getTrans('warStatusOpen') ?></option>
                        <option <?php if ($this->get('war') != '' && $this->get('war')->getWarStatus() == '2'): ?>
                                    <?='selected="selected"'; ?>
                                <?php endif; ?> value="2"><?=$this->getTrans('warStatusClose') ?></option>
                    </optgroup>
                </select>
            </div>
        </div>
        <?=$this->getSaveBar() ?>
    </form>
<?php else: ?>
    <?=$this->getTranslator()->trans('firstGroupEnemy') ?>
<?php endif; ?>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
<script src="<?=$this->getStaticUrl('js/datetimepicker/js/bootstrap-datetimepicker.min.js') ?>" charset="UTF-8"></script>
<?php if (substr($this->getTranslator()->getLocale(), 0, 2) != 'en'): ?>
    <script src="<?=$this->getStaticUrl('js/datetimepicker/js/locales/bootstrap-datetimepicker.'.substr($this->getTranslator()->getLocale(), 0, 2).'.js') ?>" charset="UTF-8"></script>
<?php endif; ?>
<script>
$(document).ready(function()
{
    $(".form_datetime").datetimepicker({
        format: "dd.mm.yyyy hh:ii",
        autoclose: true,
        language: '<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>',
        minuteStep: 15,
        todayHighlight: true
    });

    diasableXonx();
    diasableGame();
    diasableMatchtype();
    loadGames();

    document.getElementById('warXonx').onchange = diasableXonx;
    document.getElementById('warGame').onchange = diasableGame;
    document.getElementById('warMatchtype').onchange = diasableMatchtype;

    function diasableXonx()
    {
        if (document.getElementById('warXonx').value==='neu') {
            document.getElementById("warXonxNew").style.display="block";
            document.getElementById("warXonx").style.margin="0 0 5px";
        } else {
            document.getElementById("warXonxNew").style.display="none";
            document.getElementById("warXonxNew").value = '';
        }
    };

    function diasableGame()
    {
        if (document.getElementById('warGame').value==='neu') {
            document.getElementById("warGameNew").style.display="block";
            document.getElementById("warGame").style.margin="0 0 5px";
        } else {
            document.getElementById("warGameNew").style.display="none";
            document.getElementById("warGameNew").value = '';
        }
    };

    function diasableMatchtype()
    {
        if (document.getElementById('warMatchtype').value==='neu') {
            document.getElementById("warMatchtypeNew").style.display="block";
            document.getElementById("warMatchtype").style.margin="0 0 5px";
        } else {
            document.getElementById("warMatchtypeNew").style.display="none";
            document.getElementById("warMatchtypeNew").value = '';
        }
    };

    function loadGames()
    {
        $('#games').load('<?=$this->getUrl('index.php/admin/war/ajax/game/id/'.$this->getRequest()->getParam('id').''); ?>');
    };
});
</script>
