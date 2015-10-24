<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Boxes;

use Modules\Article\Mappers\Article as ArticleMapper;

class Article extends \Ilch\Box
{
    public function render()
    {
        $articleMapper = new ArticleMapper();

        $this->getView()->set('articles', $articleMapper->getArticleList('', 5));
    }
}
