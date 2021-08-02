<?php
/**
 * @copyright Ilch 2
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

        $locale = '';
        if ((bool)$this->getConfig()->get('multilingual_acp') && $this->getTranslator()->getLocale() != $this->getConfig()->get('content_language')) {
            $locale = $this->getTranslator()->getLocale();
        }

        $this->getView()->set('articles', $articleMapper->getArticleListAccess($readAccess, $locale, $this->getConfig()->get('article_box_articleLimit')));
    }
}
