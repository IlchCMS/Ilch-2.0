<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Link\Models;

class Category extends \Ilch\Mapper
{
    /**
     * The id of the category.
     *
     * @var int
     */
    private $id;

    /**
     * The position of the category.
     *
     * @var int
     */
    protected $position;

    /**
     * The name of the category.
     *
     * @var string
     */
    private $name;

    /**
     * The catid of the category.
     *
     * @var int
     */
    private $cat;

    /**
     * The description of the category.
     *
     * @var string
     */
    private $desc;
    
    /**
     * The links count of the category.
     *
     * @var integer
     */
    private $linksCount;

    /**
     * Returns the user category id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the category id.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * Returns the position of the category.
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Sets the position.
     *
     * @param int $position
     * @return this
     */
    public function setPosition($position)
    {
        $this->position = (int)$position;
        return $this;
    }

    /**
     * Returns the category name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the category name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = (string) $name;
    }

    /**
     * Returns the category id.
     *
     * @return int
     */
    public function getParentId()
    {
        return $this->cat;
    }

    /**
     * Sets the category id.
     *
     * @param int $cat
     */
    public function setParentId($cat)
    {
        $this->cat = $cat;
    }

    /**
     * Gets the description of the category.
     *
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * Sets the description of the category.
     *
     * @param string $desc
     * @return this
     */
    public function setDesc($desc)
    {
        $this->desc = (string)$desc;

        return $this;
    }
    
    /**
     * Gets the links count of the category.
     *
     * @return integer
     */
    public function getLinksCount()
    {
        return $this->linksCount;
    }

    /**
     * Sets the links count of the category.
     *
     * @param integer $count
     * @return this
     */
    public function setLinksCount($count)
    {
        $this->linksCount = $count;

        return $this;
    }
}
