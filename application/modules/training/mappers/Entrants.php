<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Training\Mappers;

use Ilch\Pagination;
use Modules\Training\Models\Entrants as EntrantsModel;

class Entrants extends \Ilch\Mapper
{
    /**
     * @var string
     */
    public $tablename = 'training_entrants';

    /**
     * Gets the Entries by param.
     *
     * @param array $where
     * @param array $orderBy
     * @param Pagination|null $pagination
     * @return EntrantsModel[]|null
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['train_id' => 'ASC'], ?Pagination $pagination = null): ?array
    {
        $select = $this->db()->select();
        $select->fields(['train_id', 'user_id', 'note'])
            ->from([$this->tablename])
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

        $entriesArray = $result->fetchRows();
        if (empty($entriesArray)) {
            return null;
        }
        $entries = [];
        foreach ($entriesArray as $entry) {
            $entryModel = new EntrantsModel();
            $entryModel->setByArray($entry);
            $entries[] = $entryModel;
        }
        return $entries;
    }

    /**
     * Gets the Event entrants.
     *
     * @param int $trainId
     * @param int $userId
     * @return EntrantsModel|null
     */
    public function getEntrants(int $trainId, int $userId): ?EntrantsModel
    {
        $entries = $this->getEntriesBy(['train_id' => $trainId, 'user_id' => $userId]);
        if (!empty($entries)) {
            return reset($entries);
        }

        return null;
    }

    /**
     * Gets the Event entrants.
     *
     * @param int $trainId
     * @return EntrantsModel[]|null
     */
    public function getEntrantsById(int $trainId): ?array
    {
        return $this->getEntriesBy(['train_id' => $trainId]);
    }

    /**
     * Inserts user on training model.
     *
     * @param EntrantsModel $training
     */
    public function saveUserOnTrain(EntrantsModel $training)
    {
        $fields = $training->getArray();
        $this->db()->insert($this->tablename)
            ->values($fields)
            ->execute();
    }

    /**
     * Deletes user from training with given userId.
     *
     * @param int $trainId
     * @param int $userId
     */
    public function deleteUserFromTrain(int $trainId, int $userId)
    {
        $this->db()->delete($this->tablename)
            ->where(['user_id' => $userId, 'train_id' => $trainId])
            ->execute();
    }

    /**
     * Deletes all users from training with given trainId.
     *
     * @param int $trainId
     */
    public function deleteAllUser(int $trainId)
    {
        $this->db()->delete($this->tablename)
                ->where(['train_id' => $trainId])
                ->execute();
    }
}
