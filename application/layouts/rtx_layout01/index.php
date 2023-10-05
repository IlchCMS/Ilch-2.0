<?php
/** @var $this \Ilch\Layout\Frontend */ ?><!DOCTYPE html>
<html lang="de">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <?= $this->getHeader() ?>
    <link href="<?= $this->getVendorUrl('twbs/bootstrap/dist/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= $this->getLayoutUrl('cssstyle.css') ?>" rel="stylesheet">
    <?= $this->getCustomCSS() ?>
    <script src="<?= $this->getVendorUrl('twbs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
</head>
<body>
<!-- ======= Header ======= -->
    <header class="fixed-top">

    </header>
<!-- ======= Ende Header ======= -->
</body>
</html>
