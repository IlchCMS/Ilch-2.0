<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Link\Mappers;

use Ilch\Pagination;
use Modules\Link\Models\Link as LinkModel;

class Link extends \Ilch\Mapper
{
    /**
     * @var string
     */
    public $tablename = 'links';

    /**
     * returns if the module is installed.
     *
     * @return bool
     */
    public function checkDB(): bool
    {
        return $this->db()->ifTableExists($this->tablename);
    }

    /**
     * Gets the Entries by param.
     *
     * @param array $where
     * @param array $orderBy
     * @param Pagination|null $pagination
     * @return LinkModel[]|null
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['l.pos' => 'ASC'], ?Pagination $pagination = null): ?array
    {
        $select = $this->db()->select();
        $select->fields(['l.id', 'l.cat_id', 'l.pos', 'l.name', 'l.desc', 'l.banner', 'l.link', 'l.hits'])
            ->from(['l' => $this->tablename])
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

        $entriesArray = $result->fetchRows();
        if (empty($entriesArray)) {
            return null;
        }
        $entries = [];

        foreach ($entriesArray as $entry) {
            $entryModel = new LinkModel();
            $entryModel->setByArray($entry);

            $entries[] = $entryModel;
        }
        return $entries;
    }

    /**
     * Gets links.
     *
     * @param array $where
     * @return LinkModel[]|null
     */
    public function getLinks(array $where = []): ?array
    {
        return $this->getEntriesBy($where);
    }

    /**
     * Gets link.
     *
     * @param int $id
     * @return LinkModel|null
     */
    public function getLinkById(int $id): ?LinkModel
    {
        $links = $this->getEntriesBy(['l.id' => $id]);

        if ($links) {
            return reset($links);
        }

        return null;
    }

    /**
     * Gets link.
     *
     * @param int $catId
     * @return LinkModel[]|null
     */
    public function getLinksByCatId(int $catId): ?array
    {
        return $this->getEntriesBy(['l.cat_id' => $catId]);
    }

    /**
     * Updates the position of a link in the database.
     *
     * @param int $id
     * @param int $position
     * @return bool
     */
    public function updatePositionById(int $id, int $position): bool
    {
        return $this->db()->update($this->tablename)
            ->values(['pos' => $position])
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Inserts or updates link model.
     *
     * @param LinkModel $link
     * @return int
     */
    public function save(LinkModel $link): int
    {
        $fields = $link->getArray();

        if ($link->getId()) {
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $link->getId()])
                ->execute();
            return $link->getId();
        } else {
            return $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Deletes link with given id.
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
}
