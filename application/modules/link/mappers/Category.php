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
        $sql = 'SELECT lc.*, COUNT(l.id) as count
                FROM `[prefix]_link_cats` as lc
                LEFT JOIN [prefix]_links as l ON l.cat_id = lc.id
                WHERE 1 ';

        foreach ($where as $key => $value) {
            $sql .= ' AND lc.`'.$key.'` = "'.$this->db()->escape($value).'"';
        }

        $sql .= 'GROUP BY lc.id';
        $categoryArray = $this->db()->queryArray($sql);

        if (empty($categoryArray)) {
            return array();
        }

        $categorys = array();

        foreach ($categoryArray as $categoryRow) {
            $categoryModel = new CategoryModel();
            $categoryModel->setId($categoryRow['id']);
            $categoryModel->setParentId($categoryRow['parent_id']);
            $categoryModel->setName($categoryRow['name']);
            $categoryModel->setDesc($categoryRow['desc']);
            $categoryModel->setLinksCount($categoryRow['count']);
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
        $cats = $this->getCategories(array('id' => $id));
        return reset($cats);
    }

    public function _getCategoriesForParentRec($models, $id)
    {
        $categoryRow = $this->db()->selectRow
        (
            '*',
            'link_cats',
            array('id' => $id)
        );
        
        if (empty($categoryRow)) {
            return null;
        }

        if (!empty($categoryRow['parent_id'])) {
           $models = $this->_getCategoriesForParentRec($models, $categoryRow['parent_id']);
        }
                        
        $categoryModel = new CategoryModel();
        $categoryModel->setId($categoryRow['id']);
        $categoryModel->setParentId($categoryRow['parent_id']);
        $categoryModel->setName($categoryRow['name']);
        $categoryModel->setDesc($categoryRow['desc']);
        $models[] = $categoryModel;
        
        return $models;
    }
    /**
     * Returns user model found by the name or false if none found.
     *
     * @param  string           $name
     * @return false|CategoryModel
     */
    public function getCategoriesForParent($id)
    {
        $models = $this->_getCategoriesForParentRec(array(), $id);
        return $models;
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
                    'parent_id' => $category->getParentId()
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
                    'parent_id' => $category->getParentId()
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
