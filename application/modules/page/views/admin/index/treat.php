<form class="form-horizontal" method="POST" action="">
    <?php echo $this->getTokenField(); ?>
    <legend>
    <?php
        if ($this->get('page') != '') {
            echo $this->trans('editPage');
        } else {
            echo $this->trans('addPage');
        }
    ?>
    </legend>
    <div class="form-group">
        <label for="pageTitleInput" class="col-lg-2 control-label">
            <?php echo $this->trans('pageTitle'); ?>:
        </label>
        <div class="col-lg-2">
            <input class="form-control"
                   type="text"
                   name="pageTitle"
                   id="pageTitleInput"
                   value="<?php if ($this->get('page') != '') { echo $this->escape($this->get('page')->getTitle()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <textarea class="form-control" name="pageContent"><?php if ($this->get('page') != '') { echo $this->get('page')->getContent(); } ?></textarea>
    </div>
    <?php
        if ($this->get('multilingual')) {
    ?>
    <div class="form-group">
        <label for="pageLanguageInput" class="col-lg-2 control-label">
            <?php echo $this->trans('pageLanguage'); ?>:
        </label>
        <div class="col-lg-2">
            <select class="form-control" name="pageLanguage" id="pageLanguageInput">
                <?php
                foreach ($this->get('languages') as $key => $value) {
                    $selected = '';

                    if ($this->getRequest()->getParam('locale') != '') {
                        if ($this->getRequest()->getParam('locale') == $key) {
                            $selected = 'selected="selected"';
                        }
                    } elseif ($this->getTranslator()->getLocale() == $key) {
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
    <div class="form-group">
        <label for="pagePerma" class="col-lg-2 control-label">
            <?php echo $this->trans('permaLink'); ?>:
        </label>
        <div class="col-lg-5">
            <?php echo $this->url(); ?>/index.php/<input
                   type="text"
                   name="pagePerma"
                   id="pagePerma"
                   value="<?php if ($this->get('page') != '') { echo $this->escape($this->get('page')->getPerma()); } ?>" />
        </div>
    </div>
    <div class="content_savebox">
        <button type="submit" name="save" class="btn">
            <?php
            if ($this->get('page') != '') {
                echo $this->trans('editButton');
            } else {
                echo $this->trans('addButton');
            }
            ?>
        </button>
    </div>
</form>
<script type="text/javascript" src="<?php echo $this->staticUrl('js/tinymce/tinymce.min.js') ?>"></script>
<script>
<?php
$pageID = '';

if ($this->get('page') != '') {
    $pageID = $this->get('page')->getId();
}
?>
$('#pageTitleInput').change
(
    function () {
        $('#pagePerma').val
        (
            $(this).val()
            .toLowerCase()
            .replace(/ /g,'-')+'.html'
        );
    }
);

$('#pageLanguageInput').change
(
    this,
    function () {
        top.location.href = '<?php echo $this->url(array('id' => $pageID)); ?>/locale/'+$(this).val();
    }
);

tinymce.init
(
    {
        height: 400,
        selector: "textarea"
    }
);
</script>
