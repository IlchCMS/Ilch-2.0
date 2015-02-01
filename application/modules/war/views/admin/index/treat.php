<legend><?php echo $this->getTrans('manageWar'); ?></legend>
<?php
if ($this->get('group') != '' and $this->get('enemy') != '') {
?>
<link href="<?=$this->getStaticUrl('../application/modules/war/static/datetimepicker/css/bootstrap-datetimepicker.min.css')?>" rel="stylesheet">

<form class="form-horizontal" method="POST" action="">
    <?php echo $this->getTokenField(); ?>
    <div class="form-group">
        <label for="warEnemy" class="col-lg-2 control-label">
            <?php echo $this->getTrans('warEnemy'); ?>:
        </label>
        <div class="col-lg-4">
            <select class="form-control" name="warEnemy" id="warEnemy">
                <optgroup label="<?php echo $this->getTrans('enemysName'); ?>">
                <?php
                    foreach ($this->get('enemy') as $enemy) {
                        $selected = '';

                        echo '<option '.$selected.' value="'.$enemy->getId().'">'.$this->escape($enemy->getEnemyName()).'</option>';
                    }
                ?>
                </optgroup>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="warGroup" class="col-lg-2 control-label">
            <?php echo $this->getTrans('warGroup'); ?>:
        </label>
        <div class="col-lg-4">
            <select class="form-control" name="warGroup" id="warGroup">
                <optgroup label="<?php echo $this->getTrans('groupsName'); ?>">
                <?php
                    foreach ($this->get('group') as $group) {
                        $selected = '';

                        echo '<option '.$selected.' value="'.$group->getId().'">'.$this->escape($group->getGroupName()).'</option>';
                    }
                ?>
                </optgroup>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="dtp_input1" class="col-md-2 control-label">
            <?php echo $this->getTrans('warTime'); ?>:
        </label>
        <div class="input-group date form_datetime col-lg-4" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
            <input class="form-control" 
                   size="16" 
                   type="text" 
                   name="warTime"
                   value="<?php if ($this->get('war') != '') { echo $this->escape($this->get('war')->getWarTime()); } ?>" 
                   readonly>
            <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
            </span>
        </div>
    </div>
    <div class="form-group">
        <label for="warMapInput" class="col-lg-2 control-label">
            <?php echo $this->getTrans('warMap'); ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="warMap"
                   value="<?php if ($this->get('war') != '') { echo $this->escape($this->get('war')->getWarMaps()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="warServerInput" class="col-lg-2 control-label">
            <?php echo $this->getTrans('warServer'); ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="warServer"
                   value="<?php if ($this->get('war') != '') { echo $this->escape($this->get('war')->getWarServer()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="warPasswordInput" class="col-lg-2 control-label">
            <?php echo $this->getTrans('warPassword'); ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="warPassword"
                   value="<?php if ($this->get('war') != '') { echo $this->escape($this->get('war')->getWarPassword()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="warXonxInput" class="col-lg-2 control-label">
            <?php echo $this->getTrans('warXonx'); ?>:
        </label>
        <div class="col-lg-2">
            <select onchange="diasableXonx()" class="form-control" name="warXonx" id="warXonx">
                <optgroup label="<?php echo $this->getTrans('warXonx'); ?>">
                    <option value="neu">neu</option>
                    <?php if($this->get('warOptXonx') != ''){
                        foreach ($this->get('warOptXonx') as $opt) {
                            echo '<option value="'.$opt->getWarXonx().'">'.$opt->getWarXonx().'</option>';
                        }
                    }
                    ?>
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
            <?php echo $this->getTrans('warGame'); ?>:
        </label>
        <div class="col-lg-2">
            <select onchange="diasableGame()" class="form-control" name="warGame" id="warGame">
                <optgroup label="<?php echo $this->getTrans('warGame'); ?>">
                    <option value="neu">neu</option>
                    <?php if($this->get('warOptGame') != ''){
                        foreach ($this->get('warOptGame') as $opt) {
                            echo '<option value="'.$opt->getWarGame().'">'.$opt->getWarGame().'</option>';
                        }
                    }
                    ?>
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
            <?php echo $this->getTrans('warMatchtype'); ?>:
        </label>
        <div class="col-lg-2">
            <select onchange="diasableMatchtype()" class="form-control" name="warMatchtype" id="warMatchtype">
                <optgroup label="<?php echo $this->getTrans('warMatchtype'); ?>">
                    <option value="neu">neu</option>
                    <?php if($this->get('warOptMatchtype') != ''){
                        foreach ($this->get('warOptMatchtype') as $opt) {
                            echo '<option value="'.$opt->getWarMatchtype().'">'.$opt->getWarMatchtype().'</option>';
                        }
                    }
                    ?>
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
    <legend>Ergebnis</legend>
    <div id="dup">
    <div id="duplicater">
        <div class="form-group">
            <label class="col-lg-2 control-label" for="textinput">Map Name
            </label>
            <div class="col-lg-4">
                <input type="text" name="warMap" placeholder="Map Name" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label" for="textinput">Ergebnis
            </label>
            <div class="col-lg-2">
                <input type="text" name="warErgebnisGroup" placeholder="Wir" class="form-control">
            </div>
            <div class="col-lg-2">
                <input type="text"name="warErgebnisEnemy" placeholder="Gegner" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2" >
            </label>
            <div class="col-lg-4">
                <legend></legend>
            </div>
        </div>
    </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label" for="textinput">
        </label>
        <div class="col-lg-2">
            <a id="button-duplicater" class="btn btn-default" onlick="duplicate()">More Maps</a>
        </div>
        <div class="col-lg-2">
            <a id="button-remover" class="btn btn-default" onlick="remove()">Remove Map</a>
        </div>
    </div>
    <legend>Bericht</legend>
    <div class="form-group">
        <label for="warReportInput" class="col-lg-2 control-label">
            <?php echo $this->getTrans('warReport'); ?>:
        </label>
        <div class="col-lg-4">
            <textarea class="form-control" 
                      name="warReport"><?php if ($this->get('war') != '') { echo $this->escape($this->get('war')->getWarReport()); } ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="warStatus" class="col-lg-2 control-label">
            <?php echo $this->getTrans('warStatus'); ?>:
        </label>
        <div class="col-lg-4">
            <select class="form-control" name="warStatus" id="warStatus">
                <optgroup label="<?php echo $this->getTrans('warStatus'); ?>">
                    <option value="1"><?php echo $this->getTrans('warOpen'); ?></option>
                    <option value="2"><?php echo $this->getTrans('warClose'); ?></option>
                </optgroup>
            </select>
        </div>
    </div>
    <?=$this->getSaveBar()?>
</form>
<?php
} else {
    echo $this->getTranslator()->trans('firstGroupEnemy');
}
?>
<script type="text/javascript" src="<?=$this->getStaticUrl('../application/modules/war/static/datetimepicker/js/jquery-1.8.3.min.js')?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?=$this->getStaticUrl('../application/modules/war/static/datetimepicker/js/bootstrap-datetimepicker.js')?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?=$this->getStaticUrl('../application/modules/war/static/datetimepicker/js/locales/bootstrap-datetimepicker.de.js')?>" charset="UTF-8"></script>
<script type="text/javascript">
$(".form_datetime").datetimepicker({
format: "dd-mm-yyyy hh:ii:ss",
linkFormat: "dd-mm-yyyy hh:ii",
autoclose: true,
todayBtn: true,
minuteStep: 30
});
</script> 
<script type="text/javascript">
    
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

    $( document ).ready(function()
    {
        diasableXonx();
        diasableGame();
        diasableMatchtype();
    });

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
        if (node.hasChildNodes()){
            if (node.childNodes.length > '2'){
             node.removeChild(node.lastChild);
            }
        }
    }

</script>
<style>
.date {
    padding-left: 15px !important;
    padding-right: 15px !important;
}

</style>
