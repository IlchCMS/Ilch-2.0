<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout;

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
    public function getBox($moduleKey, $boxKey = '')
    {
        if (empty($boxKey)) {
            $boxKey = $moduleKey;
        }

        $class = '\\Modules\\'.ucfirst($moduleKey).'\\Boxes\\'.ucfirst($boxKey);
        $view = new \Ilch\View($this->getRequest(), $this->getTranslator(), $this->getRouter());
        $this->getTranslator()->load(APPLICATION_PATH.'/modules/'.$moduleKey.'/translations');
        $boxObj = new $class($this, $view, $this->getRequest(), $this->getRouter(), $this->getTranslator());
        $boxObj->render();

        if (file_exists(APPLICATION_PATH.'/'.dirname($this->getFile()).'/views/modules/'.$moduleKey.'/boxes/views/'.$boxKey.'.php')) {
            $viewPath = APPLICATION_PATH.'/'.dirname($this->getFile()).'/views/modules/'.$moduleKey.'/boxes/views/'.$boxKey.'.php';
        } else {
            $viewPath = APPLICATION_PATH.'/modules/'.$moduleKey.'/boxes/views/'.$boxKey.'.php';
        }

        $view->setLayoutKey($this->getLayoutKey());

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
                <link href="'.$this->getStaticUrl('css/font-awesome.min.css').'" rel="stylesheet">
                <link href="'.$this->getStaticUrl('css/ilch.css').'" rel="stylesheet">
                <link href="'.$this->getStaticUrl('css/ui-lightness/jquery-ui.css').'" rel="stylesheet">
                <script type="text/javascript" src="'.$this->getStaticUrl('js/jquery.js').'"></script>
                <script type="text/javascript" src="'.$this->getStaticUrl('js/jquery-ui.js').'"></script>
                <script type="text/javascript" src="'.$this->getStaticUrl('js/ckeditor/ckeditor.js').'"></script>
                <script type="text/javascript" src="'.$this->getStaticUrl('js/ilch.js').'"></script>
                <script type="text/javascript" src="'.$this->getStaticUrl('js/jquery.mjs.nestedSortable.js').'"></script>
                <script type="text/javascript" src="'.$this->getStaticUrl('../application/modules/admin/static/js/functions.js').'"></script>';

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