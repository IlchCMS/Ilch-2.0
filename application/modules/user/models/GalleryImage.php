<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Models;

class GalleryImage extends \Ilch\Model
{
    /**
     * The id of the image.
     *
     * @var int
     */
    protected $id;

    /**
     * The userId of the image.
     *
     * @var int
     */
    protected $userId;

    /**
     * The imageId of the image.
     *
     * @var string
     */
    protected $imageId;

    /**
     * The thumb of the image.
     *
     * @var string
     */
    protected $thumb;

    /**
     * Title of the image.
     *
     * @var string
     */
    protected $title;

    /**
     * Description of the image.
     *
     * @var string
     */
    protected $desc;

    /**
     * The cat of the image.
     *
     * @var string
     */
    protected $cat;

    /**
     * The visits of the image.
     *
     * @var string
     */
    protected $visits;

    /**
     * The imageUrl of the image.
     *
     * @var string
     */
    protected $url;

    /**
     * Gets the id of the image.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the userId of the image.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Gets the imageId of the image.
     *
     * @return string
     */
    public function getImageId()
    {
        return $this->imageId;
    }

    /**
     * Gets the imageThumb of the image.
     *
     * @return string
     */
    public function getImageThumb()
    {
        return $this->thumb;
    }

    /**
     * Gets the image title.
     *
     * @return string
     */
    public function getImageTitle()
    {
        return $this->title;
    }

    /**
     * Gets the image desc.
     *
     * @return string
     */
    public function getImageDesc()
    {
        return $this->desc;
    }

    /**
     * Gets the cat of the image.
     *
     * @return string
     */
    public function getCat()
    {
        return $this->cat;
    }

    /**
     * Gets the visits of the image.
     *
     * @return string
     */
    public function getVisits()
    {
        return $this->visits;
    }

    /**
     * Gets the ImageUrl of the image.
     *
     * @return string
     */
    public function getImageUrl()
    {
        return $this->url;
    }

    /**
     * Sets the id of the image.
     *
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Sets the userId of the image.
     *
     * @param string $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = (int)$userId;

        return $this;
    }

    /**
     * Sets the imageId of the image.
     *
     * @param string $imageId
     * @return $this
     */
    public function setImageId($imageId)
    {
        $this->imageId = (string)$imageId;

        return $this;
    }

    /**
     * Sets the thumb of the image.
     *
     * @param string $thumb
     * @return $this
     */
    public function setImageThumb($thumb)
    {
        $this->thumb = (string)$thumb;

        return $this;
    }

    /**
     * Sets the title.
     *
     * @param string $title
     * @return $this
     */
    public function setImageTitle($title)
    {
        $this->title = (string) $title;

        return $this;
    }

    /**
     * Sets the desc.
     *
     * @param string $desc
     * @return $this
     */
    public function setImageDesc($desc)
    {
        $this->desc = (string) $desc;

        return $this;
    }

    /**
     * Sets the cat of the image.
     *
     * @param string $cat
     * @return $this
     */
    public function setCat($cat)
    {
        $this->cat = (string)$cat;

        return $this;
    }

    /**
     * Sets the visits of the image.
     *
     * @param string $visits
     * @return $this
     */
    public function setVisits($visits)
    {
        $this->visits = (string)$visits;

        return $this;
    }

    /**
     * Sets the Url of the image.
     *
     * @param string $url
     * @return $this
     */
    public function setImageUrl($url)
    {
        $this->url = (string)$url;

        return $this;
    }
}
