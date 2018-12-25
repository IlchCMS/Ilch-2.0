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
    public function getTraining($where = [])
    {
        $entryArray = $this->db()->select('*')
            ->from('training')
            ->where($where)
            ->order(['date' => 'ASC'])
            ->execute()
            ->fetchRows();

        if (empty($entryArray)) {
            return [];
        }

        $training = [];
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
            $entryModel->setShow($entries['show']);
            $entryModel->setReadAccess($entries['read_access']);
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
        $training = $this->getTraining(['id' => $id]);

        return reset($training);
    }

    /**
     * Get the trainings between start and end.
     *
     * @param string $start Y-m-d H:m:i
     * @param string $end Y-m-d H:m:i
     * @return TrainingModel[]|array
     * @throws \Ilch\Database\Exception
     */
    public function getTrainingsForJson($start, $end)
    {
        if ($start && $end) {
            $start = new \Ilch\Date($start);
            $end = new \Ilch\Date($end);

            $sql = sprintf("SELECT * FROM `[prefix]_training` WHERE date >= '%s' AND date <= '%s' AND `show` = 1 ORDER BY date ASC;", $start, $end);
        } else {
            return [];
        }

        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return [];
        }

        $entry = [];
        foreach ($entryArray as $entries) {
            $entryModel = new TrainingModel();
            $entryModel->setId($entries['id'])
                ->setDate($entries['date'])
                ->setTime($entries['time'])
                ->setTitle($entries['title'])
                ->setShow($entries['show'])
                ->setReadAccess($entries['read_access']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * Inserts or updates training model.
     *
     * @param TrainingModel $training
     */
    public function save(TrainingModel $training)
    {
        $fields = [
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
            'show' => $training->getShow(),
            'read_access' => $training->getReadAccess()
        ];

        if ($training->getId()) {
            $this->db()->update('training')
                ->values($fields)
                ->where(['id' => $training->getId()])
                ->execute();
        } else {
            $this->db()->insert('training')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Check if table exists.
     *
     * @param $table
     * @return false|true
     * @throws \Ilch\Database\Exception
     */
    public function existsTable($table)
    {
        $module = $this->db()->ifTableExists('[prefix]_'.$table);

        return $module;
    }

    /**
     * Deletes training with given id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('training')
            ->where(['id' => $id])
            ->execute();
    }
}
