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
     * Gets categorys.
     *
     * @param array $where
     * @return CategoryModel[]|null
     */
    public function getCategories($where = [])
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

        $categorys = [];

        foreach ($categoryArray as $categoryRow) {
            $categoryModel = new CategoryModel();
            $categoryModel->setId($categoryRow['id']);
            $categoryModel->setName($categoryRow['name']);
            $categorys[] = $categoryModel;
        }

        return $categorys;
    }

    /**
     * Returns category found by the id.
     *
     * @param int $id
     * @return false|CategoryModel
     */
    public function getCategoryById($id)
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
     */
    public function sort($catId, $key)
    {
        $this->db()->update('articles_cats')
            ->values(['sort' => $key])
            ->where(['id' => $catId])
            ->execute();
    }

    /**
     * Inserts or updates category model.
     *
     * @param CategoryModel $category
     * @return int id of the category
     */
    public function save(CategoryModel $category)
    {
        if ($category->getId()) {
            $this->db()->update('articles_cats')
                ->values(['name' => $category->getName()])
                ->where(['id' => $category->getId()])
                ->execute();

            return $category->getId();
        } else {
            $lastSort = $this->db()->select('MAX(`sort`) AS maxSort')
                ->from('articles_cats')
                ->execute()
                ->fetchAssoc();

            return $this->db()->insert('articles_cats')
                ->values(['name' => $category->getName(), 'sort' => $lastSort['maxSort']+1])
                ->execute();
        }
    }

    /**
     * Deletes category with given id.
     *
     * @param integer $id
     * @return int affectedRows
     */
    public function delete($id)
    {
        return (int)$this->db()->delete('articles_cats')
            ->where(['id' => $id])
            ->execute();
    }
}
