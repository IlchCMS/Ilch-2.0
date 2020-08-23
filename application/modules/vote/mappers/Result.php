<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Vote\Mappers;

use Modules\Vote\Models\Result as ResultModel;

class Result extends \Ilch\Mapper
{
    public function getVoteRes($pollId)
    {
        $entryArray = $this->db()->select('*')
            ->from('poll_res')
            ->where(['poll_id' => $pollId])
            ->execute()
            ->fetchRows();

        if (empty($entryArray)) {
            return null;
        }

        $result = [];
        foreach ($entryArray as $entries) {
            $entryModel = new ResultModel();
            $entryModel->setReply($entries['reply']);
            $result[] = $entryModel;
        }

        return $result;
    }

    public function getResultById($pollId)
    {
        $sql = 'SELECT SUM(`result`)
                FROM `[prefix]_poll_res`
                WHERE poll_id = '.intval($pollId).'
                GROUP BY poll_id';

        return $this->db()->queryCell($sql);
    }

    public function getResultByIdAndReply($pollId, $reply)
    {
        $result = $this->db()->select('result')
            ->from('poll_res')
            ->where(['poll_id' => $pollId, 'reply' => $reply])
            ->execute()
            ->fetchAssoc();

        return $result['result'];
    }

    public function resetResult($pollId)
    {
        $this->db()->update('poll_res')
            ->values(['result' => 0])
            ->where(['poll_id' => $pollId])
            ->execute();

        $this->db()->delete('poll_ip')
            ->where(['poll_id' => $pollId])
            ->execute();
    }

    public function getPercent($count, $totalcount)
    {
        return round(($count / $totalcount) * 100);
    }

    /**
     * Updates Result Vote model.
     *
     * @param ResultModel $result
     */
    public function saveResult(ResultModel $result)
    {
        $this->db()->update('poll_res')
            ->values(['result' => $result->getResult()])
            ->where(['poll_id' => $result->getPollId(), 'reply' => $result->getReply()])
            ->execute();
    }

    /**
     * Inserts Result model.
     *
     * @param ResultModel $result
     */
    public function saveReply(ResultModel $result)
    {
        $fields = [
            'poll_id' => $result->getPollId(),
            'reply' => $result->getReply()
        ];

        $this->db()->insert('poll_res')
            ->values($fields)
            ->execute();
    }

    /**
     * Deletes result with given poll id.
     *
     * @param integer $pollId
     */
    public function delete($pollId)
    {
        $this->db()->delete('poll_res')
            ->where(['poll_id' => $pollId])
            ->execute();

        $this->db()->delete('poll_ip')
            ->where(['poll_id' => $pollId])
            ->execute();
    }
}
