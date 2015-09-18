<form id="article_form" class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField(); ?>
    <legend>
        <?php if ($this->get('article') != ''): ?>
            <?=$this->getTrans('edit') ?>
       <?php else: ?>
            <?=$this->getTrans('add') ?>
        <?php endif; ?>
    </legend>
    <div class="form-group">
        <label for="title" class="col-lg-2 control-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="title"
                   id="title"
                   value="<?php if ($this->get('article') != '') { echo $this->escape($this->get('article')->getTitle()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="cats" class="col-lg-2 control-label">
            <?=$this->getTrans('cats') ?>:
        </label>
        <div class="col-lg-4">
            <select class="form-control" name="cats" id="cats">
                <?php foreach ($this->get('cats') as $cats): ?>
                    <?php $selected = ''; ?>
                    <?php if ($this->get('article') != '' and $this->get('article')->getCatId() == $cats->getId()): ?>
                        <?php $selected = 'selected="selected"'; ?>
                    <?php endif; ?>
                    <option <?=$selected ?> value="<?=$cats->getId() ?>"><?=$cats->getName() ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-8">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      toolbar="ilch_html"
                      name="content"><?php if ($this->get('article') != '') { echo $this->get('article')->getContent(); } ?></textarea>
        </div>
    </div>
    <?php
        if ($this->get('multilingual') && $this->getRequest()->getParam('locale') != '') {
    ?>
    <div class="form-group">
        <label for="language" class="col-lg-2 control-label">
            <?=$this->getTrans('language') ?>:
        </label>
        <div class="col-lg-8">
            <select class="form-control" name="language" id="language">
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
        <label for="description" class="col-lg-2 control-label">
            <?=$this->getTrans('description') ?>:
        </label>
        <div class="col-lg-4">
            <textarea class="form-control" 
                      id="description" 
                      name="description"><?php if ($this->get('article') != '') { echo $this->escape($this->get('article')->getDescription()); } ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="permaLink" class="col-lg-2 control-label">
            <?=$this->getTrans('permaLink') ?>:
        </label>
        <div class="col-lg-4">
            <?=$this->getUrl() ?>/index.php/
            <input type="text"
                   name="permaLink"
                   id="permaLink"
                   value="<?php if ($this->get('article') != '') { echo $this->escape($this->get('article')->getPerma()); } ?>" />
        </div>
    </div>
    <legend><?=$this->getTrans('options') ?></legend>
    <div class="form-group">
        <label for="image" class="col-lg-2 control-label">
            <?=$this->getTrans('image'); ?>:
        </label>
        <div class="col-lg-4">
            <div class="input-group">
                <input class="form-control"
                       type="text"
                       name="image"
                       id="selectedImage"
                       value="<?php if ($this->get('article') != '') { echo $this->escape($this->get('article')->getArticleImage()); } ?>" />
                <span class="input-group-addon"><a id="media" href="javascript:media()"><i class="fa fa-picture-o"></i></a></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="imageSource" class="col-lg-2 control-label">
            <?=$this->getTrans('imageSource') ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                    type="text"
                    name="imageSource"
                    value="<?php if ($this->get('article') != '') { echo $this->escape($this->get('article')->getArticleImageSource()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="preview" class="col-lg-2 control-label">
            <?=$this->getTrans('preview') ?>:
        </label>
        <div class="col-lg-4">
            <a id="preview" class="btn btn-default"><?=$this->getTrans('show') ?></a>
        </div>
    </div>
    <?php if ($this->get('article') != ''): ?>
        <?=$this->getSaveBar('edit') ?>
    <?php else: ?>
        <?=$this->getSaveBar('add') ?>
    <?php endif; ?>
</form>

<script>
<?php
$articleID = '';

if ($this->get('article') != '') {
    $articleID = $this->get('article')->getId();
}
?>
$('#title').change
(
    function () {
        $('#permaLink').val
        (
            $(this).val()
            .toLowerCase()
            .replace(/ /g,'-')+'.html'
        );
    }
);

$('#language').change
(
    this,
    function () {
        top.location.href = '<?=$this->getUrl(array('id' => $articleID)) ?>/locale/'+$(this).val();
    }
);

<?=$this->getMedia()
            ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/')) ?>

$('#preview').click
(
    function(e) 
    {
        e.preventDefault();
        $('#article_form').attr('action', '<?=$this->getUrl('index.php/article/index/show/preview/true') ?>');
        $('#article_form').attr('target', '_blank');
        $('#article_form').submit();
        $('#article_form').attr('action', '');
        $('#article_form').attr('target', '');
    }
);

var ilchPsPlugin = "<?=$this->getBaseUrl('application/modules/media/static/js/ilchps/') ?>";
</script>
