<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Layout\Helper\ScriptTag;

use http\Exception\InvalidArgumentException;

/**
 * A model for a script tag.
 *
 * @see https://html.spec.whatwg.org/multipage/scripting.html#the-script-element
 * @since 2.1.50
 */
class Model
{
    private const validReferrerPolicy = ['no-referrer', 'no-referrer-when-downgrade', 'origin', 'origin-when-cross-origin', 'same-origin', 'strict-origin', 'strict-origin-when-cross-origin', 'unsafe-url'];
    private const validCrossOrigin = ['anonymous', 'use-credentials'];
    private const validBlocking = ['render'];
    private const validFetchPriority = ['high', 'low', 'auto'];
    private const validJavaScriptMIMEType = [
        'application/ecmascript',
        'application/javascript',
        'application/x-ecmascript',
        'application/x-javascript',
        'text/ecmascript',
        'text/javascript',
        'text/javascript1.0',
        'text/javascript1.1',
        'text/javascript1.2',
        'text/javascript1.3',
        'text/javascript1.4',
        'text/javascript1.5',
        'text/jscript',
        'text/livescript',
        'text/x-ecmascript',
        'text/x-javascript'];

    /**
     * Address of the resource
     *
     * @see https://html.spec.whatwg.org/multipage/scripting.html#attr-script-src
     * @var string
     */
    protected $src;

    /**
     * Type of script
     *
     * @see https://html.spec.whatwg.org/multipage/scripting.html#attr-script-type
     * @var string
     */
    protected $type;

    /**
     * Prevents execution in user agents that support module scripts
     *
     * @see https://html.spec.whatwg.org/multipage/scripting.html#attr-script-nomodule
     * @var bool
     */
    protected $nomodule;

    /**
     * Execute script when available, without blocking while fetching
     *
     * @see https://html.spec.whatwg.org/multipage/scripting.html#attr-script-async
     * @var bool
     */
    protected $async;

    /**
     * Defer script execution
     *
     * @see https://html.spec.whatwg.org/multipage/scripting.html#attr-script-defer
     * @var bool
     */
    protected $defer;

    /**
     * How the element handles crossorigin requests
     *
     * @see https://html.spec.whatwg.org/multipage/scripting.html#attr-script-crossorigin
     * @var string
     */
    protected $crossorigin;

    /**
     * Integrity metadata used in Subresource Integrity checks
     *
     * @see https://html.spec.whatwg.org/multipage/scripting.html#attr-script-integrity
     * @see https://w3c.github.io/webappsec-subresource-integrity/
     * @var string
     */
    protected $integrity;

    /**
     * Referrer policy for fetches initiated by the element
     *
     * @see https://html.spec.whatwg.org/multipage/scripting.html#attr-script-referrerpolicy
     * @var string
     */
    protected $referrerpolicy;

    /**
     * Whether the element is potentially render-blocking.
     * By spec this is a DOMTokenList. Currently there is only one valid value ("render").
     * Simplified the attribute to a string for this.
     *
     * @see https://html.spec.whatwg.org/multipage/scripting.html#attr-script-blocking
     * @see https://dom.spec.whatwg.org/#interface-domtokenlist
     * @var string
     */
    protected $blocking;

    /**
     * Sets the priority for fetches initiated by the element
     *
     * @see https://html.spec.whatwg.org/multipage/scripting.html#attr-script-fetchpriority
     * @var string
     */
    protected $fetchpriority;

    /**
     * If type specifies a data block then this holds the data.
     *
     * @see https://html.spec.whatwg.org/multipage/scripting.html#data-block
     * @var string
     */
    protected $data;

    /**
     * Gets the address of the resource.
     *
     * @return string
     */
    public function getSrc(): string
    {
        return $this->src;
    }

    /**
     * Sets the address of the resource.
     *
     * @param string $src
     * @return Model
     */
    public function setSrc(string $src): Model
    {
        $this->src = $src;
        return $this;
    }

    /**
     * Gets the type of the the script.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Sets the type of the script.
     * A JavaScript MIME type (optional), "module", "importmap' or valid MIME type string that is not a JavaScript MIME type (data block).
     * Omitting the attribute, setting it to the empty string, or setting it to a JavaScript MIME type essence match,
     * means that the script is a classic script, to be interpreted according to the JavaScript Script top-level production.
     * Authors should omit the type attribute instead of redundantly setting it.
     *
     * Setting the attribute to any other value means that the script is a data block, which is not processed.
     * None of the script attributes (except type itself) have any effect on data blocks.
     * Authors must use a valid MIME type string that is not a JavaScript MIME type essence match to denote data blocks.
     *
     * @param string $type
     * @return Model
     * @see https://mimesniff.spec.whatwg.org/#javascript-mime-type-essence-match
     */
    public function setType(string $type): Model
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Is this a module script?
     *
     * @return bool
     */
    public function isNomodule(): bool
    {
        return $this->nomodule;
    }

    /**
     * Sets if this is a module script.
     * The nomodule attribute is a boolean attribute that prevents a script from being executed in user agents that support module scripts.
     *
     * @param bool $nomodule
     * @return Model
     */
    public function setNomodule(bool $nomodule): Model
    {
        $this->nomodule = $nomodule;
        return $this;
    }

    /**
     * Is this script executed when available, without blocking while fetching.
     *
     * @return bool
     */
    public function isAsync(): bool
    {
        return $this->async;
    }

    /**
     * Sets if this script is async.
     * For classic scripts, if the async attribute is present, then the classic script will be fetched in parallel
     * to parsing and evaluated as soon as it is available (potentially before parsing completes).
     * For module scripts, if the async attribute is present, then the module script and all its dependencies will
     * be fetched in parallel to parsing, and the module script will be evaluated as soon as it is available (potentially before parsing completes).
     *
     * @param bool $async
     * @return Model
     */
    public function setAsync(bool $async): Model
    {
        $this->async = $async;
        return $this;
    }

    /**
     * Defer script execution?
     *
     * @return bool
     */
    public function isDefer(): bool
    {
        return $this->defer;
    }

    /**
     * Set if defer script execution.
     * Classic scripts may specify defer or async, but must not specify either unless the src attribute is present.
     * Module scripts may specify the async attribute, but must not specify the defer attribute.
     *
     * @param bool $defer
     * @return Model
     */
    public function setDefer(bool $defer): Model
    {
        $this->defer = $defer;
        return $this;
    }

    /**
     * Get how the element handles crossorigin requests.
     *
     * @return string
     */
    public function getCrossorigin(): string
    {
        return $this->crossorigin;
    }

    /**
     * Set how the element handles crossorigin requests.
     * The crossorigin attribute is a CORS settings attribute.
     * For classic scripts, it controls whether error information will be exposed, when the script is obtained from other origins.
     * For module scripts, it controls the credentials mode used for cross-origin requests.
     *
     * @param string $crossorigin anonymous|use-credentials
     * @return Model
     */
    public function setCrossorigin(string $crossorigin): Model
    {
        if (!in_array(strtolower($crossorigin), self::validCrossOrigin)) {
            throw new InvalidArgumentException('Invalid value for crossorigin.');
        }

        $this->crossorigin = strtolower($crossorigin);
        return $this;
    }

    /**
     * Get integrity metadata used in Subresoruce Integrity checks.
     *
     * @return string
     */
    public function getIntegrity(): string
    {
        return $this->integrity;
    }

    /**
     * Set integrity metadata used in Subresource Integrity checks.
     * The integrity attribute represents the integrity metadata for requests which this element is responsible for.
     * The value is text. The integrity attribute must not be specified when the src attribute is not specified.
     *
     * @param string $integrity
     * @return Model
     */
    public function setIntegrity(string $integrity): Model
    {
        $this->integrity = $integrity;
        return $this;
    }

    /**
     * Get the referrer policy for fetches initiated by the element.
     *
     * @return string
     */
    public function getReferrerpolicy(): string
    {
        return $this->referrerpolicy;
    }

    /**
     * Set the referrer policy for fetches initiated by the element.
     * The referrerpolicy attribute is a referrer policy attribute.
     * Its purpose is to set the referrer policy used when fetching the script, as well as any scripts imported from it.
     *
     * @param string $referrerpolicy
     * @return Model
     */
    public function setReferrerpolicy(string $referrerpolicy): Model
    {
        if (!in_array(strtolower($referrerpolicy), self::validReferrerPolicy)) {
            throw new InvalidArgumentException('Invalid referrer policy.');
        }

        $this->referrerpolicy = strtolower($referrerpolicy);
        return $this;
    }

    /**
     * Get if the element is potentially render-blocking.
     *
     * @return string
     */
    public function getBlocking(): string
    {
        return $this->blocking;
    }

    /**
     * Set if the element is potentially render-blocking.
     * By default, an element is not implicitly potentially render-blocking.
     *
     * @param string $blocking render
     * @return Model
     */
    public function setBlocking(string $blocking): Model
    {
        if (!in_array(strtolower($blocking), self::validBlocking)) {
            throw new InvalidArgumentException('Invalid value for blocking.');
        }

        $this->blocking = strtolower($blocking);
        return $this;
    }

    /**
     * Gets the priority for fetches initiated by the element.
     *
     * @return string
     */
    public function getFetchpriority(): string
    {
        return $this->fetchpriority;
    }

    /**
     * Sets the priority for fetches initiated by the element.
     * The fetchpriority attribute is a fetch priority attribute. Its purpose is to set the priority used when fetching the script.
     * The attribute's missing value default and invalid value default are both the auto state.
     *
     * @param string $fetchpriority high|low|auto
     * @return Model
     */
    public function setFetchpriority(string $fetchpriority): Model
    {
        if (!in_array(strtolower($fetchpriority), self::validFetchPriority)) {
            throw new InvalidArgumentException('Invalid value for fetchpriority.');
        }

        $this->fetchpriority = strtolower($fetchpriority);
        return $this;
    }

    /**
     * Returns true if it is a DataBlock and not a classic script, module or importmap.
     *
     * Setting the [type] attribute to any other value means that the script is a data block, which is not processed.
     * None of the script attributes (except type itself) have any effect on data blocks.
     * Authors must use a valid MIME type string that is not a JavaScript MIME type essence match to denote data blocks.
     *
     * @return bool
     * @see https://mimesniff.spec.whatwg.org/#javascript-mime-type-essence-match
     */
    public function isDataBlock(): bool
    {
        // Empty type equals classic script.
        if (!$this->getType()) {
            return false;
        }

        // If it is a module or an importmap then it can't be a datablock.
        if (strtolower($this->getType()) === 'module' || strtolower($this->getType()) === 'importmap') {
            return false;
        }

        // If it has a valid JavaScript MIME type it's not a datablock.
        return !in_array(strtolower($this->getType()), self::validJavaScriptMIMEType);
    }

    /**
     * Get the data stored when used as a data block.
     *
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * Set the data stored when used as a data block.
     * Authors must use a valid MIME type string that is not a JavaScript MIME type essence match to denote data blocks.
     *
     * @param string $data
     * @return Model
     * @see https://html.spec.whatwg.org/multipage/scripting.html#data-block
     */
    public function setData(string $data): Model
    {
        $this->data = $data;
        return $this;
    }
}
