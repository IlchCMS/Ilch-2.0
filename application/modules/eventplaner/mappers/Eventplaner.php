<?php
/**
 * @copyright Balthazar3k
 */

namespace Eventplaner\Mappers;

use Eventplaner\Models\Eventplaner as EventModel;

defined('ACCESS') or die('no direct access');

class Eventplaner extends \Ilch\Mapper
{
    public function getEventList($where = array())
    {
        $entryArray = $this->db()->selectArray('*')
            ->from('ep_events')
            ->where($where)
            ->order(array('start' => 'ASC'))
            ->execute();

        if (empty($entryArray)) {
            return array();
        }

        $entry = array();

        foreach ($entryArray as $entries) {
            $entryModel = new EventModel();
            $entryModel->setId($entries['id']);
            $entryModel->setStatus($entries['status']);
            $entryModel->setStart($entries['start']);
            $entryModel->setEnds($entries['ends']);
            $entryModel->setRegistrations($entries['registrations']);
            $entryModel->setOrganizer($entries['organizer']);
            $entryModel->setTitle($entries['title']);
            $entryModel->setEvent($entries['event']);
            $entryModel->setMessage($entries['message']);
            $entryModel->setCreated($entries['created']);
            $entryModel->setChanged($entries['changed']);
            $entry[] = $entryModel;
        }

        return $entry;
    }
	
	public function getEvent($id)
	{
            $entryArray = $this->db()->selectRow('*')
                ->from('ep_events')
                ->where(array('id' => $id))
                ->execute();
			
            if (empty($entryArray)) {
                return array();
            }
		
		$entryModel = new EventModel();
		$entryModel->setId($entries['id']);
		$entryModel->setStatus($entries['status']);
		$entryModel->setStart($entries['start']);
		$entryModel->setEnds($entries['ends']);
		$entryModel->setOrganizer($entries['organizer']);
		$entryModel->setTitle($entries['title']);
		$entryModel->setEvent($entries['event']);
		$entryModel->setMessage($entries['message']);
		$entryModel->setCreated($entries['created']);
		$entryModel->setChanged($entries['changed']);
		return $entryModel;
	}
	
	public function getEventNames()
	{
            $entryArray = $this->db()->queryArray('
                SELECT DISTINCT event FROM [prefix]_ep_events
            ');
		
            $entry = array();
            
            foreach( $entryArray as $res )
            {    
                $entryModel = new EventModel();
                $entryModel->setEvent($res['event']);
                $entry[] = $entryModel;
            }
            
            return $entry;
	}
        
        public function getTitleJSON()
	{
            return json_encode($this->db()->queryList('
                SELECT DISTINCT title FROM [prefix]_ep_events
            '));
	}
	
	public function setEvent()
	{
		
	}
	
	public function changeEvent($id)
	{
		
	}

    public function delete($id)
    {
        return $this->db()->delete('ep_events')
            ->where(array('id' => $id))
            ->execute();
    }
}
?>