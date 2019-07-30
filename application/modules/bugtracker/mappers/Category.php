<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Bugtracker\Mappers;

use Modules\Bugtracker\Models\Category as CategoryModel;


class Category extends \Ilch\Mapper
{
    public function getAllCategories()
    {
        $query = "SELECT * FROM [prefix]_bugtracker_categories";
        $res = $this->db()->query($query);

        $i = 0;
        $categories = array();

        while ($row = mysqli_fetch_assoc($res))
        {
        	$categories[$i] = new CategoryModel($row['id'], $row['name']);
            $i++;
        }

        return $categories;
    }

    public function getCategoryByID($id)
    {
        $link = $this->db()->getLink();

        $id = mysqli_real_escape_string($link, $id);

        $query = "SELCET * FROM [prefix]_bugtracker_categories WHERE id = ?";
        $query = $this->db->getSqlWithPrefix($query);
        $stmt = $link->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();

        $row = mysqli_fetch_assoc($res);

        return new CategoryModel($row['id'], $row['name']);
    }
}
