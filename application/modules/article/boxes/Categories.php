<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Boxes;

use Modules\Article\Mappers\Category as CategoryMapper;

defined('ACCESS') or die('no direct access');

class Categories extends \Ilch\Box
{
    public function render()
    {
        $categoryMapper = new CategoryMapper();

        $this->getView()->set('cats', $categoryMapper->getCategories());
    }
}
