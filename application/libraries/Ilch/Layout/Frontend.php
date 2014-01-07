<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout;
defined('ACCESS') or die('no direct access');

class Frontend extends Base
{
    /**
     * Adds layout helper.
     *
     * @todo adds helper dynamic from folder.
     * @param \Ilch\Request $request
     * @param \Ilch\Translator $translator
     * @param \Ilch\Router $router
     */
    public function __construct(\Ilch\Request $request, \Ilch\Translator $translator, \Ilch\Router $router)
    {
        parent::__construct($request, $translator, $router);

        $this->addHelper('getMenu', 'layout', new \Ilch\Layout\Helper\GetMenu($this));
        $this->addHelper('getMenus', 'layout', new \Ilch\Layout\Helper\GetMenus($this));
        $this->addHelper('getHmenu', 'layout', new \Ilch\Layout\Helper\GetHmenu($this));
    }

    /**
     * Gets page title from config or meta settings.
     *
     * @return string
     */
    public function getTitle()
    {
        $config = \Ilch\Registry::get('config');

        /*
         * @todo page modul handling
         */

        if (!empty($config) && $config->get('page_title') !== '') {
            return $this->escape($config->get('page_title'));
        } else {
            return 'Ilch '.VERSION.' Frontend';
        }
    }

    /**
     * Gets the box with the given key.
     *
     * @return string
     */
    public function getBox($boxKey)
    {
        $class = 'Boxes\\'.ucfirst($boxKey).'\\Index';
        $view = new \Ilch\View($this->getRequest(), $this->getTranslator(), $this->getRouter());
        $boxObj = new $class($this, $view, $this->getRequest(), $this->getRouter(), $this->getTranslator());
        $boxObj->render();

        return $view->loadScript(APPLICATION_PATH.'/boxes/'.$boxKey.'/render.php');
    }

    /**
     * Gets the header.
     *
     * @return string
     */
    public function getHeader()
    {
        $html = '<meta charset="utf-8">
                <title>'.$this->getTitle().'</title>
                <meta name="description" content="">';

        $html .= '<link href="'.$this->staticUrl('css/bootstrap.css').'" rel="stylesheet">
                <link href="'.$this->staticUrl('css/font-awesome.css').'" rel="stylesheet">
                <link href="'.$this->staticUrl('css/global.css').'" rel="stylesheet">
                <link href="'.$this->staticUrl('css/ui-lightness/jquery-ui.css').'" rel="stylesheet">
                <script src="'.$this->staticUrl('js/jquery.js').'"></script>
                <script src="'.$this->staticUrl('js/bootstrap.js').'"></script>
                <script src="'.$this->staticUrl('js/jquery-ui.js').'"></script>';
        return $html;
    }
}

