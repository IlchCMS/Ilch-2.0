<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Models;

defined('ACCESS') or die('no direct access');

/**
 * The user category model class.
 */
class Category extends \Ilch\Mapper
{
    /**
     * The id of the category.
     *
     * @var int
     */
    private $id;

    /**
     * The name of the category.
     *
     * @var string
     */
    private $name;

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
}
