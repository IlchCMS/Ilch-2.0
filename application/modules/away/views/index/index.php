<?php
$userCache = $this->get('userCache');
$userMapper = $this->get('userMapper');
if ($this->getUser()) {
    $userCheck = $userMapper->getUserById($this->getUser()->getId());
}
?>

<link href="<?=$this->getModuleUrl('static/css/away.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('js/datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('menuAway') ?></h1>
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col>
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
        <?php if (!empty($this->get('aways'))): ?>
            <form class="form-horizontal" method="POST" action="">
                <?=$this->getTokenField() ?>
                <tbody>
                    <?php foreach ($this->get('aways') as $away): ?>
                        <?php $user = (!empty($userCache[$away->getUserId()])) ? $userCache[$away->getUserId()] : null; ?>
                        <tr id="<?=$away->getId() ?>">
                            <td>
                                <?php if (!empty($user)) : ?>
                                    <a href="<?=$this->getUrl('user/profil/index/user/'.$user->getId()) ?>" target="_blank"><?=$user->getName() ?></a><br>
                                <?php else : ?>
                                    <?=$this->getTrans('unknown') ?><br>
                                <?php endif; ?>
                                <?=$this->escape($away->getReason()) ?>
                            </td>
                            <?php $startDate = new \Ilch\Date($away->getStart()); ?>
                            <?php $endDate = new \Ilch\Date($away->getEnd()); ?>
                            <?php if ($away->getStart() >= date('Y-m-d') || $away->getEnd() >= date('Y-m-d')): ?>
                                <?php $style = 'color: #008000; border-right: 1px solid #dddddd; border-left: 1px solid #dddddd;'?>
                            <?php else: ?>
                                <?php $style = 'color: #ff0000; border-right: 1px solid #dddddd; border-left: 1px solid #dddddd;'?>
                            <?php endif; ?>
                            <td style="<?=$style ?>">
                                <div class="agenda" style="float:left;">
                                    <div class="dayofmonth"><?=$startDate->format('d', true) ?></div>
                                    <div><?=$this->getTrans($startDate->format('l', true)) ?></div>
                                    <div class="shortdate"><?=$this->getTrans($startDate->format('F', true)).$startDate->format(', Y', true) ?></div>
                                </div>
                                <div class="agenda-arrow"><i class="fa fa-chevron-right"></i></div>
                                <div>
                                    <div class="dayofmonth"><?=$endDate->format('d', true) ?></div>
                                    <div><?=$this->getTrans($endDate->format('l', true)) ?></div>
                                    <div class="shortdate"><?=$this->getTrans($endDate->format('F', true)).$endDate->format(', Y', true) ?></div>
                                </div>
                            </td>
                            <?php if ($away->getStatus() == 2): ?>
                                <td style="color: #4295C9;"><?=$this->getTrans('reported') ?></td>
                            <?php elseif ($away->getStatus() == 0): ?>
                                <td style="color: #ff0000;"><?=$this->getTrans('declined') ?></td>
                            <?php else: ?>
                                <td style="color: #008000;"><?=$this->getTrans('approved') ?></td>
                            <?php endif; ?>
                            <td>
                                <?php if ($this->getUser()): ?>
                                    <?php if ($userCheck->isAdmin()): ?>
                                        <?php if ($away->getStart() >= date('Y-m-d') || $away->getEnd() >= date('Y-m-d')): ?>
                                            <?php if ($away->getStatus() == 1): ?>
                                                <a href="<?=$this->getUrl(['action' => 'update', 'id' => $away->getId()], null, true) ?>">
                                                    <span class="fa fa-check-square-o text-info"></span>
                                                </a>
                                            <?php else: ?>
                                                <a href="<?=$this->getUrl(['action' => 'update', 'id' => $away->getId()], null, true) ?>">
                                                    <span class="fa fa-square-o text-info"></span>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($this->getUser()): ?>
                                    <?php if ($userCheck->isAdmin() || $away->getUserId() == $this->getUser()->getId()): ?>
                                        <?=$this->getDeleteIcon(['action' => 'del', 'id' => $away->getId()]) ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5"><?=$this->escape($away->getText()) ?></td>
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

<?php if ($this->getUser()): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <h1><?=$this->getTrans('menuEntry') ?></h1>

        <div class="form-group <?=in_array('reason', $this->get('errorFields')) ? 'has-error' : '' ?>">
            <label for="reason" class="col-lg-2 control-label">
                <?=$this->getTrans('reason') ?>:
            </label>
            <div class="col-lg-6">
                <input type="text"
                       class="form-control"
                       id="reason"
                       name="reason"
                       value="<?=($this->get('post') != '') ? $this->get('post')['reason'] : '' ?>" />
            </div>
        </div>
        <div class="form-group <?=(in_array('start', $this->get('errorFields')) or in_array('end', $this->get('errorFields'))) ? 'has-error' : '' ?>">
            <label for="start" class="col-md-2 control-label">
                <?=$this->getTrans('when') ?>:
            </label>
            <div class="col-lg-3 input-group ilch-date date form_datetime pull-left">
                <input type="text"
                       class="form-control"
                       id="start"
                       name="start"
                       size="16"
                       readonly>
                <span class="input-group-addon">
                    <span class="fa fa-calendar"></span>
                </span>
            </div>
            <div class="col-lg-3 input-group ilch-date date form_datetime">
                <input type="text"
                       class="form-control"
                       id="end"
                       name="end"
                       size="16"
                       readonly>
                <span class="input-group-addon">
                    <span class="fa fa-calendar"></span>
                </span>
            </div>
        </div>
        <div class="form-group <?=in_array('text', $this->get('errorFields')) ? 'has-error' : '' ?>">
            <label for="text" class="col-lg-2 control-label">
                <?=$this->getTrans('description') ?>:
            </label>
            <div class="col-lg-6">
                <textarea class="form-control"
                          name="text"
                          id="text"
                          rows="3"><?=($this->get('post') != '') ? $this->get('post')['text'] : '' ?></textarea>
            </div>
        </div>
        <?php if ($this->get('calendarShow') == 1): ?>
            <div class="form-group">
                <div class="col-lg-offset-2 col-lg-10">
                    <input type="checkbox"
                           id="calendarShow"
                           name="calendarShow"
                           value="1"
                           <?=($this->get('post')['calendarShow'] != '') ? 'checked' : '' ?> />
                    <label for="calendarShow">
                        <?=$this->getTrans('calendarShow') ?>
                    </label>
                </div>
            </div>
        <?php endif; ?>
        <div class="col-lg-8" align="right">
            <?=$this->getSaveBar('addButton', 'Away') ?>
        </div>
    </form>
<?php endif; ?>

<script src="<?=$this->getStaticUrl('js/datetimepicker/js/bootstrap-datetimepicker.min.js') ?>" charset="UTF-8"></script>
<?php if (strncmp($this->getTranslator()->getLocale(), 'en', 2) !== 0): ?>
    <script src="<?=$this->getStaticUrl('js/datetimepicker/js/locales/bootstrap-datetimepicker.'.substr($this->getTranslator()->getLocale(), 0, 2).'.js') ?>" charset="UTF-8"></script>
<?php endif; ?>
<script>
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
