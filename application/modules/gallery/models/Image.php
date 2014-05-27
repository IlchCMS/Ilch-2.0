<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Gallery\Models;

defined('ACCESS') or die('no direct access');

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
     * The cat of the image.
     *
     * @var string
     */
    protected $cat;

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
     * Gets the cat of the image.
     *
     * @return string
     */
    public function getCat()
    {
        return $this->cat;
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
     * Sets the cat of the image.
     *
     * @param string $cat
     */
    public function setCat($cat)
    {
        $this->cat = (string)$cat;
    }
}