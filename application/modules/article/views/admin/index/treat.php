<?php
$articleID = '';
if ($this->get('article')) {
    $articleID = $this->get('article')->getId();
}
?>
<link href="<?=$this->getStaticUrl('js/tempus-dominus/dist/css/tempus-dominus.min.css') ?>" rel="stylesheet">
<h1><?=($this->get('article')) ? $this->getTrans('edit') : $this->getTrans('add') ?></h1>
<form id="article_form" class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3 <?=$this->validation()->hasError('template') ? 'has-error' : '' ?>">
        <label for="template" class="col-xl-2 control-label">
            <?=$this->getTrans('template') ?>:
        </label>
        <div class="col-xl-4">
            <select class="chosen-select form-control"
                    id="template"
                    name="template"
                    data-placeholder="<?=$this->getTrans('selectTemplate') ?>"
                    <?=(empty($this->get('templates'))) ? 'disabled' : '' ?> >
                    <option value="0"><?=$this->getTrans('selectTemplate') ?></option>
                <?php foreach ($this->get('templates') as $template): ?>
                    <option value="<?=$template->getId() ?>" <?=($this->get('template') == $template->getId()) ? 'selected="selected"' : '' ?>>
                        <?=$this->escape($template->getTitle() . ($template->getLocale() ? ' (' . $template->getLocale() . ')' : '')) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('teaser') ? 'has-error' : '' ?>">
        <label for="teaser" class="col-lg-2 control-label">
            <?=$this->getTrans('teaser') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="teaser"
                   name="teaser"
                   value="<?=($this->get('article')) ? $this->escape($this->get('article')->getTeaser()) : $this->originalInput('teaser') ?>" />
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
                   value="<?=($this->get('article')) ? $this->escape($this->get('article')->getTitle()) : $this->originalInput('title') ?>" />
        </div>
    </div>
    <div class="row mb-3">
        <label for="date_created" class="col-xl-2 control-label">
            <?=$this->getTrans('date') ?>:
        </label>
        <div id="date_created" class="col-xl-4 input-group ilch-date date form_datetime">
            <input type="text"
                   class="form-control"
                   id="date_created"
                   name="date_created"
                   value="<?=($this->get('article') && $this->get('article')->getDateCreated()) ? date('d.m.Y H:i', strtotime($this->get('article')->getDateCreated())) : '' ?>"
                   readonly>
            <span class="input-group-text">
                <span class="fa fa-calendar"></span>
            </span>
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('cats') ? 'has-error' : '' ?>">
        <label for="cats" class="col-xl-2 control-label">
            <?=$this->getTrans('cats') ?>:
        </label>
        <div class="col-xl-4">
            <select class="chosen-select form-control"
                    id="cats"
                    name="cats[]"
                    data-placeholder="<?=$this->getTrans('selectCategories') ?>"
                    multiple>
                <?php foreach ($this->get('cats') as $cats): ?>
                    <option value="<?=$cats->getId() ?>"
                        <?php if ($this->get('article')) {
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
    <div class="row mb-3 <?=$this->validation()->hasError('content') ? 'has-error' : '' ?>">
        <div class="offset-xl-2 col-xl-8">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="content"
                      toolbar="ilch_html"><?=($this->get('article')) ? $this->get('article')->getContent(): $this->originalInput('content') ?></textarea>
        </div>
    </div>
    <?php if ($this->get('multilingual') && $this->getRequest()->getParam('locale') != ''): ?>
        <div class="row mb-3">
            <label for="language" class="col-lg-2 control-label">
                <?=$this->getTrans('language') ?>:
            </label>
            <div class="col-xl-8">
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
    <div class="row mb-3 <?=$this->validation()->hasError('groups') ? 'has-error' : '' ?>">
        <label for="access" class="col-lg-2 control-label">
            <?=$this->getTrans('visibleFor') ?>
        </label>
        <div class="col-xl-4">
            <select class="chosen-select form-control" id="access" name="groups[]" data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>" multiple>
                <?php foreach ($this->get('userGroupList') as $groupList): ?>
                    <option value="<?=$groupList->getId() ?>"<?=(in_array($groupList->getId(), $this->get('groups'))) ? ' selected' : '' ?>><?=$groupList->getName() ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <label for="topArticle" class="col-lg-2 control-label">
            <?=$this->getTrans('topArticle') ?>:
        </label>
        <div class="col-xl-4">
            <input type="checkbox"
                   name="topArticle"
                   id="topArticle"
                   value="1"
                   <?=($this->get('article') && $this->get('article')->getTopArticle()) ? 'checked="checked"' : '' ?> />
        </div>
    </div>
    <div class="row mb-3">
        <label for="commentsDisabled" class="col-lg-2 control-label">
            <?=$this->getTrans('commentsDisabled') ?>:
        </label>
        <div class="col-xl-4">
            <input type="checkbox"
                   name="commentsDisabled"
                   id="commentsDisabled"
                   value="1"
                <?=(($this->get('article') && $this->get('article')->getCommentsDisabled()) || (!$this->get('article') && $this->get('disableComments'))) ? 'checked="checked"' : '' ?> />
        </div>
    </div>
    <div class="row mb-3">
        <label for="saveAsTemplate" class="col-lg-2 control-label">
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
    <div class="row mb-3 <?=$this->validation()->hasError('image') ? 'has-error' : '' ?>">
        <label for="selectedImage" class="col-lg-2 control-label">
            <?=$this->getTrans('image') ?>:
        </label>
        <div class="col-xl-4">
            <div class="input-group">
                <input type="text"
                       class="form-control"
                       id="selectedImage"
                       name="image"
                       value="<?=$this->get('article') ? $this->escape($this->get('article')->getImage()) : $this->originalInput('image') ?>" />
                <span class="input-group-text"><a id="media" href="javascript:media()"><i class="fa-regular fa-image"></i></a></span>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="imageSource" class="col-lg-2 control-label">
            <?=$this->getTrans('imageSource') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   name="imageSource"
                   id="imageSource"
                   value="<?=($this->get('article')) ? $this->escape($this->get('article')->getImageSource()) : $this->originalInput('imageSource') ?>" />
        </div>
    </div>
    <div class="row mb-3">
        <label for="preview" class="col-lg-2 control-label">
            <?=$this->getTrans('preview') ?>:
        </label>
        <div class="col-xl-4">
            <a id="preview" class="btn btn-default"><?=$this->getTrans('show') ?></a>
        </div>
    </div>
    <h1><?=$this->getTrans('seo') ?></h1>
    <div class="row mb-3">
        <label for="description" class="col-lg-2 control-label">
            <?=$this->getTrans('seoDescription') ?>:
        </label>
        <div class="col-xl-4">
            <textarea class="form-control"
                      id="description"
                      name="description"><?=($this->get('article')) ? $this->escape($this->get('article')->getDescription()) : $this->originalInput('description') ?></textarea>
        </div>
    </div>
    <div class="row mb-3">
        <label for="keywords" class="col-lg-2 control-label">
            <?=$this->getTrans('seoKeywords') ?>:
        </label>
        <div class="col-xl-4">
            <textarea class="form-control"
                      id="keywords"
                      name="keywords"><?=($this->get('article')) ? $this->escape($this->get('article')->getKeywords()) : $this->originalInput('keywords')?></textarea>
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('permaLink') ? 'has-error' : '' ?>">
        <label for="permaLink" class="col-lg-2 control-label">
            <?=$this->getTrans('permaLink') ?>:
        </label>
        <div class="col-xl-8">
            <div class="input-group">
                <span class="input-group-text" id="basic-addon3"><?=$this->getUrl() ?>index.php/</span>
                <input class="form-control"
                       type="text"
                       id="permaLink"
                       name="permaLink"
                       value="<?=($this->get('article')) ? $this->escape($this->get('article')->getPerma()) : $this->originalInput('permaLink') ?>" />
            </div>
        </div>
    </div>
    <?=($this->get('article')) ?  $this->getSaveBar('edit') : $this->getSaveBar('add') ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
<script src="<?=$this->getStaticUrl('js/popper/dist/umd/popper.min.js') ?>" charset="UTF-8"></script>
<script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/js/tempus-dominus.min.js') ?>" charset="UTF-8"></script>
<?php if (strncmp($this->getTranslator()->getLocale(), 'en', 2) !== 0) : ?>
    <script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/locales/' . substr($this->getTranslator()->getLocale(), 0, 2) . '.js') ?>" charset="UTF-8"></script>
<?php endif; ?>
<script>
$('#access').chosen();
$(document).ready(function() {
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
$('#template').change(function() {
    if (<?=($articleID) ? json_encode($articleID) : '""' ?>) {
        window.location = '<?=$this->getCurrentUrl(['id' => $articleID], false) ?>/template/'+$(this).val();
    } else {
        window.location = '<?=$this->getCurrentUrl(['action' => 'treat'], false) ?>/template/'+$(this).val();
    }
});
</script>
