<h1><?= $this->getTrans('htmlPurifierSettings') ?></h1>
<form method="POST">
    <?= $this->getTokenField() ?>
    <div id="htmlPurifierSafeUrlsAddOwnDomain" class="row mb-3">
        <label class="col-xl-2 col-form-label" for="ownDomain">
            <?= $this->getTrans('htmlPurifierSafeUrlsAddOwnDomain') ?>:
        </label>
        <div class="col-xl-3">
            <div class="input-group">
                <input type="text"
                       class="form-control"
                       id="ownDomain"
                       name="ownDomain"
                       value="<?= $this->escape($this->get('domain')) ?>"
                       disabled />
                <div class="flipswitch">
                    <input type="radio" class="flipswitch-input" id="htmlPurifierSafeUrlsAddOwnDomain-on" name="htmlPurifierSafeUrlsAddOwnDomain" value="1" <?= ($this->get('htmlPurifierSafeUrlsAddOwnDomain')) ? 'checked="checked"' : '' ?> />
                    <label for="htmlPurifierSafeUrlsAddOwnDomain-on" class="flipswitch-label flipswitch-label-on"><?= $this->getTrans('on') ?></label>
                    <input type="radio" class="flipswitch-input" id="htmlPurifierSafeUrlsAddOwnDomain-off" name="htmlPurifierSafeUrlsAddOwnDomain" value="0" <?= (!$this->get('htmlPurifierSafeUrlsAddOwnDomain')) ? 'checked="checked"' : '' ?> />
                    <label for="htmlPurifierSafeUrlsAddOwnDomain-off" class="flipswitch-label flipswitch-label-off"><?= $this->getTrans('off') ?></label>
                    <span class="flipswitch-selection"></span>
                </div>
            </div>
            <div id="htmlPurifierOwnDomainHelp" class="form-text"><?= $this->getTrans('htmlPurifierOwnDomainHelp') ?></div>
        </div>
    </div>
    <div id="htmlPurifierUrlsConsideredSafe" class="row mb-3">
        <label class="col-xl-2 col-form-label">
            <?= $this->getTrans('htmlPurifierUrlsConsideredSafe') ?>:
        </label>
        <div class="col-xl-4">
            <ul class="list-group">
                <?php foreach ($this->get('urlsConsideredSafe') as $url): ?>
                    <li class="list-group-item"><?= $url ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?= $this->getSaveBar() ?>
</form>
