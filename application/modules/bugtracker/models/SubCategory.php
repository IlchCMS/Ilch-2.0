<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Bugtracker\Models;

class SubCategory extends \Ilch\Model
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Category
     */
    private $category;

    /**
     * @var string
     */
    private $name;

    /**
     * Default constructor of BugSubCategory
     * @param int $id
     * @param Category $category
     * @param string $name
     */
    public function __construct($id, $category, $name)
    {
        $this->id = $id;
        $this->category = $category;
        $this->name = $name;
    }

    /**
     * ID of SubCategory
     * @return int
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Returns the ParentCategory
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Name of SubCategory
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set ID ot the SubCategory
     * @param int $id
     */
    public function setID($id)
    {
        $this->id = $id;
    }

    /**
     * Set the parent Category
     * @param Category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * Set Name of the SubCategory
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
