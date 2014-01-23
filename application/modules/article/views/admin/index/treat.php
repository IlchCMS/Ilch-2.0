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
            <?php echo $this->getTrans('articleLanguage'); ?>:
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
            <?php echo $this->getTrans('permaLink'); ?>:
        </label>
        <div class="col-lg-5">
            <?php echo $this->getUrl(); ?>/index.php/<input
                   type="text"
                   name="articlePerma"
                   id="articlePerma"
                   value="<?php if ($this->get('article') != '') { echo $this->escape($this->get('article')->getPerma()); } ?>" />
        </div>
    </div>
    <?php
    if ($this->get('article') != '') {
        echo $this->getSaveBar('editButton');
    } else {
        echo $this->getSaveBar('addButton');
    }
    ?>
</form>
<script src="<?php echo $this->getStaticUrl('js/tinymce/tinymce.min.js') ?>"></script>
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

tinymce.init({
    selector : "textarea",
    height: 400,
    width:1000,
    plugins: [
         "advlist autolink link image lists charmap print preview hr anchor pagebreak",
         "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
         "table contextmenu directionality emoticons paste textcolor ilchmedia"
   ],
   toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
   toolbar2: "link | image media | preview code ",
   image_advtab: true ,
   external_filemanager_path:"<?php echo $this->getStaticUrl('../index.php/admin/media/iframe/index'); ?>",
   filemanager_title:"Media" 
 });
</script>
