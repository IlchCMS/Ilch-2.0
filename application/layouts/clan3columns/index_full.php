<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <?=$this->getHeader() ?>
        <link href="<?=$this->getStaticUrl('css/bootstrap.css') ?>" rel="stylesheet">
        <link href="<?=$this->getLayoutUrl('style.css') ?>" rel="stylesheet">
        <link rel="icon" href="<?=$this->getStaticUrl('img/favicon.ico') ?>" type="image/x-icon" />
        <script type="text/javascript" src="<?=$this->getStaticUrl('js/bootstrap.js') ?>"></script> 
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
                    <br /><br />
                    <div class="panel panel-default" id="headings">
                        <div class="panel-body">
                            <?=$this->getContent() ?>
                        </div>
                    </div>
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
