<?php
$userMapper = new \Modules\User\Mappers\User();
if ($this->getUser()) {
    $userCheck = $userMapper->getUserById($this->getUser()->getId());
}
?>

<link href="<?=$this->getStaticUrl('js/datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">

<style>
.agenda {
    width: 140px;
}
.agenda-arrow {
    float:left;
    width: 45px;
    text-align: center;
    font-weight: bold;
    line-height: 36px;
    color: #000;
}
.dayofmonth {
    width: 40px;
    font-size: 36px;
    line-height: 36px;
    float: left;
    text-align: right;
    margin-right: 10px; 
}
.shortdate {
    font-size: 0.75em; 
}
</style>

<legend><?=$this->getTrans('menuAway') ?></legend>
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col class="col-lg-6">
            <col class="col-lg-5">
            <col class="col-lg-1">
        </colgroup>
        <thead>
            <tr>
                <th><?=$this->getTrans('user') ?> / <?=$this->getTrans('reason') ?></th>
                <th><?=$this->getTrans('when') ?></th>
                <th colspan="3"><?=$this->getTrans('status') ?></th>
            </tr>
        </thead>
        <?php if ($this->get('aways') != ''): ?>
            <form class="form-horizontal" method="POST" action="">
                <?=$this->getTokenField() ?>
                <tbody>
                    <?php foreach ($this->get('aways') as $away): ?>
                        <?php $user = $userMapper->getUserById($away->getUserId()) ?>
                        <tr>
                            <td>
                                <a href="<?=$this->getUrl('user/profil/index/user/'.$user->getId()) ?>" target="_blank"><?=$user->getName() ?></a><br />
                                <?=$away->getReason() ?>
                            </td>
                            <?php $startDate = new \Ilch\Date($away->getStart()); ?>
                            <?php $endDate = new \Ilch\Date($away->getEnd()); ?>
                            <?php if ($away->getStart() >= date('Y-m-d') OR $away->getEnd() >= date('Y-m-d')): ?>
                                <td style="color: #008000; border-right: 1px solid #dddddd; border-left: 1px solid #dddddd;">
                                    <div class="agenda" style="float:left;">
                                        <div class="dayofmonth"><?=$startDate->format('d', true) ?></div>
                                        <div><?=$startDate->format('l', true) ?></div>
                                        <div class="shortdate"><?=$startDate->format('F, Y', true) ?></div>
                                    </div>
                                    <div class="agenda-arrow"><i class="fa fa-chevron-right"></i></div>
                                    <div>
                                        <div class="dayofmonth"><?=$endDate->format('d', true) ?></div>
                                        <div><?=$endDate->format('l', true) ?></div>
                                        <div class="shortdate"><?=$endDate->format('F, Y', true) ?></div>
                                    </div>
                                </td>
                            <?php else: ?>
                                <td style="color: #ff0000; border-right: 1px solid #dddddd; border-left: 1px solid #dddddd;">
                                    <div class="agenda" style="float:left;">
                                        <div class="dayofmonth"><?=$startDate->format('d', true) ?></div>
                                        <div><?=$startDate->format('l', true) ?></div>
                                        <div class="shortdate"><?=$startDate->format('F, Y', true) ?></div>
                                    </div>
                                    <div class="agenda-arrow"><i class="fa fa-chevron-right"></i></div>
                                    <div>
                                        <div class="dayofmonth"><?=$endDate->format('d', true) ?></div>
                                        <div><?=$endDate->format('l', true) ?></div>
                                        <div class="shortdate"><?=$endDate->format('F, Y', true) ?></div>
                                    </div>
                                </td>
                            <?php endif; ?>
                            <?php if ($away->getStatus() == 2): ?>
                                <td style="color: #4295C9;"><?=$this->getTrans('reported') ?></td>
                            <?php elseif ($away->getStatus() == 0): ?>
                                <td style="color: #ff0000;"><?=$this->getTrans('declined') ?></td>
                            <?php else: ?>
                                <td style="color: #008000;"><?=$this->getTrans('approved') ?></td>
                            <?php endif; ?>
                            <td>
                                <?php if($this->getUser()): ?>
                                    <?php if($userCheck->isAdmin()): ?>
                                        <?php if ($away->getStart() >= date('Y-m-d') OR $away->getEnd() >= date('Y-m-d')): ?>
                                            <?php if ($away->getStatus() == 1): ?>
                                                <a href="<?=$this->getUrl(array('action' => 'update', 'id' => $away->getId()), null, true) ?>">
                                                    <span class="fa fa-check-square-o text-info"></span>
                                                </a>
                                            <?php else: ?>
                                                <a href="<?=$this->getUrl(array('action' => 'update', 'id' => $away->getId()), null, true) ?>">
                                                    <span class="fa fa-square-o text-info"></span>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($this->getUser()): ?>
                                    <?php if ($away->getUserId() == $this->getUser()->getId() OR $userCheck->isAdmin()): ?>
                                        <?=$this->getDeleteIcon(array('action' => 'del', 'id' => $away->getId())) ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5"><?=$away->getText() ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </form>
        <?php else: ?>
            <tr>
                <td colspan="5"><?=$this->getTrans('noAway') ?></td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<?php if($this->getUser()): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <legend><?=$this->getTrans('menuEntry'); ?></legend>

        <div class="form-group">
            <label for="reason" class="col-lg-2 control-label">
                <?=$this->getTrans('reason') ?>:
            </label>
            <div class="col-lg-6">
                <input class="form-control"
                       type="text"
                       name="reason"
                       id="reason"
                       value="<?php if ($this->get('event') != '') { echo $this->escape($this->get('event')->getTitle()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="start" class="col-md-2 control-label">
                <?=$this->getTrans('when') ?>:
            </label>
            <div class="col-lg-3 input-group date form_datetime pull-left">
                <input class="form-control"
                       size="16"
                       type="text"
                       id="start"
                       name="start"
                       readonly>
                <span class="input-group-addon">
                    <span class="fa fa-calendar"></span>
                </span>
            </div>
            <div class="col-lg-3 input-group date form_datetime">
                <input class="form-control"
                       size="16"
                       type="text"
                       id="end"
                       name="end"
                       readonly>
                <span class="input-group-addon">
                    <span class="fa fa-calendar"></span>
                </span>
            </div>
        </div>
        <div class="form-group">
            <label for="text" class="col-lg-2 control-label">
                <?=$this->getTrans('description') ?>:
            </label>
            <div class="col-lg-6">
                <textarea class="form-control"
                          name="text"
                          rows="3"><?php if ($this->get('event') != '') { echo $this->escape($this->get('event')->getText()); } ?></textarea>
            </div>
        </div>
        <div class="col-lg-8" align="right">
            <?=$this->getSaveBar('addButton', 'Away') ?>
        </div>
    </form>
<?php endif; ?>

<script type="text/javascript" src="<?=$this->getStaticUrl('js/datetimepicker/js/bootstrap-datetimepicker.js')?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?=$this->getStaticUrl('js/datetimepicker/js/locales/bootstrap-datetimepicker.'.substr($this->getTranslator()->getLocale(), 0, 2).'.js')?>" charset="UTF-8"></script>
<script type="text/javascript">
$(document).ready(function() {
    $(".form_datetime").datetimepicker({
        format: "dd.mm.yyyy",
        startDate: new Date(),
        autoclose: true,
        language: '<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>',
        minView: 2,
        todayHighlight: true,
        linkField: "end",
        linkFormat: "dd.mm.yyyy"
    });
});
</script>
