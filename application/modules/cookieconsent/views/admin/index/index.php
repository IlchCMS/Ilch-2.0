<?php $cookieConsentMapper = $this->get('cookieConsentMapper'); ?>

<h1><?=$this->getTrans('manage') ?></h1>
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col class="icon_width">
            <col />
            <?php if ($this->get('multilingual')): ?>
                <col class="col-lg-1" />
            <?php endif; ?>
        </colgroup>
        <thead>
        <tr>
            <th></th>
            <th><?=$this->getTrans('cookieConsentDesc') ?></th>
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
        <?php foreach ($this->get('cookieConsents') as $cookieConsent): ?>
            <tr>
                <td><?=$this->getEditIcon(['action' => 'treat', 'locale' => $cookieConsent->getLocale()]) ?></td>
                <td><?=$this->getTrans('cookieConsentText') ?></td>
                <?php if ($this->get('multilingual')): ?>
                    <td class="text-right">
                        <?php foreach ($this->getTranslator()->getLocaleList() as $key => $value): ?>
                            <?php if ($key == $this->get('contentLanguage')): ?>
                                <?php continue; ?>
                            <?php endif; ?>

                            <a href="<?=$this->getUrl(['action' => 'treat', 'locale' => $key]) ?>"><?=($cookieConsentMapper->getCookieConsentByLocale($key)) ? '<i class="fa fa-edit"></i>' : '<i class="fa fa-plus-circle"></i>' ?></a>
                        <?php endforeach; ?>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
