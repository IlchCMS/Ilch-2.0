<?php

/** @var \Ilch\View $this */

use Modules\Away\Models\Away;
use Modules\User\Mappers\User;

/** @var \Modules\User\Models\User[] $userCache */
$userCache = $this->get('userCache');

/** @var User $userMapper */
$userMapper = $this->get('userMapper');

/** @var Away $currentlyEditingAway */
$currentlyEditingAway = $this->get('currentlyEditingAway');

$userCheck = null;
if ($this->getUser()) {
    $userCheck = $userMapper->getUserById($this->getUser()->getId());
}
?>

<link href="<?=$this->getModuleUrl('static/css/away.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('js/tempus-dominus/dist/css/tempus-dominus.min.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('menuAway') ?></h1>
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col class="col-xl-6">
            <col class="col-xl-5">
            <col class="col-xl-1">
            <col class="icon_width" />
            <col class="icon_width" />
            <col class="icon_width" />
        </colgroup>
        <thead>
            <tr>
                <th><?=$this->getTrans('user') ?> / <?=$this->getTrans('reason') ?></th>
                <th><?=$this->getTrans('when') ?></th>
                <th><?=$this->getTrans('status') ?></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <?php if (!empty($this->get('aways'))) : ?>
            <form method="POST" action="">
                <?=$this->getTokenField() ?>
                <tbody>
                    <?php
                    /** @var \Modules\Away\Models\Away $away */
                    foreach ($this->get('aways') as $away) : ?>
                        <?php $user = (!empty($userCache[$away->getUserId()])) ? $userCache[$away->getUserId()] : null; ?>
                        <tr id="<?=$away->getId() ?>">
                            <td>
                                <?php if (!empty($user)) : ?>
                                    <a href="<?=$this->getUrl('user/profil/index/user/' . $user->getId()) ?>" target="_blank"><?=$user->getName() ?></a><br>
                                <?php else : ?>
                                    <?=$this->getTrans('unknown') ?><br>
                                <?php endif; ?>
                                <?=$this->escape($away->getReason()) ?>
                            </td>
                            <?php $startDate = new \Ilch\Date($away->getStart()); ?>
                            <?php $endDate = new \Ilch\Date($away->getEnd()); ?>
                            <?php $class = ($away->getStart() >= date('Y-m-d') || $away->getEnd() >= date('Y-m-d')) ? "futureDate" : "pastDate" ?>
                            <td class="dateColumn <?=$class ?>">
                                <div class="row">
                                    <div class="agenda col">
                                        <div class="dayofmonth"><?=$startDate->format('d', true) ?></div>
                                        <div><?=$this->getTrans($startDate->format('l', true)) ?></div>
                                        <div class="shortdate"><?=$this->getTrans($startDate->format('F', true)) . $startDate->format(', Y', true) ?></div>
                                    </div>
                                    <div class="agenda-arrow col"><i class="fa-solid fa-chevron-right"></i></div>
                                    <div class="agenda col">
                                        <div class="dayofmonth"><?=$endDate->format('d', true) ?></div>
                                        <div><?=$this->getTrans($endDate->format('l', true)) ?></div>
                                        <div class="shortdate"><?=$this->getTrans($endDate->format('F', true)) . $endDate->format(', Y', true) ?></div>
                                    </div>
                                </div>
                            </td>
                            <?php if ($away->getStatus() == 2) : ?>
                                <td style="color: #4295C9;"><?=$this->getTrans('reported') ?></td>
                            <?php elseif ($away->getStatus() == 0) : ?>
                                <td style="color: #ff0000;"><?=$this->getTrans('declined') ?></td>
                            <?php else : ?>
                                <td style="color: #008000;"><?=$this->getTrans('approved') ?></td>
                            <?php endif; ?>
                            <td>
                                <?php if ($this->getUser()) : ?>
                                    <?php if ($userCheck->isAdmin()) : ?>
                                        <?php if ($away->getStart() >= date('Y-m-d') || $away->getEnd() >= date('Y-m-d')) : ?>
                                            <?php if ($away->getStatus() == 1) : ?>
                                                <a href="<?=$this->getUrl(['action' => 'update', 'id' => $away->getId()], null, true) ?>" title="<?= $this->getTrans('decline') ?>">
                                                    <span class="fa-regular fa-square-check text-info"></span>
                                                </a>
                                            <?php else : ?>
                                                <a href="<?=$this->getUrl(['action' => 'update', 'id' => $away->getId()], null, true) ?>" title="<?= $this->getTrans('approve') ?>">
                                                    <span class="fa-regular fa-square text-info"></span>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($this->getUser()) : ?>
                                    <?php if ($userCheck->isAdmin() || $away->getUserId() == $this->getUser()->getId()) : ?>
                                        <?=$this->getDeleteIcon(['action' => 'del', 'id' => $away->getId()]) ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($away->getUserId() == $this->getUser()->getId()) : ?>
                                    <?=$this->getEditIcon(['action' => 'index', 'id' => $away->getId()]) ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6"><?=$this->escape($away->getText()) ?></td colspan="6">
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </form>
        <?php else : ?>
            <tr>
                <td colspan="5"><?=$this->getTrans('noAway') ?></td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<?php if ($this->getUser()) : ?>
    <form method="POST" action="">
        <?=$this->getTokenField() ?>
        <h1><?=$this->getTrans('menuEntry') ?></h1>

        <div class="row mb-3<?=$this->validation()->hasError('reason') ? ' has-error' : '' ?>">
            <label for="reason" class="col-xl-2 col-form-label">
                <?=$this->getTrans('reason') ?>:
            </label>
            <div class="col-xl-6">
                <input type="text"
                       class="form-control"
                       id="reason"
                       name="reason"
                       value="<?=$this->originalInput('reason', $currentlyEditingAway->getReason(), true) ?>" />
            </div>
        </div>
        <div class="row mb-3<?=$this->validation()->hasError('when') ? ' has-error' : '' ?>">
            <label for="start" class="col-xl-2 col-form-label">
                <?=$this->getTrans('when') ?>:
            </label>
            <div id="start" class="col-xl-3 input-group date form_datetime float-start">
                <input type="text"
                       class="form-control"
                       id="start"
                       name="start"
                       size="16"
                       value="<?=$this->originalInput('start', ($currentlyEditingAway->getId() ? (new \Ilch\Date($currentlyEditingAway->getStart()))->format('d.m.Y') : ''), true) ?>"
                       readonly>
                <span class="input-group-text">
                    <span class="fa-solid fa-calendar"></span>
                </span>
            </div>
            <div id="end" class="col-xl-3 input-group date form_datetime">
                <input type="text"
                       class="form-control"
                       id="end"
                       name="end"
                       size="16"
                       value="<?=$this->originalInput('end', ($currentlyEditingAway->getId() ? (new \Ilch\Date($currentlyEditingAway->getEnd()))->format('d.m.Y') : ''), true) ?>"
                       readonly>
                <span class="input-group-text">
                    <span class="fa-solid fa-calendar"></span>
                </span>
            </div>
        </div>
        <div class="row mb-3<?=$this->validation()->hasError('text') ? ' has-error' : '' ?>">
            <label for="text" class="col-xl-2 col-form-label">
                <?=$this->getTrans('description') ?>:
            </label>
            <div class="col-xl-6">
                <textarea class="form-control"
                          name="text"
                          id="text"
                          rows="3"><?=$this->originalInput('text', $currentlyEditingAway->getText(), true) ?></textarea>
            </div>
        </div>
        <?php if ($this->get('calendarShow') == 1) : ?>
            <div class="row mb-3">
                <div class="offset-xl-2 col-xl-10">
                    <input type="checkbox"
                           id="calendarShow"
                           name="calendarShow"
                           value="1"
                           <?=$this->originalInput('calendarShow', $currentlyEditingAway->getShow()) ? 'checked' : '' ?> />
                    <label for="calendarShow">
                        <?=$this->getTrans('calendarShow') ?>
                    </label>
                </div>
            </div>
        <?php endif; ?>
        <div class="row col-xl-5 float-end">
            <?=$this->getSaveBar('addButton', 'Away') ?>
        </div>
    </form>
<?php endif; ?>

<script src="<?=$this->getStaticUrl('js/popper/dist/umd/popper.min.js') ?>" charset="UTF-8"></script>
<script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/js/tempus-dominus.min.js') ?>" charset="UTF-8"></script>
<?php if (strncmp($this->getTranslator()->getLocale(), 'en', 2) !== 0) : ?>
    <script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/locales/' . substr($this->getTranslator()->getLocale(), 0, 2) . '.js') ?>" charset="UTF-8"></script>
<?php endif; ?>
<script>
$(document).ready(function() {
    if ("<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>" !== 'en') {
        tempusDominus.loadLocale(tempusDominus.locales.<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>);
        tempusDominus.locale(tempusDominus.locales.<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>.name);
    }

    const start = new tempusDominus.TempusDominus(document.getElementById('start'), {
        restrictions: {
          minDate: new Date()
        },
        display: {
            calendarWeeks: true,
            buttons: {
                today: true,
                close: true
            },
            components: {
                clock: false
            }
        },
        localization: {
            locale: "<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>",
            startOfTheWeek: 1,
            format: "dd.MM.yyyy"
        }
    });

    const end = new tempusDominus.TempusDominus(document.getElementById('end'), {
        restrictions: {
          minDate: new Date()
        },
        display: {
            calendarWeeks: true,
            buttons: {
                today: true,
                close: true
            },
            components: {
                clock: false
            }
        },
        localization: {
            locale: "<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>",
            startOfTheWeek: 1,
            format: "dd.MM.yyyy"
        }
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
