<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <title>Ilch <?php echo VERSION; ?> Frontend</title>
        <meta name="description" content="Ilch - Frontend">
        <link href="<?php echo $this->staticUrl('css/bootstrap.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->staticUrl('css/global.css'); ?>" rel="stylesheet">
        <link href="<?php echo $this->staticUrl('css/ui-lightness/jquery-ui.css'); ?>" rel="stylesheet">
        <script src="<?php echo $this->staticUrl('js/jquery.js'); ?>"></script>
        <script src="<?php echo $this->staticUrl('js/jquery-ui.js'); ?>"></script>
    </head>
    <body>
        <div id="app">
            <div class="navbar">
                <div class="navbar-inner navbar-app">
                    Default Seite
                </div>
            </div>
            <div class="app_content">
                <?php echo $this->getContent(); ?>
            </div>
        </div>
    </body>
</html>
