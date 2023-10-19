<?php
$emailsMapper = $this->get('emailsMapper');
$moduleMapper = $this->get('moduleMapper');
?>

<h1><?=$this->getTrans('menuEmails') ?></h1>
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col />
            <?php if ($this->get('multilingual')): ?>
                <col class="col-xl-1" />
            <?php endif; ?>
        </colgroup>
        <thead>
            <tr>
                <th><?=$this->getTrans('modules') ?></th>
                <?php if ($this->get('multilingual')): ?>
                    <th class="text-right">
                        <?php foreach ($this->getTranslator()->getLocaleList() as $key => $value): ?>
                            <?php if ($key == $this->get('contentLanguage')): ?>
                                <?php continue; ?>
                            <?php endif; ?>

                            <img src="<?=$this->getStaticUrl('img/lang/'.$key.'.png') ?>">
                        <?php endforeach; ?>
                    </th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->get('emailModules') as $modules): ?>
                <?php $module = $moduleMapper->getModulesByKey($modules->getModuleKey(), $this->getTranslator()->getLocale()); ?>
                <?php $emails = $emailsMapper->getEmailsByKey($modules->getModuleKey(), $this->getTranslator()->getLocale()); ?>
                <tr>
                    <td <?=($this->get('multilingual')) ? 'colspan=2' : '' ?>>
                        <b><?=$module->getName() ?></b>
                    </td>
                </tr>
                <?php foreach ($emails as $email): ?>
                    <tr>
                        <td>
                            <a href="<?=$this->getUrl(['action' => 'treat', 'key' => $modules->getModuleKey(), 'type' => $email->getType()]) ?>">
                                <?=$email->getDesc() ?>
                            </a>
                        </td>
                        <?php if ($this->get('multilingual')): ?>
                            <td class="text-right">
                                <?php foreach ($this->getTranslator()->getLocaleList() as $key => $value): ?>
                                    <?php if ($key == $this->get('contentLanguage')): ?>
                                        <?php continue; ?>
                                    <?php endif; ?>

                                    <?php if ($emailsMapper->getEmailsByKeyTypeLocale($modules->getModuleKey(), $email->getType(), $key)): ?>
                                        <a href="<?=$this->getUrl(['action' => 'treat', 'key' => $modules->getModuleKey(), 'type' => $email->getType(), 'locale' => $key]) ?>"><i class="fa-regular fa-pen-to-square"></i></a>
                                    <?php else: ?>
                                        <a href="<?=$this->getUrl(['action' => 'treat', 'key' => $modules->getModuleKey(), 'type' => $email->getType(), 'locale' => $key]) ?>"><i class="fa-solid fa-circle-plus"></i></a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
