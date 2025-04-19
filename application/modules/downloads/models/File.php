<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Downloads\Models;

use Ilch\Model;

class File extends Model
{
    /**
     * The id of the image.
     *
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * The fileId of the file.
     *
     * @var int
     */
    protected int $file_id;

    /**
     * Title of the file.
     *
     * @var string
     */
    protected string $file_title;

    /**
     * Description of the file.
     *
     * @var string
     */
    protected string $file_desc;

    /**
     * Image of the file.
     *
     * @var string
     */
    protected string $file_image;

    /**
     * The item id of the file.
     *
     * @var int
     */
    protected int $item_id;

    /**
     * The visits of the file.
     *
     * @var int
     */
    protected int $visits;

    /**
     * The imageUrl of the file.
     *
     * @var string
     */
    protected string $file_url;

    /**
     * @var string
     */
    private string $filethumb;

    /**
     * Gets the id of the file.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the fileId of the file.
     *
     * @return string
     */
    public function getFileId(): string
    {
        return $this->file_id;
    }

    /**
     * Gets the fileThumb of the file.
     *
     * @return string
     */
    public function getFileThumb(): string
    {
        return $this->filethumb;
    }

    /**
     * Gets the file title.
     *
     * @return string
     */
    public function getFileTitle(): string
    {
        return $this->file_title;
    }

    /**
     * Gets the file desc.
     *
     * @return string
     */
    public function getFileDesc(): string
    {
        return $this->file_desc;
    }

    /**
     * Gets the file image.
     *
     * @return string
     */
    public function getFileImage(): string
    {
        return $this->file_image;
    }

    /**
     * Gets the item id of the file.
     *
     * @return string
     */
    public function getItemId(): string
    {
        return $this->item_id;
    }

    /**
     * Gets the visits of the file.
     *
     * @return int
     */
    public function getVisits(): int
    {
        return $this->visits;
    }

    /**
     * Gets the FileUrl of the file.
     *
     * @return string
     */
    public function getFileUrl(): string
    {
        return $this->file_url;
    }

    /**
     * Sets the id of the file.
     *
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * Sets the fileId of the file.
     *
     * @param string $file_id
     */
    public function setFileId(string $file_id)
    {
        $this->file_id = $file_id;
    }

    /**
     * Sets the fileThumb of the file.
     *
     * @param string $fileThumb
     */
    public function setFileThumb(string $fileThumb)
    {
        $this->filethumb = $fileThumb;
    }

    /**
     * Sets the title.
     *
     * @param string $fileTitle
     */
    public function setFileTitle(string $fileTitle)
    {
        $this->file_title = $fileTitle;
    }

    /**
     * Sets the image.
     *
     * @param string $fileImage
     */
    public function setFileImage(string $fileImage)
    {
        $this->file_image = $fileImage;
    }

    /**
     * Sets the desc.
     *
     * @param string $fileDesc
     */
    public function setFileDesc(string $fileDesc)
    {
        $this->file_desc = $fileDesc;
    }

    /**
     * Sets the item id of the file.
     *
     * @param int $itemId
     */
    public function setItemId(int $itemId)
    {
        $this->item_id = $itemId;
    }

    /**
     * Sets the visits of the file.
     *
     * @param int $visits
     */
    public function setVisits(int $visits)
    {
        $this->visits = $visits;
    }

    /**
     * Sets the fileUrl of the file.
     *
     * @param string $fileUrl
     */
    public function setFileUrl(string $fileUrl)
    {
        $this->file_url = $fileUrl;
    }
}
