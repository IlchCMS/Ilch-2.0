<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Article\Mappers;

use Ilch\Pagination;
use PHPUnit\Ilch\DatabaseTestCase;
use Modules\Article\Mappers\Article as ArticleMapper;
use Modules\Article\Models\Article as ArticleModel;
use Modules\Article\Config\Config as ModuleConfig;
use Modules\User\Config\Config as UserConfig;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\Comment\Config\Config as CommentConfig;
use Modules\Media\Config\Config as MediaConfig;
use PHPUnit\Ilch\PhpunitDataset;

/**
 * Tests the article mapper class.
 *
 * @package ilch_phpunit
 */
class ArticleTest extends DatabaseTestCase
{
    protected PhpunitDataset $phpunitDataset;
    private Article $articleMapper;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');

        $this->articleMapper = new ArticleMapper();
    }

    /**
     * Tests if getArticles() returns all articles from the database.
     */
    public function testGetArticlesAllRows()
    {
        $articles = $this->articleMapper->getArticles();

        self::assertCount(3, $articles);
    }

    /**
     * Tests if getArticles() returns all articles from the database with their expected values.
     */
    public function testGetArticles()
    {
        $articles = $this->articleMapper->getArticles();

        self::assertCount(3, $articles);

        self::assertEquals(3, $articles[0]->getId());
        self::assertSame('1', $articles[0]->getCatId());
        self::assertSame(1, $articles[0]->getAuthorId());
        self::assertSame('admin', $articles[0]->getAuthorName());
        self::assertSame(3, $articles[0]->getVisits());
        self::assertSame('testtitle3.html', $articles[0]->getPerma());
        self::assertSame('TestTitle3', $articles[0]->getTitle());
        self::assertSame('TestTeaser3', $articles[0]->getTeaser());
        self::assertSame('TestContent3', $articles[0]->getContent());
        self::assertSame('TestDescription3', $articles[0]->getDescription());
        self::assertSame('keyword1, keyword2', $articles[0]->getKeywords());
        self::assertSame('', $articles[0]->getLocale());
        self::assertSame('2021-05-10 08:10:38', $articles[0]->getDateCreated());
        self::assertEquals(0, $articles[0]->getTopArticle());
        self::assertEquals(1, $articles[0]->getCommentsDisabled());
        self::assertSame('1', $articles[0]->getReadAccess());
        self::assertSame('', $articles[0]->getImage());
        self::assertSame('', $articles[0]->getImageSource());
        self::assertSame('', $articles[0]->getVotes());

        self::assertEquals(2, $articles[1]->getId());
        self::assertSame('1', $articles[1]->getCatId());
        self::assertSame(1, $articles[1]->getAuthorId());
        self::assertSame('admin', $articles[1]->getAuthorName());
        self::assertSame(2, $articles[1]->getVisits());
        self::assertSame('testtitle2.html', $articles[1]->getPerma());
        self::assertSame('TestTitle2', $articles[1]->getTitle());
        self::assertSame('TestTeaser2', $articles[1]->getTeaser());
        self::assertSame('TestContent2', $articles[1]->getContent());
        self::assertSame('TestDescription2', $articles[1]->getDescription());
        self::assertSame('keyword1, keyword2', $articles[1]->getKeywords());
        self::assertSame('', $articles[1]->getLocale());
        self::assertSame('2021-05-09 08:10:38', $articles[1]->getDateCreated());
        self::assertEquals(0, $articles[1]->getTopArticle());
        self::assertEquals(0, $articles[1]->getCommentsDisabled());
        self::assertSame('1,2', $articles[1]->getReadAccess());
        self::assertSame('', $articles[1]->getImage());
        self::assertSame('', $articles[1]->getImageSource());
        self::assertSame('', $articles[1]->getVotes());

        self::assertEquals(1, $articles[2]->getId());
        self::assertSame('2', $articles[2]->getCatId());
        self::assertSame(1, $articles[2]->getAuthorId());
        self::assertSame('admin', $articles[2]->getAuthorName());
        self::assertSame(1, $articles[2]->getVisits());
        self::assertSame('testtitle.html', $articles[2]->getPerma());
        self::assertSame('TestTitle', $articles[2]->getTitle());
        self::assertSame('TestTeaser', $articles[2]->getTeaser());
        self::assertSame('TestContent', $articles[2]->getContent());
        self::assertSame('TestDescription', $articles[2]->getDescription());
        self::assertSame('keyword1, keyword2', $articles[2]->getKeywords());
        self::assertSame('', $articles[2]->getLocale());
        self::assertSame('2021-05-08 08:10:38', $articles[2]->getDateCreated());
        self::assertEquals(0, $articles[2]->getTopArticle());
        self::assertEquals(0, $articles[2]->getCommentsDisabled());
        self::assertSame('1,2,3', $articles[2]->getReadAccess());
        self::assertSame('', $articles[2]->getImage());
        self::assertSame('', $articles[2]->getImageSource());
        self::assertSame('', $articles[2]->getVotes());
    }

    /**
     * Tests if getArticlesByAccess() returns all articles by given access group.
     */
    public function testGetArticlesByAccess()
    {
        $articles = $this->articleMapper->getArticlesByAccess('1,2,3');

        self::assertCount(3, $articles);

        self::assertEquals(3, $articles[0]->getId());
        self::assertSame('1', $articles[0]->getCatId());
        self::assertSame(1, $articles[0]->getAuthorId());
        self::assertSame('admin', $articles[0]->getAuthorName());
        self::assertSame(3, $articles[0]->getVisits());
        self::assertSame('testtitle3.html', $articles[0]->getPerma());
        self::assertSame('TestTitle3', $articles[0]->getTitle());
        self::assertSame('TestTeaser3', $articles[0]->getTeaser());
        self::assertSame('TestContent3', $articles[0]->getContent());
        self::assertSame('TestDescription3', $articles[0]->getDescription());
        self::assertSame('keyword1, keyword2', $articles[0]->getKeywords());
        self::assertSame('', $articles[0]->getLocale());
        self::assertSame('2021-05-10 08:10:38', $articles[0]->getDateCreated());
        self::assertEquals(0, $articles[0]->getTopArticle());
        self::assertEquals(1, $articles[0]->getCommentsDisabled());
        self::assertSame('1', $articles[0]->getReadAccess());
        self::assertSame('', $articles[0]->getImage());
        self::assertSame('', $articles[0]->getImageSource());
        self::assertSame('', $articles[0]->getVotes());

        self::assertEquals(2, $articles[1]->getId());
        self::assertSame('1', $articles[1]->getCatId());
        self::assertSame(1, $articles[1]->getAuthorId());
        self::assertSame('admin', $articles[1]->getAuthorName());
        self::assertSame(2, $articles[1]->getVisits());
        self::assertSame('testtitle2.html', $articles[1]->getPerma());
        self::assertSame('TestTitle2', $articles[1]->getTitle());
        self::assertSame('TestTeaser2', $articles[1]->getTeaser());
        self::assertSame('TestContent2', $articles[1]->getContent());
        self::assertSame('TestDescription2', $articles[1]->getDescription());
        self::assertSame('keyword1, keyword2', $articles[1]->getKeywords());
        self::assertSame('', $articles[1]->getLocale());
        self::assertSame('2021-05-09 08:10:38', $articles[1]->getDateCreated());
        self::assertEquals(0, $articles[1]->getTopArticle());
        self::assertEquals(0, $articles[1]->getCommentsDisabled());
        self::assertSame('1,2', $articles[1]->getReadAccess());
        self::assertSame('', $articles[1]->getImage());
        self::assertSame('', $articles[1]->getImageSource());
        self::assertSame('', $articles[1]->getVotes());

        self::assertEquals(1, $articles[2]->getId());
        self::assertSame('2', $articles[2]->getCatId());
        self::assertSame(1, $articles[2]->getAuthorId());
        self::assertSame('admin', $articles[2]->getAuthorName());
        self::assertSame(1, $articles[2]->getVisits());
        self::assertSame('testtitle.html', $articles[2]->getPerma());
        self::assertSame('TestTitle', $articles[2]->getTitle());
        self::assertSame('TestTeaser', $articles[2]->getTeaser());
        self::assertSame('TestContent', $articles[2]->getContent());
        self::assertSame('TestDescription', $articles[2]->getDescription());
        self::assertSame('keyword1, keyword2', $articles[2]->getKeywords());
        self::assertSame('', $articles[2]->getLocale());
        self::assertSame('2021-05-08 08:10:38', $articles[2]->getDateCreated());
        self::assertEquals(0, $articles[2]->getTopArticle());
        self::assertEquals(0, $articles[2]->getCommentsDisabled());
        self::assertSame('1,2,3', $articles[2]->getReadAccess());
        self::assertSame('', $articles[2]->getImage());
        self::assertSame('', $articles[2]->getImageSource());
        self::assertSame('', $articles[2]->getVotes());
    }

    /**
     * Tests if getArticlesByAccess() returns all articles by given access group as array.
     */
    public function testGetArticlesByAccessArray()
    {
        $articles = $this->articleMapper->getArticlesByAccess([1, 2, 3]);

        self::assertCount(3, $articles);
    }

    /**
     * Tests if getArticlesByAccess() returns only articles accessible by guest access.
     */
    public function testGetArticlesByAccessGuest()
    {
        $articles = $this->articleMapper->getArticlesByAccess([3]);

        self::assertCount(1, $articles);
    }

    /**
     * Tests if getArticlesByAccess() returns only guest accessible articles with the default access.
     */
    public function testGetArticlesByAccessGuestDefault()
    {
        $articles = $this->articleMapper->getArticlesByAccess();

        self::assertCount(1, $articles);
    }

    /**
     * Tests if getArticlesByCats() returns all articles by given category with their expected values.
     */
    public function testGetArticlesByCats()
    {
        $articles = $this->articleMapper->getArticlesByCats('1');

        self::assertCount(2, $articles);

        self::assertEquals(3, $articles[0]->getId());
        self::assertSame('1', $articles[0]->getCatId());
        self::assertSame(1, $articles[0]->getAuthorId());
        self::assertSame('admin', $articles[0]->getAuthorName());
        self::assertSame(3, $articles[0]->getVisits());
        self::assertSame('testtitle3.html', $articles[0]->getPerma());
        self::assertSame('TestTitle3', $articles[0]->getTitle());
        self::assertSame('TestTeaser3', $articles[0]->getTeaser());
        self::assertSame('TestContent3', $articles[0]->getContent());
        self::assertSame('TestDescription3', $articles[0]->getDescription());
        self::assertSame('keyword1, keyword2', $articles[0]->getKeywords());
//        self::assertSame('', $articles[0]->getLocale());
        self::assertSame('2021-05-10 08:10:38', $articles[0]->getDateCreated());
        self::assertEquals(0, $articles[0]->getTopArticle());
        self::assertEquals(1, $articles[0]->getCommentsDisabled());
        self::assertSame('1', $articles[0]->getReadAccess());
        self::assertSame('', $articles[0]->getImage());
        self::assertSame('', $articles[0]->getImageSource());
        self::assertSame('', $articles[0]->getVotes());

        self::assertEquals(2, $articles[1]->getId());
        self::assertSame('1', $articles[1]->getCatId());
        self::assertSame(1, $articles[1]->getAuthorId());
        self::assertSame('admin', $articles[1]->getAuthorName());
        self::assertSame(2, $articles[1]->getVisits());
        self::assertSame('testtitle2.html', $articles[1]->getPerma());
        self::assertSame('TestTitle2', $articles[1]->getTitle());
        self::assertSame('TestTeaser2', $articles[1]->getTeaser());
        self::assertSame('TestContent2', $articles[1]->getContent());
        self::assertSame('TestDescription2', $articles[1]->getDescription());
        self::assertSame('keyword1, keyword2', $articles[1]->getKeywords());
//        self::assertSame('', $articles[1]->getLocale());
        self::assertSame('2021-05-09 08:10:38', $articles[1]->getDateCreated());
        self::assertEquals(0, $articles[1]->getTopArticle());
        self::assertEquals(0, $articles[1]->getCommentsDisabled());
        self::assertSame('1,2', $articles[1]->getReadAccess());
        self::assertSame('', $articles[1]->getImage());
        self::assertSame('', $articles[1]->getImageSource());
        self::assertSame('', $articles[1]->getVotes());
    }

    /**
     * Tests if getArticlesByCats() returns null if no articles exist for the given category.
     */
    public function testGetArticlesByCatsNoResult()
    {
        $articles = $this->articleMapper->getArticlesByCats('0');

        self::assertNull($articles);
    }

    /**
     * Tests if getArticlesByCatsAccess() returns all articles by given category and access group.
     */
    public function testGetArticlesByCatsAccess()
    {
        $articles = $this->articleMapper->getArticlesByCatsAccess('1', '1,2,3');

        self::assertCount(2, $articles);

        self::assertEquals(3, $articles[0]->getId());
        self::assertSame('1', $articles[0]->getCatId());
        self::assertSame(1, $articles[0]->getAuthorId());
        self::assertSame('admin', $articles[0]->getAuthorName());
        self::assertSame(3, $articles[0]->getVisits());
        self::assertSame('testtitle3.html', $articles[0]->getPerma());
        self::assertSame('TestTitle3', $articles[0]->getTitle());
        self::assertSame('TestTeaser3', $articles[0]->getTeaser());
        self::assertSame('TestContent3', $articles[0]->getContent());
        self::assertSame('TestDescription3', $articles[0]->getDescription());
        self::assertSame('keyword1, keyword2', $articles[0]->getKeywords());
//        self::assertSame('', $articles[0]->getLocale());
        self::assertSame('2021-05-10 08:10:38', $articles[0]->getDateCreated());
        self::assertEquals(0, $articles[0]->getTopArticle());
        self::assertEquals(1, $articles[0]->getCommentsDisabled());
        self::assertSame('1', $articles[0]->getReadAccess());
        self::assertSame('', $articles[0]->getImage());
        self::assertSame('', $articles[0]->getImageSource());
        self::assertSame('', $articles[0]->getVotes());

        self::assertEquals(2, $articles[1]->getId());
        self::assertSame('1', $articles[1]->getCatId());
        self::assertSame(1, $articles[1]->getAuthorId());
        self::assertSame('admin', $articles[1]->getAuthorName());
        self::assertSame(2, $articles[1]->getVisits());
        self::assertSame('testtitle2.html', $articles[1]->getPerma());
        self::assertSame('TestTitle2', $articles[1]->getTitle());
        self::assertSame('TestTeaser2', $articles[1]->getTeaser());
        self::assertSame('TestContent2', $articles[1]->getContent());
        self::assertSame('TestDescription2', $articles[1]->getDescription());
        self::assertSame('keyword1, keyword2', $articles[1]->getKeywords());
//        self::assertSame('', $articles[1]->getLocale());
        self::assertSame('2021-05-09 08:10:38', $articles[1]->getDateCreated());
        self::assertEquals(0, $articles[1]->getTopArticle());
        self::assertEquals(0, $articles[1]->getCommentsDisabled());
        self::assertSame('1,2', $articles[1]->getReadAccess());
        self::assertSame('', $articles[1]->getImage());
        self::assertSame('', $articles[1]->getImageSource());
        self::assertSame('', $articles[1]->getVotes());
    }

    /**
     * Tests if getArticlesByCatsAccess() returns all articles by given category and access group as array.
     */
    public function testGetArticlesByCatsAccessArray()
    {
        $articles = $this->articleMapper->getArticlesByCatsAccess('1', [1, 2, 3]);

        self::assertCount(2, $articles);
    }

    /**
     * Tests if getArticlesByCatsAccess() returns only guest accessible articles for the given category.
     */
    public function testGetArticlesByCatsAccessGuest()
    {
        $articles = $this->articleMapper->getArticlesByCatsAccess('2', [3]);

        self::assertCount(1, $articles);
    }

    /**
     * Tests if getArticlesByCatsAccess() returns only guest accessible articles for the given category with the default access.
     */
    public function testGetArticlesByCatsAccessGuestDefault()
    {
        $articles = $this->articleMapper->getArticlesByCatsAccess('2');

        self::assertCount(1, $articles);
    }

    /**
     * Tests if getArticlesByKeyword() returns all articles by given keyword.
     */
    public function testGetArticlesByKeyword()
    {
        $articles = $this->articleMapper->getArticlesByKeyword('keyword1');

        self::assertCount(3, $articles);

        self::assertEquals(3, $articles[0]->getId());
        self::assertSame('1', $articles[0]->getCatId());
        self::assertSame(1, $articles[0]->getAuthorId());
        self::assertSame('admin', $articles[0]->getAuthorName());
        self::assertSame(3, $articles[0]->getVisits());
        self::assertSame('testtitle3.html', $articles[0]->getPerma());
        self::assertSame('TestTitle3', $articles[0]->getTitle());
        self::assertSame('TestTeaser3', $articles[0]->getTeaser());
        self::assertSame('TestContent3', $articles[0]->getContent());
        self::assertSame('TestDescription3', $articles[0]->getDescription());
        self::assertSame('keyword1, keyword2', $articles[0]->getKeywords());
//        self::assertSame('', $articles[0]->getLocale());
        self::assertSame('2021-05-10 08:10:38', $articles[0]->getDateCreated());
        self::assertEquals(0, $articles[0]->getTopArticle());
        self::assertEquals(1, $articles[0]->getCommentsDisabled());
        self::assertSame('1', $articles[0]->getReadAccess());
        self::assertSame('', $articles[0]->getImage());
        self::assertSame('', $articles[0]->getImageSource());
        self::assertSame('', $articles[0]->getVotes());

        self::assertEquals(2, $articles[1]->getId());
        self::assertSame('1', $articles[1]->getCatId());
        self::assertSame(1, $articles[1]->getAuthorId());
        self::assertSame('admin', $articles[1]->getAuthorName());
        self::assertSame(2, $articles[1]->getVisits());
        self::assertSame('testtitle2.html', $articles[1]->getPerma());
        self::assertSame('TestTitle2', $articles[1]->getTitle());
        self::assertSame('TestTeaser2', $articles[1]->getTeaser());
        self::assertSame('TestContent2', $articles[1]->getContent());
        self::assertSame('TestDescription2', $articles[1]->getDescription());
        self::assertSame('keyword1, keyword2', $articles[1]->getKeywords());
//        self::assertSame('', $articles[1]->getLocale());
        self::assertSame('2021-05-09 08:10:38', $articles[1]->getDateCreated());
        self::assertEquals(0, $articles[1]->getTopArticle());
        self::assertEquals(0, $articles[1]->getCommentsDisabled());
        self::assertSame('1,2', $articles[1]->getReadAccess());
        self::assertSame('', $articles[1]->getImage());
        self::assertSame('', $articles[1]->getImageSource());
        self::assertSame('', $articles[1]->getVotes());

        self::assertEquals(1, $articles[2]->getId());
        self::assertSame('2', $articles[2]->getCatId());
        self::assertSame(1, $articles[2]->getAuthorId());
        self::assertSame('admin', $articles[2]->getAuthorName());
        self::assertSame(1, $articles[2]->getVisits());
        self::assertSame('testtitle.html', $articles[2]->getPerma());
        self::assertSame('TestTitle', $articles[2]->getTitle());
        self::assertSame('TestTeaser', $articles[2]->getTeaser());
        self::assertSame('TestContent', $articles[2]->getContent());
        self::assertSame('TestDescription', $articles[2]->getDescription());
        self::assertSame('keyword1, keyword2', $articles[2]->getKeywords());
//        self::assertSame('', $articles[2]->getLocale());
        self::assertSame('2021-05-08 08:10:38', $articles[2]->getDateCreated());
        self::assertEquals(0, $articles[2]->getTopArticle());
        self::assertEquals(0, $articles[2]->getCommentsDisabled());
        self::assertSame('1,2,3', $articles[2]->getReadAccess());
        self::assertSame('', $articles[2]->getImage());
        self::assertSame('', $articles[2]->getImageSource());
        self::assertSame('', $articles[2]->getVotes());
    }

    /**
     * Tests if getArticlesByKeyword() returns null if no articles exist for the given keyword.
     */
    public function testGetArticlesByKeywordNoResult()
    {
        $articles = $this->articleMapper->getArticlesByKeyword('NotExisting');

        self::assertNull($articles);
    }

    /**
     * Tests if getArticlesByKeywordAccess() returns all articles by given keyword and access group.
     */
    public function testGetArticlesByKeywordAccess()
    {
        $articles = $this->articleMapper->getArticlesByKeywordAccess('keyword1', '1,2,3');

        self::assertCount(3, $articles);

        self::assertEquals(3, $articles[0]->getId());
        self::assertSame('1', $articles[0]->getCatId());
        self::assertSame(1, $articles[0]->getAuthorId());
        self::assertSame('admin', $articles[0]->getAuthorName());
        self::assertSame(3, $articles[0]->getVisits());
        self::assertSame('testtitle3.html', $articles[0]->getPerma());
        self::assertSame('TestTitle3', $articles[0]->getTitle());
        self::assertSame('TestTeaser3', $articles[0]->getTeaser());
        self::assertSame('TestContent3', $articles[0]->getContent());
        self::assertSame('TestDescription3', $articles[0]->getDescription());
        self::assertSame('keyword1, keyword2', $articles[0]->getKeywords());
//        self::assertSame('', $articles[0]->getLocale());
        self::assertSame('2021-05-10 08:10:38', $articles[0]->getDateCreated());
        self::assertEquals(0, $articles[0]->getTopArticle());
        self::assertEquals(1, $articles[0]->getCommentsDisabled());
        self::assertSame('1', $articles[0]->getReadAccess());
        self::assertSame('', $articles[0]->getImage());
        self::assertSame('', $articles[0]->getImageSource());
        self::assertSame('', $articles[0]->getVotes());

        self::assertEquals(2, $articles[1]->getId());
        self::assertSame('1', $articles[1]->getCatId());
        self::assertSame(1, $articles[1]->getAuthorId());
        self::assertSame('admin', $articles[1]->getAuthorName());
        self::assertSame(2, $articles[1]->getVisits());
        self::assertSame('testtitle2.html', $articles[1]->getPerma());
        self::assertSame('TestTitle2', $articles[1]->getTitle());
        self::assertSame('TestTeaser2', $articles[1]->getTeaser());
        self::assertSame('TestContent2', $articles[1]->getContent());
        self::assertSame('TestDescription2', $articles[1]->getDescription());
        self::assertSame('keyword1, keyword2', $articles[1]->getKeywords());
//        self::assertSame('', $articles[1]->getLocale());
        self::assertSame('2021-05-09 08:10:38', $articles[1]->getDateCreated());
        self::assertEquals(0, $articles[1]->getTopArticle());
        self::assertEquals(0, $articles[1]->getCommentsDisabled());
        self::assertSame('1,2', $articles[1]->getReadAccess());
        self::assertSame('', $articles[1]->getImage());
        self::assertSame('', $articles[1]->getImageSource());
        self::assertSame('', $articles[1]->getVotes());

        self::assertEquals(1, $articles[2]->getId());
        self::assertSame('2', $articles[2]->getCatId());
        self::assertSame(1, $articles[2]->getAuthorId());
        self::assertSame('admin', $articles[2]->getAuthorName());
        self::assertSame(1, $articles[2]->getVisits());
        self::assertSame('testtitle.html', $articles[2]->getPerma());
        self::assertSame('TestTitle', $articles[2]->getTitle());
        self::assertSame('TestTeaser', $articles[2]->getTeaser());
        self::assertSame('TestContent', $articles[2]->getContent());
        self::assertSame('TestDescription', $articles[2]->getDescription());
        self::assertSame('keyword1, keyword2', $articles[2]->getKeywords());
//        self::assertSame('', $articles[2]->getLocale());
        self::assertSame('2021-05-08 08:10:38', $articles[2]->getDateCreated());
        self::assertEquals(0, $articles[2]->getTopArticle());
        self::assertEquals(0, $articles[2]->getCommentsDisabled());
        self::assertSame('1,2,3', $articles[2]->getReadAccess());
        self::assertSame('', $articles[2]->getImage());
        self::assertSame('', $articles[2]->getImageSource());
        self::assertSame('', $articles[2]->getVotes());
    }

    /**
     * Tests if getArticlesByKeywordAccess() returns only guest accessible articles for the given keyword with the default access.
     */
    public function testGetArticlesByKeywordAccessGuest()
    {
        $articles = $this->articleMapper->getArticlesByKeywordAccess('keyword1');

        self::assertCount(1, $articles);
    }

    /**
     * Tests if getArticlesByDate() returns all articles created after the given date.
     */
    public function testGetArticlesByDate()
    {
        $articles = $this->articleMapper->getArticlesByDate(new \Ilch\Date('2021-05-09'));

        self::assertCount(2, $articles);

        self::assertEquals(3, $articles[0]->getId());
        self::assertSame('1', $articles[0]->getCatId());
        self::assertSame(1, $articles[0]->getAuthorId());
        self::assertSame('admin', $articles[0]->getAuthorName());
        self::assertSame(3, $articles[0]->getVisits());
        self::assertSame('testtitle3.html', $articles[0]->getPerma());
        self::assertSame('TestTitle3', $articles[0]->getTitle());
        self::assertSame('TestTeaser3', $articles[0]->getTeaser());
        self::assertSame('TestContent3', $articles[0]->getContent());
        self::assertSame('TestDescription3', $articles[0]->getDescription());
        self::assertSame('keyword1, keyword2', $articles[0]->getKeywords());
//        self::assertSame('', $articles[0]->getLocale());
        self::assertSame('2021-05-10 08:10:38', $articles[0]->getDateCreated());
        self::assertEquals(0, $articles[0]->getTopArticle());
        self::assertEquals(1, $articles[0]->getCommentsDisabled());
        self::assertSame('1', $articles[0]->getReadAccess());
        self::assertSame('', $articles[0]->getImage());
        self::assertSame('', $articles[0]->getImageSource());
        self::assertSame('', $articles[0]->getVotes());

        self::assertEquals(2, $articles[1]->getId());
        self::assertSame('1', $articles[1]->getCatId());
        self::assertSame(1, $articles[1]->getAuthorId());
        self::assertSame('admin', $articles[1]->getAuthorName());
        self::assertSame(2, $articles[1]->getVisits());
        self::assertSame('testtitle2.html', $articles[1]->getPerma());
        self::assertSame('TestTitle2', $articles[1]->getTitle());
        self::assertSame('TestTeaser2', $articles[1]->getTeaser());
        self::assertSame('TestContent2', $articles[1]->getContent());
        self::assertSame('TestDescription2', $articles[1]->getDescription());
        self::assertSame('keyword1, keyword2', $articles[1]->getKeywords());
//        self::assertSame('', $articles[1]->getLocale());
        self::assertSame('2021-05-09 08:10:38', $articles[1]->getDateCreated());
        self::assertEquals(0, $articles[1]->getTopArticle());
        self::assertEquals(0, $articles[1]->getCommentsDisabled());
        self::assertSame('1,2', $articles[1]->getReadAccess());
        self::assertSame('', $articles[1]->getImage());
        self::assertSame('', $articles[1]->getImageSource());
        self::assertSame('', $articles[1]->getVotes());
    }

    /**
     * Tests if getArticlesByDate() returns paginated articles created after the given date.
     */
    public function testGetArticlesByDatePagination()
    {
        $pagination = new Pagination();

        // Without the pagination this would return 3 articles.
        $pagination->setRowsPerPage(2);
        $articles = $this->articleMapper->getArticlesByDate(new \Ilch\Date('2021-05-08'), $pagination);

        self::assertCount(2, $articles);
        self::assertCount(2, $pagination->getLimit());

        self::assertEquals(3, $articles[0]->getId());
        self::assertSame('1', $articles[0]->getCatId());
        self::assertSame(1, $articles[0]->getAuthorId());
        self::assertSame('admin', $articles[0]->getAuthorName());
        self::assertSame(3, $articles[0]->getVisits());
        self::assertSame('testtitle3.html', $articles[0]->getPerma());
        self::assertSame('TestTitle3', $articles[0]->getTitle());
        self::assertSame('TestTeaser3', $articles[0]->getTeaser());
        self::assertSame('TestContent3', $articles[0]->getContent());
        self::assertSame('TestDescription3', $articles[0]->getDescription());
        self::assertSame('keyword1, keyword2', $articles[0]->getKeywords());
//        self::assertSame('', $articles[0]->getLocale());
        self::assertSame('2021-05-10 08:10:38', $articles[0]->getDateCreated());
        self::assertEquals(0, $articles[0]->getTopArticle());
        self::assertEquals(1, $articles[0]->getCommentsDisabled());
        self::assertSame('1', $articles[0]->getReadAccess());
        self::assertSame('', $articles[0]->getImage());
        self::assertSame('', $articles[0]->getImageSource());
        self::assertSame('', $articles[0]->getVotes());

        self::assertEquals(2, $articles[1]->getId());
        self::assertSame('1', $articles[1]->getCatId());
        self::assertSame(1, $articles[1]->getAuthorId());
        self::assertSame('admin', $articles[1]->getAuthorName());
        self::assertSame(2, $articles[1]->getVisits());
        self::assertSame('testtitle2.html', $articles[1]->getPerma());
        self::assertSame('TestTitle2', $articles[1]->getTitle());
        self::assertSame('TestTeaser2', $articles[1]->getTeaser());
        self::assertSame('TestContent2', $articles[1]->getContent());
        self::assertSame('TestDescription2', $articles[1]->getDescription());
        self::assertSame('keyword1, keyword2', $articles[1]->getKeywords());
//        self::assertSame('', $articles[1]->getLocale());
        self::assertSame('2021-05-09 08:10:38', $articles[1]->getDateCreated());
        self::assertEquals(0, $articles[1]->getTopArticle());
        self::assertEquals(0, $articles[1]->getCommentsDisabled());
        self::assertSame('1,2', $articles[1]->getReadAccess());
        self::assertSame('', $articles[1]->getImage());
        self::assertSame('', $articles[1]->getImageSource());
        self::assertSame('', $articles[1]->getVotes());
    }

    /**
     * Tests if getArticlesByDateAccess() returns all articles created after the given date and by given access group.
     */
    public function testGetArticlesByDateAccess()
    {
        $articles = $this->articleMapper->getArticlesByDateAccess(new \Ilch\Date('2021-05-09'), '1,2,3');

        self::assertCount(2, $articles);

        self::assertEquals(3, $articles[0]->getId());
        self::assertSame('1', $articles[0]->getCatId());
        self::assertSame(1, $articles[0]->getAuthorId());
        self::assertSame('admin', $articles[0]->getAuthorName());
        self::assertSame(3, $articles[0]->getVisits());
        self::assertSame('testtitle3.html', $articles[0]->getPerma());
        self::assertSame('TestTitle3', $articles[0]->getTitle());
        self::assertSame('TestTeaser3', $articles[0]->getTeaser());
        self::assertSame('TestContent3', $articles[0]->getContent());
        self::assertSame('TestDescription3', $articles[0]->getDescription());
        self::assertSame('keyword1, keyword2', $articles[0]->getKeywords());
//        self::assertSame('', $articles[0]->getLocale());
        self::assertSame('2021-05-10 08:10:38', $articles[0]->getDateCreated());
        self::assertEquals(0, $articles[0]->getTopArticle());
        self::assertEquals(1, $articles[0]->getCommentsDisabled());
        self::assertSame('1', $articles[0]->getReadAccess());
        self::assertSame('', $articles[0]->getImage());
        self::assertSame('', $articles[0]->getImageSource());
        self::assertSame('', $articles[0]->getVotes());

        self::assertEquals(2, $articles[1]->getId());
        self::assertSame('1', $articles[1]->getCatId());
        self::assertSame(1, $articles[1]->getAuthorId());
        self::assertSame('admin', $articles[1]->getAuthorName());
        self::assertSame(2, $articles[1]->getVisits());
        self::assertSame('testtitle2.html', $articles[1]->getPerma());
        self::assertSame('TestTitle2', $articles[1]->getTitle());
        self::assertSame('TestTeaser2', $articles[1]->getTeaser());
        self::assertSame('TestContent2', $articles[1]->getContent());
        self::assertSame('TestDescription2', $articles[1]->getDescription());
        self::assertSame('keyword1, keyword2', $articles[1]->getKeywords());
//        self::assertSame('', $articles[1]->getLocale());
        self::assertSame('2021-05-09 08:10:38', $articles[1]->getDateCreated());
        self::assertEquals(0, $articles[1]->getTopArticle());
        self::assertEquals(0, $articles[1]->getCommentsDisabled());
        self::assertSame('1,2', $articles[1]->getReadAccess());
        self::assertSame('', $articles[1]->getImage());
        self::assertSame('', $articles[1]->getImageSource());
        self::assertSame('', $articles[1]->getVotes());
    }

    /**
     * Tests if getArticlesByDateAccess() returns null if no articles exist for the given date and access group.
     */
    public function testGetArticlesByDateAccessNoResult()
    {
        $articles = $this->articleMapper->getArticlesByDateAccess(new \Ilch\Date('2021-05-09'));

        self::assertNull($articles);
    }

    /**
     * Tests if getArticlesByDateAccess() returns articles created after the given date and by given access group.
     */
    public function testGetArticlesByDateAccessGuest()
    {
        $articles = $this->articleMapper->getArticlesByDateAccess(new \Ilch\Date('2021-05-09'), '2');

        self::assertCount(1, $articles);
    }

    /**
     * Tests if getCountArticlesByCatId() returns the number of articles in the given category.
     */
    public function testGetCountArticlesByCatId()
    {
        self::assertSame(2, $this->articleMapper->getCountArticlesByCatId('1'));
    }

    /**
     * Tests if getCountArticlesByCatId() returns 0 if the given category does not exist.
     */
    public function testGetCountArticlesByCatIdNotExisting()
    {
        self::assertSame(0, $this->articleMapper->getCountArticlesByCatId('3'));
    }

    /**
     * Tests if getCountArticlesByCatIdAccess() returns the number of articles in the given category and access group.
     */
    public function testGetCountArticlesByCatIdAccess()
    {
        self::assertSame(2, $this->articleMapper->getCountArticlesByCatIdAccess('1', '1,2,3'));
    }

    /**
     * Tests if getCountArticlesByCatIdAccess() returns 0 if the given category does not exist.
     */
    public function testGetCountArticlesByCatIdNotExistingAccess()
    {
        self::assertSame(0, $this->articleMapper->getCountArticlesByCatIdAccess('3'));
    }

    /**
     * Tests if getCountArticlesByMonthYear() returns the number of articles created in the given month and year.
     */
    public function testGetCountArticlesByMonthYear()
    {
        self::assertSame(3, $this->articleMapper->getCountArticlesByMonthYear('2021-05-10 08:10:38'));
    }

    /**
     * Tests if getCountArticlesByMonthYear() returns 0 if no articles exist in the given month and year.
     */
    public function testGetCountArticlesByMonthYearNotExisting()
    {
        self::assertSame(0, $this->articleMapper->getCountArticlesByMonthYear('2000-01-01 08:10:38'));
    }

    /**
     * Tests if getCountArticlesByMonthYearAccess() returns the number of articles created in the given month and year and by given access group.
     */
    public function testGetCountArticlesByMonthYearAccess()
    {
        self::assertSame(3, $this->articleMapper->getCountArticlesByMonthYearAccess('2021-05-10 08:10:38', '1,2,3'));
    }

    /**
     * Tests if getCountArticlesByMonthYearAccess() returns the number of guest accessible articles created in the given month and year with the default access.
     */
    public function testGetCountArticlesByMonthYearAccessGuest()
    {
        self::assertSame(1, $this->articleMapper->getCountArticlesByMonthYearAccess('2021-05-10 08:10:38'));
    }

    /**
     * Tests if getCountArticlesByMonthYearAccess() returns 0 if no articles exist in the given month and year.
     */
    public function testGetCountArticlesByMonthYearAccessNotExisting()
    {
        self::assertSame(0, $this->articleMapper->getCountArticlesByMonthYearAccess('2000-01-01 08:10:38'));
    }

    /**
     * Tests if getArticleDateList() returns a list of articles by given limit.
     */
    public function testGetArticleDateList()
    {
        $articles = $this->articleMapper->getArticleDateList(3);

        self::assertCount(1, $articles);

        self::assertSame('2021-05-10 08:10:38', $articles[0]->getDateCreated());
    }

    /**
     * Tests if getArticleDateListAccess() returns a list of articles by given access group and limit.
     */
    public function testGetArticleDateListAccess()
    {
        $articles = $this->articleMapper->getArticleDateListAccess('1,2,3', 3);

        self::assertCount(1, $articles);

        self::assertSame('2021-05-10 08:10:38', $articles[0]->getDateCreated());
    }

    /**
     * Tests if getArticleList() returns all articles ordered by date.
     */
    public function testGetArticleList()
    {
        $articles = $this->articleMapper->getArticleList();

        self::assertCount(3, $articles);

        self::assertEquals(3, $articles[0]->getId());
        self::assertSame('1', $articles[0]->getCatId());
        self::assertSame('TestTitle3', $articles[0]->getTitle());
        self::assertSame('testtitle3.html', $articles[0]->getPerma());
        self::assertSame('2021-05-10 08:10:38', $articles[0]->getDateCreated());
        self::assertSame('', $articles[0]->getImage());
        self::assertSame('test_thumb.jpg', $articles[0]->getImageThumb());
        self::assertSame('', $articles[0]->getImageSource());

        self::assertEquals(2, $articles[1]->getId());
        self::assertEquals(1, $articles[2]->getId());
    }

    /**
     * Tests if getArticleList() returns only the given number of articles.
     */
    public function testGetArticleListLimit()
    {
        $articles = $this->articleMapper->getArticleList('', 2);

        self::assertCount(2, $articles);

        self::assertEquals(3, $articles[0]->getId());
        self::assertEquals(2, $articles[1]->getId());
    }

    /**
     * Tests if getArticleList() returns null when no article matches the given locale.
     */
    public function testGetArticleListLocaleNoResults()
    {
        self::assertNull($this->articleMapper->getArticleList('en'));
    }

    /**
     * Tests if getArticleListAccess() returns all articles accessible by the given groups.
     */
    public function testGetArticleListAccess()
    {
        $articles = $this->articleMapper->getArticleListAccess('1,2,3');

        self::assertCount(3, $articles);

        self::assertEquals(3, $articles[0]->getId());
        self::assertEquals(2, $articles[1]->getId());
        self::assertEquals(1, $articles[2]->getId());
    }

    /**
     * Tests if getArticleListAccess() returns only guest accessible articles with the default access.
     */
    public function testGetArticleListAccessGuest()
    {
        $articles = $this->articleMapper->getArticleListAccess();

        self::assertCount(1, $articles);

        self::assertEquals(1, $articles[0]->getId());
        self::assertSame('testtitle.html', $articles[0]->getPerma());
        self::assertSame('test_thumb.jpg', $articles[0]->getImageThumb());
    }

    /**
     * Tests if getArticleByIdLocale() returns an article by given id and locale.
     */
    public function testGetArticleByIdLocale()
    {
        $article = $this->articleMapper->getArticleByIdLocale(3);

        self::assertNotNull($article);

        self::assertEquals(3, $article->getId());
        self::assertSame('1', $article->getCatId());
        self::assertSame(1, $article->getAuthorId());
        self::assertSame('TestDescription3', $article->getDescription());
        self::assertSame('keyword1, keyword2', $article->getKeywords());
        self::assertSame('TestTitle3', $article->getTitle());
        self::assertSame('TestTeaser3', $article->getTeaser());
        self::assertSame('TestContent3', $article->getContent());
        self::assertSame('testtitle3.html', $article->getPerma());
        self::assertSame('', $article->getLocale());
        self::assertSame('2021-05-10 08:10:38', $article->getDateCreated());
        self::assertFalse($article->getTopArticle());
        self::assertTrue($article->getCommentsDisabled());
        self::assertSame('1', $article->getReadAccess());
        self::assertSame('', $article->getImage());
        self::assertSame('', $article->getImageSource());
        self::assertSame('', $article->getVotes());
    }

    /**
     * Tests if getArticleByIdLocale() returns null if the article does not exist.
     */
    public function testGetArticleByIdLocaleNotExisting()
    {
        $articles = $this->articleMapper->getArticleByIdLocale(0);

        self::assertNull($articles);
    }

    /**
     * Tests if getKeywordsList() returns a list of keywords by given limit.
     */
    public function testGetKeywordsList()
    {
        $articles = $this->articleMapper->getKeywordsList(2);

        self::assertCount(2, $articles);

        self::assertSame('keyword1, keyword2', $articles[0]->getKeywords());
        self::assertSame('keyword1, keyword2', $articles[1]->getKeywords());
    }

    /**
     * Tests if getKeywordsListAccess() returns a list of keywords by given access group and limit.
     */
    public function testGetKeywordsListAccess()
    {
        $articles = $this->articleMapper->getKeywordsListAccess('1,2,3', 2);

        self::assertCount(2, $articles);

        self::assertSame('keyword1, keyword2', $articles[0]->getKeywords());
        self::assertSame('keyword1, keyword2', $articles[1]->getKeywords());
    }

    /**
     * Tests if getKeywordsListAccess() returns a list of guest accessible keywords with the given limit.
     */
    public function testGetKeywordsListAccessGuest()
    {
        $articles = $this->articleMapper->getKeywordsListAccess('3', 2);

        self::assertCount(1, $articles);

        self::assertSame('keyword1, keyword2', $articles[0]->getKeywords());
    }

    /**
     * Tests if keywordExists() returns true if the given keyword exists.
     */
    public function testKeywordExists()
    {
        self::assertTrue($this->articleMapper->keywordExists('keyword1'));
        self::assertTrue($this->articleMapper->keywordExists('keyword2'));
    }

    /**
     * Tests if keywordExists() returns false if the given keyword does not exist.
     */
    public function testKeywordExistsNotExisting()
    {
        self::assertFalse($this->articleMapper->keywordExists('notexisting'));
    }

    /**
     * Tests if getArticlePermas() returns all permalinks of the articles.
     */
    public function testGetArticlePermas()
    {
        $permas = $this->articleMapper->getArticlePermas();

        self::assertCount(3, $permas);

        self::assertSame('testtitle.html', $permas['testtitle.html']['perma']);
        self::assertSame('testtitle2.html', $permas['testtitle2.html']['perma']);
        self::assertSame('testtitle3.html', $permas['testtitle3.html']['perma']);
    }

    /**
     * Tests if getTopArticle() returns null if no top article exists.
     */
    public function testGetTopArticleNoTopArticle()
    {
        $articles = $this->articleMapper->getTopArticle();

        self::assertNull($articles);
    }

    /**
     * Tests if getTopArticle() returns the top article.
     */
    public function testGetTopArticle()
    {
        $this->articleMapper->setTopArticle(1, 1);
        $article = $this->articleMapper->getTopArticle();

        self::assertNotNull($article);

        self::assertEquals(1, $article->getId());
        self::assertSame('2', $article->getCatId());
        self::assertSame(1, $article->getAuthorId());
        self::assertSame('TestDescription', $article->getDescription());
        self::assertSame('keyword1, keyword2', $article->getKeywords());
        self::assertSame('TestTitle', $article->getTitle());
        self::assertSame('TestTeaser', $article->getTeaser());
        self::assertSame('TestContent', $article->getContent());
        self::assertSame('testtitle.html', $article->getPerma());
        self::assertSame('', $article->getLocale());
        self::assertSame('2021-05-08 08:10:38', $article->getDateCreated());
        self::assertEquals(0, $article->getCommentsDisabled());
        self::assertSame('1,2,3', $article->getReadAccess());
        self::assertSame('', $article->getImage());
        self::assertSame('', $article->getImageSource());
        self::assertSame('', $article->getVotes());
    }

    /**
     * Tests if getTopArticles() returns an empty array if no top article exists.
     */
    public function testGetTopArticlesNoTopArticle()
    {
        $articles = $this->articleMapper->getTopArticles();

        self::assertEmpty($articles);
    }

    /**
     * Tests if getTopArticles() returns all top articles.
     */
    public function testGetTopArticles()
    {
        $this->articleMapper->setTopArticle(1, 1);
        $this->articleMapper->setTopArticle(2, 1);
        $articles = $this->articleMapper->getTopArticles();

        self::assertCount(2, $articles);

        self::assertEquals(1, $articles[0]->getId());
        self::assertSame('2', $articles[0]->getCatId());
        self::assertSame(1, $articles[0]->getAuthorId());
//        self::assertSame('admin', $articles[0]->getAuthorName());
        self::assertSame(1, $articles[0]->getVisits());
        self::assertSame('testtitle.html', $articles[0]->getPerma());
        self::assertSame('TestTitle', $articles[0]->getTitle());
        self::assertSame('TestTeaser', $articles[0]->getTeaser());
        self::assertSame('TestContent', $articles[0]->getContent());
        self::assertSame('TestDescription', $articles[0]->getDescription());
        self::assertSame('keyword1, keyword2', $articles[0]->getKeywords());
        self::assertSame('', $articles[0]->getLocale());
        self::assertSame('2021-05-08 08:10:38', $articles[0]->getDateCreated());
        self::assertEquals(0, $articles[0]->getCommentsDisabled());
        self::assertSame('1,2,3', $articles[0]->getReadAccess());
        self::assertSame('', $articles[0]->getImage());
        self::assertSame('', $articles[0]->getImageSource());
        self::assertSame('', $articles[0]->getVotes());

        self::assertEquals(2, $articles[1]->getId());
        self::assertSame('1', $articles[1]->getCatId());
        self::assertSame(1, $articles[1]->getAuthorId());
//        self::assertSame('admin', $articles[1]->getAuthorName());
        self::assertSame(2, $articles[1]->getVisits());
        self::assertSame('testtitle2.html', $articles[1]->getPerma());
        self::assertSame('TestTitle2', $articles[1]->getTitle());
        self::assertSame('TestTeaser2', $articles[1]->getTeaser());
        self::assertSame('TestContent2', $articles[1]->getContent());
        self::assertSame('TestDescription2', $articles[1]->getDescription());
        self::assertSame('keyword1, keyword2', $articles[1]->getKeywords());
        self::assertSame('', $articles[1]->getLocale());
        self::assertSame('2021-05-09 08:10:38', $articles[1]->getDateCreated());
        self::assertEquals(0, $articles[1]->getCommentsDisabled());
        self::assertSame('1,2', $articles[1]->getReadAccess());
        self::assertSame('', $articles[1]->getImage());
        self::assertSame('', $articles[1]->getImageSource());
        self::assertSame('', $articles[1]->getVotes());
    }

    /**
     * Tests if saveVisits() saves the visits of an article.
     */
    public function testSaveVisits()
    {
        $model = new ArticleModel();

        $model->setId(1);
        $model->setVisits(20);
        $this->articleMapper->saveVisits($model);

        $article = $this->articleMapper->getArticleByIdLocale(1);

        self::assertNotNull($article);
        self::assertEquals(1, $article->getId());
        self::assertSame(20, $article->getVisits());
    }

    /**
     * Tests if saveVisits() does not change the visits if no visits are given.
     */
    public function testSaveVisitsEmptyVisits()
    {
        $model = new ArticleModel();

        $model->setId(1);
        $this->articleMapper->saveVisits($model);

        $article = $this->articleMapper->getArticleByIdLocale(1);

        self::assertNotNull($article);
        self::assertEquals(1, $article->getId());
        self::assertSame(1, $article->getVisits());
    }

    /**
     * Tests if save() saves a new article and returns its id.
     */
    public function testSaveNewArticle()
    {
        $model = new ArticleModel();
        $model->setCatId(1);
        $model->setAuthorId(1);
        $model->setDescription('TestDescription4');
        $model->setKeywords('keywords1, keywords2');
        $model->setTitle('TestTitle4');
        $model->setTeaser('TestTeaser4');
        $model->setContent('TestContent4');
        $model->setPerma('testtitle4.html');
        $model->setLocale('');
        $model->setTopArticle(1);
        $model->setCommentsDisabled(1);
        $model->setReadAccess('1,2,3');
        $model->setImage('');
        $model->setImageSource('');
        $model->setVotes('1,2,3');
        $id = $this->articleMapper->save($model);

        $article = $this->articleMapper->getArticleByIdLocale($id);

        self::assertNotNull($article);
        self::assertEquals($id, $article->getId());
        self::assertSame('1', $article->getCatId());
        self::assertSame(1, $article->getAuthorId());
        self::assertSame('TestDescription4', $article->getDescription());
        self::assertSame('keywords1, keywords2', $article->getKeywords());
        self::assertSame('TestTitle4', $article->getTitle());
        self::assertSame('TestTeaser4', $article->getTeaser());
        self::assertSame('TestContent4', $article->getContent());
        self::assertSame('testtitle4.html', $article->getPerma());
        self::assertSame('', $article->getLocale());
        self::assertTrue($article->getTopArticle());
        self::assertTrue($article->getCommentsDisabled());
        self::assertSame('1,2,3', $article->getReadAccess());
        self::assertSame('', $article->getImage());
        self::assertSame('', $article->getImageSource());
        self::assertSame('1,2,3', $article->getVotes());
    }

    /**
     * Tests if save() updates an existing article and returns its id.
     */
    public function testSaveUpdateExistingArticle()
    {
        $model = new ArticleModel();
        $model->setId(1);
        $model->setCatId(1);
        $model->setAuthorId(1);
        $model->setDescription('TestDescription4');
        $model->setKeywords('keywords1, keywords2');
        $model->setTitle('TestTitle4');
        $model->setTeaser('TestTeaser4');
        $model->setContent('TestContent4');
        $model->setPerma('testtitle4.html');
        $model->setLocale('');
        $model->setTopArticle(1);
        $model->setCommentsDisabled(1);
        $model->setReadAccess('1,2,3');
        $model->setImage('');
        $model->setImageSource('');
        $model->setVotes('1,2,3');
        $id = $this->articleMapper->save($model);

        $article = $this->articleMapper->getArticleByIdLocale($id);

        self::assertNotNull($article);
        self::assertEquals(1, $id);
        self::assertEquals(1, $article->getId());
        self::assertSame('1', $article->getCatId());
        self::assertSame(1, $article->getAuthorId());
        self::assertSame('TestDescription4', $article->getDescription());
        self::assertSame('keywords1, keywords2', $article->getKeywords());
        self::assertSame('TestTitle4', $article->getTitle());
        self::assertSame('TestTeaser4', $article->getTeaser());
        self::assertSame('TestContent4', $article->getContent());
        self::assertSame('testtitle4.html', $article->getPerma());
        self::assertSame('', $article->getLocale());
        self::assertTrue($article->getTopArticle());
        self::assertTrue($article->getCommentsDisabled());
        self::assertSame('1,2,3', $article->getReadAccess());
        self::assertSame('', $article->getImage());
        self::assertSame('', $article->getImageSource());
        self::assertSame('1,2,3', $article->getVotes());
    }

    /**
     * Tests if save() saves a new locale for an existing article and returns its id.
     */
    public function testSaveExistingArticleNewLocale()
    {
        $model = new ArticleModel();
        $model->setId(1);
        $model->setAuthorId(1);
        $model->setDescription('TestDescription4');
        $model->setKeywords('keywords1, keywords2');
        $model->setTitle('TestTitle4');
        $model->setTeaser('TestTeaser4');
        $model->setContent('TestContent4');
        $model->setPerma('testtitle4.html');
        $model->setLocale('en');
        $model->setTopArticle(1);
        $model->setReadAccess('1,2,3');
        $model->setImage('');
        $model->setImageSource('');
        $model->setVotes('1,2,3');
        $id = $this->articleMapper->save($model);

        $article = $this->articleMapper->getArticleByIdLocale($id, 'en');

        self::assertNotNull($article);
        self::assertEquals(1, $id);
        self::assertEquals(1, $article->getId());
        self::assertSame('2', $article->getCatId());
        self::assertSame(1, $article->getAuthorId());
        self::assertSame('TestDescription4', $article->getDescription());
        self::assertSame('keywords1, keywords2', $article->getKeywords());
        self::assertSame('TestTitle4', $article->getTitle());
        self::assertSame('TestTeaser4', $article->getTeaser());
        self::assertSame('TestContent4', $article->getContent());
        self::assertSame('testtitle4.html', $article->getPerma());
        self::assertSame('en', $article->getLocale());
        self::assertTrue($article->getTopArticle());
        self::assertFalse($article->getCommentsDisabled());
        self::assertSame('1,2,3', $article->getReadAccess());
        self::assertSame('', $article->getImage());
        self::assertSame('', $article->getImageSource());
        self::assertSame('1,2,3', $article->getVotes());
    }

    /**
     * Tests if saveVotes() saves votes for an article.
     */
    public function testSaveVotes()
    {
        $this->articleMapper->saveVotes(1, 1);
        $this->articleMapper->saveVotes(1, 2);
        $article = $this->articleMapper->getArticleByIdLocale(1);

        self::assertNotNull($article);
        self::assertSame('1,2,', $article->getVotes());
    }

    /**
     * Tests if getVotes() returns the votes of an article.
     */
    public function testGetVotes()
    {
        $this->articleMapper->saveVotes(1, 1);
        $this->articleMapper->saveVotes(1, 2);

        self::assertSame('1,2,', $this->articleMapper->getVotes(1));
    }

    /**
     * Tests if getVotes() returns an empty string if no votes exist for the article.
     */
    public function testGetVotesNoVotes()
    {
        self::assertSame('', $this->articleMapper->getVotes(1));
    }

    /**
     * Tests if delete() deletes an article.
     */
    public function testDelete()
    {
        self::assertSame(1, $this->articleMapper->delete(1));

        $article = $this->articleMapper->getArticleByIdLocale(1);
        self::assertNull($article);
    }

    /**
     * Tests if getArticles() returns paginated articles.
     */
    public function testGetArticlesPagination()
    {
        $pagination = new Pagination();

        $pagination->setRowsPerPage(2);
        $articles = $this->articleMapper->getArticles('', $pagination);

        self::assertCount(2, $articles);
        self::assertCount(2, $pagination->getLimit());

        self::assertEquals(3, $articles[0]->getId());
        self::assertEquals(2, $articles[1]->getId());
    }

    /**
     * Tests if getArticles() returns null when no article matches the given locale.
     */
    public function testGetArticlesLocaleNoResults()
    {
        $articles = $this->articleMapper->getArticles('en');

        self::assertNull($articles);
    }

    /**
     * Tests if getArticlesByAccess() returns paginated articles.
     */
    public function testGetArticlesByAccessPagination()
    {
        $pagination = new Pagination();

        $pagination->setRowsPerPage(2);
        $articles = $this->articleMapper->getArticlesByAccess('1,2,3', '', $pagination);

        self::assertCount(2, $articles);
        self::assertCount(2, $pagination->getLimit());

        self::assertEquals(3, $articles[0]->getId());
        self::assertEquals(2, $articles[1]->getId());
    }

    /**
     * Tests if getArticlesByCats() returns the articles of the second category.
     */
    public function testGetArticlesByCatsSecond()
    {
        $articles = $this->articleMapper->getArticlesByCats('2');

        self::assertCount(1, $articles);
        self::assertEquals(1, $articles[0]->getId());
    }

    /**
     * Tests if getCountArticlesByMonthYear() returns the total number of articles without a date.
     */
    public function testGetCountArticlesByMonthYearNoDate()
    {
        self::assertSame(3, $this->articleMapper->getCountArticlesByMonthYear());
    }

    /**
     * Tests if getCountArticlesByMonthYearAccess() returns the total number of accessible articles without a date.
     */
    public function testGetCountArticlesByMonthYearAccessNoDate()
    {
        self::assertSame(3, $this->articleMapper->getCountArticlesByMonthYearAccess(null, '1,2,3'));
    }

    /**
     * Tests if getCountArticlesByMonthYearAccess() returns the number of guest accessible articles without a date with the default access.
     */
    public function testGetCountArticlesByMonthYearAccessGuestNoDate()
    {
        self::assertSame(1, $this->articleMapper->getCountArticlesByMonthYearAccess());
    }

    /**
     * Tests if getArticleDateList() returns a list of articles without a limit.
     */
    public function testGetArticleDateListNoLimit()
    {
        $articles = $this->articleMapper->getArticleDateList();

        self::assertCount(1, $articles);
        self::assertSame('2021-05-10 08:10:38', $articles[0]->getDateCreated());
    }

    /**
     * Tests if getArticleDateList() returns an empty array if no entries exist.
     */
    public function testGetArticleDateListEmpty()
    {
        $articles = $this->articleMapper->getArticleDateList(0);

        self::assertSame([], $articles);
    }

    /**
     * Tests if getArticleDateListAccess() returns only guest accessible entries.
     */
    public function testGetArticleDateListAccessGuest()
    {
        $articles = $this->articleMapper->getArticleDateListAccess('3', 3);

        self::assertCount(1, $articles);
        self::assertSame('2021-05-08 08:10:38', $articles[0]->getDateCreated());
    }

    /**
     * Tests if getKeywordsList() returns all keywords without a limit.
     */
    public function testGetKeywordsListNoLimit()
    {
        $articles = $this->articleMapper->getKeywordsList();

        self::assertCount(3, $articles);

        self::assertSame('keyword1, keyword2', $articles[0]->getKeywords());
        self::assertSame('keyword1, keyword2', $articles[1]->getKeywords());
        self::assertSame('keyword1, keyword2', $articles[2]->getKeywords());
    }

    /**
     * Tests if getArticlePermas() returns null if no articles exist.
     */
    public function testGetArticlePermasEmpty()
    {
        $this->articleMapper->delete(1);
        $this->articleMapper->delete(2);
        $this->articleMapper->delete(3);

        self::assertNull($this->articleMapper->getArticlePermas());
    }

    /**
     * Tests if save() updates the read access groups of an existing article.
     */
    public function testSaveUpdatesReadAccess()
    {
        $model = new ArticleModel();
        $model->setId(2);
        $model->setCatId(1);
        $model->setAuthorId(1);
        $model->setDescription('TestDescription2');
        $model->setKeywords('keyword1, keyword2');
        $model->setTitle('TestTitle2');
        $model->setTeaser('TestTeaser2');
        $model->setContent('TestContent2');
        $model->setPerma('testtitle2.html');
        $model->setLocale('');
        $model->setDateCreated('2021-05-09 08:10:38');
        $model->setTopArticle(0);
        $model->setCommentsDisabled(0);
        $model->setReadAccess('1,3');
        $model->setImage('');
        $model->setImageSource('');
        $model->setVotes('');
        $id = $this->articleMapper->save($model);

        $article = $this->articleMapper->getArticleByIdLocale(2);

        self::assertNotNull($article);
        self::assertSame(2, $id);
        self::assertSame('1,3', $article->getReadAccess());
    }

    /**
     * Tests if setTopArticle() sets and unsets the top article flag.
     */
    public function testSetTopArticleToggle()
    {
        $this->articleMapper->setTopArticle(1, 1);
        self::assertNotNull($this->articleMapper->getTopArticle());

        $this->articleMapper->setTopArticle(1, 0);
        self::assertNull($this->articleMapper->getTopArticle());
    }

    /**
     * Tests that saveVotes() appends votes without deduplication (documents current behaviour).
     */
    public function testSaveVotesDuplicate()
    {
        $this->articleMapper->saveVotes(1, 5);
        $this->articleMapper->saveVotes(1, 5);

        self::assertSame('5,5,', $this->articleMapper->getVotes(1));
    }

    /**
     * Tests if deleteWithComments() deletes the article and does not throw.
     */
    public function testDeleteWithComments()
    {
        $this->articleMapper->deleteWithComments(1);

        self::assertNull($this->articleMapper->getArticleByIdLocale(1));

        $articles = $this->articleMapper->getArticles();
        self::assertCount(2, $articles);
    }

    /**
     * Returns database schema sql statements to initialize database
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $configUser = new UserConfig();
        $configAdmin = new AdminConfig();
        $configComment = new CommentConfig();
        $configMedia = new MediaConfig();

        return $configAdmin->getInstallSql() . $configUser->getInstallSql()
            . $configComment->getInstallSql() . $configMedia->getInstallSql() . $config->getInstallSql();
    }
}
