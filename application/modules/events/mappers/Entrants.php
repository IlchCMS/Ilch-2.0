<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Events\Mappers;

use Modules\Events\Models\Entrants as EntrantsModel;

class Entrants extends \Ilch\Mapper
{
    /**
     * Gets the Event entrants.
     *
     * @param int $eventId
     * @param int $userId
     *
     * @return EntrantsModel|null
     */
    public function getEventEntrants($eventId, $userId)
    {
        $entryRow = $this->db()->select('*')
            ->from('events_entrants')
            ->where(['event_id' => $eventId, 'user_id' => $userId])
            ->execute()
            ->fetchAssoc();

        if (empty($entryRow)) {
            return null;
        }

        $entryModel = new EntrantsModel();
        $entryModel->setEventId($entryRow['event_id'])
            ->setUserId($entryRow['user_id'])
            ->setStatus($entryRow['status']);

        return $entryModel;
    }

    /**
     * Gets the count of entrants of an event.
     *
     * @param int $eventId
     *
     * @return int
     */
    public function getCountOfEventEntrans($eventId)
    {
        return $this->db()->select('COUNT(*)', 'events_entrants')
            ->where(['event_id' => $eventId])
            ->execute()
            ->fetchCell();
    }

    /**
     * Gets the Event entrants.
     *
     * @param int $eventId
     *
     * @return EntrantsModel[]|array
     */
    public function getEventEntrantsById($eventId)
    {
        $entryArray = $this->db()->select('*')
            ->from('events_entrants')
            ->where(['event_id' => $eventId])
            ->execute()
            ->fetchRows();

        if (empty($entryArray)) {
            return [];
        }

        $entry = [];
        foreach ($entryArray as $entries) {
            $entryModel = new EntrantsModel();
            $entryModel->setUserId($entries['user_id'])
                ->setStatus($entries['status']);
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
        $fields = [
            'event_id' => $event->getEventId(),
            'user_id' => $event->getUserId(),
            'status' => $event->getStatus()
        ];

        $userId = (int) $this->db()->select('*')
            ->from('events_entrants')
            ->where(['user_id' => $event->getUserId(), 'event_id' => $event->getEventId()])
            ->execute()
            ->fetchCell();

        if ($userId) {
            /*
             * User does exist already, update.
             */
            $this->db()->update('events_entrants')
                ->values(['status' => $event->getStatus()])
                ->where(['event_id' => $event->getEventId(), 'user_id' => $event->getUserId()])
                ->execute();
        } else {
            /*
             * User does not exist yet, insert.
             */
            $this->db()->insert('events_entrants')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Deletes user from event with given userId.
     *
     * @param int $eventId
     * @param int $userId
     */
    public function deleteUserFromEvent($eventId, $userId)
    {
        $this->db()->delete('events_entrants')
            ->where(['user_id' => $userId, 'event_id' => $eventId])
            ->execute();
    }
}
