<?php
$entrie = $this->get('page');
?>
<h1>
    <?=($entrie->getId()) ? $this->getTrans('edit') : $this->getTrans('add') ?>
</h1>

<form method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('pageTitle') ? ' has-error' : '' ?>">
        <label for="pageTitle" class="col-xl-2 col-form-label">
            <?=$this->getTrans('pageTitle') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="pageTitle"
                   name="pageTitle"
                   value="<?=$this->escape($this->originalInput('pageTitle', ($entrie->getId()?$entrie->getTitle():''))) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('pageContent') ? ' has-error' : '' ?>">
        <label for="pageContent" class="col-xl-2 col-form-label">
            <?=$this->getTrans('pageContent') ?>:
        </label>
        <div class="col-xl-8">
            <textarea class="form-control ckeditor"
                      id="pageContent" name="pageContent"
                      toolbar="ilch_html"><?=$this->escape($this->originalInput('pageContent', ($entrie->getId()?$entrie->getContent():''))) ?></textarea>
        </div>
    </div>
    <?php if ($this->get('multilingual') && $this->getRequest()->getParam('locale')): ?>
        <div class="row mb-3">
            <label for="pageLanguage" class="col-xl-2 col-form-label">
                <?=$this->getTrans('pageLanguage') ?>:
            </label>
            <div class="col-xl-2">
                <select class="form-select" id="pageLanguage" name="pageLanguage">
                    <?php foreach ($this->get('languages') as $key => $value): ?>
                        <?php if ($key == $this->get('contentLanguage')): ?>
                            <?php continue; ?>
                        <?php endif; ?>
                        <option value="<?=$key ?>" <?=($this->originalInput('pageLanguage', ($entrie->getId()?$entrie->getLocale():''))) == $key ? 'selected=""' : '' ?>><?=$this->escape($value) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    <?php endif; ?>
    <h1><?=$this->getTrans('seo') ?></h1>
    <div class="row mb-3">
        <label for="description" class="col-xl-2 col-form-label">
            <?=$this->getTrans('seoDescription') ?>:
        </label>
        <div class="col-xl-4">
            <textarea class="form-control"
                      id="description"
                      name="description"><?=$this->escape($this->originalInput('description', ($entrie->getId()?$entrie->getDescription():''))) ?></textarea>
        </div>
    </div>
    <div class="row mb-3">
        <label for="keywords" class="col-xl-2 col-form-label">
            <?=$this->getTrans('seoKeywords') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="choices-select form-control"
                   name="keywords"
                   id="keywords"
                   data-placeholder="<?=$this->getTrans('seoKeywords') ?>"
                   value="<?=$this->escape($this->originalInput('keywords', ($entrie->getId()?$entrie->getKeywords():''))) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('permaLink') ? ' has-error' : '' ?>">
        <label for="permaLink" class="col-xl-2">
            <?=$this->getTrans('permaLink') ?>:
        </label>
        <div class="col-xl-4">
            <div class="input-group">
                <span class="input-group-text" id="basic-addon3"><?=substr($this->getUrl('a', 'default'), 0, -1) ?></span>
                <input class="form-control"
                       type="text"
                       id="permaLink"
                       name="permaLink"
                       value="<?=$this->escape($this->originalInput('permaLink', ($entrie->getId()?$entrie->getPerma():''))) ?>" />
            </div>
        </div>
    </div>
    <?=($entrie->getId()) ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
<script>
<?php
$pageID = $entrie->getId() ?? '';
?>
$('#pageTitle').change (
    function () {
        const entityMap = {
            "&": "",
            "<": "",
            ">": "",
            '"': '',
            "'": '',
            "/": '',
            "(": '',
            ")": '',
            " ": '-',
            ";": ''
        };

        function escapeHtml(string) {
            return String(string).replace(/[&<>"'\/(); ]/g, function (s) {
                return entityMap[s];
            });
        }

        $('#permaLink').val
        (
            escapeHtml($('#pageTitle').val())
            .toLowerCase()+'.html'
        );
    }
);

$('#pageLanguage').change (
    this,
    function () {
        top.location.href = '<?=$this->getUrl(['id' => $pageID]) ?>/locale/'+$(this).val()
    }
);

new Tokenfield('keywords', {
    removeItemButton: true,
    shouldSort: false,
    duplicateItemsAllowed: false,
});
</script>
