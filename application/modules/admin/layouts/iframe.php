<!DOCTYPE html>
<html lang="de">
    <head>
        <title>Ilch - Admincenter</title>

        <!-- META -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <meta name="description" content="Ilch - Admincenter">

        <!-- FAVICON -->
        <link rel="shortcut icon" type="image/x-icon" href="<?=$this->getStaticUrl('img/favicon.ico') ?>">

        <!-- STYLES -->
        <link href="<?=$this->getStaticUrl('css/bootstrap.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/font-awesome.min.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/ilch.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('../application/modules/admin/static/css/main.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/ui-lightness/jquery-ui.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/chosen/bootstrap-chosen.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/chosen/chosen.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('../application/modules/admin/static/css/admin.css') ?>" rel="stylesheet">

        <!-- SCRIPTS -->
        <script src="<?=$this->getStaticUrl('js/jquery.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/jquery-ui.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/jquery.mjs.nestedSortable.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/bootstrap.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/chosen/chosen.jquery.min.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/validate/jquery.validate.min.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/validate/additional-methods.min.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/validate/ilch-validate.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/ckeditor/ckeditor.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/ilch.js') ?>"></script>
    </head>
    <body>
        <?=$this->getContent() ?>
    </body>
</html>
