<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout\Helper\LinkTag;

use Ilch\Layout\Base as Layout;

/**
 * A model for a link tag.
 *
 * @see https://www.w3.org/TR/html50/document-metadata.html#the-link-element
 */
class Model
{
    /**
     * The href of the link tag.
     * Address of the hyperlink
     * Must be present and must contain a valid non-empty URL potentially surrounded by spaces.
     *
     * @see https://www.w3.org/TR/html50/document-metadata.html#attr-link-href
     * @var string
     */
    protected $href;

    /**
     * The crossorigin of the link tag.
     * How the element handles crossorigin requests
     *
     * @see https://www.w3.org/TR/html50/document-metadata.html#attr-link-crossorigin
     * @var string
     */
    protected $crossorigin;

    /**
     * The rel of the link tag.
     * Relationship between the document containing the hyperlink and the destination resource
     * A link element must have a rel attribute.
     *
     * @see https://www.w3.org/TR/html50/document-metadata.html#attr-link-rel
     * @var string
     */
    protected $rel;

    /**
     * The media of the link tag.
     * Applicable media
     *
     * @see https://www.w3.org/TR/html50/document-metadata.html#attr-link-media
     * @var string
     */
    protected $media;

    /**
     * The hreflang of the link tag.
     * Language of the linked resource
     *
     * @see https://www.w3.org/TR/html50/document-metadata.html#attr-link-hreflang
     * @var string
     */
    protected $hreflang;

    /**
     * The type of the link tag.
     * Hint for the type of the referenced resource
     * The type attribute gives the MIME type of the linked resource. It is purely advisory.
     * The value must be a valid MIME type.
     *
     * @see https://www.w3.org/TR/html50/document-metadata.html#attr-link-type
     * @var string
     */
    protected $type;

    /**
     * The sizes of the link tag.
     * Sizes of the icons (for rel="icon")
     *
     * @see https://www.w3.org/TR/html50/links.html#attr-link-sizes
     * @var string
     */
    protected $sizes;

    /**
     * The title of the link tag.
     * title attribute has special semantics on this element: Title of the link; alternative style sheet set name.
     *
     * @see https://www.w3.org/TR/html50/document-metadata.html#attr-link-title
     * @var string
     */
    protected $title;

    /**
     * Set href for the link tag.
     *
     * @param string $href
     * @return Model
     */
    public function setHref($href): Model
    {
        $this->href = $href;
        return $this;
    }

    /**
     * Get href for the link tag.
     * @return string
     */
    public function getHref(): string
    {
        return $this->href;
    }

    /**
     * Set crossorigin for the link tag.
     * Example: anonymous, use-credentials, ...
     *
     * @param string $crossorigin
     * @return Model
     */
    public function setCrossorigin($crossorigin): Model
    {
        $this->crossorigin = $crossorigin;
        return $this;
    }

    /**
     * Get crossorigin for the link tag.
     *
     * @return string
     */
    public function getCrossorigin(): string
    {
        return $this->crossorigin;
    }

    /**
     * Set rel for the link tag.
     * Example: alternate, stylesheet, ...
     *
     * @param string $rel
     * @return Model
     */
    public function setRel($rel): Model
    {
        $this->rel = $rel;
        return $this;
    }

    /**
     * Get rel for the link tag.
     *
     * @return string
     */
    public function getRel(): string
    {
        return $this->rel;
    }

    /**
     * Set the media for the link tag.
     * The value must be a valid media query.
     * Example: print
     *
     * @param string $media
     * @return Model
     */
    public function setMedia($media): Model
    {
        $this->media = $media;
        return $this;
    }

    /**
     * Get the media for the link tag.
     *
     * @return string
     */
    public function getMedia(): string
    {
        return $this->media;
    }

    /**
     * Set hreflang for the link tag.
     * The value must be a valid BCP 47 language tag.
     * Example: en, fr, ...
     *
     * @param string $hreflang
     * @return Model
     */
    public function setHreflang($hreflang): Model
    {
        $this->hreflang = $hreflang;
        return $this;
    }

    /**
     * Get hreflang for the link tag.
     *
     * @return string
     */
    public function getHreflang(): string
    {
        return $this->hreflang;
    }

    /**
     * Set the type for the link tag.
     * The value must be a valid MIME type.
     * Example: text/html, application/pdf, ...
     *
     * @param string $type
     * @return Model
     */
    public function setType($type): Model
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get the type for the link tag.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set sizes for the link tag.
     * Example: 16x16, 32x32, any, ...
     *
     * @param string $sizes
     * @return Model
     */
    public function setSizes($sizes): Model
    {
        $this->sizes = $sizes;
        return $this;
    }

    /**
     * Get sizes for the link tag.
     *
     * @return string
     */
    public function getSizes(): string
    {
        return $this->sizes;
    }

    /**
     * Set title for the link tag.
     *
     * @param string $title
     * @return Model
     */
    public function setTitle($title): Model
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title for the link tag.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}
