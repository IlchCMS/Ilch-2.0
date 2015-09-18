<link href="<?=$this->getStaticUrl('js/datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">

<legend>
    <?php if ($this->getRequest()->getParam('id') == ''): ?>
        <?=$this->getTrans('menuActionNewWar') ?></legend>
    <?php else: ?>
        <?=$this->getTrans('manageWar') ?>
    <?php endif; ?>
</legend>
<?php if ($this->get('group') != '' and $this->get('enemy') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="form-group">
            <label for="warEnemy" class="col-lg-2 control-label">
                <?=$this->getTrans('warEnemy') ?>:
            </label>
            <div class="col-lg-4">
                <select class="form-control" name="warEnemy" id="warEnemy">
                    <optgroup label="<?=$this->getTrans('enemysName') ?>">
                        <?php foreach ($this->get('enemy') as $enemy): ?>
                            <?php $selected = ''; ?>
                            <?php if ($this->get('war') != '' and $this->get('war')->getWarEnemy() == $enemy->getId()): ?>
                                <?php $selected = 'selected="selected"'; ?>
                            <?php endif; ?>
                            <option <?=$selected ?> value="<?=$enemy->getId() ?>"><?=$enemy->getEnemyName() ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="warGroup" class="col-lg-2 control-label">
                <?=$this->getTrans('warGroup') ?>:
            </label>
            <div class="col-lg-4">
                <select class="form-control" name="warGroup" id="warGroup">
                    <optgroup label="<?=$this->getTrans('groupsName') ?>">
                        <?php foreach ($this->get('group') as $group): ?>
                            <?php $selected = ''; ?>
                            <?php if ($this->get('war') != '' and $this->get('war')->getWarGroup() == $group->getId()): ?>
                                <?php $selected = 'selected="selected"'; ?>
                            <?php endif; ?>
                            <option <?=$selected ?> value="<?=$group->getId() ?>"><?=$group->getGroupName() ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="dtp_input1" class="col-md-2 control-label">
                <?=$this->getTrans('warTime') ?>:
            </label>
            <div class="input-group date form_datetime col-lg-4">
                <input class="form-control"
                       size="16"
                       type="text"
                       name="warTime"
                       value="<?php if ($this->get('war') != '') { echo $this->get('war')->getWarTime(); } ?>"
                       readonly>
                <span class="input-group-addon">
                    <span class="fa fa-calendar"></span>
                </span>
            </div>
        </div>
        <div class="form-group">
            <label for="warMapInput" class="col-lg-2 control-label">
                <?=$this->getTrans('warMap') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="warMap"
                       value="<?php if ($this->get('war') != '') { echo $this->get('war')->getWarMaps(); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="warServerInput" class="col-lg-2 control-label">
                <?=$this->getTrans('warServer') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="warServer"
                       value="<?php if ($this->get('war') != '') { echo $this->get('war')->getWarServer(); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="warPasswordInput" class="col-lg-2 control-label">
                <?=$this->getTrans('warPassword') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="warPassword"
                       value="<?php if ($this->get('war') != '') { echo $this->get('war')->getWarPassword(); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="warXonxInput" class="col-lg-2 control-label">
                <?=$this->getTrans('warXonx') ?>:
            </label>
            <div class="col-lg-2">
                <select onchange="diasableXonx()" class="form-control" name="warXonx" id="warXonx">
                    <optgroup label="<?=$this->getTrans('warXonx') ?>">
                        <option value="neu"><?=$this->getTrans('new') ?></option>
                        <?php if ($this->get('warOptXonx') != ''): ?>
                            <?php foreach ($this->get('warOptXonx') as $opt): ?>
                                <?php $selected = ''; ?>
                                <?php if ($this->get('war') != '' and $this->get('war')->getWarXonx() == $opt->getWarXonx()): ?>
                                    <?php $selected = 'selected="selected"'; ?>
                                <?php endif; ?>
                                <option <?=$selected ?> value="<?=$opt->getWarXonx() ?>"><?=$opt->getWarXonx() ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </optgroup>
                </select>
            </div>
            <div class="col-lg-2">
                <input class="form-control"
                       type="text"
                       style=""
                       id="warXonxNew"
                       name="warXonxNew"
                       value="" />
            </div>
        </div>
        <div class="form-group">
            <label for="warGameInput" class="col-lg-2 control-label">
                <?=$this->getTrans('warGame'); ?>:
            </label>
            <div class="col-lg-2">
                <select onchange="diasableGame()" class="form-control" name="warGame" id="warGame">
                    <optgroup label="<?=$this->getTrans('warGame') ?>">
                        <option value="neu"><?=$this->getTrans('warNew') ?></option>
                        <?php if ($this->get('warOptGame') != ''): ?>
                            <?php foreach ($this->get('warOptGame') as $opt): ?>
                                <?php $selected = ''; ?>
                                <?php if ($this->get('war') != '' and $this->get('war')->getWarGame() == $opt->getWarGame()): ?>
                                    <?php $selected = 'selected="selected"'; ?>
                                <?php endif; ?>
                                <option <?=$selected ?> value="<?=$opt->getWarGame() ?>"><?=$opt->getWarGame() ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </optgroup>
                </select>
            </div>
            <div class="col-lg-2">
                <input class="form-control"
                       type="text"
                       style=""
                       id="warGameNew"
                       name="warGameNew"
                       value="" />
            </div>
        </div>
        <div class="form-group">
            <label for="warMatchtypeInput" class="col-lg-2 control-label">
                <?=$this->getTrans('warMatchtype') ?>:
            </label>
            <div class="col-lg-2">
                <select onchange="diasableMatchtype()" class="form-control" name="warMatchtype" id="warMatchtype">
                    <optgroup label="<?=$this->getTrans('warMatchtype') ?>">
                        <option value="neu"><?=$this->getTrans('new') ?></option>
                        <?php if ($this->get('warOptMatchtype') != ''): ?>
                            <?php foreach ($this->get('warOptMatchtype') as $opt): ?>
                                <?php $selected = ''; ?>
                                <?php if ($this->get('war') != '' and $this->get('war')->getWarMatchtype() == $opt->getWarMatchtype()): ?>
                                    <?php $selected = 'selected="selected"'; ?>
                                <?php endif; ?>
                                <option <?=$selected ?> value="<?=$opt->getWarMatchtype() ?>"><?=$opt->getWarMatchtype() ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </optgroup>
                </select>
            </div>
            <div class="col-lg-2">
                <input class="form-control"
                       type="text"
                       style=""
                       id="warMatchtypeNew"
                       name="warMatchtypeNew"
                       value="" />
            </div>
        </div>
        <?php if ($this->getRequest()->getParam('id')): ?>
            <legend><?=$this->getTrans('warResult') ?></legend>
            <div id="games"></div>
        <?php else: ?>
            <legend><?=$this->getTrans('warResultInfo') ?></legend>
            <div class="form-group">
                <label for="warReportInfo" class="col-lg-2 control-label">
                    <?=$this->getTrans('warResultInfo') ?>:
                </label>
                <div class="col-lg-4">
                    <span><?=$this->getTrans('warResultInfoText') ?></span>
                </div>
            </div>
        <?php endif; ?>
        <legend><?=$this->getTrans('warReport') ?></legend>
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-8">
                <textarea class="form-control ckeditor" id="ck_1" toolbar="ilch_html" name="warReport"><?php if ($this->get('war') != '') { echo $this->get('war')->getWarReport(); } ?></textarea>
            </div>
        </div>
        <legend><?=$this->getTrans('warStatus') ?></legend>
        <div class="form-group">
            <label for="warStatus" class="col-lg-2 control-label">
                <?=$this->getTrans('warStatus') ?>:
            </label>
            <div class="col-lg-4">
                <select class="form-control" name="warStatus" id="warStatus">
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
        <?=$this->getSaveBar()?>
    </form>
<?php else: ?>
    <?=$this->getTranslator()->trans('firstGroupEnemy') ?>
<?php endif; ?>

<style>
    .date {
        padding-left: 15px !important;
        padding-right: 15px !important;
    }
</style>

<script type="text/javascript" src="<?=$this->getStaticUrl('js/datetimepicker/js/bootstrap-datetimepicker.js')?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?=$this->getStaticUrl('js/datetimepicker/js/locales/bootstrap-datetimepicker.de.js')?>" charset="UTF-8"></script>
<script type="text/javascript">
    $( document ).ready(function()
    {
        $(".form_datetime").datetimepicker({
            format: "dd.mm.yyyy hh:ii",
            autoclose: true,
            minuteStep: 15
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
            if(document.getElementById('warXonx').value==='neu'){
                document.getElementById("warXonxNew").style.display="block";
                document.getElementById("warXonx").style.margin="0 0 5px";
            } else {
                document.getElementById("warXonxNew").style.display="none";
                document.getElementById("warXonxNew").value = '';
            }
        };

        function diasableGame()
        {
            if(document.getElementById('warGame').value==='neu'){
                document.getElementById("warGameNew").style.display="block";
                document.getElementById("warGame").style.margin="0 0 5px";
            } else {
                document.getElementById("warGameNew").style.display="none";
                document.getElementById("warGameNew").value = '';
            }
        };

        function diasableMatchtype()
        {
            if(document.getElementById('warMatchtype').value==='neu'){
                document.getElementById("warMatchtypeNew").style.display="block";
                document.getElementById("warMatchtype").style.margin="0 0 5px";
            } else {
                document.getElementById("warMatchtypeNew").style.display="none";
                document.getElementById("warMatchtypeNew").value = '';
            }
        };

        function loadGames() {
            $('#games').load('<?=$this->getUrl('index.php/admin/war/ajax/game/id/'.$this->getRequest()->getParam('id').''); ?>');
        };
    });
</script>
