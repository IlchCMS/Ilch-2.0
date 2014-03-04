<!DOCTYPE html>
<html>
    <head>
        <?=$this->getHeader(); ?>
        <link href="<?=$this->getLayoutUrl('style.css'); ?>" rel="stylesheet">
    </head>
    <body>
        <div class="left_container">
            <?=$this->getMenu(1,'%s <hr /> %c')?>
        </div>
        <div class="right_container">
        <?=$this->getHmenu()?>
        <?=$this->getContent()?>
        </div>
    </body>
</html>
