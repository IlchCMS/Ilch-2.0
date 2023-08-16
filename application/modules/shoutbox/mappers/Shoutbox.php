<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shoutbox\Mappers;

use Modules\Shoutbox\Models\Shoutbox as ShoutboxModel;

class Shoutbox extends \Ilch\Mapper
{
    /**
     * @var string
     * @since 1.5.0
     */
    public $tablename = 'shoutbox';

    /**
     * Check if DB-Table exists
     *
     * @return bool
     * @throws \Ilch\Database\Exception
     *  @since 1.5.0
     */
    public function checkDB(): bool
    {
        return $this->db()->ifTableExists($this->tablename);
    }

    /**
     * @param array $where
     * @param array $orderBy
     * @param \Ilch\Pagination|null $pagination
     * @return ShoutboxModel[]|array
     *  @since 1.5.0
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['text' => 'ASC'], ?\Ilch\Pagination $pagination = null): ?array
    {
        $select = $this->db()->select('*')
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
            return [];
        }

        $entries = [];

        foreach ($entryArray as $rows) {
            $entryModel = new ShoutboxModel();
            $entryModel->setByArray($rows);

            $entries[] = $entryModel;
        }

        return $entries;
    }

    /**
     * Gets the Shoutbox.
     *
     * @return ShoutboxModel[]|null
     */
    public function getShoutbox(): ?array
    {
        return $this->getEntriesBy([], ['id' => 'DESC']);
    }

    /**
     * Gets the Shoutbox.
     *
     * @param int|null $limit
     * @return ShoutboxModel[]|null
     */
    public function getShoutboxLimit(?int $limit = null): ?array
    {
        $pagination = null;
        if ($limit) {
            $pagination = new \Ilch\Pagination();
            $pagination->setRows($limit);
        }
        return $this->getEntriesBy([], ['id' => 'DESC'], $pagination);
    }

    /**
     * Insert shoutbox model.
     *
     * @param ShoutboxModel $shoutbox
     * @return int
     */
    public function save(ShoutboxModel $shoutbox): int
    {
        $fields = $shoutbox->getArray(false);

        if ($shoutbox->getId()) {
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $shoutbox->getId()])
                ->execute();
            return $shoutbox->getId();
        } else {
            return $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Deletes shoutbox with given id.
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
     * Deletes all shoutbox entries.
     *
     * @return bool
     */
    public function truncate(): bool
    {
        return $this->db()->truncate($this->tablename);
    }
}
