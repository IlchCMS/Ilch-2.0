<?php
$articleID = '';
if ($this->get('article') != '') {
    $articleID = $this->get('article')->getId();
}
?>
<h1><?=$this->getTrans('editTemplate') ?></h1>
<form id="article_form" class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3 <?=$this->validation()->hasError('teaser') ? 'has-error' : '' ?>">
        <label for="teaser" class="col-xl-2 control-label">
            <?=$this->getTrans('teaser') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="teaser"
                   name="teaser"
                   value="<?=($this->get('article') != '') ? $this->escape($this->get('article')->getTeaser()) : $this->originalInput('teaser') ?>" />
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('title') ? 'has-error' : '' ?>">
        <label for="title" class="col-xl-2 control-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?=($this->get('article') != '') ? $this->escape($this->get('article')->getTitle()) : $this->originalInput('title') ?>" />
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('content') ? 'has-error' : '' ?>">
        <div class="offset-xl-2 col-xl-8">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="content"
                      toolbar="ilch_html"><?=($this->get('article') != '') ? $this->get('article')->getContent(): $this->originalInput('content') ?></textarea>
        </div>
    </div>
    <?php if ($this->get('multilingual')): ?>
        <div class="row mb-3">
            <label for="language" class="col-xl-2 control-label">
                <?=$this->getTrans('language') ?>:
            </label>
            <div class="col-xl-8">
                <select class="form-control" id="language" name="language">
                    <?php
                    foreach ($this->get('languages') as $key => $value) {
                        $selected = '';
                        if ($key == $this->get('contentLanguage')) {
                            $key = '';
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
    <div class="row mb-3 <?=$this->validation()->hasError('image') ? 'has-error' : '' ?>">
        <label for="selectedImage" class="col-xl-2 control-label">
            <?=$this->getTrans('image') ?>:
        </label>
        <div class="col-xl-4">
            <div class="input-group">
                <input type="text"
                       class="form-control"
                       id="selectedImage"
                       name="image"
                       value="<?=($this->get('article') != '') ? $this->escape($this->get('article')->getImage()) : $this->originalInput('image') ?>" />
                <span class="input-group-text"><a id="media" href="javascript:media()"><i class="fa-regular fa-image"></i></a></span>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="imageSource" class="col-xl-2 control-label">
            <?=$this->getTrans('imageSource') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   name="imageSource"
                   id="imageSource"
                   value="<?=($this->get('article') != '') ? $this->escape($this->get('article')->getImageSource()) : '' ?>" />
        </div>
    </div>
    <h1><?=$this->getTrans('seo') ?></h1>
    <div class="row mb-3">
        <label for="description" class="col-xl-2 control-label">
            <?=$this->getTrans('seoDescription') ?>:
        </label>
        <div class="col-xl-4">
            <textarea class="form-control"
                      id="description"
                      name="description"><?=($this->get('article') != '') ? $this->escape($this->get('article')->getDescription()) : '' ?></textarea>
        </div>
    </div>
    <div class="row mb-3">
        <label for="keywords" class="col-xl-2 control-label">
            <?=$this->getTrans('seoKeywords') ?>:
        </label>
        <div class="col-xl-4">
            <textarea class="form-control"
                      id="keywords"
                      name="keywords"><?=($this->get('article') != '') ? $this->escape($this->get('article')->getKeywords()) : '' ?></textarea>
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('permaLink') ? 'has-error' : '' ?>">
        <label for="permaLink" class="col-xl-2 control-label">
            <?=$this->getTrans('permaLink') ?>:
        </label>
        <div class="col-xl-8">
            <div class="input-group">
                <span class="input-group-text" id="basic-addon3"><?=$this->getUrl() ?>index.php/</span>
                <input class="form-control"
                       type="text"
                       id="permaLink"
                       name="permaLink"
                       value="<?=($this->get('article') != '') ? $this->escape($this->get('article')->getPerma()) : $this->get('post')['permaLink'] ?>" />
            </div>
        </div>
    </div>
    <?=$this->getSaveBar('edit') ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
<script>
$('#access').chosen();
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
