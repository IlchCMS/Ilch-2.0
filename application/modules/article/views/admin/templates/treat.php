<?php

/** @var \Ilch\View $this */

/** @var \Modules\Article\Models\Article $article */
$article = $this->get('article');

$articleID = '';
if ($article != '') {
    $articleID = $article->getId();
}
?>
<h1><?=$this->getTrans('editTemplate') ?></h1>
<form id="article_form" method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('teaser') ? ' has-error' : '' ?>">
        <label for="teaser" class="col-xl-2 col-form-label">
            <?=$this->getTrans('teaser') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="teaser"
                   name="teaser"
                   value="<?=($article != '') ? $this->escape($article->getTeaser()) : $this->originalInput('teaser') ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('title') ? ' has-error' : '' ?>">
        <label for="title" class="col-xl-2 col-form-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?=($article != '') ? $this->escape($article->getTitle()) : $this->originalInput('title') ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('content') ? ' has-error' : '' ?>">
        <div class="offset-xl-2 col-xl-8">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="content"
                      toolbar="ilch_html"><?=($article != '') ? $article->getContent(): $this->originalInput('content') ?></textarea>
        </div>
    </div>
    <?php if ($this->get('multilingual')): ?>
        <div class="row mb-3">
            <label for="language" class="col-xl-2 col-form-label">
                <?=$this->getTrans('language') ?>:
            </label>
            <div class="col-xl-8">
                <select class="form-select" id="language" name="language">
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
    <div class="row mb-3<?=$this->validation()->hasError('image') ? ' has-error' : '' ?>">
        <label for="selectedImage" class="col-xl-2 col-form-label">
            <?=$this->getTrans('image') ?>:
        </label>
        <div class="col-xl-4">
            <div class="input-group">
                <input type="text"
                       class="form-control"
                       id="selectedImage"
                       name="image"
                       value="<?=($article != '') ? $this->escape($article->getImage()) : $this->originalInput('image') ?>" />
                <span class="input-group-text"><a id="media" href="javascript:media()"><i class="fa-regular fa-image"></i></a></span>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="imageSource" class="col-xl-2 col-form-label">
            <?=$this->getTrans('imageSource') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   name="imageSource"
                   id="imageSource"
                   value="<?=($article != '') ? $this->escape($article->getImageSource()) : '' ?>" />
        </div>
    </div>
    <h1><?=$this->getTrans('seo') ?></h1>
    <div class="row mb-3">
        <label for="description" class="col-xl-2 col-form-label">
            <?=$this->getTrans('seoDescription') ?>:
        </label>
        <div class="col-xl-4">
            <textarea class="form-control"
                      id="description"
                      name="description"><?=($article != '') ? $this->escape($article->getDescription()) : '' ?></textarea>
        </div>
    </div>
    <div class="row mb-3">
        <label for="keywords" class="col-xl-2 col-form-label">
            <?=$this->getTrans('seoKeywords') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="choices-select form-control"
                   name="keywords"
                   id="keywords"
                   data-placeholder="<?=$this->getTrans('seoKeywords') ?>"
                   value="<?=($article) ? $this->escape($article->getKeywords()) : $this->originalInput('keywords') ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('permaLink') ? ' has-error' : '' ?>">
        <label for="permaLink" class="col-xl-2 col-form-label">
            <?=$this->getTrans('permaLink') ?>:
        </label>
        <div class="col-xl-8">
            <div class="input-group">
                <span class="input-group-text" id="basic-addon3"><?=$this->getUrl() ?>index.php/</span>
                <input class="form-control"
                       type="text"
                       id="permaLink"
                       name="permaLink"
                       value="<?=($article != '') ? $this->escape($article->getPerma()) : $this->get('post')['permaLink'] ?>" />
            </div>
        </div>
    </div>
    <?=$this->getSaveBar('edit') ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
<script>
    $(document).ready(function() {
        new Tokenfield('keywords', choicesOptions);
    });

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
</script>
