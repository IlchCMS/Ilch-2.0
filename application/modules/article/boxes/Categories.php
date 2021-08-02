<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Article\Boxes;

use Modules\Article\Mappers\Category as CategoryMapper;
use Modules\Article\Mappers\Article as ArticleMapper;
use Modules\User\Mappers\User as UserMapper;

class Categories extends \Ilch\Box
{
    public function render()
    {
        $categoryMapper = new CategoryMapper();
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

        $this->getView()->set('articleMapper', $articleMapper)
            ->set('cats', $categoryMapper->getCategories())
            ->set('readAccess', $readAccess);
    }
}
