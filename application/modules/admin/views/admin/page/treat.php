<?php
$entrie = $this->get('page');
?>
<h1>
    <?=($entrie->getId()) ? $this->getTrans('edit') : $this->getTrans('add') ?>
</h1>

<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('pageTitle') ? 'has-error' : '' ?>">
        <label for="pageTitle" class="col-lg-2 control-label">
            <?=$this->getTrans('pageTitle') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="pageTitle"
                   name="pageTitle"
                   value="<?=$this->escape($this->originalInput('pageTitle', ($entrie->getId()?$entrie->getTitle():''))) ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('pageContent') ? 'has-error' : '' ?>">
        <label for="pageContent" class="col-lg-2 control-label">
            <?=$this->getTrans('pageContent') ?>:
        </label>
        <div class="col-lg-8">
            <textarea class="form-control ckeditor"
                      id="pageContent" name="pageContent"
                      toolbar="ilch_html"><?=$this->escape($this->originalInput('pageContent', ($entrie->getId()?$entrie->getContent():''))) ?></textarea>
        </div>
    </div>
    <?php if ($this->get('multilingual') && $this->getRequest()->getParam('locale')): ?>
        <div class="form-group">
            <label for="pageLanguage" class="col-lg-2 control-label">
                <?=$this->getTrans('pageLanguage') ?>:
            </label>
            <div class="col-lg-2">
                <select class="form-control" id="pageLanguage" name="pageLanguage">
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
    <div class="form-group">
        <label for="description" class="col-lg-2 control-label">
            <?=$this->getTrans('seoDescription') ?>:
        </label>
        <div class="col-lg-4">
            <textarea class="form-control"
                      id="description"
                      name="description"><?=$this->escape($this->originalInput('description', ($entrie->getId()?$entrie->getDescription():''))) ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="keywords" class="col-lg-2 control-label">
            <?=$this->getTrans('seoKeywords') ?>:
        </label>
        <div class="col-lg-4">
            <textarea class="form-control"
                      id="keywords"
                      name="keywords"><?=$this->escape($this->originalInput('keywords', ($entrie->getId()?$entrie->getKeywords():''))) ?></textarea>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('permaLink') ? 'has-error' : '' ?>">
        <label for="permaLink" class="col-lg-2 control-label">
            <?=$this->getTrans('permaLink') ?>:
        </label>
        <div class="col-lg-4">
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon3"><?=substr($this->getUrl('a', 'default'), 0, -1) ?></span>
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

$('#keywords').tokenfield().on('tokenfield:createtoken', function (event) {
    $.each($(this).tokenfield('getTokens'), function(index, token) {
        if (token.value === event.attrs.value)
            event.preventDefault();
    });
});
</script>
