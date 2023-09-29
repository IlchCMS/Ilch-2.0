<!DOCTYPE html>
<html lang="de">
    <head>
        <title>Ilch - <?=$this->getTrans('admincenter') ?></title>

        <!-- META -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <meta name="description" content="Ilch - <?=$this->getTrans('admincenter') ?>">

        <!-- FAVICON -->
        <link rel="shortcut icon" type="image/x-icon" href="<?=$this->getStaticUrl('img/favicon.ico') ?>">

        <!-- STYLES -->
        <link href="<?=$this->getVendorUrl('twbs/bootstrap/dist/css/bootstrap.min.css') ?>" rel="stylesheet">
        <link href="<?=$this->getVendorUrl('fortawesome/font-awesome/css/all.min.css') ?>" rel="stylesheet">
        <link href="<?=$this->getVendorUrl('fortawesome/font-awesome/css/v4-shims.min.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/ilch.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('../application/modules/admin/static/css/main.css') ?>" rel="stylesheet">
        <link href="<?=$this->getVendorUrl('npm-asset/jquery-ui/dist/themes/ui-lightness/jquery-ui.min.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/chosen/bootstrap-chosen.css') ?>" rel="stylesheet">
        <link href="<?=$this->getVendorUrl('harvesthq/chosen/chosen.min.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('../application/modules/admin/static/css/admin.css') ?>" rel="stylesheet">

        <!-- SCRIPTS -->
        <script src="<?=$this->getVendorUrl('npm-asset/jquery/dist/jquery.min.js') ?>"></script>
        <script src="<?=$this->getVendorUrl('npm-asset/jquery-ui/dist/jquery-ui.min.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/jquery.mjs.nestedSortable.js') ?>"></script>
        <script src="<?=$this->getVendorUrl('twbs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
        <script src="<?=$this->getVendorUrl('harvesthq/chosen/chosen.jquery.min.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/validate/jquery.validate.min.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/validate/additional-methods.min.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/validate/ilch-validate.js') ?>"></script>
        <script src="<?=$this->getVendorUrl('ckeditor/ckeditor/ckeditor.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/ilch.js') ?>"></script>
    </head>
    <body>
        <?=$this->getContent() ?>
    </body>
</html>
