<?php /** @var $this \Ilch\Layout\Frontend */ ?><!DOCTYPE html>
<html lang="de">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <?=$this->getHeader() ?>
        <link href="<?=$this->getVendorUrl('twbs/bootstrap/dist/css/bootstrap.min.css') ?>" rel="stylesheet">
        <link href="<?=$this->getLayoutUrl('style.css') ?>" rel="stylesheet">
        <?=$this->getCustomCSS() ?>
        <script src="<?=$this->getVendorUrl('twbs/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
    </head>
    <body>
        <header>
            <nav class="navbar navbar-gaming navbar-fixed-top hidden-sm hidden-md hidden-lg">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse-main" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="/">Navigation</a>
                    </div>

                    <div class="collapse navbar-collapse" id="bs-navbar-collapse-main">
                        <?php
                        echo $this->getMenu
                        (
                            1,
                            '<div class="panel panel-gaming">
                                 <div class="panel-heading">%s</div>
                                    <div class="panel-body">
                                        %c
                                    </div>
                             </div>'
                        );
                        ?>
                    </div>
                </div>
            </nav>

            <div class="container">
                <div class="gaming">
                    <div id="carousel-generic" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carousel-generic" data-slide-to="0" class="active"></li>
                            <li data-target="#carousel-generic" data-slide-to="1"></li>
                            <li data-target="#carousel-generic" data-slide-to="2"></li>
                        </ol>

                        <div class="carousel-inner" role="listbox">
                            <div class="item active">
                                <img src="<?=$this->getLayoutUrl('img/slider/slider_1.jpg') ?>" alt="Slider 1">
                            </div>
                            <div class="item">
                                <img src="<?=$this->getLayoutUrl('img/slider/slider_2.jpg') ?>" alt="Slider 2">
                            </div>
                            <div class="item">
                                <img src="<?=$this->getLayoutUrl('img/slider/slider_3.jpg') ?>" alt="Slider 2">
                            </div>
                        </div>

                        <a class="left carousel-control" href="#carousel-generic" role="button" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="right carousel-control" href="#carousel-generic" role="button" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>

                    <div class="gaming-name">
                        Clanname
                    </div>
                </div>
            </div>
        </header>

        <div class="container">
            <div class="gaming">
                <div class="row">
                    <div class="hidden-xs col-sm-2 col-md-3 col-lg-2">
                        <?php
                        echo $this->getMenu
                        (
                            1,
                            '<div class="panel panel-gaming">
                                 <div class="panel-heading">%s</div>
                                    <div class="panel-body">
                                        %c
                                    </div>
                             </div>'
                        );
                        ?>
                    </div>
                    <div class="col-xs-12 col-sm-8 col-md-7 col-lg-8">
                        <?=$this->getHmenu() ?>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <?=$this->getContent() ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-2">
                        <?php
                        echo $this->getMenu
                        (
                            2,
                            '<div class="panel panel-gaming">
                                 <div class="panel-heading">%s</div>
                                    <div class="panel-body">
                                        %c
                                    </div>
                             </div>'
                        );
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="container">
                <div class="gaming">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            &copy; Ilch 2.0 Free Layout | CMS by <a href="http://www.ilch.de/">Ilch</a>
                        </div>
                        <div class="col-xs-12 col-sm-6 nav">
                            <ul>
                                <li><a href="/">Home</a></li>
                                <li><a href="/index.php/contact/index/index">Kontakt</a></li>
                                <li><a href="/index.php/imprint/index/index">Impressum</a></li>
                                <li><a href="/index.php/privacy/index/index">Datenschutz</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?=$this->getFooter() ?>
    </body>
</html>
