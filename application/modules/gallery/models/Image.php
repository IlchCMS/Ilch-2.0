<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Gallery\Models;

use Ilch\Model;

/**
 * Image model
 */
class Image extends Model
{
    /**
     * The id of the image.
     *
     * @var int
     */
    protected $id = 0;

    /**
     * The imageId of the image.
     *
     * @var int
     */
    protected $image_id = 0;

    /**
     * The imageThumb of the image.
     *
     * @var string
     */
    protected $image_thumb = '';

    /**
     * Title of the image.
     *
     * @var string
     */
    protected $image_title = '';

    /**
     * Description of the image.
     *
     * @var string
     */
    protected $image_desc = '';

    /**
     * The gallery id of the image.
     *
     * @var int
     */
    protected $gallery_id = 0;

    /**
     * The visits of the image.
     *
     * @var int
     */
    protected $visits = 0;

    /**
     * The imageUrl of the image.
     *
     * @var string
     */
    protected $image_url = '';

    /**
     * Gets the id of the image.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Gets the imageId of the image.
     *
     * @return int
     */
    public function getImageId(): int
    {
        return $this->image_id;
    }

    /**
     * Gets the imageThumb of the image.
     *
     * @return string
     */
    public function getImageThumb(): string
    {
        return $this->image_thumb;
    }

    /**
     * Gets the image title.
     *
     * @return string
     */
    public function getImageTitle(): string
    {
        return $this->image_title;
    }

    /**
     * Gets the image desc.
     *
     * @return string
     */
    public function getImageDesc(): string
    {
        return $this->image_desc;
    }

    /**
     * Gets the gallery id of the image.
     *
     * @return int
     */
    public function getGalleryId(): int
    {
        return $this->gallery_id;
    }

    /**
     * Gets the visits of the image.
     *
     * @return int
     */
    public function getVisits(): int
    {
        return $this->visits;
    }

    /**
     * Gets the ImageUrl of the image.
     *
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->image_url;
    }

    /**
     * Sets the id of the image.
     *
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * Sets the imageId of the image.
     *
     * @param int $image_id
     */
    public function setImageId(int $image_id)
    {
        $this->image_id = $image_id;
    }

    /**
     * Sets the imageThumb of the image.
     *
     * @param string $imagethumb
     */
    public function setImageThumb(string $imagethumb)
    {
        $this->image_thumb = $imagethumb;
    }

    /**
     * Sets the title.
     *
     * @param string $imageTitle
     */
    public function setImageTitle(string $imageTitle)
    {
        $this->image_title = $imageTitle;
    }

    /**
     * Sets the desc.
     *
     * @param string $imageDesc
     */
    public function setImageDesc(string $imageDesc)
    {
        $this->image_desc = $imageDesc;
    }

    /**
     * Sets the gallery id of the image.
     *
     * @param int $galleryId
     */
    public function setGalleryId(int $galleryId)
    {
        $this->gallery_id = $galleryId;
    }

    /**
     * Sets the visits of the image.
     *
     * @param int $visits
     */
    public function setVisits(int $visits)
    {
        $this->visits = $visits;
    }

    /**
     * Sets the ImageUrl of the image.
     *
     * @param string $imageUrl
     */
    public function setImageUrl(string $imageUrl)
    {
        $this->image_url = $imageUrl;
    }
}
