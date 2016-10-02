<legend>
    <?php
    if ($this->get('page') != '') {
        echo $this->getTrans('edit');
    } else {
        echo $this->getTrans('add');
    }
    ?>
</legend>

<?php if (!empty($this->get('errors'))): ?>
    <div class="alert alert-danger" role="alert">
        <strong> <?=$this->getTrans('errorsOccured') ?>:</strong>
        <ul>
            <?php foreach ($this->get('errors') as $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=in_array('pageTitle', $this->get('errorFields')) ? 'has-error' : '' ?>">
        <label for="pageTitle" class="col-lg-2 control-label">
            <?=$this->getTrans('pageTitle') ?>:
        </label>
        <div class="col-lg-8">
            <input type="text"
                   class="form-control"
                   id="pageTitle"
                   name="pageTitle"
                   value="<?php if ($this->get('page') != '') { echo $this->escape($this->get('page')->getTitle()); } else { echo $this->get('post')['pageTitle']; } ?>" />
        </div>
    </div>
    <div class="form-group <?=in_array('pageContent', $this->get('errorFields')) ? 'has-error' : '' ?>">
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

    <legend><?=$this->getTrans('seo') ?></legend>
    <div class="form-group">
        <label for="description" class="col-lg-2 control-label">
            <?=$this->getTrans('seoDescription') ?>:
        </label>
        <div class="col-lg-8">
            <textarea class="form-control"
                      id="description"
                      name="description"><?php if ($this->get('page') != '') { echo $this->escape($this->get('page')->getDescription()); } ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="keywords" class="col-lg-2 control-label">
            <?=$this->getTrans('seoKeywords') ?>:
        </label>
        <div class="col-lg-8">
            <textarea class="form-control"
                      id="keywords"
                      name="keywords"><?php if ($this->get('page') != '') { echo $this->escape($this->get('page')->getKeywords()); } ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="pagePerma" class="col-lg-2 control-label">
            <?=$this->getTrans('permaLink') ?>:
        </label>
        <div class="col-lg-8">
            <?=$this->getUrl() ?>/index.php/
            <input type="text"
                   id="pagePerma"
                   name="pagePerma"
                   value="<?php if ($this->get('page') != '') { echo $this->escape($this->get('page')->getPerma()); } ?>" />
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
$('#pageTitleInput').change (
    function () {
        $('#pagePerma').val
        (
            $(this).val()
            .toLowerCase()
            .replace(/ /g,'-')+'.html'
        );
    }
);

$('#pageLanguageInput').change (
    this,
    function () {
        top.location.href = '<?=$this->getUrl(['id' => $pageID]); ?>/locale/'+$(this).val()
    }
);
</script>
