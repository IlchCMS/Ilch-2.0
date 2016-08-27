<!DOCTYPE html>
<html lang="de">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <?=$this->getHeader() ?>
        <link href="<?=$this->getStaticUrl('css/bootstrap.min.css') ?>" rel="stylesheet">
        <link href="<?=$this->getLayoutUrl('style.css') ?>" rel="stylesheet">
        <?=$this->getCustomCSS() ?>
        <script type="text/javascript" src="<?=$this->getStaticUrl('js/bootstrap.min.js') ?>"></script> 
    </head>
    <body>
        <div id="main">
            <div class="row">
                <div class="col-lg-2">
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
                <div class="col-lg-10">
                    <?=$this->getHmenu() ?>
                    <div class="panel panel-default" id="headings">
                        <div class="panel-body">
                            <?=$this->getContent() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?=$this->getFooter() ?>
        <script type='text/javascript'>
        $(document).ready(function() {
             $('.carousel').carousel({
                 interval: 4000
             });
        }); 
        </script>
    </body>
</html>
