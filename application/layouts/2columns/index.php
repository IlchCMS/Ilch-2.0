<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <?php echo $this->getHeader(); ?>
        <link href="<?php echo $this->getLayoutUrl('style.css'); ?>" rel="stylesheet">
        <link rel="icon" href="<?php echo $this->getStaticUrl('img/favicon.ico'); ?>" type="image/x-icon" />
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-lg-10">
                    <?php echo $this->getHmenu(); ?>
                    <br /><br />
                    <div class="panel panel-default" id="headings">
                        <div class="panel-body">
                            <?php echo $this->getContent(); ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <?php
                        echo $this->getMenu
                        (
                            1,
                            '<div class="panel panel-default">
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
        <div class="container credit">
            <p class="muted credit">© Ilch CMS | <?php echo '<a href="'.$this->getUrl(array('module' => 'admin', 'controller' => 'admin', 'action' => 'index')).'">Administrator</a>'; ?></p>
        </div>
    </body>
</html>
