<form class="form-horizontal" method="POST" action="">
    <?php echo $this->getTokenField(); ?>
    <legend>
    <?php
        if ($this->get('article') != '') {
            echo $this->getTrans('editArticle');
        } else {
            echo $this->getTrans('addArticle');
        }
    ?>
    </legend>
    <div class="form-group">
        <label for="articleTitleInput" class="col-lg-2 control-label">
            <?php echo $this->getTrans('articleTitle'); ?>:
        </label>
        <div class="col-lg-8">
            <input class="form-control"
                   type="text"
                   name="articleTitle"
                   id="articleTitleInput"
                   value="<?php if ($this->get('article') != '') { echo $this->escape($this->get('article')->getTitle()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-8">
            <textarea class="form-control" id="ilch_html" name="articleContent"><?php if ($this->get('article') != '') { echo $this->get('article')->getContent(); } ?></textarea>
        </div>
    </div>
    <?php
        if ($this->get('multilingual') && $this->getRequest()->getParam('locale') != '') {
    ?>
    <div class="form-group">
        <label for="articleLanguageInput" class="col-lg-2 control-label">
            <?php echo $this->getTrans('articleLanguage'); ?>:
        </label>
        <div class="col-lg-8">
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
    <legend>SEO</legend>
    <div class="form-group">
        <label for="descriptionInput" class="col-lg-2 control-label">
            <?php echo $this->getTrans('description'); ?>:
        </label>
        <div class="col-lg-8">
            <textarea class="form-control" id="descriptionInput" name="description"><?php if ($this->get('article') != '')
                { echo $this->escape($this->get('article')->getDescription()); } ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="articlePerma" class="col-lg-2 control-label">
            <?php echo $this->getTrans('permaLink'); ?>:
        </label>
        <div class="col-lg-8">
            <?php echo $this->getUrl(); ?>/index.php/<input
                   type="text"
                   name="articlePerma"
                   id="articlePerma"
                   value="<?php if ($this->get('article') != '') { echo $this->escape($this->get('article')->getPerma()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="articleImage"
                class="col-lg-2 control-label">
            <?php echo $this->getTrans('articleImage'); ?>:
        </label>
        <div class="col-lg-2 input-group">
            <input class="form-control"
                   type="text"
                   name="articleImage"
                   id="articleImage"
                   placeholder="<?php echo $this->getTrans('articleImage'); ?>"
                   value="<?php if ($this->get('article') != '') { echo $this->escape($this->get('article')->getArticleImage()); } ?>" />
            <span class="input-group-addon"><a id="media" href="#"><i class="fa fa-picture-o"></i></a></span>
        </div>
    </div>
    <?php
    if ($this->get('article') != '') {
        echo $this->getSaveBar('updateButton');
    } else {
        echo $this->getSaveBar('addButton');
    }
    ?>
</form>
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
        top.location.href = '<?php echo $this->getUrl(array('id' => $articleID)); ?>/locale/'+$(this).val();
    }
);
$('#media').click(function(){ $('#MediaModal').modal('show');

                            var src = iframeSingleUrlImage;
                            var height = '100%';
                            var width = '100%';

                            $("#MediaModal iframe").attr({'src': src,
                                'height': height,
                                'width': width});
                        });
</script>
