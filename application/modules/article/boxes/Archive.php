<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Boxes;

use Modules\Article\Mappers\Article as ArticleMapper;

class Archive extends \Ilch\Box
{
    public function render()
    {
        $articleMapper = new ArticleMapper();

        $this->getView()->set('archive', $articleMapper->getArticleDateList(10));
    }
}
