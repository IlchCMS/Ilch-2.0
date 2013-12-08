<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $this->getTitle(); ?></title>
        <meta name="description" content="Ilch - Frontend">
		<link href="<?php echo $this->staticUrl('css/default/style.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->staticUrl('css/bootstrap.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->staticUrl('css/global.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->staticUrl('css/ui-lightness/jquery-ui.css'); ?>" rel="stylesheet">
        <script src="<?php echo $this->staticUrl('js/jquery.js'); ?>"></script>
		<script src="<?php echo $this->staticUrl('js/bootstrap.js'); ?>"></script>
        <script src="<?php echo $this->staticUrl('js/jquery-ui.js'); ?>"></script>
	    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	    <link rel="icon" href="<?php echo $this->staticUrl('img/favicon.ico'); ?>" type="image/x-icon" />
	    <meta name="HandheldFriendly" content="True">
	    <meta name="MobileOptimized" content="320">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    </head>
    <body>
        <div class="navbar navbar-default navbar-fixed-top">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="<?php echo $this->url(); ?>" class="navbar-brand"><img class="brand" src="<?php echo $this->staticUrl('img/ilch_logo_brand.png'); ?>"></img></a>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="panel panel-default" id="headings">
                        <div class="panel-heading">
                            <?php echo 'hnav'; ?>
                        </div>
                        <div class="panel-body">
                            <?php echo $this->getContent(); ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <?php
                        foreach ($this->getMenus() as $menu) {
                    ?>
                    <div class="panel panel-default" id="content-formatting">
                        <div class="panel-heading">
                            <?php echo $menu->getTitle(); ?>
                        </div>
                        <div class="panel-body">
                            <?php echo $menu->getItems(); ?>
                        </div>
                    </div>
                    <?php
                        }
                    ?>
                </div>
            </div>
        </div> 
        <div class="container credit">
            <p class="muted credit">© Ilch CMS | <?php echo '<a href="admin">Administrator</a>'; ?></p>
        </div>  
    </body>
</html>
