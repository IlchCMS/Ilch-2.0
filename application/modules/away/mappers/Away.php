<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Away\Mappers;

use Ilch\Database\Exception;
use Ilch\Date;
use Modules\Away\Models\Away as AwayModel;

class Away extends \Ilch\Mapper
{
    /**
     * @var string
     * @since 1.8.1
     */
    public $tablename = 'away';

    /**
     * returns if the module is installed.
     *
     * @return boolean
     * @throws \Ilch\Database\Exception
     * @since 1.8.1
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
     * @return AwardsModel[]|null
     * @since 1.8.1
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['id' => 'ASC'], ?\Ilch\Pagination $pagination = null): ?array
    {
        $select = $this->db()->select();
        $select->fields('*')
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
            $entryModel = new AwayModel();
            $entryModel->setByArray($entries);

            $entrys[] = $entryModel;
        }
        return $entrys;
    }

    /**
     * Gets the Away.
     *
     * @param array $where
     * @return AwayModel[]
     */
    public function getAway(array $where = []): array
    {
        $entryArray = $this->getEntriesBy($where, ['start' => 'ASC']);

        if (empty($entryArray)) {
            return [];
        }
        return $entryArray;
    }

    /**
     * Gets away.
     *
     * @param int $id
     * @return AwayModel|null
     */
    public function getAwayById(int $id): ?AwayModel
    {
        $away = $this->getAway(['id' => $id]);

        return reset($away);
    }

    public function existsTable($table): bool
    {
        return $this->db()->ifTableExists('[prefix]_' . $table);
    }

    /**
     * Inserts or updates away model.
     *
     * @param AwayModel $away
     * @return int
     */
    public function save(AwayModel $away): int
    {
        $fields = $away->getArray(false);

        if ($away->getId()) {
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $away->getId()])
                ->execute();

                return $away->getId();
        } else {
            return $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Gets the Away entries by start and end.
     *
     * @param string $start
     * @param string $end
     * @return AwayModel[]|null
     * @throws Exception
     */
    public function getEntriesForJson(string $start, string $end): ?array
    {
        if (empty($start) && empty($end)) {
            return null;
        }
        $start = new Date($start);
        $end = new Date($end);

        return $this->getEntriesBy(['start >=' => $start, 'end <=' => $end, 'show' => 1], ['start' => 'ASC']);
    }

    /**
     * Updates away with given id.
     *
     * @param int $id
     */
    public function update(int $id)
    {
        $show = (int) $this->db()->select('status')
                        ->from($this->tablename)
                        ->where(['id' => $id])
                        ->execute()
                        ->fetchCell();

        if ($show == 1) {
            $this->db()->update($this->tablename)
                ->values(['status' => 0])
                ->where(['id' => $id])
                ->execute();
        } else {
            $this->db()->update($this->tablename)
                ->values(['status' => 1])
                ->where(['id' => $id])
                ->execute();
        }
    }

    /**
     * Deletes away with given id.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->db()->delete($this->tablename)
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Deletes all entries.
     *
     * @return bool
     * @since 1.8.1
     */
    public function truncate(): bool
    {
        return (bool)$this->db()->truncate($this->tablename);
    }
}
