<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Boxes;

use Modules\Article\Mappers\Category as CategoryMapper;
use Modules\Article\Mappers\Article as ArticleMapper;

class Categories extends \Ilch\Box
{
    public function render()
    {
        $categoryMapper = new CategoryMapper();
        $articleMapper = new ArticleMapper();

        $this->getView()->set('articleMapper', $articleMapper);
        $this->getView()->set('cats', $categoryMapper->getCategories());
    }
}
