<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Training\Mappers;

use Modules\Training\Models\Training as TrainingModel;

defined('ACCESS') or die('no direct access');

class Training extends \Ilch\Mapper
{
    /**
     * Gets the Training.
     *
     * @param array $where
     * @return TrainingModel[]|array
     */
    public function getTraining($where = array())
    {
        $entryArray = $this->db()->select('*')
            ->from('training')
            ->where($where)
            ->order(array('id' => 'ASC'))
            ->execute()
            ->fetchRows();

        if (empty($entryArray)) {
            return null;
        }

        $training = array();

        foreach ($entryArray as $entries) {
            $entryModel = new TrainingModel();
            $entryModel->setId($entries['id']);
            $entryModel->setTitle($entries['title']);
            $entryModel->setDate($entries['date']);
            $entryModel->setPlace($entries['place']);
            $entryModel->setText($entries['text']);
            $training[] = $entryModel;

        }

        return $training;
    }

    /**
     * Gets training.
     *
     * @param integer $id
     * @return TrainingModel|null
     */
    public function getTrainingById($id)
    {
        $training = $this->getTraining(array('id' => $id));
        return reset($training);
    }

    /**
     * Inserts or updates training model.
     *
     * @param TrainingModel $training
     */
    public function save(TrainingModel $training)
    {
        $fields = array
        (
            'title' => $training->getTitle(),
            'date' => $training->getDate(),
            'place' => $training->getPlace(),
            'text' => $training->getText(),
        );

        if ($training->getId()) {
            $this->db()->update('training')
                ->values($fields)
                ->where(array('id' => $training->getId()))
                ->execute();
        } else {
            $this->db()->insert('training')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Deletes training with given id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('training')
            ->where(array('id' => $id))
            ->execute();
    }
}
