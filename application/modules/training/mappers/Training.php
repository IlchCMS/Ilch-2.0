<?php
/**
 * @copyright Ilch 2
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
     * @throws \Ilch\Database\Exception
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
     * Gets training by ID.
     *
     * @param integer $id
     * @return TrainingModel|null
     * @throws \Ilch\Database\Exception
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
     * Gets Trainings by limit
     *
     * @param int $limit
     * @param mixed $where
     * @return TrainingModel[]|null
     * @throws \Ilch\Database\Exception
     */
    public function getTrainingsListWithLimt($limit = NULL, $order = 'ASC')
    {
        $entryArray = $this->db()->select('*')
            ->from('training')
            ->order(['date' => $order])
            ->limit($limit)
            ->execute()
            ->fetchRows();

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
                ->setPlace($entries['place'])
                ->setReadAccess($entries['read_access']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * Gets the calculated Countdown
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param int $hour
     * @param int $minute
     * @return bool|string
     */
    public function countdown($countdown_date, $countdown_time = 60)
    {
        $date = new \Ilch\Date();
        $datenow = new \Ilch\Date($date->format("Y-m-d H:i:s",true));

        $difference = $countdown_date->getTimestamp() - $datenow->getTimestamp();

        if ($difference < 0) {
            if ($difference <= (60*$countdown_time)) {
                return false;
            }
            $difference = 0;
        }

        $days_left = floor($difference / 60 / 60 / 24);
        $hours_left = floor(($difference - $days_left * 60 * 60 * 24) / 60 / 60);
        $minutes_left = floor(($difference - $days_left * 60 * 60 * 24 - $hours_left * 60 * 60) / 60);

        // OUTPUT
        if ($days_left == '0') {
            if ($hours_left == '0' && $minutes_left > '0') {
                return $minutes_left.'m';
            }

            if ($hours_left == '0' && $minutes_left == '0') {
                return 'live';
            }

            return $hours_left.'h '.$minutes_left.'m';
        }

        return $days_left.'d '.$hours_left.'h';
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
        return $this->db()->ifTableExists('[prefix]_'.$table);
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
