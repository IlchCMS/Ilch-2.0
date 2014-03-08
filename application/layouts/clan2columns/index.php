<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <?php echo $this->getHeader(); ?>
        <link href="<?=$this->getStaticUrl('css/bootstrap.css')?>" rel="stylesheet">
        <link href="<?php echo $this->getLayoutUrl('style.css'); ?>" rel="stylesheet">
        <link rel="icon" href="<?php echo $this->getStaticUrl('img/favicon.ico'); ?>" type="image/x-icon" />
        <script type="text/javascript" src="<?=$this->getStaticUrl('js/bootstrap.js')?>"></script> 
    </head>
    <body>
        <?php
            $menu = $this->getMenu
            (
                3,
                '<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">%s <b class="caret"></b></a>%c</li>',
                array('class_ul' => 'dropdown-menu')
            );
        ?>
        
        <?php if (!empty($menu)) { ?>
        <header class="header">
            <nav class="navbar navbar-gaming container" role="navigation">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-right">
                            <?=$menu?>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <?php } ?>
        <div id="carousel-example-generic" class="carousel slide container" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                <li data-target="#carousel-example-generic" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="active item">
                    <img class="img-responsive" alt="" src="<?php echo $this->getLayoutUrl('img/slider1.jpg'); ?>"/>
                    <div class="carousel-caption">
                        <h3>First slide</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing…</p>
                    </div>
                </div>
                <div class="item">
                    <img class="img-responsive" alt="" src="<?php echo $this->getLayoutUrl('img/slider2.jpg'); ?>"/>
                    <div class="carousel-caption">
                        <h3>Second slide</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing…</p>
                    </div>
                </div>
                <div class="item">
                    <img class="img-responsive" alt="" src="<?php echo $this->getLayoutUrl('img/slider.jpg'); ?>"/>
                </div>
            </div>
            <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
            </a>
        </div>
        <div id="main" class="container">
            <div class="row">
                <div class="col-lg-8">
                    <?php echo $this->getHmenu(); ?>
                    <br /><br />
                    <div class="panel panel-default" id="headings">
                        <div class="panel-body">
                            <?php echo $this->getContent(); ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
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
        </div>
<script type='text/javascript'>
    $(document).ready(function() {
         $('.carousel').carousel({
             interval: 4000
         });
    }); 
</script>
	</body>
</html>
