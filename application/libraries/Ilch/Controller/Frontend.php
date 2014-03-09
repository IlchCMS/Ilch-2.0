<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Controller;
defined('ACCESS') or die('no direct access');

class Frontend extends Base
{
    public function __construct(\Ilch\Layout\Base $layout, \Ilch\View $view, \Ilch\Request $request, \Ilch\Router $router, \Ilch\Translator $translator)
    {
        parent::__construct($layout, $view, $request, $router, $translator);
        
        if (!empty($_SESSION['layout'])) {
            $layoutKey = $_SESSION['layout'];
            $layoutFile = 'index';
        } elseif($this->getConfig() !== NULL) {
            $layoutKey = $this->getConfig()->get('default_layout');
            $layoutFile = 'index';
        }

        if(!empty($layoutKey)) {
            require_once APPLICATION_PATH.'/layouts/'.$layoutKey.'/config/config.php';

            if(!empty($config['layouts'])) {
                foreach ($config['layouts'] as $layoutKeyConfig => $layouts) {
                    foreach ($layouts as $url) {
                        if($url['module'] == $this->getRequest()->getModuleName()) {
                            $layoutFile = $layoutKeyConfig;
                            break 2;
                        }
                    }
                }
            }

            $this->getLayout()->setFile('layouts/'.$layoutKey.'/'.$layoutFile, $layoutKey);
        }
    }
}
