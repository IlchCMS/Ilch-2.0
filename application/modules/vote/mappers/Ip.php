<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Vote\Mappers;

use Modules\Vote\Models\Ip as IpModel;

class Ip extends \Ilch\Mapper
{
    /**
     * @var string
     * @since 1.12.0
     */
    public $tablename = 'poll_ip';

    /**
     * returns if the module is installed.
     *
     * @return boolean
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
     * @return IpModel[]|null
     * @since 1.12.0
     */
    public function getEntriesBy(array $where = [], array $orderBy = [], ?\Ilch\Pagination $pagination = null): ?array
    {
        $select = $this->db()->select();
        $select->fields(['poll_id', 'ip', 'user_id'])
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
            $entryModel = new IpModel();
            $entryModel->setByArray($entries);

            $entrys[] = $entryModel;
        }
        return $entrys;
    }

    /**
     * @param int $pollId
     * @param string $clientIP
     * @return IpModel|null
     */
    public function getIP(int $pollId, string $clientIP): ?IpModel
    {
        $entrys = $this->getEntriesBy(['poll_id' => $pollId, 'ip' => $clientIP]);

        if (!empty($entrys)) {
            return reset($entrys);
        }

        return null;
    }

    /**
     * @param int $pollId
     * @param null|int $userId
     * @return IpModel|null
     */
    public function getVotedUser(int $pollId, ?int $userId): ?IpModel
    {
        if (!$userId) {
            return null;
        }

        $entrys = $this->getEntriesBy(['poll_id' => $pollId, 'user_id' => $userId]);

        if (!empty($entrys)) {
            return reset($entrys);
        }

        return null;
    }

    /**
     * Inserts Ip model.
     *
     * @param IpModel $vote
     */
    public function saveIP(IpModel $vote)
    {
        $fields = $vote->getArray();

        $this->db()->insert($this->tablename)
            ->values($fields)
            ->execute();
    }
}
