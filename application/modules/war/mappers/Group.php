<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\War\Mappers;

use Modules\War\Models\Group as EntriesModel;

class Group extends \Ilch\Mapper
{
    public $tablename = 'war_groups';

    /**
     * returns if the module is installed.
     *
     * @return boolean
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
     * @param \Ilch\Pagination|null $pagination
     * @return array|null
     */
    public function getEntriesBy($where = [], $orderBy = ['g.id' => 'DESC'], $pagination = null)
    {
        $select = $this->db()->select()
            ->fields(['g.id', 'g.name', 'g.tag', 'g.image', 'g.member', 'g.desc'])
            ->from(['g' => $this->tablename])
            ->join(['m' => 'media'], 'g.image = m.url', 'LEFT', ['m.url', 'm.url_thumb'])
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
            $entryModel = new EntriesModel();

            $entryModel->setByArray($entries);

            $entrys[] = $entryModel;
        }
        return $entrys;
    }

    /**
     * Gets the Groups.
     *
     * @param array $where
     * @param \Ilch\Pagination|null $pagination
     * @return null|array
     */
    public function getGroups($where = [], $pagination = null)
    {
        return $this->getEntriesBy($where, ['g.id' => 'DESC'], $pagination);
    }

    /**
     * Gets the Group List
     *
     * @param \Ilch\Pagination|null $pagination
     * @return null|array
     */
    public function getGroupList($pagination = null)
    {
        return $this->getEntriesBy([], ['g.id' => 'DESC'], $pagination);
    }

    /**
     * Gets group by id.
     *
     * @param int|EntriesModel $id
     * @return EntriesModel|null
     */
    public function getGroupById($id)
    {
        if (is_a($id, EntriesModel::class)) {
            $id = $id->getId();
        }

        $entrys = $this->getEntriesBy(['g.id' => (int)$id], []);

        if (!empty($entrys)) {
            return reset($entrys);
        }

        return null;
    }

    /**
     * Inserts or updates entry.
     *
     * @param EntriesModel $model
     * @return integer
     */
    public function save(EntriesModel $model): int
    {
        $fields = $model->getArray();

        if ($model->getId()) {
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $model->getId()])
                ->execute();
            $result = $model->getId();
        } else {
            $result = (int)$this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }

        return $result;
    }

    /**
     * Deletes the entry.
     *
     * @param int|EntriesModel $id
     * @return boolean
     */
    public function delete($id): bool
    {
        if (is_a($id, EntriesModel::class)) {
            $id = $id->getId();
        }

        return $this->db()->delete($this->tablename)
            ->where(['id' => (int)$id])
            ->execute();
    }

    /**
     * Get Export Json.
     *
     * @param int $options
     * @return string
     */
    public function getJson(int $options = 0): string
    {
        $entryArray = $this->getEntriesBy();
        $entrys = [];

        if ($entryArray) {
            foreach ($entryArray as $entryModel) {
                $entrys[] = $entryModel->getArray(false);
            }
        }
        
        return json_encode($entrys, $options);
    }
}
