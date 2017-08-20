<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Mappers;

use Modules\Article\Models\Category as CategoryModel;
use Modules\Article\Config\Config as ArticleConfig;

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
     */
    public function save(CategoryModel $category)
    {
        if ($category->getId()) {
            $this->db()->update('articles_cats')
                ->values(['name' => $category->getName()])
                ->where(['id' => $category->getId()])
                ->execute();
        } else {
            $lastSort = $this->db()->select('MAX(`sort`) AS maxSort')
                ->from('articles_cats')
                ->execute()
                ->fetchAssoc();

            $this->db()->insert('articles_cats')
                ->values(['name' => $category->getName(), 'sort' => $lastSort['maxSort']+1])
                ->execute();
        }
    }

    /**
     * Deletes category with given id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->trigger(ArticleConfig::EVENT_DELETECATEGORY_BEFORE, ['id' => $id]);

        $this->db()->delete('articles_cats')
            ->where(['id' => $id])
            ->execute();

        $this->trigger(ArticleConfig::EVENT_DELETECATEGORY_AFTER, ['id' => $id]);
    }
}
