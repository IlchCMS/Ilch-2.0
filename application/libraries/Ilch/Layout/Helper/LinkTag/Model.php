<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Layout\Helper\LinkTag;

use InvalidArgumentException;

/**
 * A model for a link tag.
 *
 * @see https://html.spec.whatwg.org/multipage/semantics.html#the-link-element
 * @since 2.1.22
 */
class Model
{
    private const validReferrerPolicy = ['no-referrer', 'no-referrer-when-downgrade', 'origin', 'origin-when-cross-origin', 'same-origin', 'strict-origin', 'strict-origin-when-cross-origin', 'unsafe-url'];
    private const validCrossOrigin = ['anonymous', 'use-credentials'];
    private const validAs = ['audio', 'document', 'embed', 'fetch', 'font', 'image', 'object', 'script', 'style', 'track', 'video', 'worker'];
    private const validBlocking = ['render'];
    private const validFetchPriority = ['high', 'low', 'auto'];

    /**
     * The href of the link tag.
     * Address of the hyperlink
     * Must be present and must contain a valid non-empty URL potentially surrounded by spaces.
     *
     * @see https://html.spec.whatwg.org/multipage/semantics.html#attr-link-href
     * @var string|null
     * @since 2.1.22
     */
    protected $href;

    /**
     * The crossorigin of the link tag.
     * How the element handles crossorigin requests
     *
     * @see https://html.spec.whatwg.org/multipage/semantics.html#attr-link-crossorigin
     * @var string|null
     * @since 2.1.22
     */
    protected $crossorigin;

    /**
     * The rel of the link tag.
     * Relationship between the document containing the hyperlink and the destination resource
     * A link element must have a rel attribute.
     *
     * @see https://html.spec.whatwg.org/multipage/semantics.html#attr-link-rel
     * @var string|null
     * @since 2.1.22
     */
    protected $rel;

    /**
     * The media of the link tag.
     * Applicable media
     *
     * @see https://html.spec.whatwg.org/multipage/semantics.html#attr-link-media
     * @var string|null
     * @since 2.1.22
     */
    protected $media;

    /**
     * Integrity metadata used in Subresource Integrity checks
     *
     * @see https://html.spec.whatwg.org/multipage/semantics.html#attr-link-integrity
     * @var string|null
     * @since 2.1.50
     */
    protected $integrity;

    /**
     * The hreflang of the link tag.
     * Language of the linked resource
     *
     * @see https://html.spec.whatwg.org/multipage/semantics.html#attr-link-hreflang
     * @var string|null
     * @since 2.1.22
     */
    protected $hreflang;

    /**
     * The type of the link tag.
     * Hint for the type of the referenced resource
     * The type attribute gives the MIME type of the linked resource. It is purely advisory.
     * The value must be a valid MIME type.
     *
     * @see https://html.spec.whatwg.org/multipage/semantics.html#attr-link-type
     * @var string|null
     * @since 2.1.22
     */
    protected $type;

    /**
     * Referrer policy for fetches initiated by the element
     *
     * @see https://html.spec.whatwg.org/multipage/semantics.html#attr-link-referrerpolicy
     * @var string|null
     * @since 2.1.50
     */
    protected $referrerpolicy;

    /**
     * The sizes of the link tag.
     * Sizes of the icons (for rel="icon")
     *
     * @see https://html.spec.whatwg.org/multipage/semantics.html#attr-link-sizes
     * @var string|null
     * @since 2.1.22
     */
    protected $sizes;

    /**
     * Images to use in different situations, e.g., high-resolution displays, small monitors, etc. (for rel="preload")
     *
     * @see https://html.spec.whatwg.org/multipage/semantics.html#attr-link-imagesrcset
     * @var string|null
     * @since 2.1.50
     */
    protected $imagesrcset;

    /**
     * Image sizes for different page layouts (for rel="preload")
     *
     * @see https://html.spec.whatwg.org/multipage/semantics.html#attr-link-imagesizes
     * @var string|null
     * @since 2.1.50
     */
    protected $imagesizes;

    /**
     * Potential destination for a preload request (for rel="preload" and rel="modulepreload")
     *
     * @see https://html.spec.whatwg.org/multipage/semantics.html#attr-link-as
     * @var string|null
     * @since 2.1.50
     */
    protected $as;

    /**
     * Whether the element is potentially render-blocking
     *
     * @see https://html.spec.whatwg.org/multipage/semantics.html#attr-link-blocking
     * @var string|null
     * @since 2.1.50
     */
    protected $blocking;

    /**
     * Color to use when customizing a site's icon (for rel="mask-icon")
     *
     * @see https://html.spec.whatwg.org/multipage/semantics.html#attr-link-color
     * @var string|null
     * @since 2.1.50
     */
    protected $color;

    /**
     * Whether the link is disabled
     *
     * @see https://html.spec.whatwg.org/multipage/semantics.html#attr-link-disabled
     * @var bool
     * @since 2.1.50
     */
    protected $disabled = false;

    /**
     * Sets the priority for fetches initiated by the element
     *
     * @see https://html.spec.whatwg.org/multipage/semantics.html#attr-link-fetchpriority
     * @var string|null
     * @since 2.1.50
     */
    protected $fetchpriority;

    /**
     * The title of the link tag.
     * title attribute has special semantics on this element: Title of the link; alternative style sheet set name.
     *
     * @see https://html.spec.whatwg.org/multipage/semantics.html#attr-link-title
     * @var string|null
     * @since 2.1.22
     */
    protected $title;

    /**
     * Set href for the link tag.
     *
     * @param string $href
     * @return Model
     * @since 2.1.22
     */
    public function setHref(string $href): Model
    {
        $this->href = $href;
        return $this;
    }

    /**
     * Get href for the link tag.
     * @return null|string
     * @since 2.1.22
     */
    public function getHref(): ?string
    {
        return $this->href;
    }

    /**
     * Set crossorigin for the link tag.
     * Example: anonymous, use-credentials, ...
     *
     * @param string $crossorigin anonymous|use-credentials
     * @return Model
     * @since 2.1.22
     * @since 2.1.50 throws InvalidArgumentException for invalid values.
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
     * Get crossorigin for the link tag.
     *
     * @return null|string
     * @since 2.1.22
     */
    public function getCrossorigin(): ?string
    {
        return $this->crossorigin;
    }

    /**
     * Set rel for the link tag.
     * Example: alternate, stylesheet, ...
     *
     * @param string $rel
     * @return Model
     * @since 2.1.22
     */
    public function setRel(string $rel): Model
    {
        $this->rel = $rel;
        return $this;
    }

    /**
     * Get rel for the link tag.
     *
     * @return string
     * @since 2.1.22
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
     * @since 2.1.22
     */
    public function setMedia(string $media): Model
    {
        $this->media = $media;
        return $this;
    }

    /**
     * Get the media for the link tag.
     *
     * @return null|string
     * @since 2.1.22
     */
    public function getMedia(): ?string
    {
        return $this->media;
    }

    /**
     * Get the Integrity metadata
     *
     * @return null|string
     * @since 2.1.50
     */
    public function getIntegrity(): ?string
    {
        return $this->integrity;
    }

    /**
     * Set the Integrity metadata
     *
     * @param string $integrity
     * @return Model
     * @since 2.1.50
     */
    public function setIntegrity(string $integrity): Model
    {
        $this->integrity = $integrity;
        return $this;
    }

    /**
     * Set hreflang for the link tag.
     * The value must be a valid BCP 47 language tag.
     * Example: en, fr, ...
     *
     * @param string $hreflang
     * @return Model
     * @since 2.1.22
     */
    public function setHreflang(string $hreflang): Model
    {
        $this->hreflang = $hreflang;
        return $this;
    }

    /**
     * Get hreflang for the link tag.
     *
     * @return null|string
     * @since 2.1.22
     */
    public function getHreflang(): ?string
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
     * @since 2.1.22
     */
    public function setType(string $type): Model
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get the type for the link tag.
     *
     * @return null|string
     * @since 2.1.22
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Get the referrer policy
     *
     * @return null|string
     * @since 2.1.50
     */
    public function getReferrerpolicy(): ?string
    {
        return $this->referrerpolicy;
    }

    /**
     * Set the referrer policy
     *
     * @param string $referrerpolicy
     * @return Model
     * @since 2.1.50
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
     * Set sizes for the link tag.
     * Example: 16x16, 32x32, any, ...
     *
     * @param string $sizes
     * @return Model
     * @since 2.1.22
     */
    public function setSizes(string $sizes): Model
    {
        $this->sizes = $sizes;
        return $this;
    }

    /**
     * Get sizes for the link tag.
     *
     * @return null|string
     * @since 2.1.22
     */
    public function getSizes(): ?string
    {
        return $this->sizes;
    }

    /**
     * Get imagesrcset
     *
     * @return null|string
     * @since 2.1.50
     */
    public function getImagesrcset(): ?string
    {
        return $this->imagesrcset;
    }

    /**
     * Set imagesrcset
     *
     * @param string $imagesrcset
     * @return Model
     * @since 2.1.50
     */
    public function setImagesrcset(string $imagesrcset): Model
    {
        $this->imagesrcset = $imagesrcset;
        return $this;
    }

    /**
     * Get Imagesizes
     *
     * @return null|string
     * @since 2.1.50
     */
    public function getImagesizes(): ?string
    {
        return $this->imagesizes;
    }

    /**
     * Set Imagesizes
     *
     * @param string $imagesizes
     * @return Model
     * @since 2.1.50
     */
    public function setImagesizes(string $imagesizes): Model
    {
        $this->imagesizes = $imagesizes;
        return $this;
    }

    /**
     * Get as (potential destination for a preload request)
     *
     * @return null|string
     * @since 2.1.50
     */
    public function getAs(): ?string
    {
        return $this->as;
    }

    /**
     * Set as (potential destination for a preload request)
     * The attribute must be specified on link elements that have a rel attribute that contains the preload keyword.
     * It may be specified on link elements that have a rel attribute that contains the modulepreload keyword; in such cases it must have a value which is a script-like destination.
     * For other link elements, it must not be specified.
     *
     * @param string $as
     * @return Model
     * @since 2.1.50
     */
    public function setAs(string $as): Model
    {
        if (!in_array(strtolower($as), self::validAs)) {
            throw new InvalidArgumentException('Invalid value for as.');
        }

        $this->as = strtolower($as);
        return $this;
    }

    /**
     * Get whether the element is potentially render-blocking
     *
     * @return null|string
     * @since 2.1.50
     */
    public function getBlocking(): ?string
    {
        return $this->blocking;
    }

    /**
     * Set whether the element is potentially render-blocking
     *
     * @param string $blocking render
     * @return Model
     * @since 2.1.50
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
     * Get color to use when customizing a site's icon (for rel="mask-icon")
     *
     * @return null|string
     * @since 2.1.50
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * Set color to use when customizing a site's icon (for rel="mask-icon")
     *
     * @param string $color
     * @return Model
     * @since 2.1.50
     */
    public function setColor(string $color): Model
    {
        $this->color = $color;
        return $this;
    }

    /**
     * Get whether the link is disabled
     *
     * @return bool
     * @since 2.1.50
     */
    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * Set whether the link is disabled
     *
     * @param bool $disabled
     * @return Model
     * @since 2.1.50
     */
    public function setDisabled(bool $disabled): Model
    {
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * Get the priority for fetches initiated by the element
     *
     * @return null|string
     * @since 2.1.50
     */
    public function getFetchpriority(): ?string
    {
        return $this->fetchpriority;
    }

    /**
     * Set the priority for fetches initiated by the element
     *
     * @param string $fetchpriority high|low|auto
     * @return Model
     * @since 2.1.50
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
     * Set title for the link tag.
     *
     * @param string $title
     * @return Model
     * @since 2.1.22
     */
    public function setTitle(string $title): Model
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title for the link tag.
     *
     * @return null|string
     * @since 2.1.22
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }
}
