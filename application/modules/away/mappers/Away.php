<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Away\Mappers;

use Ilch\Database\Exception;
use Ilch\Date;
use Modules\Away\Models\Away as AwayModel;

class Away extends \Ilch\Mapper
{
    /**
     * Gets the Away.
     *
     * @param array $where
     * @return AwayModel[]|array
     */
    public function getAway(array $where = []): array
    {
        $entryArray = $this->db()->select('*')
            ->from('away')
            ->where($where)
            ->order(['start' => 'ASC'])
            ->execute()
            ->fetchRows();

        if (empty($entryArray)) {
            return [];
        }

        $away = [];
        foreach ($entryArray as $entries) {
            $entryModel = new AwayModel();
            $entryModel->setId($entries['id']);
            $entryModel->setUserId($entries['user_id']);
            $entryModel->setReason($entries['reason']);
            $entryModel->setStart($entries['start']);
            $entryModel->setEnd($entries['end']);
            $entryModel->setText($entries['text']);
            $entryModel->setStatus($entries['status']);
            $entryModel->setShow($entries['show']);
            $away[] = $entryModel;
        }

        return $away;
    }

    /**
     * Gets away.
     *
     * @param integer $id
     * @return AwayModel|null
     */
    public function getAwayById(int $id): ?AwayModel
    {
        $away = $this->getAway(['id' => $id]);

        return reset($away);
    }

    public function existsTable($table): bool
    {
        return $this->db()->ifTableExists('[prefix]_'.$table);
    }

    /**
     * Inserts or updates away model.
     *
     * @param AwayModel $away
     */
    public function save(AwayModel $away)
    {
        $fields = [
            'user_id' => $away->getUserId(),
            'reason' => $away->getReason(),
            'start' => $away->getStart(),
            'end' => $away->getEnd(),
            'text' => $away->getText(),
            'show' => $away->getShow()
        ];

        if ($away->getStatus()) {
            $fields['status'] = $away->getStatus();
        }

        if ($away->getId()) {
            $this->db()->update('away')
                ->values($fields)
                ->where(['id' => $away->getId()])
                ->execute();
        } else {
            $this->db()->insert('away')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Gets the Away entries by start and end.
     *
     * @param string $start
     * @param string $end
     * @return AwayModel[]|array|null
     * @throws Exception
     */
    public function getEntriesForJson(string $start, string $end): ?array
    {
        if ($start && $end) {
            $start = new Date($start);
            $end = new Date($end);

            $sql = sprintf("SELECT * FROM `[prefix]_away` WHERE `start` >= '%s' AND `end` <= '%s' AND `show` = 1 ORDER BY `start` ASC;", $start, $end);
        } else {
            return null;
        }

        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
        }

        $away = [];
        foreach ($entryArray as $entries) {
            $entryModel = new AwayModel();
            $entryModel->setId($entries['id']);
            $entryModel->setReason($entries['reason']);
            $entryModel->setStart($entries['start']);
            $entryModel->setEnd($entries['end']);
            $entryModel->setStatus($entries['status']);
            $entryModel->setShow($entries['show']);
            $away[] = $entryModel;
        }

        return $away;
    }

    /**
     * Updates away with given id.
     *
     * @param integer $id
     */
    public function update(int $id)
    {
        $show = (int) $this->db()->select('status')
                        ->from('away')
                        ->where(['id' => $id])
                        ->execute()
                        ->fetchCell();

        if ($show == 1) {
            $this->db()->update('away')
                ->values(['status' => 0])
                ->where(['id' => $id])
                ->execute();
        } else {
            $this->db()->update('away')
                ->values(['status' => 1])
                ->where(['id' => $id])
                ->execute();
        }
    }

    /**
     * Deletes away with given id.
     *
     * @param integer $id
     */
    public function delete(int $id)
    {
        $this->db()->delete('away')
            ->where(['id' => $id])
            ->execute();
    }
}
