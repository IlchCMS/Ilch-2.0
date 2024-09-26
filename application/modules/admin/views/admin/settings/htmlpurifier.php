<h1><?= $this->getTrans('htmlPurifierSettings') ?></h1>
<form method="POST">
    <?= $this->getTokenField() ?>
    <div id="htmlPurifierSafeUrlsAddOwnDomain" class="row mb-3">
        <label class="col-xl-2 col-form-label" for="ownDomain">
            <?= $this->getTrans('htmlPurifierSafeUrlsAddOwnDomain') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="ownDomain"
                   name="ownDomain"
                   value="<?= $this->escape($this->get('domain')) ?>"
                   disabled />
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
