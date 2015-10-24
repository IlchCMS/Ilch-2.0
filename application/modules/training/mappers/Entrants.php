<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Training\Mappers;

use Modules\Training\Models\Entrants as EntrantsModel;

class Entrants extends \Ilch\Mapper
{
    /**
     * Gets the Event entrants.
     *
     * @param integer $id
     * @return EntrantsModel|null
     */
    public function getEntrants($trainId, $userId)
    {
        $entryRow = $this->db()->select('*')
                ->from('training_entrants')
                ->where(array('train_id' => $trainId, 'user_id' => $userId))
                ->execute()
                ->fetchAssoc();

        if (empty($entryRow)) {
            return null;
        }

        $entryModel = new EntrantsModel();
        $entryModel->setTrainId($entryRow['train_id']);
        $entryModel->setUserId($entryRow['user_id']);
        $entryModel->setNote($entryRow['note']);

        return $entryModel;
    }

    /**
     * Gets the Event entrants.
     *
     * @param integer $id
     * @return EntrantsModel|null
     */
    public function getEntrantsById($trainId)
    {
        $entryArray = $this->db()->select('*')
                ->from('training_entrants')
                ->where(array('train_id' => $trainId))
                ->execute()
                ->fetchRows();

        if (empty($entryArray)) {
            return null;
        }

        $entry = array();

        foreach ($entryArray as $entries) {
            $entryModel = new EntrantsModel();
            $entryModel->setUserId($entries['user_id']);
            $entryModel->setNote($entries['note']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * Inserts user on training model.
     *
     * @param EntrantsModel $training
     */
    public function saveUserOnTrain(EntrantsModel $training)
    {
        $fields = array
        (
            'train_id' => $training->getTrainId(),
            'user_id' => $training->getUserId(),
            'note' => $training->getNote(),
        );
        
        $this->db()->insert('training_entrants')
            ->values($fields)
            ->execute();
    }

    /**
     * Deletes user from training with given userId.
     *
     * @param integer $trainId, $userId
     */
    public function deleteUserFromTrain($trainId, $userId)
    {
        $this->db()->delete('training_entrants')
                ->where(array('user_id' => $userId, 'train_id' => $trainId))
                ->execute();
    }

    /**
     * Deletes all users from training with given trainId.
     *
     * @param integer $trainId
     */
    public function deleteAllUser($trainId)
    {
        $this->db()->delete('training_entrants')
                ->where(array('train_id' => $trainId))
                ->execute();
    }
}
