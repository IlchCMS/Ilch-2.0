<?php
/**
 * @copyright Ilch 2
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
     * The title of the category.
     *
     * @var string
     */
    private $title;

    /**
     * Value for read_access.
     *
     * @var string
     */
    private $read_access;

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
     * Get the value for read_access.
     *
     * @return string
     */
    public function getReadAccess()
    {
        return $this->read_access;
    }

    /**
     * Set the value for read_access.
     *
     * @param string $read_access
     * @return $this
     */
    public function setReadAccess($read_access)
    {
        $this->read_access = $read_access;
        return $this;
    }
}
