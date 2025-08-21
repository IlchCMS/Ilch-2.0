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
     * @param array $entries
     * @return $this
     * @since 1.23.4
     */
    public function setByArray(array $entries): Image
    {
        if (!empty($entries['imgid']) || !empty($entries['id'])) {
            $this->setId($entries['imgid'] ?? $entries['id']);
        }
        if (!empty($entries['image_id'])) {
            $this->setImageId($entries['image_id']);
        }
        if (!empty($entries['url'])) {
            $this->setImageUrl($entries['url']);
        }
        if (!empty($entries['url_thumb'])) {
            $this->setImageThumb($entries['url_thumb']);
        }
        if (!empty($entries['image_title'])) {
            $this->setImageTitle($entries['image_title']);
        }
        if (!empty($entries['image_description'])) {
            $this->setImageDesc($entries['image_description']);
        }
        if (!empty($entries['gallery_id'])) {
            $this->setGalleryId($entries['gallery_id']);
        }
        if (!empty($entries['visits'])) {
            $this->setVisits($entries['visits']);
        }

        return $this;
    }

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

    /**
     * @param bool $withId
     * @return array
     * @since 1.23.4
     */
    public function getArray(bool $withId = true): array
    {
        return array_merge(
            ($withId ? ['id' => $this->getId()] : []),
            [
                'image_id' =>    $this->getImageId(),
                'image_title' =>     $this->getImageTitle(),
                'image_description' =>      $this->getImageDesc(),
                'gallery_id' =>        $this->getGalleryId(),
                'visits' =>       $this->getVisits(),
                'show' =>       $this->getShow(),
                'status' =>     $this->getStatus()
            ]
        );
    }
}
