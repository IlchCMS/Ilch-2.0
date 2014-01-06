<?php
/**
 * Holds class Category.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Link\Mappers;

use Link\Models\Category as CategoryModel;

defined('ACCESS') or die('no direct access');

/**
 * The user category mapper class.
 *
 * @package ilch
 */
class Category extends \Ilch\Mapper
{
    /**
     * Gets categorys.
     *
     * @param array $where
     * @return CategoryModel[]|null
     */
    public function getCategories($where = array())
    {
        $categoryArray = $this->db()->selectArray('*', 'link_cats', $where);

        if (empty($categoryArray)) {
            return array();
        }

        $categorys = array();

        foreach ($categoryArray as $categoryRow) {
            $categoryModel = new CategoryModel();
            $categoryModel->setId($categoryRow['id']);
            $categoryModel->setCatId($categoryRow['cat_id']);
            $categoryModel->setName($categoryRow['name']);
            $categoryModel->setDesc($categoryRow['desc']);
            $categorys[] = $categoryModel;
        }

        return $categorys;
    }
    
    /**
     * Returns user model found by the id or false if none found.
     *
     * @param  int              $id
     * @return false|CategoryModel
     */
    public function getCategoryById($id)
    {
        $categoryRow = $this->db()->selectRow
        (
            '*',
            'link_cats',
            array('id' => $this->db()->escape($id))
        );

        if (empty($categoryRow)) {
            return null;
        }

        $categoryModel = new CategoryModel();
        $categoryModel->setId($categoryRow['id']);
        $categoryModel->setCatID($categoryRow['cat_id']);
        $categoryModel->setName($categoryRow['name']);
        $categoryModel->setDesc($categoryRow['desc']);

        return $categoryModel;
    }

    /**
     * Returns user model found by the name or false if none found.
     *
     * @param  string           $name
     * @return false|CategoryModel
     */
    public function getCategoryByName($name)
    {
        $where = array
        (
            'name' => $name,
        );

        $categorys = $this->_getBy($where);

        if (!empty($categorys)) {
            return reset($categorys);
        }

        return false;
    }

    /**
     * Inserts or updates category model.
     *
     * @param CategoryModel $category
     */
    public function save(CategoryModel $category)
    {
        if ($category->getId()) {
            $this->db()->update
            (
                array
                (
                    'name' => $category->getName(),
                    'desc' => $category->getDesc(),
                    'cat_id' => $category->getCatId()
                ),
                'link_cats',
                array
                (
                    'id' => $category->getId(),
                )
            );
        } else {
            $this->db()->insert
            (
                array
                (
                    'name' => $category->getName(),
                    'desc' => $category->getDesc(),
                    'cat_id' => $category->getCatId()
                ),
                'link_cats'
            );
        }
    }

    /**
     * Deletes category with given id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete
        (
            'link_cats',
            array('id' => $id)
        );
    }
}
