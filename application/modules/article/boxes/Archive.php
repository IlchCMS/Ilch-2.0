<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Article\Boxes;

use Modules\Article\Mappers\Article as ArticleMapper;
use Modules\User\Mappers\User as UserMapper;

class Archive extends \Ilch\Box
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

        $this->getView()->set('articleMapper', $articleMapper)
            ->set('archive', $articleMapper->getArticleDateListAccess($readAccess, $this->getConfig()->get('article_box_archiveLimit')))
            ->set('readAccess', $readAccess);
    }
}
