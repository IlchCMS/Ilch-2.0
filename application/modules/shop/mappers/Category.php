<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Mappers;

use Ilch\Database\Exception;
use Ilch\Mapper;
use Modules\Shop\Models\Category as CategoryModel;

class Category extends Mapper
{
    /**
     * Gets categories.
     *
     * @param array $where
     * @return CategoryModel[]|[]
     */
    public function getCategories(array $where = []): array
    {
        $categoryArray = $this->db()->select(['id', 'pos', 'title'])
            ->from('shop_cats')
            ->join(['ra' => 'shop_access'], 'id = ra.cat_id', 'LEFT', ['read_access' => 'GROUP_CONCAT(ra.group_id)'])
            ->where($where)
            ->group(['id'])
            ->order(['pos' => 'ASC'])
            ->execute()
            ->fetchRows();

        if (empty($categoryArray)) {
            return [];
        }

        $categories = [];
        foreach ($categoryArray as $categoryRow) {
            $categoryModel = new CategoryModel();
            $categoryModel->setId($categoryRow['id']);
            $categoryModel->setPos($categoryRow['pos']);
            $categoryModel->setTitle($categoryRow['title']);
            $categoryModel->setReadAccess($categoryRow['read_access']);

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
    public function getCategoryById(int $id): ?CategoryModel
    {
        $category = $this->getCategories(['id' => $id]);

        if (!$category) {
            return null;
        }

        return reset($category);
    }

    /**
     * Return the categories that the groups are allowed to see.
     *
     * @param string|array $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @return CategoryModel[]
     */
    public function getCategoriesByAccess($groupIds): array
    {
        if (\is_string($groupIds)) {
            $groupIds = explode(',', $groupIds);
        }

        return $this->getCategories(['ra.group_id' => $groupIds]);
    }

    /**
     * Updates the position of cats in the database.
     *
     * @param int $id
     * @param int $position
     */
    public function updatePositionById(int $id, int $position) {
        $this->db()->update('shop_cats')
            ->values(['pos' => $position])
            ->where(['id' => $id])
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
            $this->db()->update('shop_cats')
                ->values([
                    'title' => $category->getTitle()
                ])
                ->where(['id' => $category->getId()])
                ->execute();
            $id = $category->getId();
        } else {
            $maxPos = $this->db()->select('MAX(pos)')
                      ->from('shop_cats')
                      ->execute()
                      ->fetchCell();
                      
            $id = $this->db()->insert('shop_cats')
                ->values([
                    'title' => $category->getTitle(),
                    'pos' => $maxPos+1
                ])
                ->execute();
        }

        $this->saveReadAccess($id, $category->getReadAccess());
    }

    /**
     * Update the entries for which user groups are allowed to see the category.
     *
     * @param int $catId
     * @param string $readAccess example: "1,2,3"
     * @throws Exception
     */
    private function saveReadAccess(int $catId, string $readAccess)
    {
        // Delete possible old entries to later insert the new ones.
        $this->db()->delete('shop_access')
            ->where(['cat_id' => $catId])
            ->execute();

        if (empty($readAccess)) {
            return;
        }

        $sql = 'INSERT INTO [prefix]_shop_access (cat_id, group_id) VALUES';
        $sqlWithValues = $sql;
        $rowCount = 0;
        $groupIds = explode(',', $readAccess);

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
            $sqlWithValues .= '(' . $catId . ',' . (int)$groupId . '),';
        }

        // Insert remaining rows.
        $sqlWithValues = rtrim($sqlWithValues, ',') . ';';
        $this->db()->queryMulti($sqlWithValues);
    }

    /**
     * Deletes category with given id.
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->db()->delete('shop_cats')
            ->where(['id' => $id])
            ->execute();
    }
}
