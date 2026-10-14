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
    public string $tablename = 'poll_res';

    /**
     * Mapper for the poll_ip table.
     *
     * It is injected instead of being instantiated inside the methods, so
     * the table name is only defined in the Ip mapper.
     *
     * @var Ip
     * @since 1.14.3
     */
    private Ip $ipMapper;

    /**
     * @param Ip|null $ipMapper
     * @since 1.14.3
     */
    public function __construct(?Ip $ipMapper = null)
    {
        parent::__construct();
        $this->ipMapper = $ipMapper ?? new Ip();
    }

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
            ->fetchCell() ?? 0;
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

        return $result['result'] ?? 0;
    }

    /**
     * Resets the results of the given vote and removes its IP entries.
     *
     * Returns the result of the poll_res UPDATE (not of the poll_ip DELETE),
     * so the return value reflects the actual reset.
     *
     * @param int $pollId
     * @return bool
     */
    public function resetResult(int $pollId): bool
    {
        $update = $this->db()->update($this->tablename)
            ->values(['result' => 0])
            ->where(['poll_id' => $pollId])
            ->execute();

        $this->db()->delete($this->ipMapper->tablename)
            ->where(['poll_id' => $pollId])
            ->execute();

        return $update;
    }

    /**
     * Updates Result Vote model.
     *
     * @param ResultModel $result
     */
    public function saveResult(ResultModel $result): void
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
    public function saveReply(ResultModel $result): void
    {
        $fields = $result->getArray();

        $this->db()->insert($this->tablename)
            ->values($fields)
            ->execute();
    }

    /**
     * Deletes result with given poll id and the related IP entries.
     *
     * Returns the result of the poll_res DELETE.
     *
     * @param int $pollId
     * @return bool
     */
    public function delete(int $pollId): bool
    {
        $delete = $this->db()->delete($this->tablename)
            ->where(['poll_id' => $pollId])
            ->execute();

        $this->db()->delete($this->ipMapper->tablename)
            ->where(['poll_id' => $pollId])
            ->execute();

        return $delete;
    }
}
