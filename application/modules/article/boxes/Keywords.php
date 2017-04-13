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

        $keywordsFontSizes = explode(',',$this->getConfig()->get('article_box_keywords'));
        $this->getView()->set('keywordsList', $keywordsList)
            ->set('keywordsH2', $keywordsFontSizes[0])
            ->set('keywordsH3', $keywordsFontSizes[1])
            ->set('keywordsH4', $keywordsFontSizes[2])
            ->set('keywordsH5', $keywordsFontSizes[3]);
    }
}
