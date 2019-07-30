<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Bugtracker\Models;

class Status extends \Ilch\Model
{
    const NEW_REPORT = 1;
    const CONFIRMED = 2;
    const ASSIGNED = 3;
    const IN_PROGRESS = 4;
    const COMPLETE = 5;
    const READY_FOR_TESTING = 6;
    const FIXED = 7;
    const CLOSED = 8;
    const NO_BUG = 9;

    private $id;
    private $name;
    private $cssClass;

    /**
     * Default Constructor of BugStatus
     * @param int $id
     * @param string $name
     */
    public function __construct($id, $name, $cssClass)
    {
        $this->id = $id;
        $this->name = $name;
        $this->cssClass = $cssClass;
    }

    /**
     * ID of Status
     * @return integer
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Name of Status
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set ID ot the Status
     * @param int $id
     */
    public function setID($id)
    {
        $this->id = $id;
    }

    /**
     * Set Name of the Status
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function getCssClass()
    {
        return $this->cssClass;
    }

    public function setCssClass($cssClass)
    {
        $this->cssClass = $cssClass;
    }
}
