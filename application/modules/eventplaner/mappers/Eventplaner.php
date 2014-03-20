<?php
/**
 * @copyright Balthazar3k 2014
 * @package Eventplaner 2.0
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
	
    public function getEventById($eventId)
    {   
        $res = $this->db()->selectRow('*')
                    ->from('ep_events')
                    ->where(array('id' => $eventId))
                    ->execute();

        if(empty($res)){
            return false;
        }

        $model = new EventModel();
        $model->setId($res['id']);
        $model->setStatus($res['status']);
        $model->setStart($res['start']);
        $model->setEnds($res['ends']);
        $model->setRegistrations($res['registrations']);
        $model->setOrganizer($res['organizer']);
        $model->setTitle($res['title']);
        $model->setEvent($res['event']);
        $model->setMessage($res['message']);
        $model->setCreated($res['created']);
        $model->setChanged($res['changed']);

        return $model;
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

    public function getEventStatus()
    {
        $entryArray = $this->db()->queryArray('
            SELECT DISTINCT status FROM [prefix]_ep_events
        ');

        $entry = array();

        foreach( $entryArray as $res )
        {    
            $entryModel = new EventModel();
            $entryModel->setStatus($res['status']);
            $entry[] = $entryModel;
        }

        return $entry;
    }
        
	
    public function save(EventModel $eventplaner)
    {
        $fields = array();
        $status = $eventplaner->getStatus();
        $start = $eventplaner->getStart();
        $ends = $eventplaner->getEnds();
        $registrations = $eventplaner->getRegistrations();
        $organizer = $eventplaner->getOrganizer();
        $title = $eventplaner->getTitle();
        $event = $eventplaner->getEvent();
        $message = $eventplaner->getMessage();
        $changed = $eventplaner->getChanged();

        if(!empty($status)) {
            $fields['status'] = $eventplaner->getStatus();
        }

        if(!empty($start)) {
            $fields['start'] = $eventplaner->getStart();
        }

        if(!empty($ends)) {
            $fields['ends'] = $eventplaner->getEnds();
        }

        if(!empty($registrations)) {
            $fields['registrations'] = $eventplaner->getRegistrations();
        }

        if(!empty($organizer)) {
            $fields['organizer'] = $eventplaner->getOrganizer();
        }

        if(!empty($title)) {
            $fields['title'] = $eventplaner->getTitle();
        }

        if(!empty($event)) {
            $fields['event'] = $eventplaner->getEvent();
        }

        if(!empty($message)) {
            $fields['message'] = $eventplaner->getMessage();
        }

        if(!empty($changed)) {
            $fields['changed'] = $eventplaner->getChanged();
        }

        $eventId = (int)$this->db()->selectCell('id')
                ->from('ep_events')
                ->where(array('id' => $eventplaner->getId()))
                ->execute();
        
        if($eventId) {
            $this->db()->update('ep_events')
                ->fields($fields)
                ->where(array('id' => $eventId))
                ->execute();
        } else {
            
            $fields['created'] = $eventplaner->getCreated();

            $userId = $this->db()->insert('ep_events')
                ->fields($fields)
                ->execute();
        }


    }

    public function delete($id)
    {
        return $this->db()->delete('ep_events')
            ->where(array('id' => $id))
            ->execute();
    }
}
?>