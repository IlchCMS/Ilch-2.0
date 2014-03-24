<?php
/**
 * @copyright Balthazar3k 2014
 * @package Eventplaner 2.0
 */

namespace Eventplaner\Models;

defined('ACCESS') or die('no direct access');

class Eventplaner extends \Ilch\Model
{

    protected $_id;
    protected $_status;
    protected $_start;
    protected $_ends;
    protected $_registrations;
    protected $_organizer;
    protected $_title;
    protected $_event;
    protected $_message;
    protected $_created;
    protected $_changed;

	
    public function getId()
    {
        return $this->_id;
    }

    public function getStatus()
    {
        return $this->_status;
    }

    public function getStart()
    {
        return $this->_start;
    }
	
    public function getEnds()
    {
        return $this->_ends;
    }

    public function getRegistrations()
    {
        return $this->_registrations;
    }
	
    public function getOrganizer()
    {
        return $this->_organizer;
    }

    public function getTitle()
    {
        return $this->_title;
    }
	
    public function getEvent()
    {
        return $this->_event;
    }
	
    public function getMessage()
    {
        return $this->_message;
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

    public function setStatus($res)
    {
		$this->_status = (integer)$res;
    }
	
    public function setStart($res)
    {
		$this->_start = (string)$res;
    }
   
    public function setEnds($res)
    {
		$this->_ends = (string)$res;
    }
	
    public function setRegistrations($res)
    {
		$this->_registrations = (integer)$res;
    }
	
    public function setOrganizer($res)
    {
		$this->_organizer = (integer)$res;
    }
	
    public function setTitle($res)
    {
		$this->_title = (string)$res;
    }
	
    public function setEvent($res)
    {
		$this->_event = (string)$res;
    }
	
    public function setMessage($res)
    {
		$this->_message = (string)$res;
    }
	
    public function setCreated($res)
    {
		$this->_created = (string)$res;
    }
	
    public function setChanged($res)
    {
		$this->_changed = (string)$res;
    }
     
    public function getStartTS()
    {
        return strtotime($this->getStart());
    }
    
    public function getEndsTS()
    {
        return strtotime($this->getEnds());
    }
    
    public function getTimeDiff($pattern = 'H:i')
    {
        return date($pattern, $this->getEndsTS()-$this->getStartTS()); 
    }
    
    public function getStartDate($pattern = 'd.m.Y H:i')
    {
        return date($pattern, $this->getStartTS());
    }
    
    public function getEndsDate($pattern = 'd.m.Y H:i')
    {
        return date($pattern, $this->getEndsTS());
    }
}
?>