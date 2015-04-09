<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Events\Mappers;

use Modules\Events\Models\Events as EventModel;
use Ilch\Date as IlchDate;

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
                ->order(array('date_created' => 'DESC'))
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
            $entryModel->setText($entries['text']);
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
        $eventModel->setText($eventRow['text']);

        return $eventModel;
    }
    


    /**
     * Gets eventid.
     *
     * @param integer $id
     * @return EventModel|null
     */
    public function getCommentEventId($id)
    {
        $eventRow = $this->db()->select('*')
                ->from('events_comments')
                ->where(array('id' => $id))
                ->execute()
                ->fetchAssoc();

        if (empty($eventRow)) {
            return null;
        }

        $eventModel = new EventModel();
        $eventModel->setEventId($eventRow['event_id']);

        return $eventModel;
    }

    /**
     * Gets event.
     *
     * @param integer $id
     * @return EventModel|null
     */
    public function getEvent($id)
    {
        $sql = 'SELECT e.user_id as eventuserid, e.*, t.*
                FROM [prefix]_events as e
                LEFT JOIN [prefix]_events_entrants as t ON e.id = t.event_id
                WHERE e.`id` = "'.(int) $id.'"';
        $eventRow = $this->db()->queryRow($sql);

        if (empty($eventRow)) {
            return null;
        }

        $eventModel = new EventModel();
        $eventModel->setId($eventRow['id']);
        $eventModel->setEventUserId($eventRow['eventuserid']);
        $eventModel->setUserId($eventRow['user_id']);
        $eventModel->setDateCreated($eventRow['date_created']);
        $eventModel->setTitle($eventRow['title']);
        $eventModel->setPlace($eventRow['place']);
        $eventModel->setStatus($eventRow['status']);
        $eventModel->setText($eventRow['text']);

        return $eventModel;
    }

    /**
     * Gets the Event entrants.
     *
     * @param integer $id
     * @return EventModel|null
     */
    public function getEventEntrants($id)
    {
        $entryArray = $this->db()->select('*')
                ->from('events_entrants')
                ->where(array('event_id' => $id))
                ->execute()
                ->fetchRows();

        if (empty($entryArray)) {
            return null;
        }

        $entry = array();

        foreach ($entryArray as $entries) {
            $entryModel = new EventModel();
            $entryModel->setUserId($entries['user_id']);
            $entryModel->setStatus($entries['status']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * Gets the Event comments.
     *
     * @param integer $id
     * @return EventModel|null
     */
    public function getEventComments($id)
    {
        $entryArray = $this->db()->select('*')
                ->from('events_comments')
                ->where(array('event_id' => $id))
                ->order(array('date_created' => 'DESC'))
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
            $entryModel->setText($entries['text']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * @return \Modules\Event\Models\User[]
     */
    public function getBirthdayUserListNOW()
    {
        $userMapper = new \Modules\User\Mappers\User();

        $sql = 'SELECT *
                FROM `[prefix]_users`
                WHERE DAY(birthday) = DAY(CURDATE()) AND MONTH(birthday) = MONTH(CURDATE())';
        $rows = $this->db()->queryArray($sql);
        
        if (empty($rows)) {
            return null;
        }
        
        $users = array();

        foreach ($rows as $row) {
            $users[] = $userMapper->getUserById($row['id']);
        }

        return $users;
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
            'text' => $event->getText(),
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
     * Inserts user on event model.
     *
     * @param EventModel $event
     */
    public function saveUserOnEvent(EventModel $event)
    {
        $fields = array
        (
            'event_id' => $event->getEventId(),
            'user_id' => $event->getUserId(),
            'status' => $event->getStatus(),
        );
        
        $userId = (int) $this->db()->select('user_id')
                        ->from('events_entrants')
                        ->where(array('user_id' => $event->getUserId()))
                        ->execute()
                        ->fetchCell();

        if ($userId) {
            /*
             * User does exist already, update.
             */
            $this->db()->update('events_entrants')
                    ->values($fields)
                    ->where(array('user_id' => $userId))
                    ->execute();
        } else {

            /*
             * User does not exist yet, insert.
             */
            $userId = $this->db()->insert('events_entrants')
                    ->values($fields)
                    ->execute();
        }
    }

    /**
     * Inserts event comment model.
     *
     * @param EventModel $event
     */
    public function saveComment(EventModel $event)
    {
        $fields = array
        (
            'event_id' => $event->getId(),
            'user_id' => $event->getUserId(),
            'date_created' => $event->getDateCreated(),
            'text' => $event->getText(),
        );

        $this->db()->insert('events_comments')
            ->values($fields)
            ->execute();
    }

    /**
     * Deletes user from event with given userId.
     *
     * @param integer $id
     */
    public function deleteUserOnEvent($userId)
    {
        $this->db()->delete('events_entrants')
                ->where(array('user_id' => $userId))
                ->execute();
    }

    /**
     * Deletes event comment with given id.
     *
     * @param integer $id
     */
    public function deleteComment($id)
    {
        $this->db()->delete('events_comments')
                ->where(array('id' => $id))
                ->execute();
    }

    /**
     * Deletes event with given id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('events')
                ->where(array('id' => $id))
                ->execute();
        
        $this->db()->delete('events_entrants')
                ->where(array('event_id' => $id))
                ->execute();
        
        $this->db()->delete('events_comments')
                ->where(array('event_id' => $id))
                ->execute();
    }
}
