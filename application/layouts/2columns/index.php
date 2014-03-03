<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <?=$this->getHeader()?>
        <link rel="icon" href="<?=$this->getStaticUrl('img/favicon.ico'); ?>" type="image/x-icon" />
        <link href="<?=$this->getStaticUrl('css/bootstrap.css')?>" rel="stylesheet">
        <link href="<?=$this->getLayoutUrl('style.css'); ?>" rel="stylesheet">
        <script type="text/javascript" src="<?=$this->getStaticUrl('js/bootstrap.js')?>"></script> 
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-lg-10">
                    <?=$this->getHmenu()?>
                    <br /><br />
                    <div class="panel panel-default" id="headings">
                        <div class="panel-body">
                            <?=$this->getContent()?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <?=$this->getMenu
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
            <p class="muted credit">© Ilch CMS | <?='<a href="'.$this->getUrl(array('module' => 'admin', 'controller' => 'admin', 'action' => 'index')).'">Administrator</a>'; ?></p>
        </div>
    </body>
</html>
