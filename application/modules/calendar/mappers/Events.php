<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Calendar\Mappers;

use Modules\Calendar\Models\Events as EventsModel;

class Events extends \Ilch\Mapper
{
    /**
     * Gets the Events entries.
     *
     * @param array $where
     * @return EventsModel[]|array
     */
    public function getEntries($where = [])
    {
        $entryArray = $this->db()->select('*')
                ->from('calendar_events')
                ->where($where)
                ->execute()
                ->fetchRows();

        if (empty($entryArray)) {
            return null;
        }

        $entry = [];
        foreach ($entryArray as $entries) {
            $entryModel = new EventsModel();
            $entryModel->setId($entries['id']);
            $entryModel->setUrl($entries['url']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * Inserts Events model.
     *
     * @param EventsModel $event
     */
    public function save(EventsModel $event)
    {
        $this->db()->insert('calendar_events')
            ->values(['url' => $event->getUrl()])
            ->execute();
    }

    /**
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('calendar_events')
            ->where(['id' => $id])
            ->execute();
    }
}
