<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Bugtracker\Mappers;

use Modules\Bugtracker\Models\SubCategory as SubCategoryModel;
use Modules\Bugtracker\Models\Category as CategoryModel;


class SubCategory extends \Ilch\Mapper
{
    public function getAllSubCategories()
    {
        $query = "SELECT [prefix]_bugtracker_sub_categories.id AS sub_category_id, [prefix]_bugtracker_sub_categories.name AS sub_category_name,
                    [prefix]_bugtracker_sub_categories.category_id, [prefix]_bugtracker_categories.name AS category_name
                  FROM [prefix]_bugtracker_sub_categories
                  JOIN [prefix]_bugtracker_categories
                  ON [prefix]_bugtracker_sub_categories.category_id = [prefix]_bugtracker_categories.id";
        $res = $this->db()->query($query);

        $i = 0;
        $subCategories = array();

        while ($row = mysqli_fetch_assoc($res))
        {
        	$subCategories[$i] = new SubCategoryModel($row['sub_category_id'], new CategoryModel($row['category_id'], $row['category_name']), $row['sub_category_name']);
            $i++;
        }

        return $subCategories;
    }
}
