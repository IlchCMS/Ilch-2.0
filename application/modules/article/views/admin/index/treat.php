<?php
$articleID = '';
if ($this->get('article') != '') {
    $articleID = $this->get('article')->getId();
}
?>

<h1>
    <?php
    if ($this->get('article') != '') {
        echo $this->getTrans('edit');
    } else {
        echo $this->getTrans('add');
    }
    ?>
</h1>

<form id="article_form" class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField(); ?>
    <div class="form-group <?=$this->validation()->hasError('title') ? 'has-error' : '' ?>">
        <label for="title" class="col-lg-2 control-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?=($this->get('article') != '') ? $this->escape($this->get('article')->getTitle()) : $this->originalInput('title') ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('subTitle') ? 'has-error' : '' ?>">
        <label for="subTitle" class="col-lg-2 control-label">
            <?=$this->getTrans('subTitle') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="subTitle"
                   name="subTitle"
                   value="<?=($this->get('article') != '') ? $this->escape($this->get('article')->getSubTitle()) : $this->originalInput('subTitle') ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('cats') ? 'has-error' : '' ?>">
        <label for="cats" class="col-lg-2 control-label">
            <?=$this->getTrans('cats') ?>:
        </label>
        <div class="col-lg-4">
            <select class="chosen-select form-control"
                    id="cats"
                    name="cats[]"
                    data-placeholder="<?=$this->getTrans('selectCategories') ?>"
                    multiple>
                <?php foreach ($this->get('cats') as $cats): ?>
                    <option value="<?=$cats->getId() ?>"
                        <?php if ($this->get('article') != '') {
                            $catIds = explode(',', $this->get('article')->getCatId());
                            foreach ($catIds as $catId) {
                                if ($cats->getId() == $catId) {
                                    echo 'selected="selected"';
                                    break;
                                }
                            }
                        }
                        ?>>
                        <?=$this->escape($cats->getName()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('content') ? 'has-error' : '' ?>">
        <div class="col-lg-offset-2 col-lg-8">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="content"
                      toolbar="ilch_html"><?=($this->get('article') != '') ? $this->get('article')->getContent(): $this->originalInput('content') ?></textarea>
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
    <h1><?=$this->getTrans('options') ?></h1>
    <div class="form-group <?=$this->validation()->hasError('image') ? 'has-error' : '' ?>">
        <label for="selectedImage" class="col-lg-2 control-label">
            <?=$this->getTrans('image') ?>:
        </label>
        <div class="col-lg-4">
            <div class="input-group">
                <input type="text"
                       class="form-control"
                       id="selectedImage"
                       name="image"
                       value="<?=($this->get('article') != '') ? $this->escape($this->get('article')->getImage()) : $this->originalInput('image') ?>" />
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
                   value="<?php if ($this->get('article') != '') { echo $this->escape($this->get('article')->getImageSource()); } ?>" />
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
    <h1><?=$this->getTrans('seo') ?></h1>
    <div class="form-group">
        <label for="description" class="col-lg-2 control-label">
            <?=$this->getTrans('seoDescription') ?>:
        </label>
        <div class="col-lg-4">
            <textarea class="form-control" 
                      id="description" 
                      name="description"><?php if ($this->get('article') != '') { echo $this->escape($this->get('article')->getDescription()); } ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="keywords" class="col-lg-2 control-label">
            <?=$this->getTrans('seoKeywords') ?>:
        </label>
        <div class="col-lg-4">
            <textarea class="form-control" 
                      id="keywords" 
                      name="keywords"><?php if ($this->get('article') != '') { echo $this->escape($this->get('article')->getKeywords()); } ?></textarea>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('permaLink') ? 'has-error' : '' ?>">
        <label for="permaLink" class="col-lg-2 control-label">
            <?=$this->getTrans('permaLink') ?>:
        </label>
        <div class="col-lg-4">
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon3"><?=$this->getUrl() ?>index.php/</span>
                <input class="form-control"
                       type="text"
                       id="permaLink"
                       name="permaLink"
                       value="<?php if ($this->get('article') != '') { echo $this->escape($this->get('article')->getPerma()); } else { echo $this->get('post')['permaLink']; } ?>" />
            </div>
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
$('#cats').chosen();

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

$('#keywords').tokenfield();
$('#keywords').on('tokenfield:createtoken', function (event) {
    var existingTokens = $(this).tokenfield('getTokens');
    $.each(existingTokens, function(index, token) {
        if (token.value === event.attrs.value)
            event.preventDefault();
    });
});
$('#tags').tokenfield();
$('#tags').on('tokenfield:createtoken', function (event) {
    var existingTokens = $(this).tokenfield('getTokens');
    $.each(existingTokens, function(index, token) {
        if (token.value === event.attrs.value)
            event.preventDefault();
    });
});
</script>
