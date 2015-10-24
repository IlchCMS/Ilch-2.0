<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Downloads\Models;

class File extends \Ilch\Model
{
    /**
     * The id of the image.
     *
     * @var integer
     */
    protected $id;

    /**
     * The fileId of the file.
     *
     * @var string
     */
    protected $file_id;

    /**
     * Title of the file.
     *
     * @var string
     */
    protected $file_title;

    /**
     * Description of the file.
     *
     * @var string
     */
    protected $file_desc;

    /**
     * Image of the file.
     *
     * @var string
     */
    protected $file_image;

    /**
     * The cat of the file.
     *
     * @var string
     */
    protected $cat;

    /**
     * The visits of the file.
     *
     * @var string
     */
    protected $visits;

    /**
     * The imageUrl of the file.
     *
     * @var string
     */
    protected $file_url;

   /**
     * Gets the id of the file.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the fileId of the file.
     *
     * @return string
     */
    public function getFileId()
    {
        return $this->file_id;
    }

    /**
     * Gets the fileThumb of the file.
     *
     * @return string
     */
    public function getFileThumb()
    {
        return $this->filethumb;
    }

    /**
     * Gets the file title.
     *
     * @return string
     */
    public function getFileTitle()
    {
        return $this->file_title;
    }

    /**
     * Gets the file desc.
     *
     * @return string
     */
    public function getFileDesc()
    {
        return $this->file_desc;
    }

    /**
     * Gets the file image.
     *
     * @return string
     */
    public function getFileImage()
    {
        return $this->file_image;
    }

    /**
     * Gets the cat of the file.
     *
     * @return string
     */
    public function getCat()
    {
        return $this->cat;
    }

    /**
     * Gets the visits of the file.
     *
     * @return string
     */
    public function getVisits()
    {
        return $this->visits;
    }

    /**
     * Gets the FileUrl of the file.
     *
     * @return string
     */
    public function getFileUrl()
    {
        return $this->file_url;
    }

    /**
     * Sets the id of the file.
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * Sets the fileId of the file.
     *
     * @param string $file_id
     */
    public function setFileId($file_id)
    {
        $this->file_id = (string)$file_id;
    }

    /**
     * Sets the fileThumb of the file.
     *
     * @param string $fileThumb
     */
    public function setFileThumb($fileThumb)
    {
        $this->filethumb = (string)$fileThumb;
    }

    /**
     * Sets the title.
     *
     * @param string $fileTitle
     */
    public function setFileTitle($fileTitle)
    {
        $this->file_title = (string) $fileTitle;
    }

    /**
     * Sets the image.
     *
     * @param string $fileImage
     */
    public function setFileImage($fileImage)
    {
        $this->file_image = (string) $fileImage;
    }

    /**
     * Sets the desc.
     *
     * @param string $fileDesc
     */
    public function setFileDesc($fileDesc)
    {
        $this->file_desc = (string) $fileDesc;
    }

    /**
     * Sets the cat of the file.
     *
     * @param string $cat
     */
    public function setCat($cat)
    {
        $this->cat = (string)$cat;
    }

    /**
     * Sets the visits of the file.
     *
     * @param string $visits
     */
    public function setVisits($visits)
    {
        $this->visits = (string)$visits;
    }

    /**
     * Sets the fileUrl of the file.
     *
     * @param string $fileUrl
     */
    public function setFileUrl($fileUrl)
    {
        $this->file_url = (string)$fileUrl;
    }
}