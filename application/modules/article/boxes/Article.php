<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Boxes;

use Modules\Article\Mappers\Article as ArticleMapper;
use Modules\User\Mappers\User as UserMapper;

class Article extends \Ilch\Box
{
    public function render()
    {
        $articleMapper = new ArticleMapper();
        $userMapper = new UserMapper();

        $user = null;
        if ($this->getUser()) {
            $user = $userMapper->getUserById($this->getUser()->getId());
        }

        $readAccess = [3];
        if ($user) {
            foreach ($user->getGroups() as $us) {
                $readAccess[] = $us->getId();
            }
        }

        $this->getView()->set('articles', $articleMapper->getArticleList('', $this->getConfig()->get('article_box_articleLimit')))
                        ->set('readAccess', $readAccess);
    }
}
