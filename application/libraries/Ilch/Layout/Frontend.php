<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Layout;

use Ilch\Request;
use Ilch\Router;
use Ilch\Translator;
use Modules\Admin\Mappers\LayoutAdvSettings;
use Modules\Admin\Models\LayoutAdvSettings as LayoutAdvSettingsModel;

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
    private $settings = [];

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
     * @since 2.1.0
     */
    public function getMetaTagString($key)
    {
        $metaTagModel = $this->get('metaTags')[$key];

        // If either name or http-equiv is specified, then the content attribute must also be specified. Otherwise, it must be omitted.
        if ($metaTagModel->getName()) {
            return sprintf('<meta name="%s" content="%s">', $this->escape($metaTagModel->getName()), $this->escape($metaTagModel->getContent()));
        }

        if ($metaTagModel->getHTTPEquiv()) {
            return sprintf('<meta http-equiv="%s" content="%s">', $this->escape($metaTagModel->getHTTPEquiv()), $this->escape($metaTagModel->getContent()));
        }

        return sprintf('<meta charset="%s">', $this->escape($metaTagModel->getCharset()));
    }

    /**
     * Get the link tag as string.
     *
     * @param string $key
     * @return string
     * @since 2.1.22
     */
    public function getLinkTagString($key)
    {
        $linkTagModel = $this->get('linkTags')[$key];

        // If the rel attribute is absent, has no keywords, or if none of the keywords used are allowed according
        // to the definitions in this specification, then the element does not create any links.
        // The href attribute must be present and must contain a valid non-empty URL potentially surrounded by spaces.
        // If the href attribute is absent, then the element does not define a link.
        if (empty($linkTagModel->getRel()) || empty($linkTagModel->getHref())) {
            return '';
        }

        $linkTagString = sprintf('<link rel="%s" href="%s"', $this->escape($linkTagModel->getRel()), $this->escape($linkTagModel->getHref()));

        if ($linkTagModel->getCrossorigin()) {
            $linkTagString .= sprintf(' crossorigin="%s"', $this->escape($linkTagModel->getCrossorigin()));
        }

        if ($linkTagModel->getHreflang()) {
            $linkTagString .= sprintf(' hreflang="%s"', $this->escape($linkTagModel->getHreflang()));
        }

        if ($linkTagModel->getSizes()) {
            $linkTagString .= sprintf(' sizes="%s"', $this->escape($linkTagModel->getSizes()));
        }

        if ($linkTagModel->getType()) {
            $linkTagString .= sprintf(' type="%s"', $this->escape($linkTagModel->getType()));
        }

        if ($linkTagModel->getMedia()) {
            $linkTagString .= sprintf(' media="%s"', $this->escape($linkTagModel->getMedia()));
        }

        if ($linkTagModel->getTitle()) {
            $linkTagString .= sprintf(' title="%s"', $this->escape($linkTagModel->getTitle()));
        }

        return $linkTagString . '>';
    }

    /**
     * Get key from config.
     *
     * @param $key
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

        if ($customView !== null) {
            $viewPath = APPLICATION_PATH.'/'.dirname($this->getFile()).'/views/modules/'.$moduleKey.'/boxes/views/'.$customView.'.php';
        } elseif (file_exists(APPLICATION_PATH.'/'.dirname($this->getFile()).'/views/modules/'.$moduleKey.'/boxes/views/'.$boxKey.'.php')) {
            $viewPath = APPLICATION_PATH.'/'.dirname($this->getFile()).'/views/modules/'.$moduleKey.'/boxes/views/'.$boxKey.'.php';
        } else {
            $viewPath = APPLICATION_PATH.'/modules/'.$moduleKey.'/boxes/views/'.$boxKey.'.php';
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

        if (is_array($this->get('metaTags'))) {
            foreach ($this->get('metaTags') as $key => $metaTag) {
                $html .= '
                '.$this->getMetaTagString($key);
            }
        }

        if (is_array($this->get('linkTags'))) {
            foreach ($this->get('linkTags') as $key => $linkTag) {
                $html .= '
                '.$this->getLinkTagString($key);
            }
        }

        $html .= '
                <link rel="apple-touch-icon" href="'.$this->getBaseUrl($this->escape($this->getAppleIcon())).'">
                <link href="'.$this->getVendorUrl('fortawesome/font-awesome/css/all.min.css').'" rel="stylesheet">
                <link href="'.$this->getVendorUrl('fortawesome/font-awesome/css/v4-shims.min.css').'" rel="stylesheet">
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
                          "type": "'.$this->escape($this->getConfigKey('cookie_consent_type')).'",
                          "content": {
                            "message": "'.$this->getTrans('policyInfoText').'",
                            "dismiss": "'.$this->getTrans('dismissBTNText').'",
                            "allow": "'.$this->getTrans('allowBTNText').'",
                            "deny": "'.$this->getTrans('denyBTNText').'",
                            "link": "'.$this->getTrans('policyLinkText').'",
                            "href": "'.$this->getUrl(['module' => 'privacy', 'controller' => 'index', 'action' => 'index']).'"
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
        if ($this->getConfigKey('custom_css') !== '') {
            return '<style>'.$this->getConfigKey('custom_css').'</style>';
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
        $html = '';

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
        $this->loadSettings();

        echo $layout->loadScript(APPLICATION_PATH.'/'.dirname($this->getFile()).'/'.$file);
    }

    /**
     * Load settings made by an administrator if they exist or load default ones from layout.
     *
     * @since 2.1.32
     */
    public function loadSettings()
    {
        file_put_contents('php://stderr', print_r('Call of loadSettings'.PHP_EOL, TRUE));
        $advSettingsMapper = new LayoutAdvSettings();

        $settings = $advSettingsMapper->getSettings($this->getLayoutKey());
        if (empty($settings)) {
            // load default values
            $layoutPath = APPLICATION_PATH.'/layouts/'.$this->getLayoutKey();
            if (is_dir($layoutPath)) {
                $configClass = '\\Layouts\\' . ucfirst(basename($layoutPath)) . '\\Config\\Config';
                $config = new $configClass($this->getTranslator());
                if (!empty($config->config['settings'])) {
                    file_put_contents('php://stderr', print_r('Expensive call of loadSettings'.PHP_EOL, TRUE));
                    foreach($config->config['settings'] as $key => $value) {
                        $this->settings[$key] = $value['default'];
                    }
                }
            }
        } else {
            $this->settings = $settings;
        }
    }

    /**
     * Get value of setting specified by key.
     *
     * @param string $key name of setting
     * @return string
     * @since 2.1.32
     */
    public function getLayoutSetting($key)
    {
        if (empty($this->settings[$key])) {
            throw new \InvalidArgumentException('A setting with this key doesn\'t exist for this layout.');
        }

        if ($this->settings[$key] instanceof LayoutAdvSettingsModel) {
            return $this->settings[$key]->getValue();
        }

        return $this->settings[$key];
    }
}
