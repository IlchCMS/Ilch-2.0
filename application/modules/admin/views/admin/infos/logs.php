<?php
$userMapper = $this->get('userMapper');
$userCache = [];
?>
<link href="<?=$this->getStaticUrl('js/tempus-dominus/dist/css/tempus-dominus.min.css') ?>" rel="stylesheet">
<h1><?=$this->getTrans('logs') ?></h1>
<p>
    <a class="btn btn-primary" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
        <i class="fa-solid fa-filter"></i> <?=$this->getTrans('filter') ?>
    </a>
</p>
<div class="panel panel-default collapse" id="collapseExample">
    <form class="form-horizontal" method="POST">
        <?=$this->getTokenField() ?>
        <div class="panel-body">
            <div class="row mb-3">
                <label for="startDate" class="col-xl-2 control-label">
                    <?=$this->getTrans('startDate') ?>:
                </label>
                <div id="startDate" class="col-xl-4 input-group ilch-date date form_datetime">
                    <input type="text"
                           class="form-control"
                           id="startDate"
                           name="startDate"
                           value="<?=date('d.m.Y 00:00', strtotime( '-7 days' )) ?>"
                           readonly>
                    <span class="input-group-text">
                        <span class="fa-solid fa-calendar"></span>
                    </span>
                </div>
            </div>
            <div class="row mb-3">
                <label for="endDate" class="col-xl-2 control-label">
                    <?=$this->getTrans('endDate') ?>:
                </label>
                <div id="endDate" class="col-xl-4 input-group ilch-date date form_datetime">
                    <input type="text"
                           class="form-control"
                           id="endDate"
                           name="endDate"
                           value="<?=date('d.m.Y 23:59') ?>"
                           readonly>
                    <span class="input-group-text">
                        <span class="fa-solid fa-calendar"></span>
                    </span>
                </div>
            </div>
        </div>

        <button type="submit" name="filterLog" class="btn btn-primary" value="1"><?=$this->getTrans('filterLog') ?></button>
    </form>
</div>

<?php if ($this->get('logsDate') != ''): ?>
    <?php foreach ($this->get('logsDate') as $date => $logs): ?>
        <h4><?=$date ?></h4>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="col-lg-1" />
                    <col class="col-lg-2" />
                    <col />
                </colgroup>
                <thead>
                <tr>
                    <th><?=$this->getTrans('time') ?></th>
                    <th><?=$this->getTrans('users') ?></th>
                    <th><?=$this->getTrans('info') ?></th>
                </tr>
                </thead>
                <tbody>
        <?php foreach($logs as $log) : ?>
            <?php $time = new \Ilch\Date($log->getDate()); ?>
            <?php if (!array_key_exists($log->getUserId(), $userCache)) {
                $userCache[$log->getUserId()] = $userMapper->getUserById($log->getUserId());
            }
            $user = $userCache[$log->getUserId()];
            ?>
                    <tr>
                        <td><?=$time->format('H:i:s') ?></td>
                        <td>
                            <?php
                            if ($user != '') {
                                echo $this->escape($user->getName());
                            } else {
                                echo $this->getTrans('unknown');
                            }
                            ?>
                        </td>
                        <td><?=$this->escape($log->getInfo()) ?></td>
                    </tr>
        <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<div class="content_savebox">
    <form class="form-horizontal" method="POST">
        <?=$this->getTokenField() ?>
        <button type="submit" name="clearLog" class="btn btn-default" value="1"><?=$this->getTrans('clearLog') ?></button>
    </form>
</div>

<script src="<?=$this->getStaticUrl('js/popper/dist/umd/popper.min.js') ?>" charset="UTF-8"></script>
<script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/js/tempus-dominus.min.js') ?>" charset="UTF-8"></script>
<?php if (strncmp($this->getTranslator()->getLocale(), 'en', 2) !== 0) : ?>
    <script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/locales/' . substr($this->getTranslator()->getLocale(), 0, 2) . '.js') ?>" charset="UTF-8"></script>
<?php endif; ?>
<script>
$(document).ready(function() {
    const start = new tempusDominus.TempusDominus(document.getElementById('startDate'), {
        display: {
            sideBySide: true,
            calendarWeeks: true,
            buttons: {
                today: true,
                close: true
            }
        },
        localization: {
            locale: "<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>",
            startOfTheWeek: 1,
            format: "dd.MM.yyyy HH:mm"
        },
        promptTimeOnDateChange: true,
        stepping: 15
    });

    const end = new tempusDominus.TempusDominus(document.getElementById('endDate'), {
        display: {
            sideBySide: true,
            calendarWeeks: true,
            buttons: {
                today: true,
                close: true
            }
        },
        localization: {
            locale: "<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>",
            startOfTheWeek: 1,
            format: "dd.MM.yyyy HH:mm"
        },
        stepping: 15
    });

    start.subscribe('change.td', (e) => {
        end.updateOptions({
            restrictions: {
                minDate: e.date,
            },
        });
    });

    end.subscribe('change.td', (e) => {
        start.updateOptions({
            restrictions: {
                maxDate: e.date,
            },
        });
    });
});
</script>
