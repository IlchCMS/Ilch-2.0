<?php
/**
 * @copyright Ilch 2
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
    public function getEntries($where = [])
    {
        $entryArray = $this->db()->select('*')
            ->from('calendar')
            ->where($where)
            ->execute()
            ->fetchRows();

        if (empty($entryArray)) {
            return null;
        }

        $entry = [];
        foreach ($entryArray as $entries) {
            $entryModel = new CalendarModel();
            $entryModel->setId($entries['id'])
                ->setTitle($entries['title'])
                ->setPlace($entries['place'])
                ->setStart($entries['start'])
                ->setEnd($entries['end'])
                ->setText($entries['text'])
                ->setColor($entries['color'])
                ->setPeriodDay($entries['period_day'])
                ->setReadAccess($entries['read_access']);
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
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if (empty($calendarRow)) {
            return null;
        }

        $calendarModel = new CalendarModel();
        $calendarModel->setId($calendarRow['id'])
            ->setTitle($calendarRow['title'])
            ->setPlace($calendarRow['place'])
            ->setStart($calendarRow['start'])
            ->setEnd($calendarRow['end'])
            ->setText($calendarRow['text'])
            ->setColor($calendarRow['color'])
            ->setPeriodDay($calendarRow['period_day'])
            ->setReadAccess($calendarRow['read_access']);

        return $calendarModel;
    }

    /**
     * Gets the Calendar entries by start and end.
     *
     * @param integer $start
     * @param integer $end
     * @return CalendarModel|null
     */
    public function getEntriesForJson($start, $end)
    {
        if ($start && $end) {
            $start = new \Ilch\Date($start);
            $end = new \Ilch\Date($end);

            $sql = sprintf("SELECT * FROM `[prefix]_calendar` WHERE start >= '%s 00:00:00' AND end <= '%s 23:59:59' ORDER BY start ASC;", $start, $end);
        } else {
            return null;
        }

        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
        }

        $entry = [];
        foreach ($entryArray as $entries) {
            $entryModel = new CalendarModel();
            $entryModel->setId($entries['id'])
                ->setTitle($entries['title'])
                ->setStart($entries['start'])
                ->setEnd($entries['end'])
                ->setColor($entries['color'])
                ->setPeriodDay($entries['period_day'])
                ->setReadAccess($entries['read_access']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * Inserts Calendar term model.
     *
     * @param CalendarModel $term
     */
    public function save(CalendarModel $term)
    {
        $fields = [
            'title' => $term->getTitle(),
            'place' => $term->getPlace(),
            'start' => $term->getStart(),
            'end' => $term->getEnd(),
            'text' => $term->getText(),
            'color' => $term->getColor(),
            'period_day' => $term->getPeriodDay(),
            'read_access' => $term->getReadAccess()
        ];

        if ($term->getId()) {
            $this->db()->update('calendar')
                ->values($fields)
                ->where(['id' => $term->getId()])
                ->execute();
        } else {
            $this->db()->insert('calendar')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('calendar')
            ->where(['id' => $id])
            ->execute();
    }
}
