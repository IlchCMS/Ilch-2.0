<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Boxes;

class Layoutswitch extends \Ilch\Box
{
    public function render()
    {
        $layouts = [];
        
        foreach (glob(APPLICATION_PATH.'/layouts/*') as $layoutPath) {
            if (is_dir($layoutPath)){
                $configClass = '\\Layouts\\'.ucfirst(basename($layoutPath)).'\\Config\\Config';
                $config = new $configClass($this->getTranslator());
                $layouts[basename($layoutPath)] = $config->config['name'];
            }
        }
        
        $this->getView()->set('layouts', $layouts)
                        ->set('defaultLayout', $this->getConfig()->get('default_layout'));
    }
}
