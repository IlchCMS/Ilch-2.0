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
        $config = \Ilch\Registry::get('config');
        $metaKeywords = $this->get('metaKeywords');

        if (!empty($metaKeywords)) {
            return $metaKeywords;
        }

        if (!empty($config) && $config->get('keywords') !== '') {
            return $config->get('keywords');
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
     * Get the meta tag as string
     *
     * @param string $key
     * @return string
     */
    public function getMetaTagString($key)
    {
        $metaTagModel = $this->get('metaTags')[$key];

        // If either name or http-equiv is specified, then the content attribute must also be specified. Otherwise, it must be omitted.
        if ($metaTagModel->getName()) {
            return sprintf('<meta name="%s" content="%s">', $this->escape($metaTagModel->getName()), $this->escape($metaTagModel->getContent()));
        } else if ($metaTagModel->getHTTPEquiv()){
            return sprintf('<meta http-equiv="%s" content="%s">', $this->escape($metaTagModel->getHTTPEquiv()), $this->escape($metaTagModel->getContent()));
        } else {
            return sprintf('<meta charset="%s">', $this->escape($metaTagModel->getCharset()));
        }
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
                <meta name="description" content="'.$this->escape($this->getDescription()).'" />';

        foreach ($this->get('metaTags') as $key => $metaTag) {
            $html .= '
            '.$this->getMetaTagString($key);
        }

        $html .= '<link rel="apple-touch-icon" href="'.$this->getBaseUrl($this->escape($this->getAppleIcon())).'">
                <link href="'.$this->getVendorUrl('fortawesome/font-awesome/css/font-awesome.min.css').'" rel="stylesheet">
                <link href="'.$this->getStaticUrl('css/ilch.css').'" rel="stylesheet">
                <link href="'.$this->getVendorUrl('components/jqueryui/themes/ui-lightness/jquery-ui.min.css').'" rel="stylesheet">
                <script src="'.$this->getVendorUrl('components/jquery/jquery.min.js').'"></script>
                <script src="'.$this->getVendorUrl('components/jqueryui/jquery-ui.min.js').'"></script>
                <script src="'.$this->getVendorUrl('ckeditor/ckeditor/ckeditor.js').'"></script>
                <script src="'.$this->getStaticUrl('js/ilch.js').'"></script>
                <script src="'.$this->getStaticUrl('js/jquery.mjs.nestedSortable.js').'"></script>
                <script src="'.$this->getStaticUrl('../application/modules/admin/static/js/functions.js').'"></script>';

        $html .= $this->header();

        if (\Ilch\DebugBar::isInitialized()) {
            $html .= \Ilch\DebugBar::getInstance()->getJavascriptRenderer()->renderHead();
        }

        if ($this->getConfigKey('cookie_consent') != 0) {
            $html .= '<script>
                        window.addEventListener("load", function(){
                        window.cookieconsent.initialise({
                          "palette": {
                            "popup": {
                              "background": "'.$this->escape($this->getConfigKey('cookie_consent_popup_bg_color')).'",
                              "text": "'.$this->escape($this->getConfigKey('cookie_consent_popup_text_color')).'"
                            },
                            "button": {
                              "background": "'.$this->escape($this->getConfigKey('cookie_consent_btn_bg_color')).'",
                              "text": "'.$this->escape($this->getConfigKey('cookie_consent_btn_text_color')).'"
                            }
                          },
                          "theme": "'.$this->escape($this->getConfigKey('cookie_consent_layout')).'",
                          "position": "'.$this->escape($this->getConfigKey('cookie_consent_pos')).'",
                          "content": {
                            "message": "'.$this->getTrans('policyInfoText').'",
                            "dismiss": "'.$this->getTrans('dismissBTNText').'",
                            "link": "'.$this->getTrans('policyLinkText').'",
                            "href": "'.$this->getUrl(['module' => 'cookieconsent', 'controller' => 'index', 'action' => 'index']).'"
                          }
                        })});
                        </script>
                        <link rel="stylesheet" type="text/css" href="'.$this->getStaticUrl('js/cookieconsent/cookieconsent.min.css').'" />
                        <script src="'.$this->getStaticUrl('js/cookieconsent/cookieconsent.min.js').'"></script>';
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
