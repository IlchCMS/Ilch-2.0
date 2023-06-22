<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Faq\Mappers;

use Modules\Faq\Models\Category as CategoryModel;

class Category extends \Ilch\Mapper
{
    /**
     * @var string
     * @since 1.9.0
     */
    public $tablename = 'faqs_cats';
    /**
     * @var string
     * @since 1.9.0
     */
    public $tablenameAccess = 'faqs_cats_access';

    /**
     * returns if the module is installed.
     *
     * @return boolean
     * @throws \Ilch\Database\Exception
     * @since 1.9.0
     */
    public function checkDB(): bool
    {
        return $this->db()->ifTableExists($this->tablename) && $this->db()->ifTableExists($this->tablenameAccess);
    }

    /**
     * Gets the Entries by params.
     *
     * @param array $where
     * @param array $orderBy
     * @param \Ilch\Pagination|null $pagination
     * @return CategoryModel[]|null
     * @since 1.9.0
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['id' => 'ASC'], ?\Ilch\Pagination $pagination = null): ?array
    {
        $read_access = '';
        if (isset($where['ra.group_id'])) {
            $read_access = $where['ra.group_id'];
            unset($where['ra.group_id']);
        }

        $select = $this->db()->select();
        $select->fields(['c.id', 'c.title', 'c.read_access_all'])
            ->from(['c' => $this->tablename])
            ->join(['ra' => $this->tablenameAccess], 'c.id = ra.cat_id', 'LEFT', ['read_access' => 'GROUP_CONCAT(ra.group_id)'])
            ->where(array_merge($where, ($read_access ? [$select->orX(['ra.group_id' => $read_access, 'c.read_access_all' => '1'])] : [])))
            ->order($orderBy)
            ->group(['c.id']);

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
            $entryModel = new CategoryModel();
            $entryModel->setByArray($entries);

            $entrys[] = $entryModel;
        }
        return $entrys;
    }

    /**
     * Gets categories.
     *
     * @param array $where
     * @param array $orderBy
     * @param string|array|null $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @return CategoryModel[]|null
     */
    public function getCategories(array $where = [], array $orderBy = ['id' => 'ASC'], $groupIds = '3'): ?array
    {
        if (\is_string($groupIds)) {
            $groupIds = explode(',', $groupIds);
        }

        return $this->getEntriesBy(array_merge($where, ($groupIds ? ['ra.group_id' => $groupIds] : [])), $orderBy);
    }

    /**
     * Returns category by the id.
     *
     * @param int $id
     * @param string|array|null $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @return null|CategoryModel
     */
    public function getCategoryById(int $id, $groupIds = '3'): ?CategoryModel
    {
        if (\is_string($groupIds)) {
            $groupIds = explode(',', $groupIds);
        }

        $entrys = $this->getEntriesBy(array_merge(['c.id' => $id], ($groupIds ? ['ra.group_id' => $groupIds] : [])), []);

        if (!empty($entrys)) {
            return reset($entrys);
        }

        return null;
    }

    /**
     * Inserts or updates entry.
     *
     * @param CategoryModel $model
     * @return integer
     */
    public function save(CategoryModel $model): int
    {
        $fields = $model->getArray();

        if ($model->getId()) {
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $model->getId()])
                ->execute();
            $result = $model->getId();
        } else {
            $result = $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }

        $this->saveReadAccess($result, $model->getReadAccess());

        return $result;
    }

    /**
     * Update the entries for which user groups are allowed to read a Cat.
     *
     * @param int $warId
     * @param string|array $readAccess example: "1,2,3"
     * @param boolean $addAdmin
     * @since 1.9.0
     */
    public function saveReadAccess(int $warId, $readAccess, bool $addAdmin = true)
    {
        if (\is_string($readAccess)) {
            $readAccess = explode(',', $readAccess);
        }

        // Delete possible old entries to later insert the new ones.
        $this->db()->delete($this->tablenameAccess)
            ->where(['cat_id' => $warId])
            ->execute();

        $sql = 'INSERT INTO [prefix]_' . $this->tablenameAccess . ' (cat_id, group_id) VALUES';
        $sqlWithValues = $sql;
        $rowCount = 0;
        $groupIds = [];
        if (!empty($readAccess)) {
            if (!in_array('all', $readAccess)) {
                $groupIds = $readAccess;
            }
        }
        if ($addAdmin && !in_array('1', $groupIds)) {
            $groupIds[] = '1';
        }

        foreach ($groupIds as $groupId) {
            // There is a limit of 1000 rows per insert, but according to some benchmarks found online
            // the sweet spot seams to be around 25 rows per insert. So aim for that.
            if ($rowCount >= 25) {
                $sqlWithValues = rtrim($sqlWithValues, ',') . ';';
                $this->db()->queryMulti($sqlWithValues);
                $rowCount = 0;
                $sqlWithValues = $sql;
            }

            $rowCount++;
            $sqlWithValues .= '(' . $warId . ',' . (int)$groupId . '),';
        }

        if ($sqlWithValues != $sql) {
            // Insert remaining rows.
            $sqlWithValues = rtrim($sqlWithValues, ',') . ';';
            $this->db()->queryMulti($sqlWithValues);
        }
    }

    /**
     * Deletes category with given id.
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
