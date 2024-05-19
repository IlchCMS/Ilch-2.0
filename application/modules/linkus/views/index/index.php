<?php
$linkus = $this->get('linkus');
$config = \Ilch\Registry::get('config');
?>

<h1><?=$this->getTrans('menuLinkus') ?></h1>
<?php if ($linkus != ''): ?>
    <?php foreach ($linkus as $linkus): ?>
        <div class="row col-xl-12">
              <h4><?=$this->escape($linkus->getTitle()) ?></h4>
              <div class="col-xl-12 text-center">
                  <a href="<?=$this->getUrl() ?>" target="_blank" rel="noopener"><img src="<?=$this->getBaseUrl($this->escape($linkus->getBanner())) ?>" alt="<?=$this->escape($linkus->getTitle()) ?>" title="<?=$this->escape($linkus->getTitle()) ?>" border="0"></a>
                  <br /><br />
              </div>

              <?php if ($config->get('linkus_html') == 1): ?>
                  <div class="col-xl-6 text-center">
                      <?=$this->getTrans('htmlForWebsite') ?>
                      <textarea class="form-control bg-light"
                                style="resize: vertical"
                                name="text"
                                type="text"
                                rows="4"
                                readonly><a href="<?=$this->getUrl() ?>" target="_blank" rel="noopener"><img src="<?=$this->getBaseUrl($this->escape($linkus->getBanner())) ?>" border="0"></a></textarea>
                  </div>
              <?php endif; ?>

              <?php if ($config->get('linkus_bbcode') == 1): ?>
                  <div class="col-xl-6 text-center">
                      <?=$this->getTrans('bbcodeForForum') ?>
                      <textarea class="form-control bg-light"
                                style="resize: vertical"
                                name="text"
                                rows="4"
                                readonly>[url=<?=$this->getUrl() ?>][img]<?=$this->getBaseUrl($this->escape($linkus->getBanner())) ?>[/img][/url]</textarea>
                  </div>
              <?php endif; ?>
        </div>
        <hr />
    <?php endforeach; ?>
<?php else: ?>
    <div class="col-xl-12">
        <?=$this->getTrans('noLinkus') ?>
    </div>
<?php endif; ?>
