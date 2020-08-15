<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Vote\Mappers;

use Modules\Vote\Models\Vote as VoteModel;

class Vote extends \Ilch\Mapper
{
    /**
     * Gets the Vote.
     *
     * @param array $where
     * @return VoteModel[]|array
     */
    public function getVotes($where = [])
    {
        $entryArray = $this->db()->select('*')
            ->from('poll')
            ->where($where)
            ->order(['id' => 'DESC'])
            ->execute()
            ->fetchRows();

        if (empty($entryArray)) {
            return null;
        }

        $vote = [];
        foreach ($entryArray as $entries) {
            $entryModel = new VoteModel();
            $entryModel->setId($entries['id'])
                ->setQuestion($entries['question'])
                ->setKey($entries['key'])
                ->setGroups($entries['groups'])
                ->setReadAccess($entries['read_access'])
                ->setStatus($entries['status']);
            $vote[] = $entryModel;
        }

        return $vote;
    }

    /**
     * Gets Vote.
     *
     * @param integer $id
     * @return VoteModel|null
     */
    public function getVoteById($id)
    {
        $voteRow = $this->db()->select('*')
            ->from('poll')
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if (empty($voteRow)) {
            return null;
        }

        $voteModel = new VoteModel();
        $voteModel->setId($voteRow['id'])
            ->setQuestion($voteRow['question'])
            ->setGroups($voteRow['groups'])
            ->setReadAccess($voteRow['read_access']);

        return $voteModel;
    }

    /**
     * Updates vote status with given id.
     *
     * @param integer $id
     */
    public function lock($id)
    {
        $status = (int)$this->db()->select('status')
            ->from('poll')
            ->where(['id' => $id])
            ->execute()
            ->fetchCell();

        if ($status == 1) {
            $this->db()->update('poll')
                ->values(['status' => 0])
                ->where(['id' => $id])
                ->execute();
        } else {
            $this->db()->update('poll')
                ->values(['status' => 1])
                ->where(['id' => $id])
                ->execute();
        }
    }

    public function getLastId()
    {
        $sql = 'SELECT MAX(id)
                FROM `[prefix]_poll`';

        return $this->db()->queryCell($sql);
    }

    /**
     * Inserts or updates Vote model.
     *
     * @param VoteModel $vote
     */
    public function save(VoteModel $vote)
    {
        $fields = [
            'question' => $vote->getQuestion(),
            'key' => $vote->getKey(),
            'groups' => $vote->getGroups(),
            'read_access' => $vote->getReadAccess()
        ];

        if ($vote->getId()) {
            $this->db()->update('poll')
                ->values($fields)
                ->where(['id' => $vote->getId()])
                ->execute();
        } else {
            $this->db()->insert('poll')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Deletes Vote with given id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('poll')
            ->where(['id' => $id])
            ->execute();

        $this->db()->delete('poll_res')
            ->where(['poll_id' => $id])
            ->execute();

        $this->db()->delete('poll_ip')
            ->where(['poll_id' => $id])
            ->execute();
    }
}
