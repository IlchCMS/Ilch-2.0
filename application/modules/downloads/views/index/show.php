<?php

/** @var \Ilch\View $this */
$commentMapper = new \Modules\Comment\Mappers\Comment();

/** @var \Ilch\Pagination $pagination */
$pagination = $this->get('pagination');
?>

<style>
    @media (max-width: 990px) {
        #gallery > [class*="col-"] {
            padding: 0 !important;
        }
    }

    .panel-heading ~ .card-image img {
        border-radius: 0;
    }

    .card-body {
        overflow: hidden;
    }

    .card-image ~ .card-footer a {
        padding: 0 10px;
        font-size: 1.3em;
        color: rgb(100, 100, 100);
    }

    .card-footer {
        padding: 5px !important;
        color: #BBB;
    }

    .card-footer:hover {
        color: #000;
    }

    .thumbnail {
        position: relative;
        overflow: hidden;
        margin-bottom: 0 !important;
    }

    #gallery img {
        min-height: 20px;
    }
</style>

<div id="gallery">
    <div class="row">
        <?php
        /** @var \Modules\Downloads\Models\File $file */
        foreach ($this->get('files') as $file) :
            $commentsCount = $commentMapper->getCountComments('downloads/index/showfile/id/' . $file->getId());
            $image = '';
            if ($file->getFileImage() != '') {
                $image = $this->getBaseUrl($file->getFileImage());
            } else {
                $image = $this->getBaseUrl('application/modules/media/static/img/nomedia.png');
            }
            ?>
            <div class="col-6 col-lg-4 col-xl-3 col-md-4">
                <div class="card card-default">
                    <div class="card-body card-image me-auto ms-auto thumbnail">
                        <a href="<?=$this->getUrl(['action' => 'showfile', 'id' => $file->getId()]) ?>">
                            <img src="<?=$image ?>" class="card-image-preview" alt="<?=$this->escape($file->getFileTitle()) ?>" />
                        </a>
                    </div>
                    <div class="card-footer text-center">
                        <i class="fa-solid fa-pencil"></i> <?=$this->escape($file->getFileTitle()) ?><br>
                        <i class="fa-regular fa-comment"></i> <?=$commentsCount ?>
                        <i class="fa-solid fa-eye"></i> <?=$file->getVisits() ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if (empty($this->get('files'))) : ?>
        <?=$this->getTrans('downloadNotFound') ?>
    <?php endif; ?>
</div>
<?=$pagination->getHtml($this, ['action' => 'show', 'id' => $this->getRequest()->getParam('id')]) ?>
