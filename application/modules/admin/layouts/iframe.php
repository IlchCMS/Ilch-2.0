<?php

/** @var \Ilch\Layout\Admin $this */
?>
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
        <link href="<?=$this->getStaticUrl('js/ckeditor5/build/ckeditor.css') ?>" rel="stylesheet" type="text/css">
        <link href="<?=$this->getStaticUrl('js/ckeditor5/styles.css') ?>" rel="stylesheet" type="text/css">
        <link href="<?=$this->getVendorUrl('twbs/bootstrap/dist/css/bootstrap.min.css') ?>" rel="stylesheet">
        <link href="<?=$this->getVendorUrl('fortawesome/font-awesome/css/all.min.css') ?>" rel="stylesheet">
        <link href="<?=$this->getVendorUrl('fortawesome/font-awesome/css/v4-shims.min.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/ilch.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('../application/modules/admin/static/css/main.css') ?>" rel="stylesheet">
        <link href="<?=$this->getVendorUrl('npm-asset/jquery-ui/dist/themes/ui-lightness/jquery-ui.min.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/bootstrap-choices.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('js/choices/build/choices.min.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('../application/modules/admin/static/css/admin.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('js/highlight/default.min.css') ?>" rel="stylesheet" type="text/css">

        <!-- SCRIPTS -->
        <script src="<?=$this->getVendorUrl('npm-asset/jquery/dist/jquery.min.js') ?>"></script>
        <script src="<?=$this->getVendorUrl('npm-asset/jquery-ui/dist/jquery-ui.min.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/jquery.mjs.nestedSortable.js') ?>"></script>
        <script src="<?=$this->getVendorUrl('twbs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/choices/build/choices.min.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/choices.Tokenfield.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/validate/jquery.validate.min.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/validate/additional-methods.min.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/validate/ilch-validate.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/ckeditor5/build/ckeditor.js') ?>"></script>
        <?php if (strncmp($this->getTranslator()->getLocale(), 'en', 2) !== 0) : ?>
        <script src="<?=$this->getStaticUrl('js/ckeditor5/build/translations/' . substr($this->getTranslator()->getLocale(), 0, 2) . '.umd.js') ?>"></script>
        <?php endif; ?>
        <script src="<?=$this->getStaticUrl('js/highlight/highlight.min.js') ?>"></script>
        <script>
            hljs.highlightAll();
            $(function () {
                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
                const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
            });

            let choicesOptions = {
                removeItemButton: true,
                shouldSort: false,
                loadingText: '<?=$this->getTranslator()->trans('choicesLoadingText') ?>',
                noResultsText: '<?=$this->getTranslator()->trans('choicesNoResultsText') ?>',
                noChoicesText: '<?=$this->getTranslator()->trans('choicesNoChoicesText') ?>',
                itemSelectText: '<?=$this->getTranslator()->trans('choicesItemSelectText') ?>',
                uniqueItemText: '<?=$this->getTranslator()->trans('choicesUniqueItemText') ?>',
                customAddItemText: '<?=$this->getTranslator()->trans('choicesCustomAddItemText') ?>',
                addItemText: (value) => {
                    return '<?=$this->getTranslator()->trans('choicesAddItemText') ?>'.replace(/\${value}/g, value);
                },
                removeItemIconText: '<?=$this->getTranslator()->trans('choicesRemoveItemIconText') ?>',
                removeItemLabelText: (value) => {
                    return '<?=$this->getTranslator()->trans('choicesRemoveItemLabelText') ?>'.replace(/\${value}/g, value);
                },
                maxItemCount: (maxItemCount) => {
                    return '<?=$this->getTranslator()->trans('choicesMaxItemText') ?>'.replace(/\${maxItemCount}/g, maxItemCount);
                },
            };
        </script>
    </head>
    <body>
        <?=$this->getContent() ?>
        <script src="<?=$this->getStaticUrl('js/ilch.js') ?>"></script>
    </body>
</html>
