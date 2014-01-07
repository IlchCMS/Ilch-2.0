<form class="form-horizontal" method="POST" action="">
    <?php echo $this->getTokenField(); ?>
    <legend>
    <?php
        if ($this->get('box') != '') {
            echo $this->trans('editBox');
        } else {
            echo $this->trans('addBox');
        }
    ?>
    </legend>
    <div class="form-group">
        <label for="boxTitleInput" class="col-xs-2 control-label">
            <?php echo $this->trans('boxTitle'); ?>:
        </label>
        <div class="col-xs-2">
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
        if ($this->get('multilingual')) {
    ?>
    <div class="form-group">
        <label for="boxLanguageInput" class="col-xs-2 control-label">
            <?php echo $this->trans('boxLanguage'); ?>:
        </label>
        <div class="col-xs-2">
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
    ?>
    <div class="content_savebox">
        <button type="submit" name="save" class="btn">
            <?php
            if ($this->get('box') != '') {
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
$boxID = '';

if ($this->get('box') != '') {
    $boxID = $this->get('box')->getId();
}
?>
$('#boxLanguageInput').change
(
    this,
    function () {
        top.location.href = '<?php echo $this->url(array('id' => $boxID)); ?>/locale/'+$(this).val();
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
