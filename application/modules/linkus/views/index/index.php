<?php
$linkus = $this->get('linkus');
$config = \Ilch\Registry::get('config');
?>

<legend><?=$this->getTrans('menuLinkus') ?></legend>
<?php if ($linkus != ''): ?>
    <?php foreach ($linkus as $linkus): ?>
        <div class="row">
            <div class="col-lg-12">
                <h4><?=$this->escape($linkus->getTitle()) ?></h4>
                <div class="col-lg-12 text-center">
                    <a href="<?=$this->getUrl() ?>" target="_blank"><img src="<?=$this->getBaseUrl($this->escape($linkus->getBanner())) ?>" alt="<?=$this->escape($linkus->getTitle()) ?>" title="<?=$this->escape($linkus->getTitle()) ?>" border="0"></a>
                    <br /><br />
                </div>

                <?php if ($config->get('linkus_html') == 1): ?>
                    <div class="col-lg-6 text-center">
                        <?=$this->getTrans('htmlForWebsite') ?>
                        <textarea class="form-control"
                                  name="text"
                                  style="resize: vertical"
                                  rows="4"><a href="<?=$this->getUrl() ?>" target="_blank"><img src="<?=$this->getBaseUrl($this->escape($linkus->getBanner())) ?>" border="0"></a></textarea>
                    </div>
                <?php endif; ?>
                
                <?php if ($config->get('linkus_bbcode') == 1): ?>
                    <div class="col-lg-6 text-center">
                        <?=$this->getTrans('bbcodeForForum') ?>
                        <textarea class="form-control"
                                  name="text"
                                  style="resize: vertical"
                                  rows="4">[url=<?=$this->getUrl() ?>][img]<?=$this->getBaseUrl($this->escape($linkus->getBanner())) ?>[/img][/url]</textarea>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <hr />
    <?php endforeach; ?>
<?php else: ?>
    <div class="col-lg-12">
        <?=$this->getTrans('noLinkus') ?>
    </div>
<?php endif; ?>
