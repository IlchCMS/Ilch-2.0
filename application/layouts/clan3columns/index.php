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
        <div id="main">
            <div class="row">
                <div class="col-lg-3">
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
