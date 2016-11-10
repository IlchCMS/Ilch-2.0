<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout;

use Ilch\Request;
use Ilch\Router;
use Ilch\Translator;

/**
 * Class Frontend
 * @package Ilch\Layout

 * @method \Ilch\Layout\Helper\Title\Model getTitle() get the title model
 * @method \Ilch\Layout\Helper\Header\Model header() get the header model
 * @method string getMenu(int $menuId, string $tpl = '', array $options = array()) rendering of a menu
 * @method \Ilch\Layout\Helper\Menu\Model[] getMenus() get all menus
 * @method \Ilch\Layout\Helper\Hmenu\Model getHmenu() get the hmenu
 */
class Frontend extends Base
{
    /**
     * Adds layout helper.
     *
     * @todo adds helper dynamic from folder.
     * @param Request $request
     * @param Translator $translator
     * @param Router $router
     * @param string|null $baseUrl
     */
    public function __construct(Request $request, Translator $translator, Router $router, $baseUrl = null)
    {
        parent::__construct($request, $translator, $router, $baseUrl);

        $this->addHelper('getTitle', 'layout', new \Ilch\Layout\Helper\GetTitle($this));
        $this->addHelper('header', 'layout', new \Ilch\Layout\Helper\Header($this));
        $this->addHelper('getHmenu', 'layout', new \Ilch\Layout\Helper\GetHmenu($this));
        $this->addHelper('getMenu', 'layout', new \Ilch\Layout\Helper\GetMenu($this));
        $this->addHelper('getMenus', 'layout', new \Ilch\Layout\Helper\GetMenus($this));
    }

    /**
     * Gets page favicon from config.
     *
     * @return string
     */
    public function getFavicon()
    {
        $config = \Ilch\Registry::get('config');
        $favicon = $config->get('favicon');

        if (empty($favicon)) {
            return 'static/img/favicon.ico';
        }

        return $favicon;
    }

    /**
     * Gets page favicon from config.
     *
     * @return string
     */
    public function getAppleIcon()
    {
        $config = \Ilch\Registry::get('config');
        $appleIcon = $config->get('apple_icon');

        if (empty($appleIcon)) {
            return 'static/img/appleicon.png';
        }

        return $config->get('apple_icon');
    }
 
    /**
     * Gets page keywords from meta settings.
     *
     * @return string
     */
    public function getKeywords()
    {
        $metaKeywords = $this->get('metaKeywords');

        if (!empty($metaKeywords)) {
            return $metaKeywords;
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
     * Get key from config.
     *
     * @return string
     */
    public function getConfigKey($key)
    {
        $config = \Ilch\Registry::get('config');

        if (!empty($config) && $key !== '') {
            return $config->get($key);
        }

        return '';
    }

    /**
     * Gets the box with the given key.
     *
     * @param string $moduleKey
     * @param string $boxKey
     * @return string
     */
    public function getBox($moduleKey, $boxKey = '', $customView = null)
    {
        if (empty($boxKey)) {
            $boxKey = $moduleKey;
        }

        $class = '\\Modules\\'.ucfirst($moduleKey).'\\Boxes\\'.ucfirst($boxKey);
        $view = new \Ilch\View($this->getRequest(), $this->getTranslator(), $this->getRouter());
        $this->getTranslator()->load(APPLICATION_PATH.'/modules/'.$moduleKey.'/translations');
        $boxObj = new $class($this, $view, $this->getRequest(), $this->getRouter(), $this->getTranslator());
        $boxObj->render();

        if (! is_null($customView)) {
            $viewPath = APPLICATION_PATH.'/'.dirname($this->getFile()).'/views/modules/'.$moduleKey.'/boxes/views/'.$customView.'.php';
        } else {
            if (file_exists(APPLICATION_PATH.'/'.dirname($this->getFile()).'/views/modules/'.$moduleKey.'/boxes/views/'.$boxKey.'.php')) {
                $viewPath = APPLICATION_PATH.'/'.dirname($this->getFile()).'/views/modules/'.$moduleKey.'/boxes/views/'.$boxKey.'.php';
            } else {
                $viewPath = APPLICATION_PATH.'/modules/'.$moduleKey.'/boxes/views/'.$boxKey.'.php';
            }
        }

        $view->setLayoutKey($this->getLayoutKey());
        $view->setBoxUrl('application/modules/'.$moduleKey);

        return $view->loadScript($viewPath);
    }

    /**
     * Gets the header.
     *
     * //TODO: rework loading of css and jss to be more dynamic!!!
     * @return string
     */
    public function getHeader()
    {
        $html = '<meta charset="utf-8">
                <title>'.$this->escape($this->getTitle()).'</title>
                <link rel="icon" href="'.$this->getBaseUrl($this->escape($this->getFavicon())).'" type="image/x-icon">
                <meta name="keywords" content="'.$this->escape($this->getKeywords()).'" />
                <meta name="description" content="'.$this->escape($this->getDescription()).'" />
                <link rel="apple-touch-icon" href="'.$this->getBaseUrl($this->escape($this->getAppleIcon())).'">
                <link href="'.$this->getVendorUrl('fortawesome/font-awesome/css/font-awesome.min.css').'" rel="stylesheet">
                <link href="'.$this->getStaticUrl('css/ilch.css').'" rel="stylesheet">
                <link href="'.$this->getStaticUrl('css/ui-lightness/jquery-ui.min.css').'" rel="stylesheet">
                <script type="text/javascript" src="'.$this->getStaticUrl('js/jquery.js').'"></script>
                <script type="text/javascript" src="'.$this->getStaticUrl('js/jquery-ui.min.js').'"></script>
                <script type="text/javascript" src="'.$this->getStaticUrl('js/ckeditor/ckeditor.js').'"></script>
                <script type="text/javascript" src="'.$this->getStaticUrl('js/ilch.js').'"></script>
                <script type="text/javascript" src="'.$this->getStaticUrl('js/jquery.mjs.nestedSortable.js').'"></script>
                <script type="text/javascript" src="'.$this->getStaticUrl('../application/modules/admin/static/js/functions.js').'"></script>';

        $html .= $this->header();

        if (\Ilch\DebugBar::isInitialized()) {
            $html .= \Ilch\DebugBar::getInstance()->getJavascriptRenderer()->renderHead();
        }

        if ($this->getConfigKey('cookie_consent') != 0) {
            $html .= '<script type="text/javascript">
                    window.cookieconsent_options = {
                        message: "'.$this->escape($this->getConfigKey('cookie_consent_message')).'",
                        dismiss: "OK",
                        learnMore: "Weitere Informationen",
                        link: "'.$this->getUrl(['module' => 'cookieconsent', 'controller' => 'index', 'action' => 'index']).'",
                        theme: "'.$this->getStaticUrl('js/cookieconsent/styles/'.$this->escape($this->getConfigKey('cookie_consent_style')).'-'.$this->escape($this->getConfigKey('cookie_consent_pos')).'.css').'"
                    };
                    </script>
                    <script type="text/javascript" src="'.$this->getStaticUrl('js/cookieconsent/cookieconsent.js').'"></script>';
        }

        return $html;
    }

    /**
     * Gets the custom css.
     *
     * @return string
     */
    public function getCustomCSS()
    {
        if ($this->getConfigKey('custom_css') != '') {
            $html = '<style>'.$this->getConfigKey('custom_css').'</style>';

            return $html;
        }
        return '';
    }

    /**
     * Gets the footer.
     *
     * @return string
     */
    public function getFooter()
    {
        $html = '<script>
            var ilchSmileysPlugin = "'.$this->getBaseUrl('application/modules/smilies/static/js/ilchsmileys/').'";
            var ilchSmileysPluginUrl = "'.$this->getUrl(['module' => 'smilies', 'controller' => 'iframe', 'action' => 'smilies']).'";
        </script>';

        if (\Ilch\DebugBar::isInitialized()) {
            $html .= \Ilch\DebugBar::getInstance()->getJavascriptRenderer()->render();
        }

        return $html;
    }

    /**
     * Loads a layout file.
     *
     * @param string $file
     * @param mixed[] $data
     */
    public function load($file, $data = [])
    {
        $request = $this->getRequest();
        $layout = new \Ilch\Layout\Frontend($request,
            $this->getTranslator(),
            $this->getRouter());
        $layout->setArray($data);
        $layout->setFile($this->getFile(), $this->getLayoutKey());

        echo $layout->loadScript(APPLICATION_PATH.'/'.dirname($this->getFile()).'/'.$file);
    }
}
