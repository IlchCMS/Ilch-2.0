<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Design;

use Ilch\Config\Database;
use Ilch\HTMLPurifier\EmbedUrlDef;
use Ilch\Layout\Helper\GetMedia;
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
    private $layoutKey = '';

    /**
     * Box url that will be used
     *
     * @var string
     */
    private $boxUrl = '';

    /**
     * Base url
     *
     * @var string
     */
    private $baseUrl = '';

    /**
     * Adds view/layout helper.
     *
     * @param string $name
     * @param string $type
     * @param mixed $obj
     * @return $this
     */
    public function addHelper(string $name, string $type, $obj): Base
    {
        $this->helpers[$type][$name] = $obj;
        return $this;
    }

    /**
     * Gets view/layout helper.
     *
     * @param string $name
     * @param string $type
     * @return mixed
     */
    public function getHelper(string $name, string $type)
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
     * @var bool|null
     */
    private $modRewrite = null;

    /**
     * @var \HTMLPurifier_Config default object.
     */
    private $purifierConfig;

    /**
     * @var \HTMLPurifier
     */
    private $purifier;

    /**
     * Injects request and translator to layout/view.
     *
     * @param Request $request
     * @param Translator $translator
     * @param Router $router
     * @param string|null $baseUrl
     */
    public function __construct(Request $request, Translator $translator, Router $router, ?string $baseUrl = null)
    {
        $this->request = $request;
        $this->translator = $translator;
        $this->router = $router;
        if ($baseUrl === null) {
            $baseUrl = BASE_URL;
        }
        $this->baseUrl = $baseUrl;
    }

    /**
     * Initialize HTMLPurifier.
     *
     * @param Database|null $databaseConfig
     * @return void
     */
    public function initializeHtmlPurifier(?Database $databaseConfig = null): void
    {
        $safeIframeRegexp = '%(?|(^(http://|https://)localhost)|(^https://(youtube.com/|www.youtube.com/|www.youtube.com/embed/|www.youtube-nocookie.com/embed/|youtu.be/|vimeo.com/|player.vimeo.com/video/|open.spotify.com/artist/|open.spotify.com/album/|open.spotify.com/track/|open.spotify.com/embed/|www.dailymotion.com/video/|www.dailymotion.com/embed/video/|dai.ly/)))%';

        // HTML Purifier configuration
        $this->purifierConfig = \HTMLPurifier_Config::createDefault();
        // $this->purifierConfig->set('Cache.DefinitionImpl', null);
        $this->purifierConfig->set('Filter.YouTube', true);
        $this->purifierConfig->set('HTML.SafeIframe', true);
        $this->purifierConfig->set('URI.AllowedSchemes', ['data' => true, 'http' => true, 'https' => true, 'mailto' => true, 'src' => true]);

        if ($databaseConfig && $databaseConfig->get('htmlPurifierSafeUrlsAddOwnDomain')) {
            file_put_contents('php://stderr', print_r('htmlPurifierSafeUrlsAddOwnDomain' . PHP_EOL, TRUE));
            $safeIframeRegexp = str_replace('^(http://|https://)localhost)', '^(http://|https://)localhost)|^(http://|https://)' . $databaseConfig->get('domain') . ')' , $safeIframeRegexp);
        }
        $this->purifierConfig->set('URI.SafeIframeRegexp', $safeIframeRegexp);
        $this->purifierConfig->set('Attr.AllowedFrameTargets', '_blank, _self, _target, _parent');
        $this->purifierConfig->set('Attr.EnableID', true);
        $this->purifierConfig->set('AutoFormat.Linkify', true);
        // Had to be enabled to allow the output of the media embed plugin of CKEditor 5.
        $this->purifierConfig->set('CSS.Trusted', true);
        $def = $this->purifierConfig->getHTMLDefinition(true);
        $def->addAttribute('iframe', 'allowfullscreen', 'Bool');
        // Settings to allow (most of) the output of the media embed plugin of CKEditor 5.
        // https://github.com/ckeditor/ckeditor5/blob/v41.1.0/packages/ckeditor5-media-embed/src/mediaembedediting.ts
        // https://ckeditor.com/docs/ckeditor5/latest/features/media-embed.html#including-previews-in-data
        $def->addAttribute('div', 'data-oembed-url', new EmbedUrlDef());
        $def->addElement('oembed', 'Block', 'Inline', 'Common');
        $def->addAttribute('oembed', 'url', new EmbedUrlDef());

        $def->addElement('figure', 'Block', 'Optional: (figcaption, Flow) | (Flow, figcaption) | Flow', 'Common');
        $def->addElement('figcaption', 'Inline', 'Flow', 'Common');

        // https://github.com/ezyang/htmlpurifier/issues/152
        $def->addAttribute('a', 'download', 'URI');

        $def->addElement('video', 'Block', 'Optional: (source, Flow) | (Flow, source) | Flow', 'Common', array(
            'autoplay' => 'Bool',
            'src' => new EmbedUrlDef(),
            'width' => 'Length',
            'height' => 'Length',
            'preload' => 'Enum#auto,metadata,none',
            'controls' => 'Bool',
        ));
        $def->addElement('source', 'Block', 'Flow', 'Common', array(
            'src' => new EmbedUrlDef(),
            'type' => 'Text',
        ));
        $this->purifier = new \HTMLPurifier($this->purifierConfig);
    }

    /**
     * Get object of HTML Purifier with default configuration.
     *
     * @return \HTMLPurifier
     * @since 2.1.26
     */
    public function getPurifier(): \HTMLPurifier
    {
        return $this->purifier;
    }

    /**
     * Use HTMLPurifier to purify the content.
     * Takes the "disable_purifier" setting into account.
     *
     * @param string $content
     * @return string
     * @since 2.1.26
     */
    public function purify(string $content): string
    {
        $config = \Ilch\Registry::get('config');

        if ($config->get('disable_purifier')) {
            return $content;
        }

        return $this->getPurifier()->purify($content);
    }

    /**
     * Use HTMLPurifier to always purify the content.
     * Isn't affected by the "disable_purifier" setting.
     * Use this function if you want to make sure that the
     * content always gets purified.
     *
     * @param string $content
     * @return string
     * @since 2.1.52
     */
    public function alwaysPurify(string $content): string
    {
        return $this->getPurifier()->purify($content);
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
    public function add(string $key, string $objectKey, $value): bool
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
    public function get(string $key)
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
    public function set(string $key, $value): Base
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Sets view data array.
     *
     * @param array $data
     * @return $this
     */
    public function setArray(array $data = []): Base
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    /**
     * Gets the request object.
     *
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Gets the router object.
     *
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * Gets the translator object.
     *
     * @return Translator
     */
    public function getTranslator(): Translator
    {
        return $this->translator;
    }

    /**
     * Gets the user object.
     *
     * @return \Modules\User\Models\User|null
     */
    public function getUser(): ?\Modules\User\Models\User
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
    public function getTrans(string $key): string
    {
        $args = \func_get_args();
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
    public function getOtherLayoutTrans(string $layoutKey, string $key): string
    {
        $args = \func_get_args();
        return $this->getTranslator()->transOtherLayout(...$args);
    }

    /**
     * Returns an amount of money of the currency supplied formatted in locale-typical style.
     *
     * @param float $amount
     * @param string $currencyCode (ISO 4217)
     * @return string
     */
    public function getFormattedCurrency(float $amount, string $currencyCode): string
    {
        return $this->getTranslator()->getFormattedCurrency($amount, $currencyCode);
    }

    /**
     * Gets the base url.
     *
     * @param  string  $url
     * @return string
     */
    public function getBaseUrl(string $url = ''): string
    {
        return $this->baseUrl . '/' . $url;
    }

    /**
      * Gets the layout url.
      *
      * @param string $url
      * @return string
      */
    public function getLayoutUrl(string $url = ''): string
    {
        return $this->getBaseUrl('application/layouts/' . $this->getLayoutKey() . '/' . $url);
    }

    /**
     * Gets the module url.
     *
     * @param string $url
     * @return string
     */
    public function getModuleUrl(string $url = ''): string
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
    public function getStaticUrl(string $url = ''): string
    {
        return $this->getBaseUrl('static/' . $url);
    }

    /**
     * Gets the system, vendor url.
     *
     * @param  string $url
     * @return string
     */
    public function getVendorUrl(string $url = ''): string
    {
        return $this->getBaseUrl('vendor/' . str_replace(['components/jquery/', 'components/jqueryui/'], ['npm-asset/jquery/dist/', 'npm-asset/jquery-ui/dist/'], $url));
    }

    /**
     * Escape the given string.
     *
     * @param null|string $string
     * @return string
     */
    public function escape(?string $string): string
    {
        return \htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8', false);
    }

    /**
     * Creates a full url for the given parts.
     *
     * @param array|string $url
     * @param string|null $route
     * @param bool $secure
     * @return string
     */
    public function getUrl($url = [], ?string $route = null, bool $secure = false): string
    {
        $locale = '';
        $config = \Ilch\Registry::get('config');
        if ($config !== null && $config->get('multilingual_acp') && $this->translator->getLocale() != $config->get('content_language')) {
            $locale = $this->translator->getLocale();
        }

        if ($this->modRewrite === null) {
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

            if (isset($url['module']) && $url['module'] === 'admin' && isset($url['controller']) && $url['controller'] === 'page' && isset($url['action']) && $url['action'] === 'show' && isset($url['id'])) {
                $pageMapper = new \Modules\Admin\Mappers\Page();
                $page = $pageMapper->getPageByIdLocale((int)$url['id'], $locale);
                if (!$page) {
                    $page = $pageMapper->getPageByIdLocale((int)$url['id']);
                }
                $urlParts[] = $page ? $page->getPerma() : '';
                unset($url['module'], $url['controller'], $url['action'], $url['id']);
            } else {
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

            if (($this->getRequest()->isAdmin() && $route === null) || ($route === 'admin')) {
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
    public function getCurrentUrl(array $urlParts = [], bool $resetParams = true, bool $secure = false): string
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
    public function getTokenField(): string
    {
        return '<input type="hidden" name="ilch_token" value="' . $this->generateToken() . '" />' . "\n";
    }

    /**
     * Generates a token and stores it in user-session
     *
     * @return string
     */
    public function generateToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $_SESSION['token'][$token] = $token;

        return $token;
    }

    /**
     * Gets the captcha image field.
     *
     * @return string
     */
    public function getCaptchaField(): string
    {
        return '<img src="' . $this->getUrl() . '/application/libraries/Captcha/Captcha.php" id="captcha" />';
    }

    /**
     * Gets the MediaModal.
     * Place inside Javascript tag.
     *
     * @param string|null $mediaButton Define Media Button by given URL
     * @param string|null $actionButton Define Action Button by given URL
     * @param string|null $inputId
     * @return GetMedia
     */
    public function getMedia(?string $mediaButton = null, ?string $actionButton = null, ?string $inputId = null): GetMedia
    {
        return new GetMedia($mediaButton, $actionButton, $inputId);
    }

    /**
     * Gets the page loading time in microsecond.
     *
     * @return float
     */
    public function loadTime(): float
    {
        $startTime = \Ilch\Registry::get('startTime');

        return \microtime(true) - $startTime;
    }

    /**
     * Gets the page queries.
     *
     * @return int
     */
    public function queryCount(): int
    {
        $db = \Ilch\Registry::get('db');

        return $db->queryCount();
    }

    /**
     * Limit the given string to the given length.
     *
     * @param  string  $str
     * @param  int $length
     * @return string
     */
    public function limitString(string $str, int $length): string
    {
        if (strlen($str) <= $length) {
            return $str;
        }

        return \preg_replace('/[^ ]*$/', '', substr($str, 0, $length)) . '...';
    }

    /**
     * Gets the key of the layout.
     *
     * @return string
     */
    public function getLayoutKey(): string
    {
        return $this->layoutKey;
    }

    /**
     * Set the key of the layout.
     *
     * @param string $layoutKey
     */
    public function setLayoutKey(string $layoutKey)
    {
        $this->layoutKey = $layoutKey;
    }

    /**
     * Gets the box url.
     *
     * @param string $url
     * @return string
     */
    public function getBoxUrl(string $url = ''): string
    {
        if (empty($url)) {
            return $this->getUrl() . '/' . $this->boxUrl;
        }

        return $this->getUrl() . '/' . $this->boxUrl . '/' . $url;
    }

    /**
     * Set the box url.
     *
     * @param string $boxUrl
     */
    public function setBoxUrl(string $boxUrl)
    {
        $this->boxUrl = $boxUrl;
    }

    /**
     * Gets the dialog.
     *
     * @param string $id
     * @param string $name
     * @param string $content
     * @param mixed $submit
     * @return string
     */
    public function getDialog(string $id, string $name, string $content, $submit = null): string
    {
        $html = '<div class="modal fade" id="' . $id . '">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modalLabel">' . $name . '</h4>
                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        ' . $content . '
                    </div>
                    <div class="modal-footer">';
        if ($submit != null) {
            $html .= '<button type="button"
                                 class="btn btn-primary"
                                 id="modalButton">' . $this->getTrans('ack') . '
                            </button>
                            <button type="button"
                                    class="btn btn-outline-secondary"
                                    data-bs-dismiss="modal">' . $this->getTrans('cancel') . '
                            </button>';
        } else {
            $html .= '<button type="button"
                                class="btn btn-primary"
                                data-bs-dismiss="modal">
                            ' . $this->getTrans('close') . '
                            </button>';
        }
        $html .= '</div>
                </div>
            </div>
        </div>';

        return $html;
    }
}
