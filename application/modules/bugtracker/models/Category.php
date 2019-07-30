<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Bugtracker\Models;

class Category extends \Ilch\Model
{
    private $id;
    private $name;

    /**
     * Default Constructor of BugCategory
     * @param int $id 
     * @param string $name 
     */
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * ID of Category
     * @return integer
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Name of Category
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set ID ot the Category
     * @param int $id 
     */
    public function setID($id)
    {
        $this->id = $id;
    }

    /**
     * Set Name of the Category
     * @param string $name 
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
