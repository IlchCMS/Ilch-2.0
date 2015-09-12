<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Mappers;

use Modules\Article\Models\Article as ArticleModel;

defined('ACCESS') or die('no direct access');

/**
 * The article mapper class.
 */
class Article extends \Ilch\Mapper
{
    /**
     * Get articles.
     *
     * @param string $locale
     * @return Article_ArticleModel[]|array
     */

    public function getArticles($locale = '')
    {
        $select = $this->db()->select();
        $result = $select->fields(['p.id', 'p.cat_id', 'p.date_created'])
            ->from(['p' => 'articles'])
            ->join(['pc' => 'articles_content'], 'p.id = pc.article_id', 'LEFT', ['pc.article_id', 'pc.author_id', 'pc.visits', 'pc.content', 'pc.description', 'pc.locale', 'pc.title', 'pc.perma', 'pc.article_img', 'pc.article_img_source'])
            ->where(['pc.locale' => $this->db()->escape($locale)])
            ->group(['p.id'])
            ->order(['date_created' => 'DESC']);

        $items = $result->execute();

        $articleArray = $items->fetchRows();

        if (empty($articleArray)) {
            return null;
        }

        $articles = array();

        foreach ($articleArray as $articleRow) {
            $articleModel = new ArticleModel();
            $articleModel->setId($articleRow['id']);
            $articleModel->setCatId($articleRow['cat_id']);
            $articleModel->setVisits($articleRow['visits']);
            $articleModel->setAuthorId($articleRow['author_id']);
            $articleModel->setDescription($articleRow['description']);
            $articleModel->setTitle($articleRow['title']);
            $articleModel->setPerma($articleRow['perma']);
            $articleModel->setContent($articleRow['content']);
            $articleModel->setDateCreated($articleRow['date_created']);
            $articleModel->setArticleImage($articleRow['article_img']);
            $articleModel->setArticleImageSource($articleRow['article_img_source']);
            $articles[] = $articleModel;
        }

        return $articles;
    }

    /**
     * Get articles.
     *
     * @param string $locale
     * @return Article_ArticleModel[]|array
     */
    public function getArticlesByCats($catId, $locale = '')
    {
        $sql = 'SELECT pc.*, p.*
                FROM `[prefix]_articles` as p
                LEFT JOIN `[prefix]_articles_content` as pc ON p.id = pc.article_id
                AND pc.locale = "'.$this->db()->escape($locale).'"
                WHERE p.cat_id = "'.$catId.'"
                GROUP BY p.id DESC';
        $articleArray = $this->db()->queryArray($sql);

        if (empty($articleArray)) {
            return null;
        }

        $articles = array();

        foreach ($articleArray as $articleRow) {
            $articleModel = new ArticleModel();
            $articleModel->setId($articleRow['id']);
            $articleModel->setCatId($articleRow['cat_id']);
            $articleModel->setVisits($articleRow['visits']);
            $articleModel->setAuthorId($articleRow['author_id']);
            $articleModel->setDescription($articleRow['description']);
            $articleModel->setTitle($articleRow['title']);
            $articleModel->setPerma($articleRow['perma']);
            $articleModel->setContent($articleRow['content']);
            $articleModel->setDateCreated($articleRow['date_created']);
            $articleModel->setArticleImage($articleRow['article_img']);
            $articleModel->setArticleImageSource($articleRow['article_img_source']);
            $articles[] = $articleModel;
        }

        return $articles;
    }

    public function getArticlesByDate($date)
    {
        $sql = 'SELECT pc.*, p.*
                FROM `[prefix]_articles` as p
                LEFT JOIN `[prefix]_articles_content` as pc ON p.id = pc.article_id
                WHERE YEAR(p.date_created) = YEAR("'.$date.'") AND MONTH(p.date_created) = MONTH("'.$date.'")
                GROUP BY p.id DESC';
        $articleArray = $this->db()->queryArray($sql);

        if (empty($articleArray)) {
            return null;
        }

        $articles = array();

        foreach ($articleArray as $articleRow) {
            $articleModel = new ArticleModel();
            $articleModel->setId($articleRow['id']);
            $articleModel->setCatId($articleRow['cat_id']);
            $articleModel->setVisits($articleRow['visits']);
            $articleModel->setAuthorId($articleRow['author_id']);
            $articleModel->setDescription($articleRow['description']);
            $articleModel->setTitle($articleRow['title']);
            $articleModel->setPerma($articleRow['perma']);
            $articleModel->setContent($articleRow['content']);
            $articleModel->setDateCreated($articleRow['date_created']);
            $articleModel->setArticleImage($articleRow['article_img']);
            $articleModel->setArticleImageSource($articleRow['article_img_source']);
            $articles[] = $articleModel;
        }

        return $articles;
    }

    public function getCountArticlesByMonthYear($date = null)
    {
        $sql = 'SELECT COUNT(*)
                FROM `[prefix]_articles`';
        
        if ($date != null) {
            $sql .= ' WHERE YEAR(date_created) = YEAR("'.$date.'") AND MONTH(date_created) = MONTH("'.$date.'")';
        }

        $article = $this->db()->queryCell($sql);

        return $article;
    }

    public function getArticleDateList($limit = null)
    {
        $sql = 'SELECT *
                FROM `[prefix]_articles`
                GROUP BY YEAR(date_created), MONTH(date_created)
                ORDER BY `date_created` DESC';
        
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
            $articleModel->setDateCreated($articleRow['date_created']);
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
        $sql = 'SELECT `a`.`id`, `a`.`cat_id`, `ac`.`author_id`, `ac`.`visits`, `ac`.`title`, `ac`.`perma`, `ac`.`article_img`,`ac`.`article_img_source`,`m`.`url_thumb`,`m`.`url`
                FROM `[prefix]_articles` as `a`
                LEFT JOIN `[prefix]_articles_content` as `ac` ON `a`.`id` = `ac`.`article_id`
                AND `ac`.`locale` = "'.$this->db()->escape($locale).'"
                LEFT JOIN `[prefix]_media` `m` ON `ac`.`article_img` = `m`.`url`
                GROUP BY `a`.`id`
                ORDER BY `a`.`date_created` DESC';
        
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
            $articleModel->setCatId($articleRow['cat_id']);
            $articleModel->setAuthorId($articleRow['author_id']);
            $articleModel->setVisits($articleRow['visits']);
            $articleModel->setTitle($articleRow['title']);
            $articleModel->setPerma($articleRow['perma']);
            $articleModel->setArticleImage($articleRow['article_img']);
            $articleModel->setArticleImageThumb($articleRow['url_thumb']);
            $articleModel->setArticleImageSource($articleRow['article_img_source']);
            $articles[] = $articleModel;
        }

        return $articles;
    }

    /**
     * Returns article model found by the key.
     *
     * @param string $id
     * @param string $locale
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
        $articleModel->setCatId($articleRow['cat_id']);
        $articleModel->setAuthorId($articleRow['author_id']);
        $articleModel->setVisits($articleRow['visits']);
        $articleModel->setDescription($articleRow['description']);
        $articleModel->setTitle($articleRow['title']);
        $articleModel->setContent($articleRow['content']);
        $articleModel->setLocale($articleRow['locale']);
        $articleModel->setPerma($articleRow['perma']);
        $articleModel->setDateCreated($articleRow['date_created']);
        $articleModel->setArticleImage($articleRow['article_img']);
        $articleModel->setArticleImageSource($articleRow['article_img_source']);

        return $articleModel;
    }

    /**
     * Returns all article permas.
     *
     * @return array|null
     */
    public function getArticlePermas()
    {
        $sql = 'SELECT article_id, locale, perma FROM `[prefix]_articles_content`';
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
     * Updates visits.
     *
     * @param ArticleModel $article
     */
    public function saveVisits(ArticleModel $article)
    {
        if ($article->getVisits()) {
            $this->db()->update('articles_content')
                    ->values(array('visits' => $article->getVisits()))
                    ->where(array('article_id' => $article->getId()))
                    ->execute();
        }
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
                $this->db()->update('articles')
                    ->values(array('cat_id' => $article->getCatId()))
                    ->where(array('id' => $article->getId()))
                    ->execute();

                $this->db()->update('articles_content')
                    ->values
                    (
                        array
                        (
                            'title' => $article->getTitle(),
                            'description' => $article->getDescription(),
                            'content' => $article->getContent(),
                            'perma' => $article->getPerma(),
                            'article_img' => $article->getArticleImage(),
                            'article_img_source' => $article->getArticleImageSource()
                        )
                    )
                    ->where
                    (
                        array
                        (
                            'article_id' => $article->getId(), 
                            'locale' => $article->getLocale()
                        )
                    )
                    ->execute();
            } else {
                $this->db()->insert('articles_content')
                    ->values
                    (
                        array
                        (
                            'article_id' => $article->getId(),
                            'author_id' => $article->getAuthorId(),
                            'description' => $article->getDescription(),
                            'title' => $article->getTitle(),
                            'content' => $article->getContent(),
                            'perma' => $article->getPerma(),
                            'locale' => $article->getLocale(),
                            'article_img' => $article->getArticleImage(),
                            'article_img_source' => $article->getArticleImageSource()
                        )
                    )
                    ->execute();
            }
        } else {
            $date = new \Ilch\Date();
            $articleId = $this->db()->insert('articles')
                ->values
                (
                    array
                    (
                        'cat_id' => $article->getCatId(),
                        'date_created' => $date->toDb()
                    )
                )
                ->execute();

            $this->db()->insert('articles_content')
                ->values
                (
                    array
                    (
                        'article_id' => $articleId,
                        'author_id' => $article->getAuthorId(),
                        'description' => $article->getDescription(),
                        'title' => $article->getTitle(),
                        'content' => $article->getContent(),
                        'perma' => $article->getPerma(),
                        'locale' => $article->getLocale(),
                        'article_img' => $article->getArticleImage(),
                        'article_img_source' => $article->getArticleImageSource()
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
