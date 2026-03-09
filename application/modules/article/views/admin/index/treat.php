<?php

/** @var \Ilch\View $this */

/** @var \Modules\Article\Models\Article $article */
$article = $this->get('article');

$articleID = '';
if ($article) {
    $articleID = $article->getId();
}
?>
<link href="<?=$this->getStaticUrl('js/tempus-dominus/dist/css/tempus-dominus.min.css') ?>" rel="stylesheet">
<h1><?=($article) ? $this->getTrans('edit') : $this->getTrans('add') ?></h1>
<form id="article_form" method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('template') ? ' has-error' : '' ?>">
        <label for="template" class="col-xl-2 col-form-label">
            <?=$this->getTrans('template') ?>:
        </label>
        <div class="col-xl-4">
            <select class="choices-select form-control"
                    id="template"
                    name="template"
                    data-placeholder="<?=$this->getTrans('selectTemplate') ?>"
                    <?=(empty($this->get('templates'))) ? 'disabled' : '' ?> >
                <?php
                /** @var \Modules\Article\Models\Article $template */
                foreach ($this->get('templates') as $template): ?>
                    <option value="<?=$template->getId() ?>" <?=($this->get('template') == $template->getId()) ? 'selected="selected"' : '' ?>>
                        <?=$this->escape($template->getTitle() . ($template->getLocale() ? ' (' . $template->getLocale() . ')' : '')) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('teaser') ? ' has-error' : '' ?>">
        <label for="teaser" class="col-lg-2 col-form-label">
            <?=$this->getTrans('teaser') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="teaser"
                   name="teaser"
                   value="<?=($article) ? $this->escape($article->getTeaser()) : $this->originalInput('teaser') ?>" />
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
                   value="<?=($article) ? $this->escape($article->getTitle()) : $this->originalInput('title') ?>" />
        </div>
    </div>
    <div class="row mb-3">
        <label for="date_created" class="col-xl-2 col-form-label">
            <?=$this->getTrans('date') ?>:
        </label>
        <div id="date_created" class="col-xl-4 input-group date form_datetime">
            <input type="text"
                   class="form-control"
                   id="date_created"
                   name="date_created"
                   value="<?=($article && $article->getDateCreated()) ? date('d.m.Y H:i', strtotime($article->getDateCreated())) : '' ?>"
                   readonly>
            <span class="input-group-text">
                <span class="fa-solid fa-calendar"></span>
            </span>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('cats') ? ' has-error' : '' ?>">
        <label for="cats" class="col-xl-2 col-form-label">
            <?=$this->getTrans('cats') ?>:
        </label>
        <div class="col-xl-4">
            <select class="choices-select form-control"
                    id="cats"
                    name="cats[]"
                    data-placeholder="<?=$this->getTrans('selectCategories') ?>"
                    multiple>
                <?php
                /** @var \Modules\Article\Models\Category $cats */
                foreach ($this->get('cats') as $cats): ?>
                    <option value="<?=$cats->getId() ?>"
                        <?php
                            $catIds = [];

                            if ($this->originalInput('cats')) {
                                $catIds = $this->originalInput('cats');
                            } else {
                                $catIds = ($article) ? explode(',', $article->getCatId()) : [];
                            }

                            foreach ($catIds as $catId) {
                                if ($cats->getId() == $catId) {
                                    echo 'selected="selected"';
                                    break;
                                }
                            }
                        ?>>
                        <?=$this->escape($cats->getName()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('content') ? ' has-error' : '' ?>">
        <div class="offset-xl-2 col-xl-8">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="content"
                      toolbar="ilch_html"><?=($article) ? $article->getContent(): $this->originalInput('content') ?></textarea>
        </div>
    </div>
    <?php if ($this->get('multilingual') && $this->getRequest()->getParam('locale') != ''): ?>
        <div class="row mb-3">
            <label for="language" class="col-lg-2 col-form-label">
                <?=$this->getTrans('language') ?>:
            </label>
            <div class="col-xl-8">
                <select class="form-select" id="language" name="language">
                    <?php
                    foreach ($this->get('languages') as $key => $value) {
                        $selected = '';
                        if ($key == $this->get('contentLanguage')) {
                            continue;
                        }

                        if ($this->getRequest()->getParam('locale') == $key) {
                            $selected = 'selected="selected"';
                        }

                        echo '<option ' . $selected . ' value="' . $key . '">'.$this->escape($value).'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    <?php endif; ?>
    <h1><?=$this->getTrans('options') ?></h1>
    <div class="row mb-3<?=$this->validation()->hasError('groups') ? ' has-error' : '' ?>">
        <label for="access" class="col-lg-2 col-form-label">
            <?=$this->getTrans('visibleFor') ?>
        </label>
        <div class="col-xl-4">
            <select class="choices-select form-control" id="access" name="groups[]" data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>" multiple>
                <?php
                /** @var \Modules\User\Models\Group $groupList */
                foreach ($this->get('userGroupList') as $groupList): ?>
                    <option value="<?=$groupList->getId() ?>"<?=(in_array($groupList->getId(), $this->get('groups'))) ? ' selected' : '' ?>><?=$groupList->getName() ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <label for="topArticle" class="col-lg-2 col-form-label">
            <?=$this->getTrans('topArticle') ?>:
        </label>
        <div class="col-xl-4">
            <input type="checkbox"
                   name="topArticle"
                   id="topArticle"
                   value="1"
                   <?=($article && $article->getTopArticle()) ? 'checked="checked"' : '' ?> />
        </div>
    </div>
    <div class="row mb-3">
        <label for="commentsDisabled" class="col-lg-2 col-form-label">
            <?=$this->getTrans('commentsDisabled') ?>:
        </label>
        <div class="col-xl-4">
            <input type="checkbox"
                   name="commentsDisabled"
                   id="commentsDisabled"
                   value="1"
                <?=(($article && $article->getCommentsDisabled()) || (!$article && $this->get('disableComments'))) ? 'checked="checked"' : '' ?> />
        </div>
    </div>
    <div class="row mb-3">
        <label for="saveAsTemplate" class="col-lg-2 col-form-label">
            <?=$this->getTrans('saveAsTemplate') ?>:
        </label>
        <div class="col-xl-4">
            <input type="checkbox"
                   name="saveAsTemplate"
                   id="saveAsTemplate"
                   value="1"
            />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('image') ? ' has-error' : '' ?>">
        <label for="selectedImage" class="col-lg-2 col-form-label">
            <?=$this->getTrans('image') ?>:
        </label>
        <div class="col-xl-4">
            <div class="input-group">
                <input type="text"
                       class="form-control"
                       id="selectedImage"
                       name="image"
                       value="<?=$article ? $this->escape($article->getImage()) : $this->originalInput('image') ?>" />
                <span class="input-group-text"><a id="media" href="javascript:media()"><i class="fa-regular fa-image"></i></a></span>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="imageSource" class="col-lg-2 col-form-label">
            <?=$this->getTrans('imageSource') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   name="imageSource"
                   id="imageSource"
                   value="<?=($article) ? $this->escape($article->getImageSource()) : $this->originalInput('imageSource') ?>" />
        </div>
    </div>
    <div class="row mb-3">
        <label for="preview" class="col-lg-2 col-form-label">
            <?=$this->getTrans('preview') ?>:
        </label>
        <div class="col-xl-4">
            <a id="preview" class="btn btn-outline-secondary"><?=$this->getTrans('show') ?></a>
        </div>
    </div>
    <h1><?=$this->getTrans('seo') ?></h1>
    <div class="row mb-3">
        <label for="description" class="col-lg-2 col-form-label">
            <?=$this->getTrans('seoDescription') ?>:
        </label>
        <div class="col-xl-4">
            <textarea class="form-control"
                      id="description"
                      name="description"><?=($article) ? $this->escape($article->getDescription()) : $this->originalInput('description') ?></textarea>
        </div>
    </div>
    <div class="row mb-3">
        <label for="keywords" class="col-lg-2 col-form-label">
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
        <label for="permaLink" class="col-lg-2 col-form-label">
            <?=$this->getTrans('permaLink') ?>:
        </label>
        <div class="col-xl-8">
            <div class="input-group">
                <span class="input-group-text" id="basic-addon3"><?=$this->getUrl() ?>index.php/</span>
                <input class="form-control"
                       type="text"
                       id="permaLink"
                       name="permaLink"
                       value="<?=($article) ? $this->escape($article->getPerma()) : $this->originalInput('permaLink') ?>" />
            </div>
        </div>
    </div>
    <?=($article) ?  $this->getSaveBar('edit') : $this->getSaveBar('add') ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
<script src="<?=$this->getStaticUrl('js/popper/dist/umd/popper.min.js') ?>" charset="UTF-8"></script>
<script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/js/tempus-dominus.min.js') ?>" charset="UTF-8"></script>
<?php if (strncmp($this->getTranslator()->getLocale(), 'en', 2) !== 0) : ?>
    <script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/locales/' . substr($this->getTranslator()->getLocale(), 0, 2) . '.js') ?>" charset="UTF-8"></script>
<?php endif; ?>
<script>
$(document).ready(function() {
    new Choices('#access', {
        ...choicesOptions,
        searchEnabled: true
    });
    new Choices('#cats', {
        ...choicesOptions,
        searchEnabled: true
    });
    new Choices('#template', {
        ...choicesOptions,
        searchEnabled: true
    });

    new Tokenfield('keywords', choicesOptions);


    if ("<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>" !== 'en') {
        tempusDominus.loadLocale(tempusDominus.locales.<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>);
        tempusDominus.locale(tempusDominus.locales.<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>.name);
    }

    new tempusDominus.TempusDominus(document.getElementById('date_created'), {
        restrictions: {
          minDate: new Date()
        },
        display: {
            sideBySide: true,
            calendarWeeks: true,
            buttons: {
                today: true,
                close: true
            }
        },
        localization: {
            locale: "<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>",
            startOfTheWeek: 1,
            format: "dd.MM.yyyy HH:mm"
        },
        stepping: 15
    });
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

$('#template').change(function() {
    if (<?=($articleID) ? json_encode($articleID) : '""' ?>) {
        window.location = '<?=$this->getCurrentUrl(['id' => $articleID], false) ?>/template/'+$(this).val();
    } else {
        window.location = '<?=$this->getCurrentUrl(['action' => 'treat'], false) ?>/template/'+$(this).val();
    }
});
</script>
