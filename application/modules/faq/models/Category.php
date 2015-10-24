<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Faq\Models;

class Category extends \Ilch\Mapper
{
    /**
     * The id of the category.
     *
     * @var int
     */
    private $id;

    /**
     * The parentId of the category.
     *
     * @var int
     */
    private $parentId;

    /**
     * The parentId of the category.
     *
     * @var int
     */
    private $childId;

    /**
     * The title of the category.
     *
     * @var string
     */
    private $title;

    /**
     * The text of the category.
     *
     * @var string
     */
    private $text;

    /**
     * Gets the category id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the category.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * Gets the category parentId.
     *
     * @return int
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Sets the parentId of the category.
     *
     * @param int $childId
     */
    public function setParentId($parentId)
    {
        $this->parentId = (int)$parentId;
    }

    /**
     * Gets the category parentId.
     *
     * @return int
     */
    public function getChildId()
    {
        return $this->childId;
    }

    /**
     * Sets the parentId of the category.
     *
     * @param int $id
     */
    public function setChildId($childId)
    {
        $this->childId = (int)$childId;
    }

    /**
     * Gets the title of the category.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title of the category.
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = (string)$title;
    }

    /**
     * Gets the text of the category.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets the text of the category.
     *
     * @param string $text
     * @return this
     */
    public function setText($text)
    {
        $this->text = (string)$text;

        return $this;
    }
}
