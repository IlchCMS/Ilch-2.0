<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Boxes;

use Modules\Admin\Models\Layout as LayoutModel;

class Layoutswitch extends \Ilch\Box
{
    public function render()
    {
        $layouts = [];
        
        foreach (glob(APPLICATION_PATH.'/layouts/*') as $layoutPath) {
            if (is_dir($layoutPath)){
                $configClass = '\\Layouts\\'.ucfirst(basename($layoutPath)).'\\Config\\Config';
                $config = new $configClass($this->getTranslator());
                $model = new LayoutModel();
                $model->setKey(basename($layoutPath));
                $model->setName($config->config['name']);
                $model->setVersion($config->config['version']);
                $model->setAuthor($config->config['author']);
                if (!empty($config->config['link'])) {
                    $model->setLink($config->config['link']);
                }
                $model->setDesc($config->config['desc']);
                if (!empty($config->config['modulekey'])) {
                    $model->setModulekey($config->config['modulekey']);
                }
                $layouts[] = $model;
            }
        }
        
        $this->getView()->set('layouts', $layouts)
                        ->set('defaultLayout', $this->getConfig()->get('default_layout'));
    }
}
