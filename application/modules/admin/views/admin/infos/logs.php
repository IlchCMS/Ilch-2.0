<?php
$userMapper = $this->get('userMapper');
$userCache = [];
?>
<h1><?=$this->getTrans('logs') ?></h1>
<p>
    <a class="btn btn-primary" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
        <i class="fa-solid fa-filter"></i> <?=$this->getTrans('filter') ?>
    </a>
</p>
<div class="card card-default collapse" id="collapseExample">
    <form method="POST">
        <?=$this->getTokenField() ?>
        <div class="card-body">
            <div class="row mb-3">
                <label for="startDate" class="col-xl-2 col-form-label">
                    <?=$this->getTrans('startDate') ?>:
                </label>
                <div class="col-xl-4">
                    <input type="datetime-local"
                           class="form-control"
                           id="startDate"
                           name="startDate"
                           value="<?=date('Y-m-d\T00:00', strtotime('-7 days')) ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="endDate" class="col-xl-2 col-form-label">
                    <?=$this->getTrans('endDate') ?>:
                </label>
                <div class="col-xl-4">
                    <input type="datetime-local"
                           class="form-control"
                           id="endDate"
                           name="endDate"
                           value="<?=date('Y-m-d\T23:59') ?>">
                </div>
            </div>
        </div>

        <button type="submit" name="filterLog" class="btn btn-primary" value="1"><?=$this->getTrans('filterLog') ?></button>
    </form>
</div>

<?php if ($this->get('logsDate') != '') : ?>
    <?php foreach ($this->get('logsDate') as $date => $logs) : ?>
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
        <?php foreach ($logs as $log) : ?>
            <?php $time = new \Ilch\Date($log->getDate()); ?>
            <?php
            if (!array_key_exists($log->getUserId(), $userCache)) {
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
    <form method="POST">
        <?=$this->getTokenField() ?>
        <button type="submit" name="clearLog" class="btn btn-secondary" value="1"><?=$this->getTrans('clearLog') ?></button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const startInput = document.getElementById('startDate');
    const endInput = document.getElementById('endDate');

    function syncDateLimits() {
        if (startInput.value) {
            endInput.min = startInput.value;
        } else {
            endInput.removeAttribute('min');
        }
        if (endInput.value) {
            startInput.max = endInput.value;
        } else {
            startInput.removeAttribute('max');
        }
    }

    startInput.addEventListener('change', syncDateLimits);
    endInput.addEventListener('change', syncDateLimits);
    syncDateLimits();
});
</script>
