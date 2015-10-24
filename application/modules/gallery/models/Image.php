<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Gallery\Models;

class Image extends \Ilch\Model
{
    /**
     * The id of the image.
     *
     * @var integer
     */
    protected $id;

    /**
     * The imageId of the image.
     *
     * @var string
     */
    protected $image_id;

    /**
     * Title of the image.
     *
     * @var string
     */
    protected $image_title;

    /**
     * Description of the image.
     *
     * @var string
     */
    protected $image_desc;

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
    protected $image_url;

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
     * Gets the imageId of the image.
     *
     * @return string
     */
    public function getImageId()
    {
        return $this->image_id;
    }

    /**
     * Gets the imageThumb of the image.
     *
     * @return string
     */
    public function getImageThumb()
    {
        return $this->imagethumb;
    }

    /**
     * Gets the image title.
     *
     * @return string
     */
    public function getImageTitle()
    {
        return $this->image_title;
    }

    /**
     * Gets the image desc.
     *
     * @return string
     */
    public function getImageDesc()
    {
        return $this->image_desc;
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
        return $this->image_url;
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
     * Sets the imageId of the image.
     *
     * @param string $image_id
     */
    public function setImageId($image_id)
    {
        $this->image_id = (string)$image_id;
    }

    /**
     * Sets the imageThumb of the image.
     *
     * @param string $imagethumb
     */
    public function setImageThumb($imagethumb)
    {
        $this->imagethumb = (string)$imagethumb;
    }

    /**
     * Sets the title.
     *
     * @param string $imageTitle
     */
    public function setImageTitle($imageTitle)
    {
        $this->image_title = (string) $imageTitle;
    }

    /**
     * Sets the desc.
     *
     * @param string $imageDesc
     */
    public function setImageDesc($imageDesc)
    {
        $this->image_desc = (string) $imageDesc;
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
     * Sets the ImageUrl of the image.
     *
     * @param string $imageUrl
     */
    public function setImageUrl($imageUrl)
    {
        $this->image_url = (string)$imageUrl;
    }
}