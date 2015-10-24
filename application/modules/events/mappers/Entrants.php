<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Events\Mappers;

use Modules\Events\Models\Entrants as EntrantsModel;

class Entrants extends \Ilch\Mapper
{
    /**
     * Gets the Event entrants.
     *
     * @param integer $id
     * @return EntrantsModel|null
     */
    public function getEventEntrants($eventId, $userId)
    {
        $entryRow = $this->db()->select('*')
                ->from('events_entrants')
                ->where(array('event_id' => $eventId, 'user_id' => $userId))
                ->execute()
                ->fetchAssoc();

        if (empty($entryRow)) {
            return null;
        }

        $entryModel = new EntrantsModel();
        $entryModel->setEventId($entryRow['event_id']);
        $entryModel->setUserId($entryRow['user_id']);
        $entryModel->setStatus($entryRow['status']);

        return $entryModel;
    }

    /**
     * Gets the Event entrants.
     *
     * @param integer $id
     * @return EntrantsModel|null
     */
    public function getEventEntrantsById($eventId)
    {
        $entryArray = $this->db()->select('*')
                ->from('events_entrants')
                ->where(array('event_id' => $eventId))
                ->limit('17')
                ->execute()
                ->fetchRows();

        if (empty($entryArray)) {
            return null;
        }

        $entry = array();

        foreach ($entryArray as $entries) {
            $entryModel = new EntrantsModel();
            $entryModel->setUserId($entries['user_id']);
            $entryModel->setStatus($entries['status']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * Inserts user on event model.
     *
     * @param EntrantsModel $event
     */
    public function saveUserOnEvent(EntrantsModel $event)
    {
        $fields = array
        (
            'event_id' => $event->getEventId(),
            'user_id' => $event->getUserId(),
            'status' => $event->getStatus(),
        );

        $userId = (int) $this->db()->select('*')
                        ->from('events_entrants')
                        ->where(array('user_id' => $event->getUserId(), 'event_id' => $event->getEventId()))
                        ->execute()
                        ->fetchCell();

        if ($userId) {
            /*
             * User does exist already, update.
             */
            $this->db()->update('events_entrants')
                    ->values(array('status' => $event->getStatus()))
                    ->where(array('event_id' => $event->getEventId(), 'user_id' => $event->getUserId()))
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
     * Deletes user from event with given userId.
     *
     * @param integer $id
     */
    public function deleteUserFromEvent($eventId, $userId)
    {
        $this->db()->delete('events_entrants')
                ->where(array('user_id' => $userId, 'event_id' => $eventId))
                ->execute();
    }
}
