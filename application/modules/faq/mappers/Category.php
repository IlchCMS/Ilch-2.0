<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Faq\Mappers;

use Modules\Faq\Models\Category as CategoryModel;

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
            ->from('faqs_cats')
            ->where($where)
            ->execute()
            ->fetchRows();

        if (empty($categoryArray)) {
            return null;
        }

        $categorys = [];
        foreach ($categoryArray as $categoryRow) {
            $categoryModel = new CategoryModel();
            $categoryModel->setId($categoryRow['id']);
            $categoryModel->setTitle($categoryRow['title']);

            $categorys[] = $categoryModel;
        }

        return $categorys;
    }

    /**
     * Returns user model found by the id or false if none found.
     *
     * @param int $id
     * @return false|CategoryModel
     */
    public function getCategoryById($id)
    {
        $cats = $this->getCategories(['id' => $id]);
        return reset($cats);
    }

    public function getCategoryMinId()
    {
        $categoryRow = $this->db()->select('*')
            ->from('faqs_cats')
            ->order(['id' => 'ASC'])
            ->limit('1')
            ->execute()
            ->fetchAssoc();

        if (empty($categoryRow)) {
            return null;
        }

        $categoryModel = new CategoryModel();
        $categoryModel->setId($categoryRow['id']);

        return $categoryModel;
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
                ->values(['title' => $category->getTitle()])
                ->where(['id' => $category->getId()])
                ->execute();
        } else {
            $this->db()->insert('faqs_cats')
                ->values(['title' => $category->getTitle()])
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
