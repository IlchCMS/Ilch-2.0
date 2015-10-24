<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Training\Mappers;

use Modules\Training\Models\Training as TrainingModel;

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
            $entryModel->setTime($entries['time']);
            $entryModel->setPlace($entries['place']);
            $entryModel->setContact($entries['contact']);
            $entryModel->setVoiceServer($entries['voice_server']);
            $entryModel->setVoiceServerIP($entries['voice_server_ip']);
            $entryModel->setVoiceServerPW($entries['voice_server_pw']);
            $entryModel->setGameServer($entries['game_server']);
            $entryModel->setGameServerIP($entries['game_server_ip']);
            $entryModel->setGameServerPW($entries['game_server_pw']);
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
            'time' => $training->getTime(),
            'place' => $training->getPlace(),
            'contact' => $training->getContact(),
            'voice_server' => $training->getVoiceServer(),
            'voice_server_ip' => $training->getVoiceServerIP(),
            'voice_server_pw' => $training->getVoiceServerPW(),
            'game_server' => $training->getGameServer(),
            'game_server_ip' => $training->getGameServerIP(),
            'game_server_pw' => $training->getGameServerPW(),
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
