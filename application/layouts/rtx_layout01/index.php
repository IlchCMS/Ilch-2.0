<?php
/** @var $this \Ilch\Layout\Frontend */ ?><!DOCTYPE html>
<html lang="de">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <?= $this->getHeader() ?>
    <link href="<?= $this->getLayoutUrl('vendor/aos/aos.css') ?>" rel="stylesheet">
    <link href="<?= $this->getVendorUrl('twbs/bootstrap/dist/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= $this->getLayoutUrl('css/style.css') ?>" rel="stylesheet">
    <?= $this->getCustomCSS() ?>
    <script src="<?= $this->getVendorUrl('twbs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
</head>
<body>
<!-- ======= Header ======= -->
    <header id="header" class="fixed">
        <div class="container d-flex align-items-center">

            <a href="<?=$this->getUrl() ?>" class="me-auto logo"><img src="<?= $this->getStaticUrl('img/ilch_logo.png') ?>" alt="Ilch2-Logo" class="img-fluid"></a>
            <!-- ======= Navbar ======= -->
            <nav id="navbar" class="navbar">
                <ul>
                    <?=$this->getMenu(1, '%c', [
                        'menus' => [
                            'a-class' => 'rtx rtx-one',
                        ],
                            'boxes' => [
                            'render' => false
                        ],
                    ])
                    ?>
                </ul>
                <i class="fa-solid fa-list mobile-nav-toggle"></i>
            </nav>
            <!-- ======= Ende Navbar ======= -->
        </div>
    </header>
<!-- ======= Ende Header ======= -->

<!-- ======= Hero Section ======= -->
<section id="hero" class="d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 d-flex flex-column justify-content-center pt-4 pt-lg-0 order-2 order-lg-1" data-aos="fade-up" data-aos-delay="200">
                <h1>Ilch CMS</h1>
                <h2>Du hast einen Clan, eine Community oder willst eine eigene private Homepage erstellen?</h2>
                <div class="d-flex justify-content-center justify-content-lg-start">
                    <a href="https://github.com/IlchCMS/Ilch-2.0" class="btn-download">Kostenloser Download</a>

                </div>
            </div>
            <div class="col-lg-4 order-1 order-lg-2 hero-img" data-aos="zoom-in" data-aos-delay="200">
                <img src="<?=$this->getStaticUrl('img/ilch_logo.png')?>" class="img-fluid animated" alt="">
            </div>
        </div>
    </div>

</section><!-- End Hero -->

<main id="main">
    <!-- ======= Content Section ======= -->
    <section id="ilch-content" class="">
        <div class="container-fluid ">
            <div class="row justify-content-center">
                <div class="col-lg-2">
                    <?=$this->getMenu(2, '<div class="card my-2">
                        <div class="card-header card-header-bg ">
                            %s
                        </div>%c</div>', [
                        'menus' => [
                            'ul-class-root'  => 'list-group',
                            'li-class-root'  => 'list-group-item',
                            'a-class' => 'rtx rtx-one',
                        ],
                        'boxes' => [
                            'render' => true
                        ],
                    ])
                    ?>
                </div>
                <div class="col-lg-7 ilch-content ">
                    <?=$this->getContent()?>
                </div>
                <div class="col-lg-2">

                    <?=$this->getMenu(3, '<div class="card my-2">
                        <div class="card-header card-header-bg">
                            %s
                        </div>%c</div>', [
                        'menus' => [
                            'ul-class-root'  => 'list-group list-group-flush ',
                            'li-class-root'  => 'list-group-item',
                            'a-class' => 'rtx rtx-one',
                        ],
                        'boxes' => [
                            'render' => true
                        ],
                    ])
                    ?>
                </div>
            </div>
        </div>
    </section><!-- Ende Content Section -->

</main>
<!-- ======= Footer ======= -->
<footer id="footer">

<div class="footer-top">
    <div class="container">
        <div class="row">
                <?=$this->getMenu(4, '<div class="col-lg-3 col-md-6 footer-links"><h4>%s</h4>%c</div>', [
                    'menus' => [
                        'ul-class-root'  => '',
                        'ul-class-child' => '',
                        'li-class-root'  => '',
                        'li-class-child' => '',
                        'a-class'        => 'rtx rtx-one',
                        'span-class'     => ''
                    ],
                    'boxes' => [
                        'render' => true,
                    ],
                ])
                ?>

                <?=$this->getMenu(5, '<div class="col-lg-3 col-md-6 footer-links"><h4>%s</h4>%c</div>', [
                    'menus' => [
                        'ul-class-root'  => '',
                        'ul-class-child' => '',
                        'li-class-root'  => '',
                        'li-class-child' => '',
                        'a-class'        => 'rtx rtx-one',
                        'span-class'     => ''
                    ],
                    'boxes' => [
                        'render' => true,
                    ],
                ])
                ?>

                <?=$this->getMenu(6, '<div class="col-lg-3 col-md-6 footer-links"><h4>%s</h4>%c</div>', [
                    'menus' => [
                        'ul-class-root'  => '',
                        'ul-class-child' => '',
                        'li-class-root'  => '',
                        'li-class-child' => '',
                        'a-class'        => 'rtx rtx-one',
                        'span-class'     => ''
                    ],
                    'boxes' => [
                        'render' => true,
                    ],
                ])
                ?>

            <div class="col-lg-3 col-md-6 footer-links">
                <h4>Soziale Netzwerke</h4>
                <p>Besuche uns auf diesen Plattformen</p>
                <div class="social-links mt-3">
                    <a href="#" class="twitter"><i class="fa fa-twitter"></i></a>
                    <a href="#" class="facebook"><i class="fa fa-facebook"></i></a>
                    <a href="#" class="instagram"><i class="fa fa-instagram"></i></a>
                    <a href="#" class="google-plus"><i class="fa fa-skype"></i></a>
                    <a href="#" class="linkedin"><i class="fa fa-linkedin"></i></a>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="container footer-bottom clearfix">
    <div class="copyright">
        &copy; Copyright <strong><span>RTX2070</span></strong>. All Rights Reserved
    </div>
    <div class="credits">

        Designed by <a href="#">RTX2070</a>
    </div>
</div>
</footer><!-- End Footer -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="fa-solid fa-arrow-up"></i></a>
<script src="<?=$this->getLayoutUrl('vendor/aos/aos.js')?>"></script>

<script src="<?=$this->getLayoutUrl('js/main.js')?>"></script>
</body>
</html>
