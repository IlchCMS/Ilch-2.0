<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Design;

use Ilch\Request;
use Ilch\Router;
use Ilch\Translator;

abstract class Base
{
    /**
     * Holds all Helpers.
     *
     * @var array
     */
    private $helpers = [];

    /**
     * Name of the layout that will be used
     *
     * @var string
     */
    private $layoutKey;

    /**
     * Box url that will be used
     *
     * @var string
     */
    private $boxUrl;

    /**
     * Base url
     *
     * @var string
     */
    private $baseUrl;

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
     * @return mixed
     */
    public function getHelper($name, $type)
    {
        return $this->helpers[$type][$name];
    }

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var boolean
     */
    private $modRewrite;

    /**
     * @var \HTMLPurifier_Config default object.
     */
    private $purifierConfig;

    /**
     * @var \HTMLPurifier
     */
    private $purifier;

    /**
     * Get object of HTML Purifier with default configuration.
     *
     * @return \HTMLPurifier
     */
    public function getPurifier()
    {
        return $this->purifier;
    }

    /**
     * Use HTMLPurifier to purify the content.
     * Takes the "disable_purifier" setting into account.
     *
     * @param string $content
     * @return string
     */
    public function purify($content)
    {
        $config = \Ilch\Registry::get('config');

        if ($config->get('disable_purifier')) {
            return $content;
        }

        return $this->getPurifier()->purify($content);
    }

    /**
     * Injects request and translator to layout/view.
     *
     * @param Request $request
     * @param Translator $translator
     * @param Router $router
     * @param string|null $baseUrl
     */
    public function __construct(Request $request, Translator $translator, Router $router, $baseUrl = null)
    {
        $this->request = $request;
        $this->translator = $translator;
        $this->router = $router;
        if ($baseUrl === null) {
            $baseUrl = BASE_URL;
        }
        $this->baseUrl = $baseUrl;

        $this->purifierConfig = \HTMLPurifier_Config::createDefault();
        $this->purifierConfig->set('Filter.YouTube', true);
        $this->purifierConfig->set('HTML.SafeIframe', true);
        $this->purifierConfig->set('URI.SafeIframeRegexp','%^https://(www.youtube.com/embed/|www.youtube-nocookie.com/embed/|player.vimeo.com/video/|)%');
        $this->purifierConfig->set('Attr.AllowedFrameTargets', '_blank, _self, _target, _parent');
        $this->purifierConfig->set('Attr.EnableID', true);
        $this->purifierConfig->set('AutoFormat.Linkify', true);
        $def = $this->purifierConfig->getHTMLDefinition(true);
        $def->addAttribute('iframe', 'allowfullscreen', 'Bool');
        $def->addElement('video', 'Block', 'Optional: (source, Flow) | (Flow, source) | Flow', 'Common', array(
            'autoplay' => 'Bool',
            'src' => 'URI',
            'width' => 'Length',
            'height' => 'Length',
            'preload' => 'Enum#auto,metadata,none',
            'controls' => 'Bool',
        ));
        $def->addElement('source', 'Block', 'Flow', 'Common', array(
            'src' => 'URI',
            'type' => 'Text',
        ));
        $this->purifier = new \HTMLPurifier($this->purifierConfig);
    }

    /**
     * Adds value with specific key ($objectKey) to an array in view data.
     *
     * @param string $key
     * @param string $objectKey
     * @param mixed $value
     *
     * @return bool
     * @throws \RuntimeException
     * @since 2.1.0
     */
    public function add($key, $objectKey, $value)
    {
        if (empty($this->data[$key])) {
            $this->data[$key] = [];
        }

        if (is_array($this->data[$key])) {
            $this->data[$key][$objectKey] = $value;

            return true;
        }

        throw new \RuntimeException('Unable to add value. The value of `' . $key . '` is not an array.');
    }

    /**
     * Gets view data.
     *
     * @param  string     $key
     * @return mixed|null
     */
    public function get($key)
    {
        return $this->data[$key] ?? null;
    }

    /**
     * Set view data.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Sets view data array.
     *
     * @param mixed[] $data
     */
    public function setArray($data = [])
    {
        $this->data = array_merge($this->data, $data);
    }

    /**
     * Gets the request object.
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Gets the router object.
     *
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Gets the translator object.
     *
     * @return Translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * Gets the user object.
     *
     * @return \Modules\User\Models\User
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
      return $this->getTranslator()->trans(...$args);
    }

    /**
     * Returns the translated text for a specific key of a specific layout.
     * Added for usage in the advanced layout settings feature.
     *
     * @param string $layoutKey
     * @param string $key
     * @param [, mixed $args [, mixed $... ]]
     * @return string
     * @since 2.1.32
     */
    public function getOtherLayoutTrans($layoutKey, $key)
    {
        $args = func_get_args();
        return $this->getTranslator()->transOtherLayout(...$args);
    }

    /**
     * Returns an amount of money of the currency supplied formatted in locale-typical style.
     *
     * @param float $amount
     * @param string $currencyCode (ISO 4217)
     * @return string
     */
    public function getFormattedCurrency($amount, $currencyCode)
    {
        return $this->getTranslator()->getFormattedCurrency($amount, $currencyCode);
    }

    /**
     * Gets the base url.
     *
     * @param  string  $url
     * @return string
     */
    public function getBaseUrl($url = '')
    {
        return $this->baseUrl . '/' . $url;
    }

   /**
     * Gets the layout url.
     *
     * @param string $url
     * @return string
     */
    public function getLayoutUrl($url = '')
    {
        return $this->getBaseUrl('application/layouts/'.$this->getLayoutKey().'/'.$url);
    }

    /**
     * Gets the module url.
     *
     * @param string $url
     * @return string
     */
    public function getModuleUrl($url = '')
    {
        if (!empty($url)) {
            $url = '/' . $url;
        }

        return $this->getBaseUrl('application/modules/' . $this->getRequest()->getModuleName() . $url);
    }

    /**
     * Gets the system, static url.
     *
     * @param  string $url
     * @return string
     */
    public function getStaticUrl($url = '')
    {
        return $this->getBaseUrl('static/' . $url);
    }

    /**
     * Gets the system, vendor url.
     *
     * @param  string $url
     * @return string
     */
    public function getVendorUrl($url = '')
    {
        return $this->getBaseUrl('vendor/' . $url);
    }

    /**
     * Escape the given string.
     *
     * @param  string $string
     * @return string
     */
    public function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8', false);
    }

    /**
     * Gets html from bbcode.
     *
     * @param string $bbcode
     * @return string
     */
    public function getHtmlFromBBCode($bbcode)
    {
        $parser = new \JBBCode\Parser();
        //test without default
        //$parser->addCodeDefinitionSet(new \JBBCode\DefaultCodeDefinitionSet());
        //$parser->addCodeDefinition(new \Ilch\BBCode\CodeHelper());

        $urlValidator = new \JBBCode\validators\UrlValidator();

        $builder = new \JBBCode\CodeDefinitionBuilder('b', '<strong>{param}</strong>');
        $parser->addCodeDefinition($builder->build());

        $builder = new \JBBCode\CodeDefinitionBuilder('i', '<em>{param}</em>');
        $parser->addCodeDefinition($builder->build());

        $builder = new \JBBCode\CodeDefinitionBuilder('u', '<u>{param}</u>');
        $parser->addCodeDefinition($builder->build());

        $builder = new \JBBCode\CodeDefinitionBuilder('s', '<s>{param}</s>');
        $parser->addCodeDefinition($builder->build());

        $builder = new \JBBCode\CodeDefinitionBuilder('center', '<p style="text-align: center;">{param}</p>');
        $parser->addCodeDefinition($builder->build());

        $builder = new \JBBCode\CodeDefinitionBuilder('right', '<p style="text-align: right;">{param}</p>');
        $parser->addCodeDefinition($builder->build());

        $builder = new \JBBCode\CodeDefinitionBuilder('justify', '<p style="text-align: justify;">{param}</p>');
        $parser->addCodeDefinition($builder->build());

        $builder = new \JBBCode\CodeDefinitionBuilder('url', '<a target="_blank" href="{param}" rel="noopener">{param}</a>');
        $builder->setParseContent(false)->setBodyValidator($urlValidator);
        $parser->addCodeDefinition($builder->build());

        $builder = new \JBBCode\CodeDefinitionBuilder('url', '<a target="_blank" href="{option}" rel="noopener">{param}</a>');
        $builder->setUseOption(true)->setParseContent(true)->setOptionValidator($urlValidator);
        $parser->addCodeDefinition($builder->build());

        $builder = new \JBBCode\CodeDefinitionBuilder('img', '<img src="{param}" />');
        $builder->setUseOption(false)->setParseContent(false)->setBodyValidator($urlValidator);
        $parser->addCodeDefinition($builder->build());

        $builder = new \JBBCode\CodeDefinitionBuilder('img', '<img src="{param}" alt="{option}" />');
        $builder->setUseOption(true)->setParseContent(false)->setBodyValidator($urlValidator);
        $parser->addCodeDefinition($builder->build());

        $builder = new \JBBCode\CodeDefinitionBuilder('color', '<span style="color: {option}">{param}</span>');
        $builder->setUseOption(true)->setOptionValidator(new \JBBCode\validators\CssColorValidator());
        $parser->addCodeDefinition($builder->build());

        $builder = new \JBBCode\CodeDefinitionBuilder('quote', '<blockquote>{param}</blockquote>');
        $parser->addCodeDefinition($builder->build());

        $builder = new \JBBCode\CodeDefinitionBuilder('list', '<ul>{param}</ul>');
        $parser->addCodeDefinition($builder->build());

        $builder = new \JBBCode\CodeDefinitionBuilder('list', '<ol>{param}</ol>');
        $builder->setUseOption(true);
        $parser->addCodeDefinition($builder->build());

        $builder = new \JBBCode\CodeDefinitionBuilder('*', '<li>{param}</li>');
        $parser->addCodeDefinition($builder->build());

        $builder = new \JBBCode\CodeDefinitionBuilder('email', '<a href="mailto:{param}">{param}</a>');
        $parser->addCodeDefinition($builder->build());

        $builder = new \JBBCode\CodeDefinitionBuilder('size', '<span style="font-size:{option}%;">{param}</span>');
        $builder->setUseOption(true);
        $parser->addCodeDefinition($builder->build());

        $builder = new \JBBCode\CodeDefinitionBuilder('youtube', '<div class="ckeditor-bbcode--youtube"><div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="https://www.youtube-nocookie.com/embed/{param}" frameborder="0" allowfullscreen></iframe></div></div>');
        $builder->setUseOption(false);
        $parser->addCodeDefinition($builder->build());

        $builder = new \JBBCode\CodeDefinitionBuilder('youtube', '<div class="ckeditor-bbcode--youtube"><iframe src="https://www.youtube-nocookie.com/embed/{param}" width="{w}" height="{h}" frameborder="0" allowfullscreen></iframe></div>');
        $builder->setUseOption(true);
        $parser->addCodeDefinition($builder->build());

        $builder = new \JBBCode\CodeDefinitionBuilder('code', '<pre><code>{param}</code></pre>');
        $builder->setParseContent(false);
        $parser->addCodeDefinition($builder->build());

        $parser->parse($bbcode);

        return $parser->getAsHTML();
    }

    /**
     * Creates a full url for the given parts.
     *
     * @param  array|string $url
     * @param  string  $route
     * @param  boolean $secure
     * @return string
     */
    public function getUrl($url = [], $route = null, $secure = false)
    {
        if ($this->modRewrite === null) {
            $config = \Ilch\Registry::get('config');
            if ($config !== null) {
                $this->modRewrite = (bool) $config->get('mod_rewrite');
            } else {
                $this->modRewrite = false;
            }
        }

        if (empty($url)) {
            return $this->getBaseUrl();
        }

        $rewrite = true;
        if (is_array($url)) {
            $urlParts = [];

            if (isset($url['module'])) {
                $urlParts[] = $url['module'];
                unset($url['module']);
            } else {
                $urlParts[] = $this->getRequest()->getModuleName();
            }

            if (isset($url['controller'])) {
                $urlParts[] = $url['controller'];
                unset($url['controller']);
            } else {
                $urlParts[] = $this->getRequest()->getControllerName();
            }

            if (isset($url['action'])) {
                $urlParts[] = $url['action'];
                unset($url['action']);
            } else {
                $urlParts[] = $this->getRequest()->getActionName();
            }

            foreach ($url as $key => $value) {
                if (is_string($key)) {
                    $urlParts[] = $key;
                }
                if (!empty($value)) {
                    $urlParts[] = $value;
                }
            }

            if ($secure) {
                $urlParts[] = 'ilch_token/' . $this->generateToken();
            }

            $url = implode('/', $urlParts);

            if (($this->getRequest()->isAdmin() && $route === null) || ($route !== null && $route === 'admin')) {
                $url = 'admin/' . $url;
                $rewrite = false;
            }
        }

        if (!$this->modRewrite || !$rewrite) {
            $url = 'index.php/' . $url;
        }

        return $this->getBaseUrl($url);
    }

    /**
     * Returns a full url for the current url with only the given parts changed
     * @param array $urlParts
     * @param bool $resetParams
     * @param bool $secure
     * @return string
     */
    public function getCurrentUrl(array $urlParts = [], $resetParams = true, $secure = false)
    {
        $currentUrlParts = [
            'module' => $this->request->getModuleName(),
            'controller' => $this->request->getControllerName(),
            'action' => $this->request->getActionName()
        ];

        $params = $this->request->getParams();
        if (empty($params)) {
            $resetParams = true;
        }

        $urlParams = array_merge(
            $currentUrlParts,
            $resetParams ? [] : $params,
            $urlParts
        );

        return $this->getUrl($urlParams, null, $secure);
    }

    /**
     * Gets formular hidden token input field.
     *
     * @return string
     */
    public function getTokenField()
    {
        return '<input type="hidden" name="ilch_token" value="'.$this->generateToken().'" />'."\n";
    }

    /**
     * Generates a token and stores it in user-session
     *
     * @return string
     */
    public function generateToken() {
        $token = bin2hex(random_bytes(32));
        $_SESSION['token'][$token] = $token;

        return $token;
    }

    /**
     * Gets the captcha image field.
     *
     * @return string
     */
    public function getCaptchaField()
    {
        return '<img src="'.$this->getUrl().'/application/libraries/Captcha/Captcha.php" id="captcha" />';
    }

    /**
     * Gets the MediaModal.
     * Place inside Javascript tag.
     *
     * @param string $mediaButton Define Media Button by given URL
     * @param string $actionButton Define Action Button by given URL
     * @param null $inputId
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
        }

        return preg_replace('/[^ ]*$/', '', substr($str, 0, $length)).'...';
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

    /**
     * Gets the box url.
     *
     * @param string $url
     * @return string
     */
    public function getBoxUrl($url = '')
    {
        if (empty($url)) {
            return $this->getUrl().'/'.$this->boxUrl;
        }

        return $this->getUrl().'/'.$this->boxUrl.'/'.$url;
    }

    /**
     * Set the box url.
     *
     * @param $boxUrl
     */
    public function setBoxUrl($boxUrl)
    {
        $this->boxUrl = $boxUrl;
    }

    /**
     * Gets the dialog.
     *
     * @param $id
     * @param $name
     * @param $content
     * @param null $submit
     * @return string
     */
    public function getDialog($id, $name, $content, $submit = null)
    {
        $html = '<div class="modal fade" id="'.$id.'">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-hidden="true">&times;
                        </button>
                        <h4 class="modal-title" id="modalLabel">'.$name.'</h4>
                    </div>
                    <div class="modal-body">
                        '.$content.'
                    </div>
                    <div class="modal-footer">';
                        if ($submit != null) {
                            $html .= '<button type="button"
                                 class="btn btn-primary"
                                 id="modalButton">'.$this->getTrans('ack').'
                            </button>
                            <button type="button"
                                    class="btn btn-default"
                                    data-dismiss="modal">'.$this->getTrans('cancel').'
                            </button>';
                        } else {
                            $html .= '<button type="button"
                                class="btn btn-primary"
                                data-dismiss="modal">
                            '.$this->getTrans('close').'
                            </button>';
                        }
                    $html .= '</div>
                </div>
            </div>
        </div>';

        return $html;
    }
}
