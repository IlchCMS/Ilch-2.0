<?php
/**
 * Holds Article_ArticleMapper.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Article\Mappers;
use Article\Models\Article as ArticleModel;

defined('ACCESS') or die('no direct access');

/**
 * The article mapper class.
 *
 * @package ilch
 */
class Article extends \Ilch\Mapper
{
    /**
     * Get articles.
     *
     * @param  string $locale
     * @return Article_ArticleModel[]|array
     */
    public function getArticles($locale = '')
    {
        $sql = 'SELECT pc.*, p.* FROM [prefix]_articles as p
                LEFT JOIN [prefix]_articles_content as pc ON p.id = pc.article_id
                    AND pc.locale = "'.$this->db()->escape($locale).'"
                GROUP BY p.id';
        $articleArray = $this->db()->queryArray($sql);

        if (empty($articleArray)) {
            return array();
        }

        $articles = array();

        foreach ($articleArray as $articleRow) {
            $articleModel = new ArticleModel();
            $articleModel->setId($articleRow['id']);
            $articleModel->setTitle($articleRow['title']);
            $articleModel->setPerma($articleRow['perma']);
            $articleModel->setContent($articleRow['content']);
            $articleModel->setDateCreated($articleRow['date_created']);
            $articleModel->setArticleImage($articleRow['article_img']);
            $articles[] = $articleModel;
        }

        return $articles;
    }

    /**
     * Get article lists for overview.
     *
     * @param string $locale
     * @param integer $limit
     * @return Article_ArticleModel[]|null
     */
    public function getArticleList($locale = '', $limit = null)
    {
        $sql = 'SELECT pc.title, pc.perma, p.id FROM [prefix]_articles as p
                LEFT JOIN [prefix]_articles_content as pc ON p.id = pc.article_id
                    AND pc.locale = "'.$this->db()->escape($locale).'"
                GROUP BY p.id
                ORDER BY p.`date_created` ASC';
        
        if ($limit !== null) {
           $sql .= ' LIMIT '.(int)$limit;
        }

        $articleArray = $this->db()->queryArray($sql);

        if (empty($articleArray)) {
            return null;
        }

        $articles = array();

        foreach ($articleArray as $articleRow) {
            $articleModel = new ArticleModel();
            $articleModel->setId($articleRow['id']);
            $articleModel->setTitle($articleRow['title']);
            $articleModel->setPerma($articleRow['perma']);
            $articles[] = $articleModel;
        }

        return $articles;
    }

    /**
     * Returns article model found by the key.
     *
     * @param  string              $id
     * @param  string              $locale
     * @return Article_ArticleModel|null
     */
    public function getArticleByIdLocale($id, $locale = '')
    {
            $sql = 'SELECT * FROM [prefix]_articles as p
                    INNER JOIN [prefix]_articles_content as pc ON p.id = pc.article_id
                    WHERE p.`id` = "'.(int) $id.'" AND pc.locale = "'.$this->db()->escape($locale).'"';
        $articleRow = $this->db()->queryRow($sql);

        if (empty($articleRow)) {
            return null;
        }

        $articleModel = new ArticleModel();
        $articleModel->setId($articleRow['id']);
        $articleModel->setTitle($articleRow['title']);
        $articleModel->setContent($articleRow['content']);
        $articleModel->setLocale($articleRow['locale']);
        $articleModel->setPerma($articleRow['perma']);
        $articleModel->setDateCreated($articleRow['date_created']);
        $articleModel->setArticleImage($articleRow['article_img']);

        return $articleModel;
    }

    /**
     * Returns all article permas.
     *
     * @return array|null
     */
    public function getArticlePermas()
    {
        $sql = 'SELECT article_id, locale, perma FROM [prefix]_articles_content';
        $permas = $this->db()->queryArray($sql);
        $permaArray = array();

        if (empty($permas)) {
            return null;
        }

        foreach ($permas as $perma) {
            $permaArray[$perma['perma']] = $perma;
        }

        return $permaArray;
    }

    /**
     * Inserts or updates a article model in the database.
     *
     * @param ArticleModel $article
     */
    public function save(ArticleModel $article)
    {
        if ($article->getId()) {
            if ($this->getArticleByIdLocale($article->getId(), $article->getLocale())) {
                $this->db()->update('articles_content')
                    ->fields(array('title' => $article->getTitle(), 'content' => $article->getContent(), 'perma' => $article->getPerma(), 'article_img' => $article->getArticleImage()))
                    ->where(array('article_id' => $article->getId(), 'locale' => $article->getLocale()))
                    ->execute();
            } else {
                $this->db()->insert('articles_content')
                    ->fields
                    (
                        array
                        (
                            'article_id' => $article->getId(),
                            'title' => $article->getTitle(),
                            'content' => $article->getContent(),
                            'perma' => $article->getPerma(),
                            'locale' => $article->getLocale(),
                            'article_img' => $article->getArticleImage()
                        )
                    )
                    ->execute();
            }
        } else {
            $date = new \Ilch\Date();
            $articleId = $this->db()->insert('articles')
                ->fields(array('date_created' => $date->toDb()))
                ->execute();

            $this->db()->insert('articles_content')
                ->fields
                (
                    array
                    (
                        'article_id' => $articleId,
                        'title' => $article->getTitle(),
                        'content' => $article->getContent(),
                        'perma' => $article->getPerma(),
                        'locale' => $article->getLocale(),
                        'article_img' => $article->getArticleImage()
                    )
                )
                ->execute();
        }
    }

    public function delete($id)
    {
        $this->db()->delete('articles')
            ->where(array('id' => $id))
            ->execute();
        
        $this->db()->delete('articles_content')
            ->where(array('article_id' => $id))
            ->execute();
    }
}
