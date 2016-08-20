<?php
$articleID = '';
if ($this->get('article') != '') {
    $articleID = $this->get('article')->getId();
}
?>

<legend>
    <?php
    if ($this->get('article') != '') {
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

<form id="article_form" class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField(); ?>
    <div class="form-group<?=in_array('title', $this->get('errorFields')) ? ' has-error' : '' ?>">
        <label for="title" class="col-lg-2 control-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?php if ($this->get('article') != '') { echo $this->escape($this->get('article')->getTitle()); } else { echo $this->get('post')['title']; } ?>" />
        </div>
    </div>
    <div class="form-group<?=in_array('cats', $this->get('errorFields')) ? ' has-error' : '' ?>">
        <label for="cats" class="col-lg-2 control-label">
            <?=$this->getTrans('cats') ?>:
        </label>
        <div class="col-lg-4">
            <select class="form-control" id="cats" name="cats">
                <?php foreach ($this->get('cats') as $cats): ?>
                    <?php $selected = ''; ?>
                    <?php if ($this->get('article') != '' and $this->get('article')->getCatId() == $cats->getId()): ?>
                        <?php $selected = 'selected="selected"'; ?>
                    <?php endif; ?>
                    <option <?=$selected ?> value="<?=$cats->getId() ?>"><?=$this->escape($cats->getName()) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group<?=in_array('content', $this->get('errorFields')) ? ' has-error' : '' ?>">
        <div class="col-lg-offset-2 col-lg-8">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="content"
                      toolbar="ilch_html"><?php if ($this->get('article') != '') { echo $this->get('article')->getContent(); } else { echo $this->get('post')['content']; } ?></textarea>
        </div>
    </div>
    <?php if ($this->get('multilingual') && $this->getRequest()->getParam('locale') != ''): ?>
        <div class="form-group">
            <label for="language" class="col-lg-2 control-label">
                <?=$this->getTrans('language') ?>:
            </label>
            <div class="col-lg-8">
                <select class="form-control" id="language" name="language">
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
    <div class="form-group<?=in_array('permaLink', $this->get('errorFields')) ? ' has-error' : '' ?>">
        <label for="permaLink" class="col-lg-2 control-label">
            <?=$this->getTrans('permaLink') ?>:
        </label>
        <div class="col-lg-4">
            <?=$this->getUrl() ?>/index.php/
            <input type="text"
                   id="permaLink"
                   name="permaLink"
                   value="<?php if ($this->get('article') != '') { echo $this->escape($this->get('article')->getPerma()); } else { echo $this->get('post')['permaLink']; } ?>" />
        </div>
    </div>
    <legend><?=$this->getTrans('options') ?></legend>
    <div class="form-group<?=in_array('image', $this->get('errorFields')) ? ' has-error' : '' ?>">
        <label for="selectedImage" class="col-lg-2 control-label">
            <?=$this->getTrans('image') ?>:
        </label>
        <div class="col-lg-4">
            <div class="input-group">
                <input type="text"
                       class="form-control"
                       id="selectedImage"
                       name="image"
                       value="<?php if ($this->get('article') != '') { echo $this->escape($this->get('article')->getArticleImage()); } else { echo $this->get('post')['image']; } ?>" />
                <span class="input-group-addon"><a id="media" href="javascript:media()"><i class="fa fa-picture-o"></i></a></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="imageSource" class="col-lg-2 control-label">
            <?=$this->getTrans('imageSource') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
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
    <?php
    if ($this->get('article') != '') {
        echo $this->getSaveBar('edit');
    } else {
        echo $this->getSaveBar('add');
    }
    ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
<script>
$('#title').change(
    function () {
        $('#permaLink').val
        (
            $(this).val()
            .toLowerCase()
            .replace(/ /g,'-')+'.html'
        );
    }
);

$('#language').change(
    this,
    function () {
        top.location.href = '<?=$this->getUrl(['id' => $articleID]) ?>/locale/'+$(this).val();
    }
);

<?=$this->getMedia()
        ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/'))
        ->addUploadController($this->getUrl('admin/media/index/upload'))
?>

$('#preview').click(
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
