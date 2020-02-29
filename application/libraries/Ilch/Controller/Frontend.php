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
        } elseif ($this->getConfig() !== NULL) {
            $layoutKey = $this->getConfig()->get('default_layout');
        } else {
            $layoutKey = DEFAULT_LAYOUT;
        }

        $layoutFile = '';

        if (!empty($layoutKey)) {
            if (is_file(APPLICATION_PATH.'/layouts/'.$layoutKey.'/config/config.php')) {
                $configClass = '\\Layouts\\'.ucfirst(basename($layoutKey)).'\\Config\\Config';
                $layoutConfig = new $configClass($this->getTranslator());
                if (array_key_exists('layouts', $layoutConfig->config)) {
                    $config['layouts'] = $layoutConfig->config['layouts'];
                }
            }

            if (!empty($config['layouts'])) {
                foreach ($config['layouts'] as $layoutKeyConfig => $layouts) {
                    foreach ($layouts as $url) {
                        $arrayValues = array_values($url);
                        if (!empty($arrayValues[3])) {
                            $arrayKeys = array_keys($url);
                            $paramKey = $arrayKeys[3];
                        } else {
                            $paramKey = '';
                        }
                        if (empty($url['module'])) {
                            $url['module'] = '';
                        }
                        if (empty($url['controller'])) {
                            $url['controller'] = '';
                        }
                        if (empty($url['action'])) {
                            $url['action'] = '';
                        }
                        if (empty($url[$paramKey])) {
                            $url[$paramKey] = '';
                        }

                        if ($url['module'] === $this->getRequest()->getModuleName()) {
                            if ($url['controller'] === $this->getRequest()->getControllerName()) {
                                if ($url['action'] === $this->getRequest()->getActionName()) {
                                    if ($url[$paramKey] === $this->getRequest()->getParam($paramKey)) {
                                        $layoutFile = $layoutKeyConfig;
                                        break;
                                    }

                                    if (empty($url[$paramKey])) {
                                        $layoutFile = $layoutKeyConfig;
                                        break;
                                    }
                                }

                                if (empty($url['action']) && empty($url[$paramKey])) {
                                    $layoutFile = $layoutKeyConfig;
                                    break;
                                }
                            }

                            if (empty($url['controller']) && empty($url['action']) && empty($url[$paramKey])) {
                                $layoutFile = $layoutKeyConfig;
                                break;
                            }
                        }
                    }
                }
            }

            if (empty($layoutFile)) {
                $layoutFile = 'index';
            }

            $this->getLayout()->setFile('layouts/'.$layoutKey.'/'.$layoutFile, $layoutKey);

            $this->getLayout()->loadSettings();
        }
    }
}
