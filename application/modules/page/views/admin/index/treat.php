<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <legend>
    <?php
    if ($this->get('page') != '') {
        echo $this->getTrans('editPage');
    } else {
        echo $this->getTrans('addPage');
    }
    ?>
    </legend>
    <div class="form-group">
        <label for="pageTitleInput" class="col-lg-2 control-label">
            <?=$this->getTrans('pageTitle') ?>:
        </label>
        <div class="col-lg-8">
            <input class="form-control"
                   type="text"
                   name="pageTitle"
                   id="pageTitleInput"
                   value="<?php if ($this->get('page') != '') { echo $this->escape($this->get('page')->getTitle()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-8">
            <textarea class="form-control ckeditor" id="ck_1" toolbar="ilch_html" name="pageContent"><?php if ($this->get('page') != '') { echo $this->get('page')->getContent(); } ?></textarea>
        </div>
    </div>
    <?php
    if ($this->get('multilingual') && $this->getRequest()->getParam('locale') != '') {
    ?>
    <div class="form-group">
        <label for="pageLanguageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('pageLanguage') ?>:
        </label>
        <div class="col-lg-8">
            <select class="form-control" name="pageLanguage" id="pageLanguageInput">
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
    <?php
    }
    ?>
    <legend>SEO</legend>
    <div class="form-group">
        <label for="descriptionInput" class="col-lg-2 control-label">
            <?=$this->getTrans('description') ?>:
        </label>
        <div class="col-lg-8">
            <textarea class="form-control" id="descriptionInput" name="description"><?php if ($this->get('page') != '')
                { echo $this->escape($this->get('page')->getDescription()); } ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="pagePerma" class="col-lg-2 control-label">
            <?=$this->getTrans('permaLink') ?>:
        </label>
        <div class="col-lg-8">
            <?=$this->getUrl() ?>/index.php/<input
                   type="text"
                   name="pagePerma"
                   id="pagePerma"
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
        top.location.href = '<?=$this->getUrl(array('id' => $pageID)); ?>/locale/'+$(this).val()
    }
);
</script>
