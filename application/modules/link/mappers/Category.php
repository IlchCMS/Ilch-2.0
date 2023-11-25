<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Link\Mappers;

use Ilch\Pagination;
use Modules\Link\Models\Category as CategoryModel;
use Modules\Link\Mappers\Link as LinkMapper;

class Category extends \Ilch\Mapper
{
    /**
     * @var string
     */
    public $tablename = 'link_cats';
    /**
     * @var string
     */
    public $tablename_entries = null;

    /**
     */
    public function __construct()
    {
        parent::__construct();

        $this->tablename_entries = (new LinkMapper())->tablename;

        return $this;
    }

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
     * @param bool $countEntries
     * @return CategoryModel[]|null
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['lc.pos' => 'ASC'], ?Pagination $pagination = null, bool $countEntries = false): ?array
    {
        $select = $this->db()->select();
        $select->fields(['lc.id', 'lc.parent_id', 'lc.pos', 'lc.name', 'lc.desc'])
            ->from(['lc' => $this->tablename])
            ->where($where)
            ->order($orderBy);

        if ($countEntries) {
            $select->join(['l' => $this->tablename_entries], ['l.cat_id = lc.id'], 'LEFT', ['count' => 'COUNT(l.id)'])
                ->group(['lc.id', 'lc.parent_id', 'lc.pos', 'lc.name', 'lc.desc']);
        }

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
            $entryModel = new CategoryModel();
            $entryModel->setByArray($entry);

            $entries[] = $entryModel;
        }
        return $entries;
    }

    /**
     * Gets categorys.
     *
     * @param array $where
     * @return CategoryModel[]|null
     */
    public function getCategories(array $where = []): ?array
    {
        return $this->getEntriesBy($where, ['lc.pos' => 'ASC'], null, true);
    }

    /**
     * Returns user model found by the id or false if none found.
     *
     * @param int $id
     * @return CategoryModel|null
     */
    public function getCategoryById(int $id): ?CategoryModel
    {
        $cats = $this->getEntriesBy(['lc.id' => $id]);

        if ($cats) {
            return reset($cats);
        }

        return null;
    }

    /**
     * Returns user model found by the id or false if none found.
     *
     * @param int $parentId
     * @return CategoryModel[]|null
     */
    public function getCategorysByParentId(int $parentId): ?array
    {
        return $this->getEntriesBy(['lc.parent_id' => $parentId], ['lc.pos' => 'ASC'], null, true);
    }

    /**
     * @param array $models
     * @param int $id
     * @return CategoryModel[]|null
     */
    public function getCategoriesForParentRec(array $models, int $id): ?array
    {
        $categoryModel = $this->getCategoryById($id);

        if (!$categoryModel) {
            return null;
        }

        if ($categoryModel->getParentId()) {
            $models = $this->getCategoriesForParentRec($models, $categoryModel->getParentId());
        }

        $models[] = $categoryModel;

        return $models;
    }

    /**
     * Returns user model found by the name or false if none found.
     *
     * @param int $id
     * @return CategoryModel[]|null
     */
    public function getCategoriesForParent(int $id): ?array
    {
        return $this->getCategoriesForParentRec([], $id);
    }

    /**
     * Updates the position of a category in the database.
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
     * Inserts or updates category model.
     *
     * @param CategoryModel $category
     * @return int
     */
    public function save(CategoryModel $category): int
    {
        $fields = $category->getArray();

        if ($category->getId()) {
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $category->getId()])
                ->execute();
            return $category->getId();
        } else {
            return $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
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
