<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Article\Mappers;

use Modules\Article\Models\Article as ArticleModel;
use Modules\Comment\Mappers\Comment as CommentMapper;
use Modules\Article\Config\Config as ArticleConfig;

class Article extends \Ilch\Mapper
{
    /**
     * Get articles.
     *
     * @param string $locale
     * @param \Ilch\Pagination|null $pagination
     * @return ArticleModel[]|array
     */
    public function getArticles($locale = '', $pagination = null)
    {
        $select = $this->db()->select()
            ->fields(['p.id', 'p.cat_id', 'p.date_created', 'p.top', 'p.commentsDisabled'])
            ->from(['p' => 'articles'])
            ->join(['ra' => 'articles_access'], 'p.id = ra.article_id', 'LEFT', ['read_access' => 'GROUP_CONCAT(ra.group_id)'])
            ->join(['pc' => 'articles_content'], 'p.id = pc.article_id', 'LEFT', ['pc.article_id', 'pc.author_id', 'pc.visits', 'pc.content', 'pc.description', 'pc.keywords', 'pc.keywords', 'pc.locale', 'pc.title', 'pc.teaser', 'pc.perma', 'pc.img', 'pc.img_source', 'pc.votes'])
            ->join(['u' => 'users'], 'pc.author_id = u.id', 'LEFT', ['u.name'])
            ->where(['pc.locale' => $this->db()->escape($locale)])
            ->group(['p.id', 'p.cat_id', 'p.date_created', 'p.top', 'pc.article_id', 'pc.author_id', 'pc.visits', 'pc.content', 'pc.description', 'pc.keywords', 'pc.keywords', 'pc.locale', 'pc.title', 'pc.teaser', 'pc.perma', 'pc.img', 'pc.img_source', 'pc.votes'])
            ->order(['top' => 'DESC', 'date_created' => 'DESC']);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $articleArray = $result->fetchRows();

        if (empty($articleArray)) {
            return null;
        }

        $articles = [];
        foreach ($articleArray as $articleRow) {
            $articles[] = $this->loadFromArray($articleRow);
        }

        return $articles;
    }

    /**
     * Get articles and taking the group IDs into account.
     *
     * @param string|array $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @param string $locale
     * @param null $pagination
     * @return array|null
     * @since 2.1.44
     */
    public function getArticlesByAccess($groupIds = '3', string $locale = '', $pagination = null)
    {
        if (\is_string($groupIds)) {
            $groupIds = explode(',', $groupIds);
        }

        $select = $this->db()->select()
            ->fields(['p.id', 'p.cat_id', 'p.date_created', 'p.top', 'p.commentsDisabled'])
            ->from(['p' => 'articles'])
            ->join(['ra' => 'articles_access'], 'p.id = ra.article_id', 'LEFT', ['read_access' => 'GROUP_CONCAT(ra.group_id)'])
            ->join(['pc' => 'articles_content'], 'p.id = pc.article_id', 'LEFT', ['pc.article_id', 'pc.author_id', 'pc.visits', 'pc.content', 'pc.description', 'pc.keywords', 'pc.keywords', 'pc.locale', 'pc.title', 'pc.teaser', 'pc.perma', 'pc.img', 'pc.img_source', 'pc.votes'])
            ->join(['u' => 'users'], 'pc.author_id = u.id', 'LEFT', ['u.name'])
            ->where(['ra.group_id' => $groupIds, 'pc.locale' => $this->db()->escape($locale)])
            ->group(['p.id', 'p.cat_id', 'p.date_created', 'p.top', 'pc.article_id', 'pc.author_id', 'pc.visits', 'pc.content', 'pc.description', 'pc.keywords', 'pc.keywords', 'pc.locale', 'pc.title', 'pc.teaser', 'pc.perma', 'pc.img', 'pc.img_source', 'pc.votes'])
            ->order(['top' => 'DESC', 'date_created' => 'DESC']);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $articleArray = $result->fetchRows();

        if (empty($articleArray)) {
            return null;
        }

        $articles = [];
        foreach ($articleArray as $articleRow) {
            $articles[] = $this->loadFromArray($articleRow);
        }

        return $articles;
    }

    /**
     * Get articles by cat id.
     *
     * @param integer $catId
     * @param string $locale
     * @param \Ilch\Pagination|null $pagination
     * @return ArticleModel[]|array
     */
    public function getArticlesByCats($catId, $locale = '', $pagination = null)
    {
        $select = $this->db()->select()
            ->fields(['p.id', 'p.cat_id', 'p.date_created', 'p.top', 'p.commentsDisabled'])
            ->from(['p' => 'articles'])
            ->join(['ra' => 'articles_access'], 'p.id = ra.article_id', 'LEFT', ['read_access' => 'GROUP_CONCAT(ra.group_id)'])
            ->join(['pc' => 'articles_content'], 'p.id = pc.article_id', 'LEFT', ['pc.visits', 'pc.author_id', 'pc.description', 'pc.keywords', 'pc.title', 'pc.teaser', 'pc.perma', 'pc.content', 'pc.img', 'pc.img_source', 'pc.votes'])
            ->join(['u' => 'users'], 'pc.author_id = u.id', 'LEFT', ['u.name'])
            ->where(['p.cat_id LIKE' => '%' . $catId . '%', 'pc.locale' => $this->db()->escape($locale)])
            ->group(['p.id'])
            ->order(['id' => 'DESC']);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }
        $articleArray = $result->fetchRows();

        if (empty($articleArray)) {
            return null;
        }

        $articles = [];
        foreach ($articleArray as $articleRow) {
            $articles[] = $this->loadFromArray($articleRow);
        }

        return $articles;
    }

    /**
     * Get articles by category id and taking the group IDs into account.
     *
     * @param integer $catId
     * @param string|array $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @param string $locale
     * @param null $pagination
     * @return array|null
     * @since 2.1.44
     */
    public function getArticlesByCatsAccess(int $catId, $groupIds = '3', string $locale = '', $pagination = null)
    {
        if (\is_string($groupIds)) {
            $groupIds = explode(',', $groupIds);
        }

        $select = $this->db()->select()
            ->fields(['p.id', 'p.cat_id', 'p.date_created', 'p.top', 'p.commentsDisabled'])
            ->from(['p' => 'articles'])
            ->join(['ra' => 'articles_access'], 'p.id = ra.article_id', 'LEFT', ['read_access' => 'GROUP_CONCAT(ra.group_id)'])
            ->join(['pc' => 'articles_content'], 'p.id = pc.article_id', 'LEFT', ['pc.visits', 'pc.author_id', 'pc.description', 'pc.keywords', 'pc.title', 'pc.teaser', 'pc.perma', 'pc.content', 'pc.img', 'pc.img_source', 'pc.votes'])
            ->join(['u' => 'users'], 'pc.author_id = u.id', 'LEFT', ['u.name'])
            ->where(['ra.group_id' => $groupIds, 'p.cat_id LIKE' => '%' . $catId . '%', 'pc.locale' => $this->db()->escape($locale)])
            ->group(['p.id'])
            ->order(['id' => 'DESC']);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }
        $articleArray = $result->fetchRows();

        if (empty($articleArray)) {
            return null;
        }

        $articles = [];
        foreach ($articleArray as $articleRow) {
            $articles[] = $this->loadFromArray($articleRow);
        }

        return $articles;
    }

    /**
     * Get articles by keyword.
     *
     * @param string $keyword
     * @param string $locale
     * @param \Ilch\Pagination|null $pagination
     * @return ArticleModel[]|array
     */
    public function getArticlesByKeyword($keyword, $locale = '', $pagination = null)
    {
        $select = $this->db()->select()
            ->fields(['p.id', 'p.cat_id', 'p.date_created', 'p.top', 'p.commentsDisabled'])
            ->from(['p' => 'articles'])
            ->join(['ra' => 'articles_access'], 'p.id = ra.article_id', 'LEFT', ['read_access' => 'GROUP_CONCAT(ra.group_id)'])
            ->join(['pc' => 'articles_content'], 'p.id = pc.article_id', 'LEFT', ['pc.visits', 'pc.author_id', 'pc.description', 'pc.keywords', 'pc.title', 'pc.teaser', 'pc.perma', 'pc.content', 'pc.img', 'pc.img_source', 'pc.votes'])
            ->join(['u' => 'users'], 'pc.author_id = u.id', 'LEFT', ['u.name'])
            ->where(['pc.keywords LIKE' => '%' . $keyword . '%', 'pc.locale' => $this->db()->escape($locale)])
            ->group(['p.id'])
            ->order(['id' => 'DESC']);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }
        $articleArray = $result->fetchRows();

        if (empty($articleArray)) {
            return null;
        }

        $articles = [];
        foreach ($articleArray as $articleRow) {
            $articles[] = $this->loadFromArray($articleRow);
        }

        return $articles;
    }

    /**
     * Get articles by keyword and taking the group IDs into account.
     *
     * @param string $keyword
     * @param string|array $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @param string $locale
     * @param null $pagination
     * @return array|null
     * @since 2.1.44
     */
    public function getArticlesByKeywordAccess(string $keyword, $groupIds = '3', string $locale = '', $pagination = null)
    {
        if (\is_string($groupIds)) {
            $groupIds = explode(',', $groupIds);
        }

        $select = $this->db()->select()
            ->fields(['p.id', 'p.cat_id', 'p.date_created', 'p.top', 'p.commentsDisabled'])
            ->from(['p' => 'articles'])
            ->join(['ra' => 'articles_access'], 'p.id = ra.article_id', 'LEFT', ['read_access' => 'GROUP_CONCAT(ra.group_id)'])
            ->join(['pc' => 'articles_content'], 'p.id = pc.article_id', 'LEFT', ['pc.visits', 'pc.author_id', 'pc.description', 'pc.keywords', 'pc.title', 'pc.teaser', 'pc.perma', 'pc.content', 'pc.img', 'pc.img_source', 'pc.votes'])
            ->join(['u' => 'users'], 'pc.author_id = u.id', 'LEFT', ['u.name'])
            ->where(['ra.group_id' => $groupIds, 'pc.keywords LIKE' => '%' . $keyword . '%', 'pc.locale' => $this->db()->escape($locale)])
            ->group(['p.id'])
            ->order(['id' => 'DESC']);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }
        $articleArray = $result->fetchRows();

        if (empty($articleArray)) {
            return null;
        }

        $articles = [];
        foreach ($articleArray as $articleRow) {
            $articles[] = $this->loadFromArray($articleRow);
        }

        return $articles;
    }

    /**
     * Get articles of the month of the given date
     *
     * @param \DateTime $date
     * @param \Ilch\Pagination|null $pagination
     * @param string $locale
     * @return ArticleModel[]|array
     */
    public function getArticlesByDate(\DateTime $date, $pagination = null, $locale = '')
    {
        $db = $this->db();

        $dateTo = clone $date;
        $dateTo->modify('first day of next month');

        $dateFrom = $date->format($db::FORMAT_DATETIME);
        $dateTo = $dateTo->format($db::FORMAT_DATETIME);

        $select = $this->db()->select()
            ->fields(['p.id', 'p.cat_id', 'p.date_created', 'p.top', 'p.commentsDisabled'])
            ->from(['p' => 'articles'])
            ->join(['ra' => 'articles_access'], 'p.id = ra.article_id', 'LEFT', ['read_access' => 'GROUP_CONCAT(ra.group_id)'])
            ->join(['pc' => 'articles_content'], 'p.id = pc.article_id', 'LEFT', ['pc.visits', 'pc.author_id', 'pc.description', 'pc.keywords', 'pc.title', 'pc.teaser', 'pc.perma', 'pc.content', 'pc.img', 'pc.img_source', 'pc.votes'])
            ->join(['u' => 'users'], 'pc.author_id = u.id', 'LEFT', ['u.name'])
            ->where(['p.date_created >=' => $dateFrom, 'p.date_created <' => $dateTo, 'pc.locale' => $this->db()->escape($locale)])
            ->group(['p.id', 'p.cat_id', 'p.date_created', 'p.top', 'pc.article_id', 'pc.author_id', 'pc.visits', 'pc.content', 'pc.description', 'pc.keywords', 'pc.locale', 'pc.title', 'pc.teaser', 'pc.perma', 'pc.img', 'pc.img_source', 'pc.votes'])
            ->order(['p.id' => 'DESC']);


        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }
        $articleArray = $result->fetchRows();

        if (empty($articleArray)) {
            return null;
        }

        $articles = [];
        foreach ($articleArray as $articleRow) {
            $articles[] = $this->loadFromArray($articleRow);
        }

        return $articles;
    }

    /**
     * Get articles of the month of the given date and taking the group IDs into account.
     *
     * @param \DateTime $date
     * @param string|array $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @param null $pagination
     * @param string $locale
     * @return array|null
     * @since 2.1.44
     */
    public function getArticlesByDateAccess(\DateTime $date, $groupIds = '3', $pagination = null, string $locale = '')
    {
        $db = $this->db();

        $dateTo = clone $date;
        $dateTo->modify('first day of next month');

        $dateFrom = $date->format($db::FORMAT_DATETIME);
        $dateTo = $dateTo->format($db::FORMAT_DATETIME);

        if (\is_string($groupIds)) {
            $groupIds = explode(',', $groupIds);
        }

        $select = $this->db()->select()
            ->fields(['p.id', 'p.cat_id', 'p.date_created', 'p.top', 'p.commentsDisabled'])
            ->from(['p' => 'articles'])
            ->join(['ra' => 'articles_access'], 'p.id = ra.article_id', 'LEFT', ['read_access' => 'GROUP_CONCAT(ra.group_id)'])
            ->join(['pc' => 'articles_content'], 'p.id = pc.article_id', 'LEFT', ['pc.visits', 'pc.author_id', 'pc.description', 'pc.keywords', 'pc.title', 'pc.teaser', 'pc.perma', 'pc.content', 'pc.img', 'pc.img_source', 'pc.votes'])
            ->join(['u' => 'users'], 'pc.author_id = u.id', 'LEFT', ['u.name'])
            ->where(['ra.group_id' => $groupIds, 'p.date_created >=' => $dateFrom, 'p.date_created <' => $dateTo, 'pc.locale' => $this->db()->escape($locale)])
            ->group(['p.id', 'p.cat_id', 'p.date_created', 'p.top', 'pc.article_id', 'pc.author_id', 'pc.visits', 'pc.content', 'pc.description', 'pc.keywords', 'pc.locale', 'pc.title', 'pc.teaser', 'pc.perma', 'pc.img', 'pc.img_source', 'pc.votes'])
            ->order(['p.id' => 'DESC']);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }
        $articleArray = $result->fetchRows();

        if (empty($articleArray)) {
            return null;
        }

        $articles = [];
        foreach ($articleArray as $articleRow) {
            $articles[] = $this->loadFromArray($articleRow);
        }

        return $articles;
    }

    /**
     * Get articles count by cat id
     *
     * @param int $catId
     * @return int
     * @throws \Ilch\Database\Exception
     */
    public function getCountArticlesByCatId($catId)
    {
        return (int)$this->db()->select('COUNT(*)')
            ->from('articles')
            ->where(['cat_id LIKE' => '%' . $catId . '%'])
            ->execute()
            ->fetchCell();
    }

    /**
     * Get articles count by category id and taking the group IDs into account.
     *
     * @param int $catId
     * @param string|array $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @return int
     * @since 2.1.44
     */
    public function getCountArticlesByCatIdAccess(int $catId, $groupIds = '3'): int
    {
        if (\is_string($groupIds)) {
            $groupIds = explode(',', $groupIds);
        }

        return (int)$this->db()->select('COUNT(DISTINCT(id))')
            ->from('articles')
            ->join(['articles_access'], 'id = article_id', 'LEFT')
            ->where(['group_id' => $groupIds, 'cat_id LIKE' => '%' . $catId . '%'])
            ->execute()
            ->fetchCell();
    }

    /**
     * Get articles count by month and year
     *
     * @param string $date
     * @return int
     * @throws \Ilch\Database\Exception
     */
    public function getCountArticlesByMonthYear($date = null)
    {
        $sql = 'SELECT COUNT(*)
                FROM `[prefix]_articles`';

        if ($date != null) {
            $sql .= ' WHERE YEAR(date_created) = YEAR("' . $this->db()->escape($date)
                . '") AND MONTH(date_created) = MONTH("' . $this->db()->escape($date) . '")';
        }

        return (int)$this->db()->queryCell($sql);
    }

    /**
     * Get articles count by month and year and taking the group IDs into account.
     *
     * @param string|null $date
     * @param string|array $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @return int
     * @throws \Ilch\Database\Exception
     * @since 2.1.44
     */
    public function getCountArticlesByMonthYearAccess(string $date = null, $groupIds = '3'): int
    {
        if (\is_string($groupIds)) {
            $groupIds = explode(',', $groupIds);
        }

        $sql = 'SELECT COUNT(DISTINCT(id))
                FROM `[prefix]_articles`
                LEFT JOIN `[prefix]_articles_access` ON `id` = `article_id`';

        if ($date != null) {
            $sql .= ' WHERE YEAR(date_created) = YEAR("' . $this->db()->escape($date)
                . '") AND MONTH(date_created) = MONTH("' . $this->db()->escape($date) . '")';
        }

        $sql .= ' AND `group_id` IN (';
        foreach ($groupIds as $groupId) {
            $sql .= (int)$groupId . ',';
        }
        $sql = rtrim($sql, ',') . ') ';
        return (int)$this->db()->queryCell($sql);
    }

    /**
     * Get a list for the archive-box.
     *
     * @param int|null $limit
     * @return array []|ArticleModel[]
     * @throws \Ilch\Database\Exception
     * @todo: Remove the group (aggregate) function MAX() workaround, which avoids duplicated entries in the archive-box if possible.
     */
    public function getArticleDateList($limit = null)
    {
        $sql = 'SELECT MAX(`date_created`) AS `date_created`
                FROM `[prefix]_articles`
                GROUP BY YEAR(date_created), MONTH(date_created)
                ORDER BY `date_created` DESC';

        if ($limit !== null) {
            $sql .= ' LIMIT ' . (int)$limit;
        }

        $articleArray = $this->db()->queryArray($sql);

        if (empty($articleArray)) {
            return [];
        }

        $articles = [];
        foreach ($articleArray as $articleRow) {
            $articleModel = new ArticleModel();
            $articleModel->setDateCreated($articleRow['date_created']);
            $articles[] = $articleModel;
        }

        return $articles;
    }

    /**
     * Get a list for the archive box and take the group IDs into account.
     *
     * @param string|array $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @param int|null $limit
     * @return array
     * @throws \Ilch\Database\Exception
     * @todo: Remove the group (aggregate) function MAX() workaround, which avoids duplicated entries in the archive-box if possible.
     * @since 2.1.44
     */
    public function getArticleDateListAccess($groupIds = '3', int $limit = null): array
    {
        $sql = 'SELECT MAX(`date_created`) AS `date_created`
                FROM `[prefix]_articles`
                LEFT JOIN `[prefix]_articles_access` ON `article_id` = `id`';

        if (\is_string($groupIds)) {
            $groupIds = explode(',', $groupIds);
        }

        $sql .= ' WHERE `group_id` IN (';
        foreach ($groupIds as $groupId) {
            $sql .= (int)$groupId . ',';
        }
        $sql = rtrim($sql, ',') . ') ';

        $sql .= 'GROUP BY YEAR(date_created), MONTH(date_created)
                ORDER BY `date_created` DESC';

        if ($limit !== null) {
            $sql .= ' LIMIT ' . (int)$limit;
        }

        $articleArray = $this->db()->queryArray($sql);

        if (empty($articleArray)) {
            return [];
        }

        $articles = [];
        foreach ($articleArray as $articleRow) {
            $articleModel = new ArticleModel();
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
     * @return ArticleModel[]|null
     */
    public function getArticleList($locale = '', $limit = null)
    {
        $select = $this->db()->select()
            ->fields(['p.id', 'p.cat_id', 'p.date_created'])
            ->from(['p' => 'articles'])
            ->join(['ra' => 'articles_access'], 'p.id = ra.article_id', 'LEFT', ['read_access' => 'GROUP_CONCAT(ra.group_id)'])
            ->join(['pc' => 'articles_content'], 'p.id = pc.article_id', 'LEFT', ['pc.visits', 'pc.author_id', 'pc.description', 'pc.keywords', 'pc.title', 'pc.teaser', 'pc.perma', 'pc.content', 'pc.img', 'pc.img_source', 'pc.votes'])
            ->join(['m' => 'media'], 'pc.img = m.url', 'LEFT', ['m.url_thumb', 'm.url'])
            ->join(['u' => 'users'], 'pc.author_id = u.id', 'LEFT', ['u.name'])
            ->where(['pc.locale' => $this->db()->escape($locale)])
            ->group(['p.id', 'p.cat_id', 'p.date_created', 'pc.visits', 'pc.author_id', 'pc.description', 'pc.keywords', 'pc.title', 'pc.teaser', 'pc.perma', 'pc.content', 'pc.img', 'pc.img_source', 'pc.votes', 'm.url_thumb', 'm.url'])
            ->order(['date_created' => 'DESC']);

        if ($limit !== null) {
            $select->limit($limit);
        }
        $result = $select->execute();
        $articleArray = $result->fetchRows();

        if (empty($articleArray)) {
            return null;
        }

        $articles = [];
        foreach ($articleArray as $articleRow) {
            $articles[] = $this->loadFromArray($articleRow);
        }

        return $articles;
    }

    /**
     * Get article list for overview and take the group IDs into account.
     *
     * @param string|array $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @param string $locale
     * @param int|null $limit
     * @return array|null
     * @since 2.1.44
     */
    public function getArticleListAccess($groupIds = '3', string $locale = '', int $limit = null)
    {
        if (\is_string($groupIds)) {
            $groupIds = explode(',', $groupIds);
        }

        $select = $this->db()->select()
            ->fields(['p.id', 'p.cat_id', 'p.date_created'])
            ->from(['p' => 'articles'])
            ->join(['ra' => 'articles_access'], 'p.id = ra.article_id', 'LEFT', ['read_access' => 'GROUP_CONCAT(ra.group_id)'])
            ->join(['pc' => 'articles_content'], 'p.id = pc.article_id', 'LEFT', ['pc.visits', 'pc.author_id', 'pc.description', 'pc.keywords', 'pc.title', 'pc.teaser', 'pc.perma', 'pc.content', 'pc.img', 'pc.img_source', 'pc.votes'])
            ->join(['m' => 'media'], 'pc.img = m.url', 'LEFT', ['m.url_thumb', 'm.url'])
            ->join(['u' => 'users'], 'pc.author_id = u.id', 'LEFT', ['u.name'])
            ->where(['ra.group_id' => $groupIds, 'pc.locale' => $this->db()->escape($locale)])
            ->group(['p.id', 'p.cat_id', 'p.date_created', 'pc.visits', 'pc.author_id', 'pc.description', 'pc.keywords', 'pc.title', 'pc.teaser', 'pc.perma', 'pc.content', 'pc.img', 'pc.img_source', 'pc.votes', 'm.url_thumb', 'm.url'])
            ->order(['date_created' => 'DESC']);

        if ($limit !== null) {
            $select->limit($limit);
        }
        $result = $select->execute();
        $articleArray = $result->fetchRows();

        if (empty($articleArray)) {
            return null;
        }

        $articles = [];
        foreach ($articleArray as $articleRow) {
            $articles[] = $this->loadFromArray($articleRow);
        }

        return $articles;
    }

    /**
     * Returns article model found by the key.
     *
     * @param int $id
     * @param string $locale
     * @return ArticleModel|null
     */
    public function getArticleByIdLocale($id, $locale = '')
    {
        $articleRow = $this->db()->select()
            ->fields(['p.id', 'p.cat_id', 'p.date_created', 'p.top', 'p.commentsDisabled'])
            ->from(['p' => 'articles'])
            ->join(['ra' => 'articles_access'], 'p.id = ra.article_id', 'LEFT', ['read_access' => 'GROUP_CONCAT(ra.group_id)'])
            ->join(['pc' => 'articles_content'], 'p.id = pc.article_id', 'LEFT', ['pc.visits', 'pc.author_id', 'pc.description', 'pc.keywords', 'pc.title', 'pc.teaser', 'pc.perma', 'pc.content', 'pc.locale', 'pc.img', 'pc.img_source', 'pc.votes'])
            ->group(['p.id'])
            ->where(['p.id' => $id, 'pc.locale' => $this->db()->escape($locale)])
            ->execute()
            ->fetchAssoc();

        if (empty($articleRow)) {
            return null;
        }

        return $this->loadFromArray($articleRow);
    }

    /**
     * Get a list of the keywords.
     *
     * @param int $limit
     * @return ArticleModel[]|array
     * @throws \Ilch\Database\Exception
     */
    public function getKeywordsList($limit = null)
    {
        $sql = $this->db()->select('keywords')
            ->from('articles_content');

        if ($limit !== null) {
            $sql = $sql->limit((int)$limit);
        }

        $keywordsArray = $sql->execute()
            ->fetchRows();

        if (empty($keywordsArray)) {
            return [];
        }

        $keywordsList = [];
        foreach ($keywordsArray as $keywords) {
            if ($keywords['keywords'] === '') {
                continue;
            }
            $articleModel = new ArticleModel();
            $articleModel->setKeywords($keywords['keywords']);
            $keywordsList[] = $articleModel;
        }

        return $keywordsList;
    }

    /**
     * Get a list of the keywords and take the group IDs into account.
     *
     * @param string|array $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @param int|null $limit
     * @return array
     * @since 2.1.44
     */
    public function getKeywordsListAccess($groupIds = '3', int $limit = null): array
    {
        if (\is_string($groupIds)) {
            $groupIds = explode(',', $groupIds);
        }

        $sql = $this->db()->select('keywords')
            ->from('articles_content')
            ->join(['p' => 'articles'], 'p.id = article_id', 'LEFT')
            ->join(['ra' => 'articles_access'], 'p.id = ra.article_id', 'LEFT')
            ->where(['ra.group_id' => $groupIds]);

        if ($limit !== null) {
            $sql = $sql->limit((int)$limit);
        }

        $keywordsArray = $sql->execute()
            ->fetchRows();

        if (empty($keywordsArray)) {
            return [];
        }

        $keywordsList = [];
        foreach ($keywordsArray as $keywords) {
            if ($keywords['keywords'] === '') {
                continue;
            }
            $articleModel = new ArticleModel();
            $articleModel->setKeywords($keywords['keywords']);
            $keywordsList[] = $articleModel;
        }

        return $keywordsList;
    }

    /**
     * Check if keyword exists.
     *
     * @param string $keyword
     * @return bool
     * @since 2.1.25
     */
    public function keywordExists($keyword)
    {
        $keywordsList = [];
        foreach ($this->getKeywordsList() as $keywords) {
            $keywordsList[] = $keywords->getKeywords();
        }

        $keywordsListString = implode(', ', $keywordsList);
        $keywordsListArray = explode(', ', $keywordsListString);

        return \in_array($keyword, $keywordsListArray);
    }

    /**
     * Returns all article permas.
     *
     * @return array|null
     * @throws \Ilch\Database\Exception
     */
    public function getArticlePermas()
    {
        $permas = $this->db()->select(['perma'])
            ->from('articles_content')
            ->execute()
            ->fetchRows();

        if (empty($permas)) {
            return null;
        }

        $permaArray = [];
        foreach ($permas as $perma) {
            $permaArray[$perma['perma']] = $perma;
        }

        return $permaArray;
    }

    /**
     * Get the top article.
     *
     * @return ArticleModel|null
     * @deprecated Use getTopArticles() instead. There can be more than one top article.
     */
    public function getTopArticle()
    {
        $articleRow = $this->db()->select('*')
            ->fields(['p.id', 'p.cat_id', 'p.date_created', 'p.top', 'p.commentsDisabled'])
            ->from(['p' => 'articles'])
            ->join(['ra' => 'articles_access'], 'p.id = ra.article_id', 'LEFT', ['read_access' => 'GROUP_CONCAT(ra.group_id)'])
            ->join(['pc' => 'articles_content'], 'p.id = pc.article_id', 'LEFT', ['pc.visits', 'pc.author_id', 'pc.description', 'pc.keywords', 'pc.title', 'pc.teaser', 'pc.perma', 'pc.content', 'pc.locale', 'pc.img', 'pc.img_source', 'pc.votes'])
            ->group(['p.id'])
            ->where(['top' => 1])
            ->execute()
            ->fetchAssoc();

        if (empty($articleRow)) {
            return null;
        }

        return $this->loadFromArray($articleRow);
    }

    /**
     * Get the top articles.
     *
     * @return array|ArticleModel[]
     * @since 2.1.44
     */
    public function getTopArticles()
    {
        $articleRows = $this->db()->select('*')
            ->fields(['p.id', 'p.cat_id', 'p.date_created', 'p.top', 'p.commentsDisabled'])
            ->from(['p' => 'articles'])
            ->join(['ra' => 'articles_access'], 'p.id = ra.article_id', 'LEFT', ['read_access' => 'GROUP_CONCAT(ra.group_id)'])
            ->join(['pc' => 'articles_content'], 'p.id = pc.article_id', 'LEFT', ['pc.visits', 'pc.author_id', 'pc.description', 'pc.keywords', 'pc.title', 'pc.teaser', 'pc.perma', 'pc.content', 'pc.locale', 'pc.img', 'pc.img_source', 'pc.votes'])
            ->group(['p.id'])
            ->where(['top' => 1])
            ->execute()
            ->fetchRows();

        if (empty($articleRows)) {
            return [];
        }

        $articleModels = [];
        foreach ($articleRows as $articleRow) {
            $articleModels[] = $this->loadFromArray($articleRow);
        }

        return $articleModels;
    }

    /**
     * Set the top article.
     *
     * @param int $id
     * @param int $value
     */
    public function setTopArticle($id, $value)
    {
        $this->db()->update('articles')
            ->values(['top' => $value])
            ->where(['id' => $id])
            ->execute();
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
                ->values(['visits' => $article->getVisits()])
                ->where(['article_id' => $article->getId()])
                ->execute();
        }
    }

    /**
     * Inserts or updates a article.
     *
     * @param ArticleModel $article
     * @return int $id
     */
    public function save(ArticleModel $article)
    {
        $id = 0;

        if ($article->getId()) {
            // Existing article
            if ($this->getArticleByIdLocale($article->getId(), $article->getLocale())) {
                // Update existing article with specific id and locale
                $this->db()->update('articles')
                    ->values(['cat_id' => $article->getCatId(), 'date_created' => $article->getDateCreated(), 'commentsDisabled' => (int)$article->getCommentsDisabled()])
                    ->where(['id' => $article->getId()])
                    ->execute();

                $this->db()->update('articles_content')
                    ->values(['title' => $article->getTitle(),
                        'teaser' => $article->getTeaser(),
                        'description' => $article->getDescription(),
                        'keywords' => $article->getKeywords(),
                        'content' => $article->getContent(),
                        'perma' => $article->getPerma(),
                        'img' => $article->getImage(),
                        'img_source' => $article->getImageSource(),
                        'votes' => $article->getVotes()])
                    ->where(['article_id' => $article->getId(), 'locale' => $article->getLocale()])
                    ->execute();
            } else {
                // Insert content with a new locale for an existing article
                $this->db()->insert('articles_content')
                    ->values(['article_id' => $article->getId(),
                        'author_id' => $article->getAuthorId(),
                        'description' => $article->getDescription(),
                        'keywords' => $article->getKeywords(),
                        'title' => $article->getTitle(),
                        'teaser' => $article->getTeaser(),
                        'content' => $article->getContent(),
                        'perma' => $article->getPerma(),
                        'locale' => $article->getLocale(),
                        'img' => $article->getImage(),
                        'img_source' => $article->getImageSource(),
                        'votes' => $article->getVotes()])
                    ->execute();
            }

            $id = $article->getId();
        } else {
            // Insert new article
            $articleId = $this->db()->insert('articles')
                ->values(['cat_id' => $article->getCatId(), 'date_created' => $article->getDateCreated(), 'commentsDisabled' => (int)$article->getCommentsDisabled()])
                ->execute();

            $this->db()->insert('articles_content')
                ->values(['article_id' => $articleId,
                    'author_id' => $article->getAuthorId(),
                    'description' => $article->getDescription(),
                    'keywords' => $article->getKeywords(),
                    'title' => $article->getTitle(),
                    'teaser' => $article->getTeaser(),
                    'content' => $article->getContent(),
                    'perma' => $article->getPerma(),
                    'locale' => $article->getLocale(),
                    'img' => $article->getImage(),
                    'img_source' => $article->getImageSource(),
                    'votes' => $article->getVotes()])
                ->execute();

            $id = $articleId;
        }

        $this->setTopArticle($id, (int)$article->getTopArticle());
        $this->saveReadAccess($id, $article->getReadAccess());

        return $id;
    }

    /**
     * Update the entries for which user groups are allowed to read an article.
     *
     * @param int $articleId
     * @param string $readAccess example: "1,2,3"
     * @throws \Ilch\Database\Exception
     * @since 2.1.44
     */
    private function saveReadAccess(int $articleId, string $readAccess)
    {
        // Delete possible old entries to later insert the new ones.
        $this->db()->delete('articles_access')
            ->where(['article_id' => $articleId])
            ->execute();

        $sql = 'INSERT INTO [prefix]_articles_access (article_id, group_id) VALUES';
        $sqlWithValues = $sql;
        $rowCount = 0;
        $groupIds = explode(',', $readAccess);

        foreach ($groupIds as $groupId) {
            // There is a limit of 1000 rows per insert, but according to some benchmarks found online
            // the sweet spot seams to be around 25 rows per insert. So aim for that.
            if ($rowCount >= 25) {
                $sqlWithValues = rtrim($sqlWithValues, ',') . ';';
                $this->db()->queryMulti($sqlWithValues);
                $rowCount = 0;
                $sqlWithValues = $sql;
            }

            $rowCount++;
            $sqlWithValues .= '(' . (int)$articleId . ',' . (int)$groupId . '),';
        }

        // Insert remaining rows.
        $sqlWithValues = rtrim($sqlWithValues, ',') . ';';
        $this->db()->queryMulti($sqlWithValues);
    }

    /**
     * Save article vote/like.
     *
     * @param integer $id
     * @param integer $userId
     */
    public function saveVotes($id, $userId)
    {
        $votes = $this->getVotes($id);

        $this->db()->update('articles_content')
            ->values(['votes' => $votes . $userId . ','])
            ->where(['article_id' => $id])
            ->execute();
    }

    /**
     * Get the votes/likes for an article.
     *
     * @param integer $id
     * @return false|null|string
     */
    public function getVotes($id)
    {
        return $this->db()->select('votes')
            ->from('articles_content')
            ->where(['article_id' => $id])
            ->execute()
            ->fetchCell();
    }

    /**
     * Delete an article (with all language contents)
     *
     * @param int $id
     * @return int
     */
    public function delete($id)
    {
        $deleted = $this->db()->delete('articles')
            ->where(['id' => $id])
            ->execute();

        $this->db()->delete('articles_content')
            ->where(['article_id' => $id])
            ->execute();

        // Rows in articles_access get automatically deleted due to foreign key constraints.

        return $deleted;
    }

    /**
     * Delete an article with all associated comments
     *
     * @param int $id
     * @param CommentMapper|null $commentsMapper
     */
    public function deleteWithComments($id, CommentMapper $commentsMapper = null)
    {
        $this->delete($id);
        // An instance of the comments mapper can be passed as argument to this
        // function so it doesn't need to be instantiated every time in case
        // a lot of articles get deleted at once.
        if ($commentsMapper === null) {
            $commentsMapper = new CommentMapper();
        }
        $commentsMapper->deleteByKey(sprintf(ArticleConfig::COMMENT_KEY_TPL, $id));
    }

    /**
     * Returns an article model created from an article row.
     *
     * @param array $articleRow
     * @return ArticleModel
     * @since 2.1.44
     */
    private function loadFromArray(array $articleRow)
    {
        $articleModel = new ArticleModel();

        if (isset($articleRow['id'])) {
            $articleModel->setId($articleRow['id']);
        }

        if (isset($articleRow['cat_id'])) {
            $articleModel->setCatId($articleRow['cat_id']);
        }

        if (isset($articleRow['visits'])) {
            $articleModel->setVisits($articleRow['visits']);
        }

        if (isset($articleRow['author_id'])) {
            $articleModel->setAuthorId($articleRow['author_id']);
        }

        if (isset($articleRow['name'])) {
            $articleModel->setAuthorName($articleRow['name']);
        }

        if (isset($articleRow['description'])) {
            $articleModel->setDescription($articleRow['description']);
        }

        if (isset($articleRow['keywords'])) {
            $articleModel->setKeywords($articleRow['keywords']);
        }

        if (isset($articleRow['title'])) {
            $articleModel->setTitle($articleRow['title']);
        }

        if (isset($articleRow['teaser'])) {
            $articleModel->setTeaser($articleRow['teaser']);
        }

        if (isset($articleRow['perma'])) {
            $articleModel->setPerma($articleRow['perma']);
        }

        if (isset($articleRow['content'])) {
            $articleModel->setContent($articleRow['content']);
        }

        if (isset($articleRow['locale'])) {
            $articleModel->setLocale($articleRow['locale']);
        }

        if (isset($articleRow['date_created'])) {
            $articleModel->setDateCreated($articleRow['date_created']);
        }

        if (isset($articleRow['top'])) {
            $articleModel->setTopArticle($articleRow['top']);
        }

        if (isset($articleRow['commentsDisabled'])) {
            $articleModel->setCommentsDisabled($articleRow['commentsDisabled']);
        }

        if (isset($articleRow['read_access'])) {
            $articleModel->setReadAccess($articleRow['read_access']);
        }

        if (isset($articleRow['img'])) {
            $articleModel->setImage($articleRow['img']);
        }

        if (isset($articleRow['img_source'])) {
            $articleModel->setImageSource($articleRow['img_source']);
        }

        if (isset($articleRow['votes'])) {
            $articleModel->setVotes($articleRow['votes']);
        }

        return $articleModel;
    }
}
