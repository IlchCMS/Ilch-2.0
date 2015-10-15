<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Events\Mappers;

use Modules\Events\Models\Events as EventModel;

defined('ACCESS') or die('no direct access');

class Events extends \Ilch\Mapper
{
    /**
     * Gets the Event entries.
     *
     * @param array $where
     * @return EventModel[]|array
     */
    public function getEntries($where = array())
    {
        $entryArray = $this->db()->select('*')
                ->from('events')
                ->where($where)
                ->order(array('date_created' => 'ASC'))
                ->execute()
                ->fetchRows();

        if (empty($entryArray)) {
            return null;
        }

        $entry = array();

        foreach ($entryArray as $entries) {
            $entryModel = new EventModel();
            $entryModel->setId($entries['id']);
            $entryModel->setUserId($entries['user_id']);
            $entryModel->setDateCreated($entries['date_created']);
            $entryModel->setTitle($entries['title']);
            $entryModel->setPlace($entries['place']);
            $entryModel->setImage($entries['image']);
            $entryModel->setText($entries['text']);
            $entryModel->setShow($entries['show']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * Gets event.
     *
     * @param integer $id
     * @return EventModel|null
     */
    public function getEventById($id)
    {
        $eventRow = $this->db()->select('*')
                ->from('events')
                ->where(array('id' => $id))
                ->execute()
                ->fetchAssoc();

        if (empty($eventRow)) {
            return null;
        }

        $eventModel = new EventModel();
        $eventModel->setId($eventRow['id']);
        $eventModel->setUserId($eventRow['user_id']);
        $eventModel->setDateCreated($eventRow['date_created']);
        $eventModel->setTitle($eventRow['title']);
        $eventModel->setPlace($eventRow['place']);
        $eventModel->setImage($eventRow['image']);
        $eventModel->setText($eventRow['text']);
        $eventModel->setShow($eventRow['show']);

        return $eventModel;
    }

    /**
     * @return \Modules\Events\Mappers\Events[]
     */
    public function getEventListUpcoming($limit = null)
    {
        $eventMapper = new \Modules\Events\Mappers\Events();

        $sql = 'SELECT *
                FROM `[prefix]_events`
                WHERE DAY(date_created) >= DAY(CURDATE()) AND MONTH(date_created) = MONTH(CURDATE()) OR MONTH(date_created) = MONTH(CURDATE()+INTERVAL 1 MONTH)
                ORDER BY date_created ASC';

        if ($limit !== null) { $sql .= ' LIMIT '.$limit; }

        $rows = $this->db()->queryArray($sql);

        if (empty($rows)) {
            return null;
        }

        $events = array();

        foreach ($rows as $row) {
            $events[] = $eventMapper->getEventById($row['id']);
        }

        return $events;
    }

    /**
     * @return \Modules\Events\Mappers\Events[]
     */
    public function getEventListUpcomingALL()
    {
        $eventMapper = new \Modules\Events\Mappers\Events();

        $sql = 'SELECT *
                FROM `[prefix]_events`
                WHERE date_created >= CURDATE()
                ORDER BY date_created ASC';

        $rows = $this->db()->queryArray($sql);

        if (empty($rows)) {
            return null;
        }

        $events = array();

        foreach ($rows as $row) {
            $events[] = $eventMapper->getEventById($row['id']);
        }

        return $events;
    }

    /**
     * @return \Modules\Events\Mappers\Events[]
     */
    public function getEventListParticipation($userId)
    {
        $eventMapper = new \Modules\Events\Mappers\Events();
        
        $entryRow = $this->db()->select('*')
                ->from('events_entrants')
                ->where(array('user_id' => $userId))
                ->execute()
                ->fetchRows();

        if (empty($entryRow)) {
            return null;
        }
        
        $events = array();
        
        foreach ($entryRow as $row) {
            $events[] = $eventMapper->getEventById($row['event_id']);
        }

        return $events;
    }

    /**
     * @return \Modules\Events\Mappers\Events[]
     */
    public function getEventListOther($limit = null)
    {
        $eventMapper = new \Modules\Events\Mappers\Events();

        $sql = 'SELECT *
                FROM `[prefix]_events`
                WHERE DAY(date_created) > DAY(CURDATE()) AND MONTH(date_created) > MONTH(CURDATE()) AND MONTH(date_created) = MONTH(CURDATE()+INTERVAL 2 MONTH)
                ORDER BY date_created ASC';

        if ($limit !== null) { $sql .= ' LIMIT '.$limit; }

        $rows = $this->db()->queryArray($sql);

        if (empty($rows)) {
            return null;
        }

        $events = array();

        foreach ($rows as $row) {
            $events[] = $eventMapper->getEventById($row['id']);
        }

        return $events;
    }

    /**
     * @return \Modules\Events\Mappers\Events[]
     */
    public function getEventListPast($limit = null)
    {
        $eventMapper = new \Modules\Events\Mappers\Events();

        $sql = 'SELECT *
                FROM `[prefix]_events`
                WHERE date_created < CURDATE()
                ORDER BY date_created DESC';

        if ($limit !== null) { $sql .= ' LIMIT '.$limit; }

        $rows = $this->db()->queryArray($sql);
        
        if (empty($rows)) {
            return null;
        }

        $events = array();

        foreach ($rows as $row) {
            $events[] = $eventMapper->getEventById($row['id']);
        }

        return $events;
    }

    public function existsTable($table)
    {
        $module = $this->db()->ifTableExists('[prefix]_'.$table);

        return $module;
    }

    /**
     * Inserts or updates event model.
     *
     * @param EventModel $event
     */
    public function save(EventModel $event)
    {
        $fields = array
        (
            'user_id' => $event->getUserId(),
            'date_created' => $event->getDateCreated(),
            'title' => $event->getTitle(),
            'place' => $event->getPlace(),
            'image' => $event->getImage(),
            'text' => $event->getText(),
            'show' => $event->getShow(),
        );

        if ($event->getId()) {
            $this->db()->update('events')
                ->values($fields)
                ->where(array('id' => $event->getId()))
                ->execute();
        } else {
            $this->db()->insert('events')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Deletes event with given id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $imageRow = $this->db()->select('*')
            ->from('events')
            ->where(array('id' => $id))
            ->execute()
            ->fetchAssoc();

        if (file_exists($imageRow['image'])) {
            unlink($imageRow['image']);
        }

        $this->db()->delete('events')
                ->where(array('id' => $id))
                ->execute();

        $this->db()->delete('events_entrants')
                ->where(array('event_id' => $id))
                ->execute();

        $this->db()->delete('comments')
                ->where(array('key' => 'events/show/event/id/'.$id))
                ->execute();
    }

    /**
     * Delete/Unlink Image by id.
     *
     * @param int $id
     */
    public function delImageById($id) 
    {
        $imageRow = $this->db()->select('*')
            ->from('events')
            ->where(array('id' => $id))
            ->execute()
            ->fetchAssoc();

        if (file_exists($imageRow['image'])) {
            unlink($imageRow['image']);
        }

        $this->db()->update('events')
            ->values(array('image' => ''))
            ->where(array('id' => $id))
            ->execute();
    }
}
