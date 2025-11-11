<?php
/** @var \Ilch\View $this */

/** @var \Modules\Linkus\Models\Linkus[]|null $linkus */
$linkus = $this->get('linkus');

/** @var \Ilch\Config\Database $config */
$config = \Ilch\Registry::get('config');
?>

<h1><?=$this->getTrans('menuLinkus') ?></h1>
<?php if ($linkus != '') : ?>
    <?php foreach ($linkus as $linkusModel) : ?>
        <div class="row col-xl-12">
              <h4><?=$this->escape($linkusModel->getTitle()) ?></h4>
              <div class="col-xl-12 text-center">
                  <a href="<?=$this->getUrl() ?>" target="_blank" rel="noopener"><img src="<?=$this->getBaseUrl($this->escape($linkusModel->getBanner())) ?>" alt="<?=$this->escape($linkusModel->getTitle()) ?>" title="<?=$this->escape($linkusModel->getTitle()) ?>" border="0"></a>
                  <br /><br />
              </div>

              <?php if ($config->get('linkus_html') == 1) : ?>
                  <div class="col-xl-6 text-center">
                      <?=$this->getTrans('htmlForWebsite') ?>
                      <textarea class="form-control bg-body-tertiary"
                                style="resize: vertical"
                                name="text"
                                type="text"
                                rows="4"
                                readonly><a href="<?=$this->getUrl() ?>" target="_blank" rel="noopener"><img src="<?=$this->getBaseUrl($this->escape($linkusModel->getBanner())) ?>" border="0"></a></textarea>
                  </div>
              <?php endif; ?>

              <?php if ($config->get('linkus_bbcode') == 1) : ?>
                  <div class="col-xl-6 text-center">
                      <?=$this->getTrans('bbcodeForForum') ?>
                      <textarea class="form-control bg-body-tertiary"
                                style="resize: vertical"
                                name="text"
                                rows="4"
                                readonly>[url=<?=$this->getUrl() ?>][img]<?=$this->getBaseUrl($this->escape($linkusModel->getBanner())) ?>[/img][/url]</textarea>
                  </div>
              <?php endif; ?>
        </div>
        <hr />
    <?php endforeach; ?>
<?php else : ?>
    <div class="col-xl-12">
        <?=$this->getTrans('noLinkus') ?>
    </div>
<?php endif; ?>
