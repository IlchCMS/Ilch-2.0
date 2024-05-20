<?php

/** @var \Ilch\View $this */

/**
 * @param string $name
 * @param array $value
 * @param \Modules\Admin\Models\LayoutAdvSettings[] $settingsValues
 * @param \Ilch\View $obj
 * @return string
 */
function getInput(string $name, array $value, array $settingsValues, \Ilch\View $obj): string
{
    $settingsValue = (empty($settingsValues[$name]) ? $obj->escape($value['default']) : $obj->escape($settingsValues[$name]->getValue()));
    $name = $obj->escape($name);
    $input = '';
    switch ($value['type']) {
        case 'bscolorpicker':
            $input = sprintf('<input class="form-control color {hash:true}"
                               id="%s"
                               name="%s"
                               data-jscolor=""
                               value="%s">', $name, $name, $settingsValue);
            break;
        case 'ckeditorhtml':
            $input = sprintf('<textarea class="form-control ckeditor"
                               name="%s"
                               id="%s"
                               toolbar="ilch_html">%s</textarea>', $name, $name, $settingsValue);
            break;
        case 'colorpicker':
            $input = sprintf('<input type="color" id="%s" name="%s" value="%s">', $name, $name, $settingsValue);
            break;
        case 'flipswitch':
            $input = '<div class="flipswitch"><input type="radio" class="flipswitch-input" id="%s-on" name="%s" value="1" ' . (empty($settingsValue) ? '' : 'checked="checked"') . '/>
                      <label for="%s-on" class="flipswitch-label flipswitch-label-on">%s</label>';
            $input = sprintf($input, $name, $name, $name, $obj->getTrans('on'));
            $input .= '<input type="radio" class="flipswitch-input" id="%s-off" name="%s" value="0" ' . (!empty($settingsValue) ? '' : 'checked="checked"') . ' />
                       <label for="%s-off" class="flipswitch-label flipswitch-label-off">%s</label><span class="flipswitch-selection"></span></div>';
            $input = sprintf($input, $name, $name, $name, $obj->getTrans('off'));
            break;
        case 'mediaselection':
            $input = sprintf('<div class="input-group">
                                        <input class="form-control"
                                               type="text"
                                               name="%s"
                                               id="selectedImage_%s"
                                               value="%s"
                                               readonly />
                                        <span class="input-group-text"><a id="media_%s" href="javascript:media_%s()"><i class="fa-regular fa-image"></i></a></span>
                                    </div>', $name, $name, $settingsValue, $name, $name);
            $input .= '<script>' . $obj->getMedia()
                            ->addMediaButton($obj->getUrl('admin/media/iframe/index/type/single/input/_' . $name . '/'))
                            ->addInputId('_' . $name)
                            ->addUploadController($obj->getUrl('admin/media/index/upload')) .
                      '</script>';
            break;
        case 'text':
            $input = sprintf('<input class="form-control"
                               type="text"
                               name="%s"
                               id="%s"
                               maxlength="255"
                               value="%s" />', $name, $name, $settingsValue);
            break;
        case 'url':
            $input = sprintf('<input class="form-control"
                               type="url"
                               name="%s"
                               id="%s"
                               value="%s" />', $name, $name, $settingsValue);
            break;
        case 'textarea':
            $input = sprintf('<textarea class="form-control"
                               name="%s"
                               id="%s">%s</textarea>', $name, $name, $settingsValue);

            break;
        default:
    }

    return $input;
}
?>

<h1><?=$this->getTrans('menuAdvSettings') ?></h1>
<form id="advsettings_form" method="POST">
    <?=$this->getTokenField() ?>
    <?php foreach ($this->get('settings') as $key => $value) : ?>
        <?php if (!empty($value['type']) && $value['type'] === 'separator') : ?>
            <h2><?=$this->getOtherLayoutTrans($this->get('layoutKey'), $key) ?></h2>
        <?php else : ?>
            <div class="row mb-3">
                <label for="<?=$key ?>" class="col-xl-2 col-form-label">
                    <?=$this->getOtherLayoutTrans($this->get('layoutKey'), $key) ?>:
                </label>
                <div class="col-xl-10">
                    <?=getInput($key, $value, $this->get('settingsValues'), $this) ?>
                    <?php if (!empty($value['description'])) : ?>
                        <div class="text-end"><small><?=$this->getOtherLayoutTrans($this->get('layoutKey'), $value['description']) ?></small><p></p></div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <?=$this->getSaveBar() ?>
</form>
<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
<script src="<?=$this->getStaticUrl('js/jscolor/jscolor.min.js') ?>"></script>
