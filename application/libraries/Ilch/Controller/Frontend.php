<?php
/**
 * @package ilch
 */

namespace Ilch\Controller;

class Frontend extends Base
{
    public function __construct(\Ilch\Layout\Base $layout, \Ilch\View $view, \Ilch\Request $request, \Ilch\Router $router, \Ilch\Translator $translator)
    {
        parent::__construct($layout, $view, $request, $router, $translator);
        
        if (!empty($_SESSION['layout'])) {
            $layoutKey = $_SESSION['layout'];
        } elseif($this->getConfig() !== NULL) {
            $layoutKey = $this->getConfig()->get('default_layout');
        } else {
            $layoutKey = DEFAULT_LAYOUT;
        }

        $layoutFile = 'index';

        if(!empty($layoutKey)) {
            if (is_file(APPLICATION_PATH.'/layouts/'.$layoutKey.'/config/config.php')) {
                require_once APPLICATION_PATH.'/layouts/'.$layoutKey.'/config/config.php';
            }

            if(!empty($config['layouts'])) {
                foreach ($config['layouts'] as $layoutKeyConfig => $layouts) {
                    foreach ($layouts as $url) {
                        if(empty($url['action'])) {
                            $url['action'] = '';
                        }
                        if(empty($url['controller'])) {
                            $url['controller'] = '';
                        }
                        if(empty($url['module'])) {
                            $url['module'] = '';
                        }
                        if($url['module'] == $this->getRequest()->getModuleName() and $url['controller'] == $this->getRequest()->getControllerName() and $url['action'] == $this->getRequest()->getActionName()) {
                            $layoutFile = $layoutKeyConfig;
                            break 2;
                        } elseif ($url['module'] == $this->getRequest()->getModuleName() and $url['controller'] == $this->getRequest()->getControllerName() and empty ($url['action'])){
                            $layoutFile = $layoutKeyConfig;
                            break 2;
                        } elseif ($url['module'] == $this->getRequest()->getModuleName() and empty ($url['controller']) and empty ($url['action'])){
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
