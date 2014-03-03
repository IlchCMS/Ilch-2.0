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
            return 'Ilch Frontend';
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
        $this->getTranslator()->load(APPLICATION_PATH.'/boxes/'.$boxKey.'/translations');
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

        $html .= '<link href="'.$this->getStaticUrl('css/font-awesome.css').'" rel="stylesheet">
                <link href="'.$this->getStaticUrl('css/ilch.css').'" rel="stylesheet">
                <link href="'.$this->getStaticUrl('css/ui-lightness/jquery-ui.css').'" rel="stylesheet">
                <link href="'.$this->getStaticUrl('../application/modules/user/static/css/user.css').'" rel="stylesheet">
                <script type="text/javascript" src="'.$this->getStaticUrl('js/jquery.js').'"></script>
                <script type="text/javascript" src="'.$this->getStaticUrl('js/jquery-ui.js').'"></script>
                <script type="text/javascript" src="'.$this->getStaticUrl('js/ckeditor/ckeditor.js').'"></script>
                <script type="text/javascript" src="'.$this->getStaticUrl('js/ilch.js').'"></script>';
        return $html;
    }
}