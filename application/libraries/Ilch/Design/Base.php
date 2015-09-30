<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Design;

defined('ACCESS') or die('no direct access');

abstract class Base
{
    /**
     * Holds all Helpers.
     *
     * @var array
     */
    private $helpers = array();

    /**
     * Adds view/layout helper.
     *
     * @param string $name
     * @param string $type
     * @param mixed $obj
     */
    public function addHelper($name, $type, $obj)
    {
        $this->helpers[$type][$name] = $obj;
    }

    /**
     * Gets view/layout helper.
     *
     * @param string $name
     * @param string $type
     */
    public function getHelper($name, $type)
    {
        return $this->helpers[$type][$name];
    }

    /**
     * @var Ilch_Request
     */
    private $request;

    /**
     * @var Ilch_Translator
     */
    private $translator;

    /**
     * @var Ilch_Router
     */
    private $router;

    /**
     * @var array
     */
    private $data = array();

    /**
     * @var boolean
     */
    private $modRewrite;

    /**
     * Injects request and translator to layout/view.
     *
     * @param \Ilch\Request    $request
     * @param \Ilch\Translator $translator
     * @param \Ilch\Router     $router
     */
    public function __construct(\Ilch\Request $request, \Ilch\Translator $translator, \Ilch\Router $router)
    {
        $this->request = $request;
        $this->translator = $translator;
        $this->router = $router;
    }

    /**
     * Gets view data.
     *
     * @param  string     $key
     * @return mixed|null
     */
    public function get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        return null;
    }

    /**
     * Set view data.
     *
     * @param sting $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Sets view data array.
     *
     * @param mixed[] $data
     */
    public function setArray($data = array())
    {
        $this->data = array_merge($this->data, $data);
    }

    /**
     * Gets the request object.
     *
     * @return Ilch_Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Gets the router object.
     *
     * @return \Ilch\Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Gets the translator object.
     *
     * @return Ilch_Translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * Gets the user object.
     *
     * @return User_UserModel
     */
    public function getUser()
    {
        return \Ilch\Registry::get('user');
    }

    /**
     * Returns the translated text for a specific key.
     *
     * @param string $key
     * @param [, mixed $args [, mixed $... ]]
     * @return string
     */
    public function getTrans($key)
    {
      $args = func_get_args();
      return call_user_func_array(array($this->getTranslator(), 'trans'), $args);
    }

    /**
     * Gets the base url.
     *
     * @param  sting  $url
     * @return string
     */
    public function getBaseUrl($url = '')
    {
        return BASE_URL.'/'.$url;
    }

   /**
     * Gets the layout url.
     *
     * @param string $url
     * @return string
     */
    public function getLayoutUrl($url = '')
    {
        if (empty($url)) {
            return BASE_URL.'/application/layouts/'.$this->layoutKey.'/';
        }

        return BASE_URL.'/application/layouts/'.$this->layoutKey.'/'.$url;
    }

    /**
     * Gets the module url.
     *
     * @param string $url
     * @return string
     */
    public function getModuleUrl($url = '')
    {
        if (empty($url)) {
            return BASE_URL.'/application/modules/'.$this->getRequest()->getModuleName();
        }

        return BASE_URL.'/application/modules/'.$this->getRequest()->getModuleName().'/'.$url;
    }

    /**
     * Gets the system, static url.
     *
     * @param  string $url
     * @return string
     */
    public function getStaticUrl($url = '')
    {
        if (empty($url)) {
            return BASE_URL.'/static/';
        }

        return BASE_URL.'/static/'.$url;
    }

    /**
     * Escape the given string.
     *
     * @param  string $string
     * @return string
     */
    public function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES);
    }

    /**
     * Gets html from bbcode.
     *
     * @param string $bbcode
     * @return string
     */
    public function getHtmlFromBBCode($bbcode)
    {
        require_once APPLICATION_PATH.'/libraries/jbbcode/Parser.php';

        $parser = new \JBBCode\Parser();
        $parser->addCodeDefinitionSet(new \JBBCode\DefaultCodeDefinitionSet());

        $builder = new \JBBCode\CodeDefinitionBuilder('quote', '<div class="quote">{param}</div>');
        $parser->addCodeDefinition($builder->build());
        
        $builder = new \JBBCode\CodeDefinitionBuilder('list', '<ul>{param}</ul>');
        $parser->addCodeDefinition($builder->build());
        
        $builder = new \JBBCode\CodeDefinitionBuilder('*', '<li>{param}</li>');
        $parser->addCodeDefinition($builder->build());
        
        $builder = new \JBBCode\CodeDefinitionBuilder('email', '<a href="mailto:{param}">{param}</a>');
        $parser->addCodeDefinition($builder->build());

        $builder = new \JBBCode\CodeDefinitionBuilder('img', '<img src="{param}" alt="Image">');
        $parser->addCodeDefinition($builder->build());
        
        $builder = new \JBBCode\CodeDefinitionBuilder('i', '<em>{param}</em>');
        $parser->addCodeDefinition($builder->build());
 
        $builder = new \JBBCode\CodeDefinitionBuilder('u', '<u>{param}</u>');
        $parser->addCodeDefinition($builder->build());
        
        $builder = new \JBBCode\CodeDefinitionBuilder('url', '<a href="{option}">{param}</a>');
        $builder->setUseOption(true)->setOptionValidator(new \JBBCode\validators\UrlValidator());
        $parser->addCodeDefinition($builder->build());

        $builder = new \JBBCode\CodeDefinitionBuilder('code', '<pre class="code">{param}</pre>');
        $builder->setParseContent(false);
        $parser->addCodeDefinition($builder->build());

        $parser->parse($bbcode);

        return $parser->getAsHTML();
    }

    /**
     * Creates a full url for the given parts.
     *
     * @param  array   $urlArray
     * @param  string  $route
     * @param  boolean $secure
     * @return string
     */
    public function getUrl($urlArray = array(), $route = null, $secure  = false)
    {
        $config = \Ilch\Registry::get('config');

        if($config !== null && $this->modRewrite === null) {
            $this->modRewrite = (bool)$config->get('mod_rewrite');
        }

        if (empty($urlArray)) {
            return BASE_URL;
        }

        if (is_string($urlArray)) {
            return BASE_URL.'/index.php/'.$urlArray;
        }

        $urlParts = array();

        if (!isset($urlArray['module'])) {
            $urlParts[] = $this->getRequest()->getModuleName();
        } else {
            $urlParts[] = $urlArray['module'];
            unset($urlArray['module']);
        }

        if (!isset($urlArray['controller'])) {
            $urlParts[] = $this->getRequest()->getControllerName();
        } else {
            $urlParts[] = $urlArray['controller'];
            unset($urlArray['controller']);
        }

        if (!isset($urlArray['action'])) {
            $urlParts[] = $this->getRequest()->getActionName();
        } else {
            $urlParts[] = $urlArray['action'];
            unset($urlArray['action']);
        }

        foreach ($urlArray as $key => $value) {
            $urlParts[] = $key.'/'.$value;
        }

        if ($secure) {
            $token = uniqid();
            $_SESSION['token'][$token] = $token;
            $urlParts[] = 'ilch_token/'.$token;
        }

        $s = '';

        if (($this->getRequest()->isAdmin() && $route === null) || ($route !== null && $route == 'admin')) {
            $s = 'admin/';
        }

        if ($this->modRewrite && empty($s)) {
            return BASE_URL.'/'.$s.implode('/', $urlParts);
        } else {
            return BASE_URL.'/index.php/'.$s.implode('/', $urlParts);
        }
    }

    /**
     * Gets formular hidden token input field.
     *
     * @return string
     */
    public function getTokenField()
    {
        $token = md5(uniqid());
        $_SESSION['token'][$token] = $token;

        return '<input type="hidden" name="ilch_token" value="'.$token.'" />'."\n";
    }

    /**
     * Gets the captcha image field.
     *
     * @return string
     */
    public function getCaptchaField()
    {
        $html = '<img src="'.$this->getUrl().'/application/libraries/Captcha/Captcha.php" id="captcha" />';

        return $html;
    }

    /**
     * Gets the MediaModal.
     * Place inside Javascript tag.
     * 
     * @param string $mediaButton Define Media Button by given URL
     * @param string $actionButton Define Action Button by given URL
     * @return string
     */
    public function getMedia($mediaButton = null, $actionButton = null, $inputId = null)
    {
        return  new \Ilch\Layout\Helper\GetMedia($this, $mediaButton, $actionButton, $inputId);
    }

    /**
     * Gets the page loading time in microsecond.
     *
     * @return float
     */
    public function loadTime()
    {
        $startTime = \Ilch\Registry::get('startTime');

        return microtime(true) - $startTime;
    }

    /**
     * Gets the page queries.
     *
     * @return integer
     */
    public function queryCount()
    {
        $db = \Ilch\Registry::get('db');

        return $db->queryCount();
    }

    /**
     * Limit the given string to the given length.
     *
     * @param  string  $str
     * @param  integer $length
     * @return string
     */
    public function limitString($str, $length)
    {
        if (strlen($str) <= $length) {
            return $str;
        } else {
            return preg_replace("/[^ ]*$/", '', substr($str, 0, $length)).'...';
        }
    }

    /**
     * Gets the key of the layout.
     *
     * @return string
     */
    public function getLayoutKey()
    {
        return $this->layoutKey;
    }

    /**
     * Set the key of the layout.
     *
     * @param string $layoutKey
     */
    public function setLayoutKey($layoutKey)
    {
        $this->layoutKey = $layoutKey;
    }
}
