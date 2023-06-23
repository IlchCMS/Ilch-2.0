<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Vote\Mappers;

use Modules\Vote\Models\Result as ResultModel;

class Result extends \Ilch\Mapper
{
    /**
     * @var string
     * @since 1.12.0
     */
    public $tablename = 'poll_res';

    /**
     * returns if the module is installed.
     *
     * @return bool
     * @throws \Ilch\Database\Exception
     * @since 1.12.0
     */
    public function checkDB(): bool
    {
        return $this->db()->ifTableExists($this->tablename);
    }

    /**
     * Gets the Entries by params.
     *
     * @param array $where
     * @param array $orderBy
     * @param \Ilch\Pagination|null $pagination
     * @return ResultModel[]|null
     * @since 1.12.0
     */
    public function getEntriesBy(array $where = [], array $orderBy = [], ?\Ilch\Pagination $pagination = null): ?array
    {
        $select = $this->db()->select();
        $select->fields(['poll_id', 'reply', 'result'])
            ->from($this->tablename)
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

        $entryArray = $result->fetchRows();
        if (empty($entryArray)) {
            return null;
        }
        $entrys = [];

        foreach ($entryArray as $entries) {
            $entryModel = new ResultModel();
            $entryModel->setByArray($entries);

            $entrys[] = $entryModel;
        }
        return $entrys;
    }

    /**
     * @param int $pollId
     * @return ResultModel[]|null
     */
    public function getVoteRes(int $pollId): ?array
    {
        return $this->getEntriesBy(['poll_id' => $pollId]);
    }

    /**
     * @param int $pollId
     * @return int
     */
    public function getResultById(int $pollId): int
    {
        return $this->db()->select('SUM(result)')
            ->from($this->tablename)
            ->where(['poll_id' => $pollId])
            ->group(['poll_id'])
            ->execute()
            ->fetchCell();
    }

    /**
     * @param int $pollId
     * @param string $reply
     * @return int
     */
    public function getResultByIdAndReply(int $pollId, string $reply): int
    {
        $result = $this->db()->select('result')
            ->from($this->tablename)
            ->where(['poll_id' => $pollId, 'reply' => $reply])
            ->execute()
            ->fetchAssoc();

        return $result['result'];
    }

    /**
     * @param int $pollId
     * @return bool
     */
    public function resetResult(int $pollId): bool
    {
        $ipMapper = new Ip();

        $this->db()->update($this->tablename)
            ->values(['result' => 0])
            ->where(['poll_id' => $pollId])
            ->execute();

        return $this->db()->delete($ipMapper->tablename)
            ->where(['poll_id' => $pollId])
            ->execute();
    }

    /**
     * @param int $count
     * @param int $totalcount
     * @return float
     */
    public function getPercent(int $count, int $totalcount): float
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
        $fields = $result->getArray();

        $this->db()->update($this->tablename)
            ->values($fields)
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
        $fields = $result->getArray();

        $this->db()->insert($this->tablename)
            ->values($fields)
            ->execute();
    }

    /**
     * Deletes result with given poll id.
     *
     * @param int $pollId
     */
    public function delete(int $pollId)
    {
        $ipMapper = new Ip();

        $this->db()->delete($this->tablename)
            ->where(['poll_id' => $pollId])
            ->execute();

        $this->db()->delete($ipMapper->tablename)
            ->where(['poll_id' => $pollId])
            ->execute();
    }
}
