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
        $metaTitle = $this->get('metaTitle');

        if (!empty($metaTitle)) {
            return $metaTitle;
        }

        if (!empty($config) && $config->get('page_title') !== '') {
            return $config->get('page_title');
        }

        return '';
    }
 
    /**
     * Gets page description from config or meta settings.
     *
     * @return string
     */
    public function getDescription()
    {
        $config = \Ilch\Registry::get('config');
        $metaDescription = $this->get('metaDescription');

        if (!empty($metaDescription)) {
            return $metaDescription;
        }

        if (!empty($config) && $config->get('description') !== '') {
            return $config->get('description');
        }

        return '';
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
        $viewPath = APPLICATION_PATH.'/'.dirname($this->getFile()).'/views/boxes/'.$boxKey.'/render.php';

        if (!file_exists($viewPath)) {
            $viewPath = APPLICATION_PATH.'/boxes/'.$boxKey.'/render.php';
        }

        return $view->loadScript($viewPath);
    }

    /**
     * Gets the header.
     *
     * @return string
     */
    public function getHeader()
    {
        $html = '<meta charset="utf-8">
                <title>'.$this->escape($this->getTitle()).'</title>
                <meta name="description" content="'.$this->escape($this->getDescription()).'">';

        $html .= '<link href="'.$this->getStaticUrl('css/normalize.css').'" rel="stylesheet">
                <link href="'.$this->getStaticUrl('css/font-awesome.css').'" rel="stylesheet">
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