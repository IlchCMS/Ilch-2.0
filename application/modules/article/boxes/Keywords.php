<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Article\Boxes;

use Modules\User\Mappers\User as UserMapper;
use Modules\Article\Mappers\Article as ArticleMapper;

class Keywords extends \Ilch\Box
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

        $keywordsList = [];
        foreach ($articleMapper->getKeywordsListAccess($readAccess) as $keywords) {
            $keywordsList[] = $keywords->getKeywords();
        }

        $keywordsListString = implode(', ', $keywordsList);
        $keywordsListArray = explode(', ', $keywordsListString);
        $keywordsList = array_count_values($keywordsListArray);

        $keywordsFontSizes = explode(',', $this->getConfig()->get('article_box_keywords'));
        $this->getView()->set('keywordsList', $keywordsList)
            ->set('keywordsH2', $keywordsFontSizes[0])
            ->set('keywordsH3', $keywordsFontSizes[1])
            ->set('keywordsH4', $keywordsFontSizes[2])
            ->set('keywordsH5', $keywordsFontSizes[3]);
    }
}
