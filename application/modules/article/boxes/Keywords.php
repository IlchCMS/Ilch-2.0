<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Boxes;

use Modules\Article\Mappers\Article as ArticleMapper;

class Keywords extends \Ilch\Box
{
    public function render()
    {
        $articleMapper = new ArticleMapper();

        $keywordsList = [];
        foreach ($articleMapper->getKeywordsList() as $keywords) {
            $keywordsList[] = $keywords->getKeywords();
        }

        $keywordsListString = implode(', ', $keywordsList);
        $keywordsListArray = explode(", ", $keywordsListString);
        $keywordsList = array_count_values($keywordsListArray);

        $this->getView()->set('keywordsList', $keywordsList)
            ->set('keywordsH2', $this->getConfig()->get('article_box_keywordsH2'))
            ->set('keywordsH3', $this->getConfig()->get('article_box_keywordsH3'))
            ->set('keywordsH4', $this->getConfig()->get('article_box_keywordsH4'))
            ->set('keywordsH5', $this->getConfig()->get('article_box_keywordsH5'));
    }
}
