<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Models;

class GalleryImage extends \Ilch\Model
{
    /**
     * The id of the image.
     *
     * @var integer
     */
    protected $id;

    /**
     * The userId of the image.
     *
     * @var integer
     */
    protected $userId;

    /**
     * The imageId of the image.
     *
     * @var string
     */
    protected $imageId;

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
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the userId of the image.
     *
     * @return integer
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
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * Sets the userId of the image.
     *
     * @param string $userId
     */
    public function setUserId($userId)
    {
        $this->userId = (int)$userId;
    }

    /**
     * Sets the imageId of the image.
     *
     * @param string $imageId
     */
    public function setImageId($imageId)
    {
        $this->imageId = (string)$imageId;
    }

    /**
     * Sets the thumb of the image.
     *
     * @param string $thumb
     */
    public function setImageThumb($thumb)
    {
        $this->thumb = (string)$thumb;
    }

    /**
     * Sets the title.
     *
     * @param string $title
     */
    public function setImageTitle($title)
    {
        $this->title = (string) $title;
    }

    /**
     * Sets the desc.
     *
     * @param string $desc
     */
    public function setImageDesc($desc)
    {
        $this->desc = (string) $desc;
    }

    /**
     * Sets the cat of the image.
     *
     * @param string $cat
     */
    public function setCat($cat)
    {
        $this->cat = (string)$cat;
    }

    /**
     * Sets the visits of the image.
     *
     * @param string $visits
     */
    public function setVisits($visits)
    {
        $this->visits = (string)$visits;
    }

    /**
     * Sets the Url of the image.
     *
     * @param string $url
     */
    public function setImageUrl($url)
    {
        $this->url = (string)$url;
    }
}