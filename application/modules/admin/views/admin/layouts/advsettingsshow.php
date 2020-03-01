<?php
function getInput($name, $value, $settingsValues, $obj)
{
    $settingsValue = (empty($settingsValues[$name]) ? $obj->escape($value['default']) : $obj->escape($settingsValues[$name]->getValue()));
    $name = $obj->escape($name);
    $input = '';
    switch($value['type'])
    {
        case 'bscolorpicker':
            $input = sprintf('<input class="form-control color {hash:true}"
                               id="%s"
                               name="%s"
                               value="%s">', $name, $name, $settingsValue);
            break;
        case 'ckeditorbbcode':
            $input = sprintf('<textarea class="form-control ckeditor"
                               name="%s"
                               id="%s"
                               toolbar="ilch_bbcode">%s</textarea>', $name, $name, $settingsValue);
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
            $input = '<div class="flipswitch"><input type="radio" class="flipswitch-input" id="%s-on" name="%s" value="1" '.(empty($settingsValue) ? '' : 'checked="checked"').'/>
                      <label for="%s-on" class="flipswitch-label flipswitch-label-on">%s</label>';
            $input = sprintf($input, $name, $name, $name, $obj->getTrans('on'));
            $input .= '<input type="radio" class="flipswitch-input" id="%s-off" name="%s" value="0" '.(!empty($settingsValue) ? '' : 'checked="checked"').' />
                       <label for="%s-off" class="flipswitch-label flipswitch-label-off">%s</label><span class="flipswitch-selection"></span></div>';
            $input = sprintf($input, $name, $name, $name, $obj->getTrans('off'));
            break;
        case 'mediaselection':
            $input = sprintf('<div class="input-group">
                                        <input class="form-control"
                                               type="text"
                                               name="%s"
                                               id="%s"
                                               value="%s" />
                                        <span class="input-group-addon"><a id="media" href="javascript:media()"><i class="fa fa-picture-o"></i></a></span>
                                    </div>', $name, $name, $settingsValue);
            break;
        case 'text':
            $input = sprintf('<input class="form-control"
                               type="text"
                               name="%s"
                               id="%s"
                               maxlength="40" 
                               value="%s" />', $name, $name, $settingsValue);
            break;
        default:
    }

    return $input;
}
?>

<h1><?=$this->getTrans('menuAdvSettings') ?></h1>
<form id="advsettings_form" class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <?php foreach ($this->get('settings') as $key => $value) : ?>
        <div class="form-group">
            <label for="<?=$key ?>>" class="col-lg-2 control-label">
                <?=$this->getTrans($key) ?>:
            </label>
            <div class="col-lg-10">
                <?=getInput($key, $value, $this->get('settingsValues'), $this) ?>
                <?php if (!empty($value['description'])) : ?>
                    <div class="text-right"><small><?=$this->escape($value['description']) ?></small><p></p></div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <?=$this->getSaveBar() ?>
</form>
<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
<script>
    <?=$this->getMedia()
        ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/'))
        ->addUploadController($this->getUrl('admin/media/index/upload'))
    ?>
</script>
<script src="<?=$this->getStaticUrl('js/jscolor/jscolor.js') ?>"></script>
