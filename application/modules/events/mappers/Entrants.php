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
     * @var string
     * @since 1.23.6
     */
    public $tablename = 'events_entrants';

    /**
     * returns if the module is installed.
     *
     * @return boolean
     * @throws \Ilch\Database\Exception
     * @since 1.23.6
     */
    public function checkDB(): bool
    {
        return $this->db()->ifTableExists($this->tablename);
    }

    /**
     * Gets the Entries by params.
     *
     * @param array $where
     * @param array $orderBy
     * @param \Ilch\Pagination|null $pagination
     * @return EntrantsModel[]|null
     * @since 1.23.6
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['event_id' => 'ASC'], ?\Ilch\Pagination $pagination = null): ?array
    {
        $select = $this->db()->select('*')
            ->from($this->tablename)
            ->where($where)
            ->order($orderBy);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $entryArray = $result->fetchRows();
        if (empty($entryArray)) {
            return null;
        }
        $entrys = [];

        foreach ($entryArray as $entries) {
            $entryModel = new EntrantsModel();
            $entryModel->setByArray($entries);

            $entrys[] = $entryModel;
        }
        return $entrys;
    }

    /**
     * Gets the Event entrants.
     *
     * @param int $eventId
     * @param int $userId
     *
     * @return EntrantsModel|null
     */
    public function getEventEntrants(int $eventId, int $userId): ?EntrantsModel
    {
        $entryRow = $this->getEntriesBy(['event_id' => $eventId, 'user_id' => $userId], []);

        if ($entryRow) {
            return reset($entryRow);
        }
        return null;
    }

    /**
     * Gets the count of entrants of an event.
     *
     * @param int $eventId
     *
     * @return int
     */
    public function getCountOfEventEntrans(int $eventId): int
    {
        return $this->db()->select('COUNT(*)', $this->tablename)
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
    public function getEventEntrantsById(int $eventId): array
    {
        $entryArray = $this->getEntriesBy(['event_id' => $eventId], []);

        if (!$entryArray) {
            return [];
        }
        return $entryArray;
    }

    /**
     * Inserts user on event model.
     *
     * @param EntrantsModel $event
     */
    public function saveUserOnEvent(EntrantsModel $event)
    {
        $fields = $event->getArray();

        $userId = (int) $this->db()->select('*')
            ->from($this->tablename)
            ->where(['user_id' => $event->getUserId(), 'event_id' => $event->getEventId()])
            ->execute()
            ->fetchCell();

        if ($userId) {
            /*
             * User does exist already, update.
             */
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['event_id' => $event->getEventId(), 'user_id' => $event->getUserId()])
                ->execute();
        } else {
            /*
             * User does not exist yet, insert.
             */
            $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Deletes user from event with given userId.
     *
     * @param int $eventId
     * @param int $userId
     * @return bool
     */
    public function deleteUserFromEvent(int $eventId, int $userId): bool
    {
        return $this->db()->delete($this->tablename)
            ->where(['user_id' => $userId, 'event_id' => $eventId])
            ->execute();
    }

    /**
     * Deletes all entries.
     *
     * @return bool
     * @since 1.23.6
     */
    public function truncate(): bool
    {
        return (bool)$this->db()->truncate($this->tablename);
    }
}
