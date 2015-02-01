<legend><?php echo $this->getTrans('manageNewEnemy'); ?></legend>
<form id="article_form" class="form-horizontal" method="POST" action="">
    <?php echo $this->getTokenField(); ?>
    <div class="form-group">
        <label for="enemyNameInput" class="col-lg-2 control-label">
            <?php echo $this->getTrans('enemyName'); ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="enemyName"
                   value="<?php if ($this->get('enemy') != '') { echo $this->escape($this->get('enemy')->getEnemyName()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="enemyTagInput" class="col-lg-2 control-label">
            <?php echo $this->getTrans('enemyTag'); ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="enemyTag"
                   value="<?php if ($this->get('enemy') != '') { echo $this->escape($this->get('enemy')->getEnemyTag()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="enemyHomepageInput" class="col-lg-2 control-label">
            <?php echo $this->getTrans('enemyHomepage'); ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="enemyHomepage"
                   value="<?php if ($this->get('enemy') != '') { echo $this->escape($this->get('enemy')->getEnemyHomepage()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="enemyLogo"
                class="col-lg-2 control-label">
            <?php echo $this->getTrans('enemyLogo'); ?>:
        </label>
        <div class="col-lg-4">
            <div class="input-group">
                <input class="form-control"
                       type="text"
                       name="enemyLogo"
                       id="selectedImage"
                       placeholder="<?php echo $this->getTrans('enemyLogoInfo'); ?>"
                       value="<?php if ($this->get('enemy') != '') { echo $this->escape($this->get('enemy')->getEnemyLogo()); } ?>" />
                <span class="input-group-addon"><a id="media" href="#"><i class="fa fa-picture-o"></i></a></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="enemyContactNameInput" class="col-lg-2 control-label">
            <?php echo $this->getTrans('enemyContactName'); ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="enemyContactName"
                   value="<?php if ($this->get('enemy') != '') { echo $this->escape($this->get('enemy')->getEnemyContactName()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="enemyContactEmailInput" class="col-lg-2 control-label">
            <?php echo $this->getTrans('enemyContactEmail'); ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="enemyContactEmail"
                   value="<?php if ($this->get('enemy') != '') { echo $this->escape($this->get('enemy')->getEnemyContactEmail()); } ?>" />
        </div>
    </div>
    <?php
    if ($this->get('enemy') != '') {
        echo $this->getSaveBar('updateButton');
    } else {
        echo $this->getSaveBar('addButton');
    }
    ?>
</form>
<script>
$('#media').click
(
    function()
    {
        $('#MediaModal').modal('show');

        var src = iframeSingleUrlImage;
        var height = '100%';
        var width = '100%';

        $("#MediaModal iframe").attr
        (
            {
                'src': src,
                'height': height,
                'width': width
            }
        );
    }
);
</script>
