<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Events\Mappers;

use Ilch\Database\Exception;
use Modules\Events\Models\Events as EventModel;
use Ilch\Pagination;

class Events extends \Ilch\Mapper
{
    /**
     * @var string
     * @since 1.23.6
     */
    public $tablename = 'events';

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
     * @return EventModel[]|null
     * @since 1.23.6
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['start' => 'ASC'], ?\Ilch\Pagination $pagination = null): ?array
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
            $entryModel = new EventModel();
            $entryModel->setByArray($entries);

            $entrys[] = $entryModel;
        }
        return $entrys;
    }

    /**
     * Gets the Event entries.
     *
     * @param array $where
     * @return EventModel[]|null
     */
    public function getEntries(array $where = []): ?array
    {
        return $this->getEntriesBy($where);
    }

    /**
     * Gets event.
     *
     * @param int $id
     *
     * @return EventModel|null
     */
    public function getEventById(int $id): ?EventModel
    {
        $eventRow = $this->getEntriesBy(['id' => $id]);

        if ($eventRow) {
            return reset($eventRow);
        }
        return null;
    }

    /**
     * Get list of upcoming events.
     *
     * @param int|null $limit
     * @return EventMapper[]|null
     * @throws Exception
     */
    public function getEventListUpcoming(?int $limit = null): ?array
    {
        $pagination = null;
        if ($limit) {
            $pagination = new Pagination();
            $pagination->setRowsPerPage($limit);
        }

        return $this->getEntriesBy([new \Ilch\Database\Mysql\Expression\Comparison('`start`', '>', 'NOW()')], [], $pagination);
    }

    /**
     * Get list of events a user participates in.
     *
     * @param int $userId
     * @return EventMapper[]|array
     */
    public function getEventListParticipation(int $userId): ?array
    {
        return $this->getEntriesBy(['user_id' => $userId], []);
    }

    /**
     * Get list of past events.
     *
     * @param int|null $limit
     * @return EventMapper[]|array
     * @throws Exception
     */
    public function getEventListPast(?int $limit = null): ?array
    {
        $pagination = null;
        if ($limit) {
            $pagination = new Pagination();
            $pagination->setRowsPerPage($limit);
        }

        return $this->getEntriesBy([new \Ilch\Database\Mysql\Expression\Comparison('`end`', '<', 'NOW()')], [], $pagination);
    }

    /**
     * Get a list of the current events.
     *
     * @param int|null $limit
     * @return EventMapper[]|null
     * @throws Exception
     */
    public function getEventListCurrent(?int $limit = null): ?array
    {
        $pagination = null;
        if ($limit) {
            $pagination = new Pagination();
            $pagination->setRowsPerPage($limit);
        }

        return $this->getEntriesBy(
            [
                new \Ilch\Database\Mysql\Expression\Comparison('`start`', '<', 'NOW()'),
                new \Ilch\Database\Mysql\Expression\Comparison('`end`', '>', 'NOW()'),
            ],
            [],
            $pagination
        );
    }

    /**
     * Check if table exists.
     *
     * @param string $table
     * @return false|true
     * @throws Exception
     */
    public function existsTable(string $table): bool
    {
        return $this->db()->ifTableExists('[prefix]_' . $table);
    }

    /**
     * Gets the Events by start and end.
     *
     * @param string $start
     * @param string $end
     *
     * @return EventModel[]|array|null
     * @throws Exception
     */
    public function getEntriesForJson(string $start, string $end): ?array
    {
        if ($start && $end) {
            $start = new \Ilch\Date($start);
            $end = new \Ilch\Date($end);

            return $this->getEntriesBy(['start >=' => $start, 'end <=' => $end, 'show' => 1]);
        } else {
            return null;
        }
    }

    /**
     * Get latitude and longitude for Google Maps by address
     *
     * @param string $address
     * @param string $googleMapsKey
     *
     * @return string $latlongitude
     */
    public function getLatLongFromAddress(string $address, string $googleMapsKey): ?string
    {
        $geocode = url_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&key=' . urlencode($googleMapsKey));
        $output = json_decode($geocode);

        // "OK" indicates that no errors occurred; the address was successfully parsed and at least one geocode was returned.
        if (empty($output) || $output->status !== 'OK') {
            return null;
        }

        $latitude = $output->results[0]->geometry->location->lat;
        $longitude = $output->results[0]->geometry->location->lng;
        return $latitude . ',' . $longitude;
    }

    /**
     * Returns a list of all existing types.
     *
     * @return string[]
     */
    public function getListOfTypes(): array
    {
        return $this->db()->select('type')
            ->from($this->tablename)
            ->execute()
            ->fetchList();
    }

    /**
     * Inserts or updates event model.
     *
     * @param EventModel $event
     */
    public function save(EventModel $event)
    {
        $fields = $event->getArray();

        if ($event->getId()) {
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $event->getId()])
                ->execute();
        } else {
            $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Deletes event with given id.
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $imageRow = $this->db()->select('*')
            ->from($this->tablename)
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if (isset($imageRow['image']) && file_exists($imageRow['image'])) {
            unlink($imageRow['image']);
        }

        $this->db()->delete($this->tablename)
            ->where(['id' => $id])
            ->execute();

        $this->db()->delete('events_entrants')
            ->where(['event_id' => $id])
            ->execute();

        $this->db()->delete('comments')
            ->where(['key' => 'events/show/event/id/' . $id])
            ->execute();
    }

    /**
     * Delete/Unlink Image by id.
     *
     * @param int $id
     */
    public function delImageById(int $id)
    {
        $imageRow = $this->db()->select('*')
            ->from($this->tablename)
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if (file_exists($imageRow['image'])) {
            unlink($imageRow['image']);
        }

        $this->db()->update($this->tablename)
            ->values(['image' => ''])
            ->where(['id' => $id])
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
