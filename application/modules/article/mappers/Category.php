<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Article\Mappers;

use Modules\Article\Models\Category as CategoryModel;

class Category extends \Ilch\Mapper
{
    /**
     * Get categories.
     *
     * @param array $where
     * @return CategoryModel[]|null
     */
    public function getCategories(array $where = []): ?array
    {
        $categoryArray = $this->db()->select('*')
            ->from('articles_cats')
            ->where($where)
            ->order(['sort' => 'ASC'])
            ->execute()
            ->fetchRows();

        if (empty($categoryArray)) {
            return null;
        }

        $categories = [];

        foreach ($categoryArray as $categoryRow) {
            $categoryModel = new CategoryModel();
            $categoryModel->setId($categoryRow['id']);
            $categoryModel->setName($categoryRow['name']);
            $categories[] = $categoryModel;
        }

        return $categories;
    }

    /**
     * Returns category found by the id.
     *
     * @param int $id
     * @return false|CategoryModel
     */
    public function getCategoryById(int $id): CategoryModel|bool
    {
        $cats = $this->getCategories(['id' => $id]);

        if ($cats == null) {
            return false;
        }

        return reset($cats);
    }

    /**
     * Sort category.
     *
     * @param int $catId
     * @param int $key
     * @return int affectedRows
     */
    public function sort(int $catId, int $key): int
    {
        return (int)$this->db()->update('articles_cats')
            ->values(['sort' => $key])
            ->where(['id' => $catId])
            ->execute();
    }

    /**
     * Inserts or updates category model.
     *
     * If the given id exists, the category is updated. Otherwise (including
     * when the given id no longer exists) a new category is inserted and the
     * auto-increment id is returned.
     *
     * @param CategoryModel $category
     * @return int id of the category
     */
    public function save(CategoryModel $category): int
    {
        if ($category->getId()) {
            $affectedRows = (int)$this->db()->update('articles_cats')
                ->values(['name' => $category->getName()])
                ->where(['id' => $category->getId()])
                ->execute();

            if ($affectedRows > 0) {
                return $category->getId();
            }
        }

        // Insert new category (also when the given id no longer exists).
        $lastSort = $this->db()->select('MAX(`sort`) AS maxSort')
            ->from('articles_cats')
            ->execute()
            ->fetchAssoc();

        return $this->db()->insert('articles_cats')
            ->values(['name' => $category->getName(), 'sort' => $lastSort['maxSort'] + 1])
            ->execute();
    }

    /**
     * Deletes category with given id.
     *
     * @param int $id
     * @return int affectedRows
     */
    public function delete(int $id): int
    {
        return (int)$this->db()->delete('articles_cats')
            ->where(['id' => $id])
            ->execute();
    }
}
