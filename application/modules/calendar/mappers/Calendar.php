<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Calendar\Mappers;

use Modules\Calendar\Models\Calendar as CalendarModel;

class Calendar extends \Ilch\Mapper
{
    /**
     * Gets the Calendar entries.
     *
     * @param array $where
     * @return CalendarModel[]|array
     */
    public function getEntries($where = array())
    {        
        $entryArray = $this->db()->select('*')
                ->from('calendar')
                ->where($where)
                ->execute()
                ->fetchRows();

        if (empty($entryArray)) {
            return null;
        }

        $entry = array();

        foreach ($entryArray as $entries) {
            $entryModel = new CalendarModel();
            $entryModel->setId($entries['id']);
            $entryModel->setTitle($entries['title']);
            $entryModel->setPlace($entries['place']);
            $entryModel->setStart($entries['start']);
            $entryModel->setEnd($entries['end']);
            $entryModel->setText($entries['text']);
            $entryModel->setColor($entries['color']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * Gets calendar.
     *
     * @param integer $id
     * @return CalendarModel|null
     */
    public function getCalendarById($id)
    {
        $calendarRow = $this->db()->select('*')
                ->from('calendar')
                ->where(array('id' => $id))
                ->execute()
                ->fetchAssoc();

        if (empty($calendarRow)) {
            return null;
        }

        $calendarModel = new CalendarModel();
        $calendarModel->setId($calendarRow['id']);
        $calendarModel->setTitle($calendarRow['title']);
        $calendarModel->setPlace($calendarRow['place']);
        $calendarModel->setStart($calendarRow['start']);
        $calendarModel->setEnd($calendarRow['end']);
        $calendarModel->setText($calendarRow['text']);
        $calendarModel->setColor($calendarRow['color']);

        return $calendarModel;
    }

    /**
     * Inserts Calendar term model.
     *
     * @param CalendarModel $term
     */
    public function save(CalendarModel $term)
    {
        $fields = array
        (
            'title' => $term->getTitle(),
            'place' => $term->getPlace(),
            'start' => $term->getStart(),
            'end' => $term->getEnd(),
            'text' => $term->getText(),
            'color' => $term->getColor()
        );

        if ($term->getId()) {
            $this->db()->update('calendar')
                ->values($fields)
                ->where(array('id' => $term->getId()))
                ->execute();
        } else {
            $this->db()->insert('calendar')
                ->values($fields)
                ->execute();
        }
    }

    public function existsTable($table)
    {
        $module = $this->db()->ifTableExists('[prefix]_'.$table);

        return $module;
    }

    /**
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('calendar')
            ->where(array('id' => $id))
            ->execute();
    }
}
