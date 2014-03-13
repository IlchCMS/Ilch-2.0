<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Boxes\Article;
defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Box
{
    public function render()
    {
        $articleMapper = new \Article\Mappers\Article();
        $this->getView()->set('articles', $articleMapper->getArticleList('', 5));
    }
}