<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Awards\Mappers;

use Ilch\Database\Exception;
use Ilch\Database\Mysql\Result;
use Ilch\Mapper;
use Modules\Awards\Models\Awards as AwardsModel;
use Modules\Awards\Models\Recipient as RecipientModel;

/**
 * The awards mapper.
 */
class Awards extends Mapper
{
    /**
     * Gets the Awards entries.
     *
     * @param array $where
     * @param array $orderBy
     * @return AwardsModel[]|array
     */
    public function getAwards(array $where = [], array $orderBy = ['date' => 'DESC']): array
    {
        $awardsArray = $this->db()->select(['a.id', 'a.date', 'a.rank', 'a.image', 'a.event', 'a.url'])
            ->from(['a' => 'awards'])
            ->where($where)
            ->join(['r' => 'awards_recipients'], 'a.id = r.award_id', 'INNER', ['utIds' => 'GROUP_CONCAT(r.ut_id)', 'types' => 'GROUP_CONCAT(r.typ)'])
            ->group(['a.id'])
            ->order($orderBy)
            ->execute()
            ->fetchRows();

        if (empty($awardsArray)) {
            return [];
        }

        $awards = [];
        foreach ($awardsArray as $entries) {
            $awardsModel = new AwardsModel();
            $awardsModel->setByArray($entries);

            $recipients = [];
            $types = explode(',', $entries['types']);
            foreach (explode(',', $entries['utIds']) as $key => $id) {
                $recipientModel = new RecipientModel();
                $recipientModel->setAwardId($entries['id'])
                    ->setUtId($id)
                    ->setTyp($types[$key]);
                $recipients[] = $recipientModel;
            }
            $awardsModel->setRecipients($recipients);

            $awards[] = $awardsModel;
        }

        return $awards;
    }

    /**
     * Gets awards.
     *
     * @param int $id
     * @return AwardsModel|null
     */
    public function getAwardsById(int $id): ?AwardsModel
    {
        $awards = $this->getAwards(['a.id' => $id], []);

        if ($awards) {
            return reset($awards);
        }
        return null;
    }

    /**
     * Inserts or updates awards model.
     *
     * @param AwardsModel $awards
     * @return Result|int
     */
    public function save(AwardsModel $awards)
    {
        $fields = $awards->getArray(false);

        if ($awards->getId()) {
            return $this->db()->update('awards')
                ->values($fields)
                ->where(['id' => $awards->getId()])
                ->execute();
        } else {
            return $this->db()->insert('awards')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Check if a table exists. This is used to look for the teams table of the teams module.
     *
     * @param $table
     * @return bool
     * @throws Exception
     */
    public function existsTable($table): bool
    {
        return $this->db()->ifTableExists('[prefix]_' . $table);
    }

    /**
     * Deletes awards with given id.
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->db()->delete('awards')
            ->where(['id' => $id])
            ->execute();
    }
}
