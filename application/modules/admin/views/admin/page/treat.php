<h1>
    <?php
    if ($this->get('page') != '') {
        echo $this->getTrans('edit');
    } else {
        echo $this->getTrans('add');
    }
    ?>
</h1>

<form class="form-horizontal" method="POST" action="">
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
                   value="<?php if ($this->get('page') != '') { echo $this->escape($this->get('page')->getTitle()); } else { echo $this->get('post')['pageTitle']; } ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('pageContent') ? 'has-error' : '' ?>">
        <label for="pageContent" class="col-lg-2 control-label">
            <?=$this->getTrans('pageContent') ?>:
        </label>
        <div class="col-lg-8">
            <textarea class="form-control ckeditor"
                      id="pageContent" name="pageContent"
                      toolbar="ilch_html"><?php if ($this->get('page') != '') { echo $this->escape($this->get('page')->getContent()); } else { echo $this->get('post')['pageContent']; } ?></textarea>
        </div>
    </div>
    <?php if ($this->get('multilingual') && $this->getRequest()->getParam('locale') != ''): ?>
        <div class="form-group">
            <label for="pageLanguage" class="col-lg-2 control-label">
                <?=$this->getTrans('pageLanguage') ?>:
            </label>
            <div class="col-lg-8">
                <select class="form-control" id="pageLanguage" name="pageLanguage">
                    <?php
                    foreach ($this->get('languages') as $key => $value) {
                        $selected = '';

                        if ($key == $this->get('contentLanguage')) {
                            continue;
                        }

                        if ($this->getRequest()->getParam('locale') == $key) {
                            $selected = 'selected="selected"';
                        }

                        echo '<option '.$selected.' value="'.$key.'">'.$this->escape($value).'</option>';
                    }
                    ?>
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
                      name="description"><?php if ($this->get('page') != '') { echo $this->escape($this->get('page')->getDescription()); } else { echo $this->get('post')['description']; } ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="keywords" class="col-lg-2 control-label">
            <?=$this->getTrans('seoKeywords') ?>:
        </label>
        <div class="col-lg-4">
            <textarea class="form-control"
                      id="keywords"
                      name="keywords"><?php if ($this->get('page') != '') { echo $this->escape($this->get('page')->getKeywords()); } else { echo $this->get('post')['keywords']; } ?></textarea>
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
                       value="<?php if ($this->get('page') != '') { echo $this->escape($this->get('page')->getPerma()); } else { echo $this->get('post')['permaLink']; } ?>" />
            </div>
        </div>
    </div>
    <?php
    if ($this->get('page') != '') {
        echo $this->getSaveBar('updateButton');
    } else {
        echo $this->getSaveBar('addButton');
    }
    ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
<script>
<?php
$pageID = '';

if ($this->get('page') != '') {
    $pageID = $this->get('page')->getId();
}
?>
$('#pageTitle').change (
    function () {
        var entityMap = {
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
        var title = escapeHtml($('#pageTitle').val());
        $('#permaLink').val
        (
            title
            .toLowerCase()+'.html'
        );
    }
);

$('#pageLanguage').change (
    this,
    function () {
        top.location.href = '<?=$this->getUrl(['id' => $pageID]); ?>/locale/'+$(this).val()
    }
);

$('#keywords').tokenfield();
$('#keywords').on('tokenfield:createtoken', function (event) {
    var existingTokens = $(this).tokenfield('getTokens');
    $.each(existingTokens, function(index, token) {
        if (token.value === event.attrs.value)
            event.preventDefault();
    });
});
</script>
