<?php
/**
 * @copyright Balthazar3k 2014
 * @package Eventplaner 2.0
 */

namespace Eventplaner\Models;

defined('ACCESS') or die('no direct access');

class Registrations extends \Ilch\Model
{
    protected $_id;
    protected $_status;
    protected $_eid;
    protected $_uid;
    protected $_cid;
    protected $_comemnt;
    protected $_changed;
    protected $_registered;
	
	
    public function getId()
    {
        return $this->_id;
    }

    public function getStatus()
    {
        return $this->_status;
    }

    public function getEid()
    {
        return $this->_eid;
    }
	
    public function getUid()
    {
        return $this->_uid;
    }

    public function getCid()
    {
        return $this->_cid;
    }

    public function getComment()
    {
        return $this->_comment;
    }
	
    public function getChanged()
    {
        return $this->_changed;
    }
	
    public function getRegistered()
    {
        return $this->_registered;
    }

    public function setId($id)
    {
        $this->_id = (integer)$id;
    }

    public function setStatus($res)
    {
        $this->_status = (integer)$res;
    }
	
    public function setEid($res)
    {
        $this->_eid = (integer)$res;
    }
	
    public function setUid($res)
    {
        $this->_uid = (integer)$res;
    }
	
    public function setCid($res)
    {
        $this->_cid = (integer)$res;
    }
	
    public function setComment($res)
    {
        $this->_comment = (string)$res;
    }
	
    public function setChanged($res)
    {
        $this->_changed = (string)$res;
    }
	
    public function setRegistered($res)
    {
        $this->_registered = (string)$res;
    }
    
    
    public function getUserName()
    {
        $user = new \User\Mappers\User();
        return $user->getUserById($this->_uid)->getName();
    }
}
?>