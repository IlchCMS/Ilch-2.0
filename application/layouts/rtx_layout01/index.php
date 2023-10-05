<?php
/** @var $this \Ilch\Layout\Frontend */ ?><!DOCTYPE html>
<html lang="de">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <?= $this->getHeader() ?>
    <link href="<?= $this->getVendorUrl('twbs/bootstrap/dist/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= $this->getLayoutUrl('css/style.css') ?>" rel="stylesheet">
    <?= $this->getCustomCSS() ?>
    <script src="<?= $this->getVendorUrl('twbs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
</head>
<body>
<!-- ======= Header ======= -->
    <header class="fixed-top">
        <div class="container d-flex align-items-center">
            <h1 class="me-auto"><a href="<?=$this->getUrl() ?>" class="logo me-auto"><img src="<?= $this->getStaticUrl('img/ilch_logo.png') ?>" alt="" class="img-fluid"></a></h1>

        </div>

    </header>
<!-- ======= Ende Header ======= -->
</body>
</html>
