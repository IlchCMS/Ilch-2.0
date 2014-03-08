<?php
/**
 * @copyright Balthazar3k
 * @package eventplaner
 */

namespace Eventplaner\Models;

defined('ACCESS') or die('no direct access');

class Eventplaner extends \Ilch\Model
{

    protected $_id;
    protected $_aktiv;
    protected $_start;
    protected $_ends;
    protected $_organizer;
	protected $_title;
	protected $_message;
	protected $_created;
	//protected $_;

	
    public function getId()
    {
        return $this->_id;
    }

    public function getAktiv()
    {
        return $this->_aktiv;
    }

    public function getStart()
    {
        return $this->_start;
    }
	
	public function getEnds()
    {
        return $this->_ends;
    }

    public function getOrganizer()
    {
        return $this->_organizer;
    }

    public function getTitle()
    {
        return $this->_title;
    }
	
	public function getMessage()
    {
        return $this->_message;
    }
	
	public function getCreated()
    {
        return $this->_created;
    }

	## SETTER #################################### 

    public function setId($id)
    {
        $this->_id = (integer)$id;
    }

	public function setAktiv($res)
    {
		$this->_aktiv = (integer)$res;
    }
	
	public function setStart($res)
    {
		$this->_start = (integer)$res;
    }
   
   	public function setEnds($res)
    {
		$this->_ends = (integer)$res;
    }
	
	public function setOrganizer($res)
    {
		$this->_organizer = (integer)$res;
    }
	
	public function setTitle($res)
    {
		$this->_title = (string)$res;
    }
	
	public function setMessage($res)
    {
		$this->_message = (string)$res;
    }
	
	public function setCreated($res)
    {
		$this->_created = (integer)$res;
    }
}
