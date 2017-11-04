<h1><?=$this->getTrans('settings') ?></h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('articlesPerPage') ? 'has-error' : '' ?>">
        <label for="articlesPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('articlesPerPage') ?>
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="articlesPerPageInput"
                   name="articlesPerPage"
                   min="1"
                   value="<?=($this->get('articlesPerPage') != '') ? $this->escape($this->get('articlesPerPage')) : $this->originalInput('articlesPerPage') ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('articleRating') ? 'has-error' : '' ?>">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('articleRating') ?>
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="articleRating-on" name="articleRating" value="1" <?php if ($this->get('articleRating') == '1') { echo 'checked="checked"'; } ?> />
                <label for="articleRating-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="articleRating-off" name="articleRating" value="0" <?php if ($this->get('articleRating') != '1') { echo 'checked="checked"'; } ?> />
                <label for="articleRating-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>

    <h2><?=$this->getTrans('boxSettings') ?></h2>
    <b><?=$this->getTrans('boxArticle') ?></b>
    <div class="form-group <?=$this->validation()->hasError('boxArticleLimit') ? 'has-error' : '' ?>">
        <label for="boxArticleLimit" class="col-lg-2 control-label">
            <?=$this->getTrans('boxArticleLimit') ?>
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="boxArticleLimit"
                   name="boxArticleLimit"
                   min="1"
                   value="<?=($this->get('boxArticleLimit') != '') ? $this->escape($this->get('boxArticleLimit')) : $this->originalInput('boxArticleLimit') ?>" />
        </div>
    </div>
    <b><?=$this->getTrans('boxArchive') ?></b>
    <div class="form-group <?=$this->validation()->hasError('boxArchiveLimit') ? 'has-error' : '' ?>">
        <label for="boxArchiveLimit" class="col-lg-2 control-label">
            <?=$this->getTrans('boxArchiveLimit') ?>
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="boxArchiveLimit"
                   name="boxArchiveLimit"
                   min="1"
                   value="<?=($this->get('boxArchiveLimit') != '') ? $this->escape($this->get('boxArchiveLimit')) : $this->originalInput('boxArchiveLimit') ?>" />
        </div>
    </div>
    <b><?=$this->getTrans('boxKeywords') ?></b>
    <div class="form-group <?=$this->validation()->hasError('boxKeywordsH2') ? 'has-error' : '' ?>">
        <label for="boxKeywordsH2" class="col-lg-2 control-label">
            <?=$this->getTrans('boxKeywordsH2') ?>
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="boxKeywordsH2"
                   name="boxKeywordsH2"
                   min="1"
                   value="<?=($this->get('boxKeywordsH2') != '') ? $this->escape($this->get('boxKeywordsH2')) : $this->originalInput('boxKeywordsH2') ?>"
                   required />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('boxKeywordsH3') ? 'has-error' : '' ?>">
        <label for="boxKeywordsH3" class="col-lg-2 control-label">
            <?=$this->getTrans('boxKeywordsH3') ?>
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="boxKeywordsH3"
                   name="boxKeywordsH3"
                   min="1"
                   value="<?=($this->get('boxKeywordsH3') != '') ? $this->escape($this->get('boxKeywordsH3')) : $this->originalInput('boxKeywordsH3') ?>"
                   required />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('boxKeywordsH4') ? 'has-error' : '' ?>">
        <label for="boxKeywordsH4" class="col-lg-2 control-label">
            <?=$this->getTrans('boxKeywordsH4') ?>
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="boxKeywordsH4"
                   name="boxKeywordsH4"
                   min="1"
                   value="<?=($this->get('boxKeywordsH4') != '') ? $this->escape($this->get('boxKeywordsH4')) : $this->originalInput('boxKeywordsH4') ?>"
                   required />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('boxKeywordsH5') ? 'has-error' : '' ?>">
        <label for="boxKeywordsH5" class="col-lg-2 control-label">
            <?=$this->getTrans('boxKeywordsH5') ?>
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="boxKeywordsH5"
                   name="boxKeywordsH5"
                   min="1"
                   value="<?=($this->get('boxKeywordsH5') != '') ? $this->escape($this->get('boxKeywordsH5')) : $this->originalInput('boxKeywordsH5') ?>"
                   required />
        </div>
    </div>

    <?=$this->getSaveBar() ?>
</form>
