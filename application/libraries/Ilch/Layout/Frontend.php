<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Layout;

use Ilch\Accesses;
use Ilch\Request;
use Ilch\Router;
use Ilch\Translator;
use Modules\Admin\Mappers\LayoutAdvSettings;
use Modules\Admin\Mappers\Page as PageMapper;
use Modules\Admin\Models\Box;
use Modules\Admin\Models\LayoutAdvSettings as LayoutAdvSettingsModel;
use Modules\Admin\Mappers\Box as BoxMapper;

/**
 * Class Frontend
 * @package Ilch\Layout

 * @method \Ilch\Layout\Helper\Title\Model getTitle() get the title model
 * @method \Ilch\Layout\Helper\Header\Model header() get the header model
 * @method string getMenu(int $menuId, string $tpl = '', array $options = []) rendering of a menu
 * @method \Ilch\Layout\Helper\Menu\Model[] getMenus() get all menus
 * @method \Ilch\Layout\Helper\Hmenu\Model getHmenu() get the hmenu
 */
class Frontend extends Base
{
    private $settings = [];

    /**
     * Adds layout helper.
     *
     * @param Request $request
     * @param Translator $translator
     * @param Router $router
     * @param string|null $baseUrl
     * @todo adds helper dynamic from folder.
     */
    public function __construct(Request $request, Translator $translator, Router $router, ?string $baseUrl = null)
    {
        parent::__construct($request, $translator, $router, $baseUrl);

        $this->addHelper('getTitle', 'layout', new \Ilch\Layout\Helper\GetTitle());
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
    public function getFavicon(): string
    {
        $favicon = $this->getConfigKey('favicon');

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
    public function getAppleIcon(): string
    {
        $appleIcon = $this->getConfigKey('apple_icon');

        if (empty($appleIcon)) {
            return 'static/img/appleicon.png';
        }

        return $appleIcon;
    }

    /**
     * Gets page keywords from meta settings.
     *
     * @return string
     */
    public function getKeywords(): string
    {
        $metaKeywords = $this->get('metaKeywords');

        if (!empty($metaKeywords)) {
            return $metaKeywords;
        }

        return $this->getConfigKey('keywords');
    }

    /**
     * Gets page description from config or meta settings.
     *
     * @return string
     */
    public function getDescription(): string
    {
        $metaDescription = $this->get('metaDescription');

        if (!empty($metaDescription)) {
            return $metaDescription;
        }

        return $this->getConfigKey('description');
    }

    /**
     * Get the meta tag as string
     *
     * @param string $key
     * @return string
     * @since 2.1.0
     */
    public function getMetaTagString(string $key): string
    {
        /** @var Helper\MetaTag\Model $metaTagModel */
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
    public function getLinkTagString(string $key): string
    {
        /** @var Helper\LinkTag\Model $linkTagModel */
        $linkTagModel = $this->get('linkTags')[$key];

        // If the rel attribute is absent, has no keywords, or if none of the keywords used are allowed according
        // to the definitions in this specification, then the element does not create any links.
        // The href attribute must be present and must contain a valid non-empty URL potentially surrounded by spaces.
        // If the href attribute is absent, then the element does not define a link.
        // If both the href and imagesrcset attributes are absent, then the element does not define a link.
        if (empty($linkTagModel->getRel()) && empty($linkTagModel->getHref())) {
            return '';
        }

        $linkTagString = '<link';

        if ($linkTagModel->getRel()) {
            $linkTagString .= sprintf(' rel="%s"', $this->escape($linkTagModel->getRel()));
        }

        if ($linkTagModel->getHref()) {
            $linkTagString .= sprintf(' href="%s"', $this->escape($linkTagModel->getHref()));
        }

        if ($linkTagModel->getCrossorigin()) {
            $linkTagString .= sprintf(' crossorigin="%s"', $this->escape($linkTagModel->getCrossorigin()));
        }

        if ($linkTagModel->getHreflang()) {
            $linkTagString .= sprintf(' hreflang="%s"', $this->escape($linkTagModel->getHreflang()));
        }

        if (((strpos($linkTagModel->getRel(), 'icon') !== false) || (strpos($linkTagModel->getRel(), 'apple-touch-icon') !== false)) && $linkTagModel->getSizes()) {
            // The attribute must only be specified on link elements that have a rel attribute that specifies the icon keyword or the apple-touch-icon keyword.
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

        if (((strpos($linkTagModel->getRel(), 'stylesheet') !== false) || (strpos($linkTagModel->getRel(), 'preload') !== false) || (strpos($linkTagModel->getRel(), 'modulepreload') !== false)) && $linkTagModel->getIntegrity()) {
            // The attribute must only be specified on link elements that have a rel attribute that contains the stylesheet, preload, or modulepreload keyword.
            $linkTagString .= sprintf(' integrity="%s"', $this->escape($linkTagModel->getIntegrity()));
        }

        if ($linkTagModel->getReferrerpolicy()) {
            $linkTagString .= sprintf(' referrerpolicy="%s"', $this->escape($linkTagModel->getReferrerpolicy()));
        }

        if ((strpos($linkTagModel->getRel(), 'preload') !== false) && (strcasecmp($linkTagModel->getAs(), 'image') === 0)) {
            // The imagesrcset and imagesizes attributes must only be specified on link elements that have both a rel attribute that specifies the preload keyword, as well as an as attribute in the "image" state.
            if ($linkTagModel->getImagesrcset()) {
                $linkTagString .= sprintf(' imagesrcset="%s"', $this->escape($linkTagModel->getImagesrcset()));
            }

            if ($linkTagModel->getImagesizes()) {
                $linkTagString .= sprintf(' imagesizes="%s"', $this->escape($linkTagModel->getImagesizes()));
            }
        }

        if (((strpos($linkTagModel->getRel(), 'preload') !== false) || (strpos($linkTagModel->getRel(), 'modulepreload') !== false)) && $linkTagModel->getAs()) {
            // The attribute must be specified on link elements that have a rel attribute that contains the preload keyword.
            // It may be specified on link elements that have a rel attribute that contains the modulepreload keyword; in such cases it must have a value which is a script-like destination.
            // For other link elements, it must not be specified.
            $linkTagString .= sprintf(' as="%s"', $this->escape($linkTagModel->getAs()));
        }

        if ((strpos($linkTagModel->getRel(), 'stylesheet') !== false) && $linkTagModel->getBlocking()) {
            // It is used by link type stylesheet, and it must only be specified on link elements that have a rel attribute containing that keyword.
            $linkTagString .= sprintf(' blocking="%s"', $this->escape($linkTagModel->getBlocking()));
        }

        if ((strpos($linkTagModel->getRel(), 'mask-icon') !== false) && $linkTagModel->getColor()) {
            // The attribute must only be specified on link elements that have a rel attribute that contains the mask-icon keyword.
            $linkTagString .= sprintf(' color="%s"', $this->escape($linkTagModel->getColor()));
        }

        if ($linkTagModel->isDisabled()) {
            $linkTagString .= ' disabled';
        }

        if ($linkTagModel->getFetchpriority()) {
            $linkTagString .= sprintf(' fetchpriority="%s"', $this->escape($linkTagModel->getFetchpriority()));
        }

        return $linkTagString . '>';
    }

    /**
     * Get the script tag as string.
     *
     * @param string $key
     * @return string
     * @since 2.1.50
     */
    public function getScriptTagString(string $key): string
    {
        /** @var Helper\ScriptTag\Model $scriptTagModel */
        $scriptTagModel = $this->get('scriptTags')[$key];
        $scriptTagString = '<script';

        // When used to include data blocks, the data must be embedded inline, the format of the data must be given using the type attribute,
        // and the contents of the script element must conform to the requirements defined for the format used.
        if ($scriptTagModel->getType()) {
            $scriptTagString .= sprintf(' type="%s"', $this->escape($scriptTagModel->getType()));
        }

        // For import map script elements, the src, async, nomodule, defer, crossorigin, integrity, and referrerpolicy attributes must not be specified.
        // A document must not have more than one import map script element.
        // For data blocks: The src, async, nomodule, defer, crossorigin, integrity, referrerpolicy, and fetchpriority attributes must not be specified.
        if (strcasecmp($scriptTagModel->getType(), 'importmap') !== 0 && !$scriptTagModel->isDataBlock()) {
            if ($scriptTagModel->getSrc()) {
                $scriptTagString .= sprintf(' src="%s"', $this->escape($scriptTagModel->getSrc()));
            }

            if ($scriptTagModel->getCrossorigin()) {
                $scriptTagString .= sprintf(' crossorigin="%s"', $this->escape($scriptTagModel->getCrossorigin()));
            }

            if ($scriptTagModel->getIntegrity() && $scriptTagModel->getSrc()) {
                // The integrity attribute must not be specified when the src attribute is not specified.
                $scriptTagString .= sprintf(' integrity="%s"', $this->escape($scriptTagModel->getIntegrity()));
            }

            if ($scriptTagModel->getReferrerpolicy()) {
                $scriptTagString .= sprintf(' referrerpolicy="%s"', $this->escape($scriptTagModel->getReferrerpolicy()));
            }

            if ($scriptTagModel->isAsync() && $scriptTagModel->getSrc() || ($scriptTagModel->isAsync() && strcasecmp($scriptTagModel->getType(), 'module') === 0)) {
                // Classic scripts may specify defer or async, but must not specify either unless the src attribute is present.
                // Module scripts are not affected by the defer attribute, but are affected by the async attribute (regardless of the state of the src attribute).
                $scriptTagString .= ' async';
            }

            if ($scriptTagModel->isDefer() && $scriptTagModel->getSrc() && strcasecmp($scriptTagModel->getType(), 'module') !== 0) {
                // Classic scripts may specify defer or async, but must not specify either unless the src attribute is present.
                // Module scripts may specify the async attribute, but must not specify the defer attribute.
                $scriptTagString .= ' defer';
            }

            if ($scriptTagModel->isNomodule() && strcasecmp($scriptTagModel->getType(), 'module') !== 0) {
                // The nomodule attribute must not be specified on module scripts (and will be ignored if it is).
                $scriptTagString .= ' nomodule';
            }
        }

        if ($scriptTagModel->getBlocking()) {
            // As of implementing this there is just one valid value ("render"), but implement this with more
            // valid values in the future in mind.
            $scriptTagString .= sprintf(' blocking="%s"', $this->escape($scriptTagModel->getBlocking()));
        }

        if ($scriptTagModel->getFetchpriority() && !$scriptTagModel->isDataBlock()) {
            $scriptTagString .= sprintf(' fetchpriority="%s"', $this->escape($scriptTagModel->getFetchpriority()));
        }

        $scriptTagString .= '>';

        if (!$scriptTagModel->getSrc() || strcasecmp($scriptTagModel->getType(), 'importmap') === 0 || $scriptTagModel->isDataBlock()) {
            // This can be inline code, import map JSON representation format, data block, ...
            // Normally if a source is specified there can't be inline "text", but allow this for import maps and data blocks
            // if the type points to an import map or a data block.
            $scriptTagString .= sprintf('%s', $scriptTagModel->getInline());
        }

        $scriptTagString .= '</script>';
        return $scriptTagString;
    }

    /**
     * Get key from config.
     *
     * @param string $key
     * @return string
     */
    public function getConfigKey(string $key): string
    {
        /** @var \Ilch\Config\Database $config */
        $config = \Ilch\Registry::get('config');

        if (!empty($config) && $key !== '') {
            return $config->get($key) ?? '';
        }

        return '';
    }

    /**
     * Gets the box with the given key.
     *
     * @param string $moduleKey
     * @param string $boxKey
     * @param null|string $customView
     * @return string
     */
    public function getBox(string $moduleKey, string $boxKey = '', ?string $customView = null): string
    {
        $accessMapper = new Accesses($this->getRequest());

        if ($accessMapper->hasAccess('Module', $moduleKey, $accessMapper::TYPE_MODULE)) {
            if (empty($boxKey)) {
                $boxKey = $moduleKey;
            }

            $class = '\\Modules\\' . ucfirst($moduleKey) . '\\Boxes\\' . ucfirst($boxKey);
            $view = new \Ilch\View($this->getRequest(), $this->getTranslator(), $this->getRouter());
            $this->getTranslator()->load(APPLICATION_PATH . '/modules/' . $moduleKey . '/translations');
            $boxObj = new $class($this, $view, $this->getRequest(), $this->getRouter(), $this->getTranslator());
            $boxObj->render();

            if ($customView !== null) {
                $viewPath = APPLICATION_PATH . '/' . dirname($this->getFile()) . '/views/modules/' . $moduleKey . '/boxes/views/' . $customView . '.php';
            } elseif (file_exists(APPLICATION_PATH . '/' . dirname($this->getFile()) . '/views/modules/' . $moduleKey . '/boxes/views/' . $boxKey . '.php')) {
                $viewPath = APPLICATION_PATH . '/' . dirname($this->getFile()) . '/views/modules/' . $moduleKey . '/boxes/views/' . $boxKey . '.php';
            } else {
                $viewPath = APPLICATION_PATH . '/modules/' . $moduleKey . '/boxes/views/' . $boxKey . '.php';
            }

            $view->setLayoutKey($this->getLayoutKey());
            $view->setBoxUrl('application/modules/' . $moduleKey);

            return $view->loadScript($viewPath);
        }
        return '';
    }

    /**
     * Gets the self box with the given id.
     *
     * @param int $id
     * @param string|null $tpl
     * @return string|Box|null
     * @since 2.1.42
     */
    public function getSelfBoxById(int $id, ?string $tpl = null)
    {
        $accessMapper = new Accesses($this->getRequest());

        if ($accessMapper->hasAccess('Module', $id, $accessMapper::TYPE_BOX)) {
            /** @var \Ilch\Config\Database $config */
            $config = \Ilch\Registry::get('config');
            $locale = '';

            if ((bool)$config->get('multilingual_acp') && $this->getTranslator()->getLocale() != $config->get('content_language')) {
                $locale = $this->getTranslator()->getLocale();
            }
            $boxMapper = new BoxMapper();
            $model = $boxMapper->getSelfBoxByIdLocale($id, $locale);
            if ($model) {
                if ($tpl) {
                    return str_replace(['%s', '%c'], [$this->escape($model->getTitle()), $model->getContent()], $tpl);
                } else {
                    return $model;
                }
            }
        }
        return $tpl ? '' : null;
    }

    /**
     * Gets the self Page with the given id.
     *
     * @param int $id
     * @param string|null $tpl
     * @return \Modules\Admin\Models\Page|string|null
     * @since 2.1.51
     */
    public function getSelfPageById(int $id, ?string $tpl = null)
    {
        $accessMapper = new Accesses($this->getRequest());
        if ($accessMapper->hasAccess('Module', $id, $accessMapper::TYPE_PAGE)) {
            /** @var \Ilch\Config\Database $config */
            $config = \Ilch\Registry::get('config');
            $locale = '';

            if ((bool)$config->get('multilingual_acp') && $this->getTranslator()->getLocale() != $config->get('content_language')) {
                $locale = $this->getTranslator()->getLocale();
            }

            $pageMapper = new PageMapper();
            $page = $pageMapper->getPageByIdLocale($id, $locale);

            if ($tpl) {
                return str_replace(['%s', '%c'], [$this->escape($page->getTitle()), $page->getContent()], $tpl);
            } else {
                return $page;
            }
        }
        return $tpl ? '' : null;
    }

    /**
     * Gets the self Page with the given id.
     *
     * @param string $moduleName
     * @param string|null $tpl
     * @param string $controllerName
     * @param string $actionName
     * @return string|null
     * @since 2.1.51
     */
    public function getModuleContent(string $moduleName, ?string $tpl = null, string $controllerName = 'index', string $actionName = 'index'): ?string
    {
        $findSub = strpos($controllerName, '_');
        $dir = '';

        if ($findSub !== false) {
            $controllerParts = explode('_', $controllerName);
            $controllerName = $controllerParts[1];
            $dir = ucfirst($controllerParts[0]) . '\\';
        }

        $accessMapper = new Accesses($this->getRequest());

        if ($accessMapper->hasAccess('Module', $moduleName, $accessMapper::TYPE_MODULE) && $this->getRequest()->getModuleName() != $moduleName) {
            $controller = '\\Modules\\' . ucfirst($moduleName) . '\\Controllers\\' . $dir . ucfirst($controllerName);

            $request = new Request(false);
            $request->setModuleName($moduleName)->setControllerName($controllerName)->setActionName($actionName);
            $router = new Router($request);
            $view = new \Ilch\View($request, $this->getTranslator(), $router);
            $frontend = new Frontend($request, $view->getTranslator(), $router);

            // Check if module exists.
            if (is_dir(APPLICATION_PATH . '/modules/' . $moduleName) && class_exists($controller)) {
                $controller = new $controller($frontend, $view, $request, $router, $frontend->getTranslator());
                $action = $actionName . 'Action';
                if (method_exists($controller, 'init')) {
                    $controller->init();
                }

                if (method_exists($controller, $action)) {
                    $controller->$action();
                }
                $this->getTranslator()->load(APPLICATION_PATH . '/modules/' . $moduleName . '/translations');

                $viewPath = APPLICATION_PATH . '/' . dirname($controller->getLayout()->getFile()) . '/views/modules/' . $moduleName . '/' . $dir . $controllerName . '/' . $actionName . '.php';

                if (!file_exists($viewPath)) {
                    $viewPath = APPLICATION_PATH . '/modules/' . $moduleName . '/views/' . $dir . $controllerName . '/' . $actionName . '.php';
                }

                $viewOutput = $view->loadScript($viewPath);

                if ($tpl) {
                    return str_replace('%c', $viewOutput, $tpl);
                } else {
                    return $viewOutput;
                }
            }
        }
        return $tpl ? '' : null;
    }

    /**
     * Gets the header.
     *
     * @return string
     */
    public function getHeader(): string
    {
        $html = '<meta charset="utf-8">
                <title>' . $this->escape($this->getTitle()) . '</title>
                <link rel="icon" href="' . $this->getBaseUrl($this->escape($this->getFavicon())) . '" type="image/x-icon">
                <meta name="keywords" content="' . $this->escape($this->getKeywords()) . '" />
                <meta name="description" content="' . $this->escape($this->getDescription()) . '" />';

        if (is_array($this->get('metaTags'))) {
            foreach ($this->get('metaTags') as $key => $metaTag) {
                $html .= '
                ' . $this->getMetaTagString($key);
            }
        }

        if (is_array($this->get('linkTags'))) {
            foreach ($this->get('linkTags') as $key => $linkTag) {
                $html .= '
                ' . $this->getLinkTagString($key);
            }
        }

        $html .= '
                <link rel="apple-touch-icon" href="' . $this->getBaseUrl($this->escape($this->getAppleIcon())) . '">
                <link href="' . $this->getVendorUrl('fortawesome/font-awesome/css/all.min.css') . '" rel="stylesheet">
                <link href="' . $this->getVendorUrl('fortawesome/font-awesome/css/v4-shims.min.css') . '" rel="stylesheet">
                <link href="' . $this->getStaticUrl('css/ilch.css') . '" rel="stylesheet">
                <link href="' . $this->getVendorUrl('npm-asset/jquery-ui/dist/themes/ui-lightness/jquery-ui.min.css') . '" rel="stylesheet">
                <link href="' . $this->getVendorUrl('ckeditor/ckeditor/plugins/codesnippet/lib/highlight/styles/default.css') . '" rel="stylesheet">
                <script src="' . $this->getVendorUrl('npm-asset/jquery/dist/jquery.min.js') . '"></script>
                <script src="' . $this->getVendorUrl('npm-asset/jquery-ui/dist/jquery-ui.min.js') . '"></script>
                <script src="' . $this->getVendorUrl('ckeditor/ckeditor/ckeditor.js') . '"></script>
                <script src="' . $this->getStaticUrl('js/ilch.js') . '"></script>
                <script src="' . $this->getStaticUrl('js/jquery.mjs.nestedSortable.js') . '"></script>
                <script src="' . $this->getStaticUrl('../application/modules/admin/static/js/functions.js') . '"></script>
                <script src="' . $this->getVendorUrl('ckeditor/ckeditor/plugins/codesnippet/lib/highlight.pack.js') . '"></script>
                ';

        if (is_array($this->get('scriptTags'))) {
            foreach ($this->get('scriptTags') as $key => $scriptTag) {
                $html .= '
                ' . $this->getScriptTagString($key);
            }
        }

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
                              "background": "' . $this->escape($this->getConfigKey('cookie_consent_popup_bg_color')) . '",
                              "text": "' . $this->escape($this->getConfigKey('cookie_consent_popup_text_color')) . '"
                            },
                            "button": {
                              "background": "' . $this->escape($this->getConfigKey('cookie_consent_btn_bg_color')) . '",
                              "text": "' . $this->escape($this->getConfigKey('cookie_consent_btn_text_color')) . '"
                            }
                          },
                          "theme": "' . $this->escape($this->getConfigKey('cookie_consent_layout')) . '",
                          "position": "' . $this->escape($this->getConfigKey('cookie_consent_pos')) . '",
                          "type": "' . $this->escape($this->getConfigKey('cookie_consent_type')) . '",
                          "content": {
                            "message": "' . $this->getTrans('policyInfoText') . '",
                            "dismiss": "' . $this->getTrans('dismissBTNText') . '",
                            "allow": "' . $this->getTrans('allowBTNText') . '",
                            "deny": "' . $this->getTrans('denyBTNText') . '",
                            "link": "' . $this->getTrans('policyLinkText') . '",
                            "href": "' . $this->getUrl(['module' => 'privacy', 'controller' => 'index', 'action' => 'index']) . '"
                          }
                        })});
                        </script>
                        <link rel="stylesheet" type="text/css" href="' . $this->getStaticUrl('js/cookieconsent/cookieconsent.min.css') . '" />
                        <script src="' . $this->getStaticUrl('js/cookieconsent/cookieconsent.min.js') . '"></script>';
        }

        return $html;
    }

    /**
     * Gets the custom css.
     *
     * @return string
     */
    public function getCustomCSS(): string
    {
        if ($this->getConfigKey('custom_css') !== '') {
            return '<style>' . $this->getConfigKey('custom_css') . '</style>';
        }
        return '';
    }

    /**
     * Gets the footer.
     *
     * @return string
     */
    public function getFooter(): string
    {
        $html = '';

        if (\Ilch\DebugBar::isInitialized()) {
            $html .= \Ilch\DebugBar::getInstance()->getJavascriptRenderer()->render()
                  . '<script>hljs.initHighlightingOnLoad();</script>';
        }

        return $html;
    }

    /**
     * Loads a layout file.
     *
     * @param string $file
     * @param array $data
     * @return void
     */
    public function load(string $file, array $data = [])
    {
        $request = $this->getRequest();
        $layout = new Frontend(
            $request,
            $this->getTranslator(),
            $this->getRouter()
        );
        $layout->setArray($data);
        $layout->setFile($this->getFile(), $this->getLayoutKey());
        $this->loadSettings();

        $layout->loadScript(APPLICATION_PATH . '/' . dirname($this->getFile()) . '/' . $file);
    }

    /**
     * Load settings made by an administrator if they exist or load default ones from layout.
     *
     * @since 2.1.32
     */
    public function loadSettings()
    {
        $advSettingsMapper = new LayoutAdvSettings();

        $settings = $advSettingsMapper->getSettings($this->getLayoutKey());
        if (empty($settings)) {
            // load default values
            $layoutPath = APPLICATION_PATH . '/layouts/' . $this->getLayoutKey();
            if (is_dir($layoutPath)) {
                $configClass = '\\Layouts\\' . ucfirst(basename($layoutPath)) . '\\Config\\Config';
                $config = new $configClass($this->getTranslator());
                if (!empty($config->config['settings'])) {
                    foreach ($config->config['settings'] as $key => $value) {
                        if ($value['type'] !== 'separator') {
                            $this->settings[$key] = $value['default'];
                        }
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
    public function getLayoutSetting(string $key): string
    {
        if (empty($this->settings[$key])) {
            // That specific setting seems to be not loaded. Try to load default value.
            $layoutPath = APPLICATION_PATH . '/layouts/' . $this->getLayoutKey();
            if (is_dir($layoutPath)) {
                $configClass = '\\Layouts\\' . ucfirst(basename($layoutPath)) . '\\Config\\Config';
                $config = new $configClass($this->getTranslator());

                if (empty($config->config['settings'][$key])) {
                    throw new \InvalidArgumentException('A setting with the key "' . $key . '" doesn\'t exist for this layout.');
                }

                if ($config->config['settings'][$key]['type'] === 'separator') {
                    throw new \InvalidArgumentException($key . '" is a seperator and not a setting with a value.');
                }

                $this->settings[$key] = $config->config['settings'][$key]['default'];
            }
        }

        if ($this->settings[$key] instanceof LayoutAdvSettingsModel) {
            return $this->settings[$key]->getValue();
        }

        return $this->settings[$key];
    }
}
