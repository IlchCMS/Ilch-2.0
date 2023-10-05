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
    <header id="header" class="fixed-top">
        <div class="container d-flex align-items-center">

            <!-- ToDo:: Später hat man die Wahl zwischen LogoGrafik oder einfachem Text -->
            <a href="<?=$this->getUrl() ?>" class="me-auto"><img src="<?= $this->getStaticUrl('img/ilch_logo.png') ?>" alt="Ilch2-Logo" class="img-fluid"></a>
            <!-- ======= Navbar ======= -->
            <nav id="navbar" class="navbar">
                <ul>
                    <?=$this->getMenu(1, '%c', [
                        'boxes' => [
                            'render' => false
                        ],
                    ])
                    ?>
                    <?=$this->getMenu(2, '<li class="dropdown"><a class="nav-link" href="#" title="%s">%s</a>%c</li>', [
                        'menus' => [
                            'li-class-root'  => 'dropdown',
                            'allow-nesting' => false
                        ],
                        'boxes' => [
                            'render' => false
                        ],
                    ]) ?>
                </ul>
            </nav>
            <!-- ======= Ende Navbar ======= -->
        </div>
    </header>
<!-- ======= Ende Header ======= -->
</body>
</html>
