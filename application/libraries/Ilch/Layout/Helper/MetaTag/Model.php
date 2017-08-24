<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout\Helper\MetaTag;

use Ilch\Layout\Base as Layout;

/**
 * A model for a meta tag.
 *
 * @see https://www.w3.org/TR/html5/document-metadata.html#the-meta-element
 */
class Model
{
    /**
     * The http-equiv of the meta tag.
     *
     * @see https://www.w3.org/TR/html5/document-metadata.html#attr-meta-http-equiv
     * @var string
     */
    protected $httpEquiv;

    /**
     * The name of the meta tag.
     *
     * @see https://www.w3.org/TR/html5/document-metadata.html#attr-meta-name
     * @var string
     */
    protected $name;

    /**
     * The content of the meta tag.
     *
     * @see https://www.w3.org/TR/html5/document-metadata.html#attr-meta-content
     * @var string
     */
    protected $content;

    /**
     * The charset of the meta tag.
     *
     * @see https://www.w3.org/TR/html5/document-metadata.html#attr-meta-charset
     * @var string
     */
    protected $charset;

    /**
     * Gets the http-equiv of the meta tag.
     *
     * @see https://www.w3.org/TR/html5/document-metadata.html#attr-meta-http-equiv
     * @return string
     */
    public function getHTTPEquiv()
    {
        return $this->httpEquiv;
    }

    /**
     * Sets the http-equiv of the meta tag.
     *
     * @see https://www.w3.org/TR/html5/document-metadata.html#attr-meta-http-equiv
     * @param string $httpEquiv
     * @return $this
     */
    public function setHTTPEquiv($httpEquiv)
    {
        $this->httpEquiv = $httpEquiv;

        return $this;
    }

    /**
     * Gets the name of the meta tag.
     *
     * @see https://www.w3.org/TR/html5/document-metadata.html#attr-meta-name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name of the meta tag.
     *
     * @see https://www.w3.org/TR/html5/document-metadata.html#attr-meta-name
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the content of the meta tag.
     *
     * @see https://www.w3.org/TR/html5/document-metadata.html#attr-meta-content
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sets the content of the meta tag.
     *
     * @see https://www.w3.org/TR/html5/document-metadata.html#attr-meta-content
     * @param string $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Gets the charset of the meta tag.
     *
     * @see https://www.w3.org/TR/html5/document-metadata.html#attr-meta-charset
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * Sets the charset of the meta tag.
     *
     * @see https://www.w3.org/TR/html5/document-metadata.html#attr-meta-charset
     * @param string $charset
     * @return $this
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;

        return $this;
    }
}