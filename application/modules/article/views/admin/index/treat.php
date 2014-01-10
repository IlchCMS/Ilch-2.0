<form class="form-horizontal" method="POST" action="">
    <?php echo $this->getTokenField(); ?>
    <legend>
    <?php
        if ($this->get('article') != '') {
            echo $this->trans('editArticle');
        } else {
            echo $this->trans('addArticle');
        }
    ?>
    </legend>
    <div class="form-group">
        <label for="articleTitleInput" class="col-lg-2 control-label">
            <?php echo $this->trans('articleTitle'); ?>:
        </label>
        <div class="col-lg-2">
            <input class="form-control"
                   type="text"
                   name="articleTitle"
                   id="articleTitleInput"
                   value="<?php if ($this->get('article') != '') { echo $this->escape($this->get('article')->getTitle()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <textarea class="form-control" name="articleContent"><?php if ($this->get('article') != '') { echo $this->get('article')->getContent(); } ?></textarea>
    </div>
    <?php
        if ($this->get('multilingual') && $this->getRequest()->getParam('locale') != '') {
    ?>
    <div class="form-group">
        <label for="articleLanguageInput" class="col-lg-2 control-label">
            <?php echo $this->trans('articleLanguage'); ?>:
        </label>
        <div class="col-lg-2">
            <select class="form-control" name="articleLanguage" id="articleLanguageInput">
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
    <div class="form-group">
        <label for="articlePerma" class="col-lg-2 control-label">
            <?php echo $this->trans('permaLink'); ?>:
        </label>
        <div class="col-lg-5">
            <?php echo $this->url(); ?>/index.php/<input
                   type="text"
                   name="articlePerma"
                   id="articlePerma"
                   value="<?php if ($this->get('article') != '') { echo $this->escape($this->get('article')->getPerma()); } ?>" />
        </div>
    </div>
    <div class="content_savebox">
        <button type="submit" name="save" class="btn">
            <?php
            if ($this->get('article') != '') {
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
$articleID = '';

if ($this->get('article') != '') {
    $articleID = $this->get('article')->getId();
}
?>
$('#articleTitleInput').change
(
    function () {
        $('#articlePerma').val
        (
            $(this).val()
            .toLowerCase()
            .replace(/ /g,'-')+'.html'
        );
    }
);

$('#articleLanguageInput').change
(
    this,
    function () {
        top.location.href = '<?php echo $this->url(array('id' => $articleID)); ?>/locale/'+$(this).val();
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
