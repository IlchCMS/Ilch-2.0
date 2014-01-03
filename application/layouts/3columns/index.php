<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->getHeader(); ?>
        <link href="<?php echo $this->staticUrl('../application/layouts/3columns/style.css'); ?>" rel="stylesheet">
        <link rel="icon" href="<?php echo $this->staticUrl('img/favicon.ico'); ?>" type="image/x-icon" />
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
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
                <div class="col-lg-6">
                    <?php echo $this->getHmenu(); ?>
                    <br /><br />
                    <div class="panel panel-default" id="headings">
                        <div class="panel-body">
                            <?php echo $this->getContent(); ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <?php
                        echo $this->getMenu
                        (
                            2,
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
            <p class="muted credit">© Ilch CMS | <?php echo '<a href="'.$this->url('admin').'">Administrator</a>'; ?></p>
        </div>  
    </body>
</html>
