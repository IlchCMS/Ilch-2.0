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
     * @return LinkModel[]|null
     */
    public function getCategories($where = array())
    {
        return $this->_getBy($where);
    }
    
    /**
     * Returns user model found by the id or false if none found.
     *
     * @param  int              $id
     * @return false|CategoryModel
     */
    public function getCategoryById($id)
    {
        $where = array
        (
            'id' => (int)$id,
        );

        $categorys = $this->_getBy($where);

        if (!empty($categorys)) {
            return reset($categorys);
        }

        return null;
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
     * Returns an array with user category models found by the where clause of false if
     * none found.
     *
     * @param  mixed[]            $where
     * @return false|CategoryModel[]
     */
    protected function _getBy($where = null)
    {
        $categoryRows = $this->db()->selectArray
        (
            '*',
            'link_cats',
            $where
        );

        if (!empty($categoryRows)) {
            $categorys = array_map(array($this, 'loadFromArray'), $categoryRows);

            return $categorys;
        }

        return false;
    }

    /**
     * Returns a user category created using an array with user category data.
     *
     * @param  mixed[]    $categoryRow
     * @return CategoryModel
     */
    public function loadFromArray($categoryRow = array())
    {
        $category = new CategoryModel();

        if (isset($categoryRow['id'])) {
            $category->setId($categoryRow['id']);
        }

        if (isset($categoryRow['name'])) {
            $category->setName($categoryRow['name']);
        }
        
        if (isset($categoryRow['cat_id'])) {
            $category->setCatId($categoryRow['cat_id']);
        }
        
        if (isset($categoryRow['desc'])) {
            $category->setDesc($categoryRow['desc']);
        }
 
        return $category;
    }

    /**
     * Loads the user ids associated with a user category.
     *
     * @param int $categoryId
     */
    public function getUsersForCategory($categoryId)
    {
        $userIds = $this->db()->selectList
        (
            'id',
            'cat',
            array('id' => $categoryId)
        );

        return $userIds;
    }

    /**
     * Inserts or updates a user category model into the database.
     *
     * @param CategoryModel $category
     */
    public function save(CategoryModel $category)
    {
        $fields = array();
        $name = $category->getName();

        if (!empty($name)) {
            $fields['name'] = $category->getName();
        }

        $categoryId = (int) $this->db()->selectCell
        (
            'id',
            'link_cats',
            array
            (
                'id' => $category->getId(),
            )
        );

        if ($categoryId) {
            /*
             * Category does exist already, update.
             */
            $this->db()->update
            (
                $fields,
                'link_cats',
                array
                (
                    'id' => $categoryId,
                )
            );
        } else {
            /*
             * Category does not exist yet, insert.
             */
            $categoryId = $this->db()->insert
            (
                $fields,
                'link_cats'
            );
        }

        return $categoryId;
    }

    /**
     * Returns a array of all category model objects.
     *
     * @return categoryModel[]
     */
    public function getCategoryList()
    {
        return $this->_getBy();
    }

    /**
     * Returns whether a category with the given id exists in the database.
     *
     * @param  int $categoryId
     * @return boolean
     */
    public function categoryWithIdExists($categoryId)
    {
        $categoryExists = (boolean)$this->db()->selectCell
        (
            'COUNT(*)',
            'link_cats',
            array
            (
                'id' => (int)$categoryId
            )
        );

        return $categoryExists;
    }

    /**
     * Deletes a given category or a user with the given id.
     *
     * @param  int|CategoryModel $categoryId
     *
     * @return boolean True of success, otherwise false.
     */
    public function delete($categoryId)
    {
        if(is_a($categoryId, '\User\Models\Category'))
        {
            $categoryId = $categoryId->getId();
        }

        $this->db()->delete('link_cats', array('id' => $categoryId));
        return $this->db()->delete('link_cats', array('id' => $categoryId));
    }
}
