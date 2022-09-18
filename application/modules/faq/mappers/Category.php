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
     * Gets categories.
     *
     * @param array $where
     * @param array $orderBy
     * @return CategoryModel[]|[]
     */
    public function getCategories($where = [], $orderBy = ['id' => 'ASC'])
    {
        $categoryArray = $this->db()->select('*')
            ->from('faqs_cats')
            ->where($where)
            ->order($orderBy)
            ->execute()
            ->fetchRows();

        if (empty($categoryArray)) {
            return [];
        }

        $categories = [];
        foreach ($categoryArray as $categoryRow) {
            $categoryModel = new CategoryModel($categoryRow);
            $categories[] = $categoryModel;
        }

        return $categories;
    }

    /**
     * Returns category by the id.
     *
     * @param int $id
     * @return null|CategoryModel
     */
    public function getCategoryById($id)
    {
        $category = $this->getCategories(['id' => $id]);

        if (!$category) {
            return null;
        }

        return reset($category);
    }

    /**
     * Inserts or updates category model.
     *
     * @param CategoryModel $category
     */
    public function save(CategoryModel $category)
    {
        if ($category->getId()) {
            $this->db()->update('faqs_cats')
                ->values([
                    'title' => $category->title,
                    'read_access' => $category->read_access
                ])
                ->where(['id' => $category->id])
                ->execute();
        } else {
            $this->db()->insert('faqs_cats')
                ->values([
                    'title' => $category->title,
                    'read_access' => $category->read_access
                ])
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
        $this->db()->delete('faqs_cats')
            ->where(['id' => $id])
            ->execute();
    }
}
