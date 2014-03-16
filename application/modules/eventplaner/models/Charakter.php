<?php
/**
 * @copyright Balthazar3k 2014
 * @package Eventplaner 2.0
 */

namespace Eventplaner\Models;

defined('ACCESS') or die('no direct access');

class Charakter extends \Ilch\Model
{

    protected $_id;
    protected $_uid;
    protected $_name;
    protected $_created;
    protected $_changed;


    public function getId()
    {
        return $this->_id;
    }

    public function getUid()
    {
        return $this->_uid;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getCreated()
    {
        return $this->_created;
    }

    public function getChanged()
    {
        return $this->_changed;
    }

	## SETTER #################################### 

    public function setId($id)
    {
        $this->_id = (integer)$id;
    }

	public function setUid($uid)
    {
		$this->_uid = (integer)$uid;
    }


    public function setName($name)
    {
        $this->_name = (string)$name;
    }

    public function setCreated($created)
    {
        $this->_created = (string)$created;
    }

    public function setChanged($changed)
    {
        $this->_changed = (string)$changed;
    }
   
}
?>