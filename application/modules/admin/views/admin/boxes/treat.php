<form class="form-horizontal" method="POST" action="">
    <?php echo $this->getTokenField(); ?>
    <legend>
    <?php
        if ($this->get('box') != '') {
            echo $this->getTrans('editBox');
        } else {
            echo $this->getTrans('addBox');
        }
    ?>
    </legend>
    <div class="form-group">
        <label for="boxTitleInput" class="col-lg-2 control-label">
            <?php echo $this->getTrans('boxTitle'); ?>:
        </label>
        <div class="col-lg-2">
            <input class="form-control"
                   type="text"
                   name="boxTitle"
                   id="boxTitleInput"
                   value="<?php if ($this->get('box') != '') { echo $this->escape($this->get('box')->getTitle()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <textarea class="form-control" name="boxContent"><?php if ($this->get('box') != '') { echo $this->get('box')->getContent(); } ?></textarea>
    </div>
    <?php
        if ($this->get('multilingual') && $this->getRequest()->getParam('locale') != '') {
    ?>
    <div class="form-group">
        <label for="boxLanguageInput" class="col-lg-2 control-label">
            <?php echo $this->getTrans('boxLanguage'); ?>:
        </label>
        <div class="col-lg-2">
            <select class="form-control" name="boxLanguage" id="boxLanguageInput">
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

        if ($this->get('box') != '') {
            echo $this->getSaveBar('editButtonBox');
        } else {
            echo $this->getSaveBar('addButtonBox');
        }
    ?>
</form>
<script type="text/javascript" src="<?php echo $this->getStaticUrl('js/tinymce/tinymce.min.js') ?>"></script>
<script>
<?php
$boxID = '';

if ($this->get('box') != '') {
    $boxID = $this->get('box')->getId();
}
?>
$('#boxLanguageInput').change
(
    this,
    function () {
        top.location.href = '<?php echo $this->getUrl(array('id' => $boxID)); ?>/locale/'+$(this).val();
    }
);

tinymce.init
(
    {
        height: 400,
        selector: "textarea",
        plugins: ["code table image preview"]
    }
);
</script>
