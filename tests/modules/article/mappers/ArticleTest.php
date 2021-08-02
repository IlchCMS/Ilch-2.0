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
use PHPUnit\Ilch\PhpunitDataset;

/**
 * Tests the article mapper class.
 *
 * @package ilch_phpunit
 */
class ArticleTest extends DatabaseTestCase
{
    protected $phpunitDataset;
    private $articleMapper;

    public function setUp()
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');

        $this->articleMapper = new ArticleMapper();
    }

    /**
     * Tests if getArticles() returns all articles from the database.
     */
    public function testgetArticlesAllRows()
    {
        $articles = $this->articleMapper->getArticles();

        self::assertCount(3, $articles);
    }

    public function testgetArticles()
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

    public function testgetArticlesByAccess()
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

    public function testgetArticlesByAccessArray()
    {
        $articles = $this->articleMapper->getArticlesByAccess([1, 2, 3]);

        self::assertCount(3, $articles);
    }

    public function testgetArticlesByAccessGuest()
    {
        $articles = $this->articleMapper->getArticlesByAccess([3]);

        self::assertCount(1, $articles);
    }

    public function testgetArticlesByAccessGuestDefault()
    {
        $articles = $this->articleMapper->getArticlesByAccess();

        self::assertCount(1, $articles);
    }

    public function testgetArticlesByCats()
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

    public function testgetArticlesByCatsNoResult()
    {
        $articles = $this->articleMapper->getArticlesByCats('0');

        self::assertNull($articles);
    }

    public function testgetArticlesByCatsAccess()
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

    public function testgetArticlesByCatsAccessArray()
    {
        $articles = $this->articleMapper->getArticlesByCatsAccess('1', [1, 2, 3]);

        self::assertCount(2, $articles);
    }

    public function testgetArticlesByCatsAccessGuest()
    {
        $articles = $this->articleMapper->getArticlesByCatsAccess('2', [3]);

        self::assertCount(1, $articles);
    }

    public function testgetArticlesByCatsAccessGuestDefault()
    {
        $articles = $this->articleMapper->getArticlesByCatsAccess('2');

        self::assertCount(1, $articles);
    }

    public function testgetArticlesByKeyword()
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

    public function testgetArticlesByKeywordNoResult()
    {
        $articles = $this->articleMapper->getArticlesByKeyword('NotExisting');

        self::assertNull($articles);
    }

    public function testgetArticlesByKeywordAccess()
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

    public function testgetArticlesByKeywordAccessGuest()
    {
        $articles = $this->articleMapper->getArticlesByKeywordAccess('keyword1', '3');

        self::assertCount(1, $articles);
    }

    public function testgetArticlesByDate()
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

    public function testgetArticlesByDatePagination()
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

    public function testgetArticlesByDateAccess()
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

    public function testgetArticlesByDateAccessNoResult()
    {
        $articles = $this->articleMapper->getArticlesByDateAccess(new \Ilch\Date('2021-05-09'), '3');

        self::assertNull($articles);
    }

    public function testgetArticlesByDateAccessGuest()
    {
        $articles = $this->articleMapper->getArticlesByDateAccess(new \Ilch\Date('2021-05-09'), '2');

        self::assertCount(1, $articles);
    }

    public function testgetCountArticlesByCatId()
    {
        self::assertSame(2, $this->articleMapper->getCountArticlesByCatId("1"));
    }

    public function testgetCountArticlesByCatIdNotExisting()
    {
        self::assertSame(0, $this->articleMapper->getCountArticlesByCatId("3"));
    }

    public function testgetCountArticlesByCatIdAccess()
    {
        self::assertSame(2, $this->articleMapper->getCountArticlesByCatIdAccess("1", '1,2,3'));
    }

    public function testgetCountArticlesByCatIdNotExistingAccess()
    {
        self::assertSame(0, $this->articleMapper->getCountArticlesByCatIdAccess("3"));
    }

    public function testgetCountArticlesByMonthYear()
    {
        self::assertSame(3, $this->articleMapper->getCountArticlesByMonthYear("2021-05-10 08:10:38"));
    }

    public function testgetCountArticlesByMonthYearNotExisting()
    {
        self::assertSame(0, $this->articleMapper->getCountArticlesByMonthYear("2000-01-01 08:10:38"));
    }

    public function testgetCountArticlesByMonthYearAccess()
    {
        self::assertSame(3, $this->articleMapper->getCountArticlesByMonthYearAccess("2021-05-10 08:10:38", '1,2,3'));
    }

    public function testgetCountArticlesByMonthYearAccessGuest()
    {
        self::assertSame(1, $this->articleMapper->getCountArticlesByMonthYearAccess("2021-05-10 08:10:38"));
    }

    public function testgetCountArticlesByMonthYearAccessNotExisting()
    {
        self::assertSame(0, $this->articleMapper->getCountArticlesByMonthYearAccess("2000-01-01 08:10:38"));
    }

    public function testgetArticleDateList()
    {
        $articles = $this->articleMapper->getArticleDateList(3);

        self::assertCount(1, $articles);

        self::assertSame('2021-05-10 08:10:38', $articles[0]->getDateCreated());
    }

    public function testgetArticleDateListAccess()
    {
        $articles = $this->articleMapper->getArticleDateListAccess('1,2,3', 3);

        self::assertCount(1, $articles);

        self::assertSame('2021-05-10 08:10:38', $articles[0]->getDateCreated());
    }

//    // Needs the media table
//    public function testgetArticleList()
//    {
//        $articles = $this->articleMapper->getArticleList('', 3);
//
//        self::assertCount(1, $articles);
//
//        self::assertSame('2021-05-10 08:10:38', $articles[0]->getDateCreated());
//    }

    public function testgetArticleByIdLocale()
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
        self::assertSame('0', $article->getTopArticle());
        self::assertSame('1', $article->getCommentsDisabled());
        self::assertSame('1', $article->getReadAccess());
        self::assertSame('', $article->getImage());
        self::assertSame('', $article->getImageSource());
        self::assertSame('', $article->getVotes());
    }

    public function testgetArticleByIdLocaleNotExisting()
    {
        $articles = $this->articleMapper->getArticleByIdLocale(0);

        self::assertNull($articles);
    }

    public function testgetKeywordsList()
    {
        $articles = $this->articleMapper->getKeywordsList(2);

        self::assertCount(2, $articles);

        self::assertSame('keyword1, keyword2', $articles[0]->getKeywords());
        self::assertSame('keyword1, keyword2', $articles[1]->getKeywords());
    }

    public function testgetKeywordsListAccess()
    {
        $articles = $this->articleMapper->getKeywordsListAccess('1,2,3', 2);

        self::assertCount(2, $articles);

        self::assertSame('keyword1, keyword2', $articles[0]->getKeywords());
        self::assertSame('keyword1, keyword2', $articles[1]->getKeywords());
    }

    public function testgetKeywordsListAccessGuest()
    {
        $articles = $this->articleMapper->getKeywordsListAccess('3', 2);

        self::assertCount(1, $articles);

        self::assertSame('keyword1, keyword2', $articles[0]->getKeywords());
    }

    public function testkeywordExists()
    {
        self::assertTrue($this->articleMapper->keywordExists('keyword1'));
        self::assertTrue($this->articleMapper->keywordExists('keyword2'));

    }

    public function testkeywordExistsNotExisting()
    {
        self::assertFalse($this->articleMapper->keywordExists('notexisting'));
    }

    public function testgetArticlePermas()
    {
        $permas = $this->articleMapper->getArticlePermas();

        self::assertCount(3, $permas);

        self::assertSame('testtitle.html', $permas['testtitle.html']['perma']);
        self::assertSame('testtitle2.html', $permas['testtitle2.html']['perma']);
        self::assertSame('testtitle3.html', $permas['testtitle3.html']['perma']);
    }

    public function testgetTopArticleNoTopArticle()
    {
        $articles = $this->articleMapper->getTopArticle();

        self::assertNull($articles);
    }

    public function testgetTopArticle()
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

    public function testgetTopArticles()
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

    public function testsaveVisits()
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

    public function testsaveVisitsEmptyVisits()
    {
        $model = new ArticleModel();

        $model->setId(1);
        $this->articleMapper->saveVisits($model);

        $article = $this->articleMapper->getArticleByIdLocale(1);

        self::assertNotNull($article);
        self::assertEquals(1, $article->getId());
        self::assertSame(1, $article->getVisits());
    }

    public function testsaveNewArticle()
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
        self::assertSame('1', $article->getTopArticle());
        self::assertSame('1', $article->getCommentsDisabled());
        self::assertSame('1,2,3', $article->getReadAccess());
        self::assertSame('', $article->getImage());
        self::assertSame('', $article->getImageSource());
        self::assertSame('1,2,3', $article->getVotes());
    }

    public function testsaveUpdateExistingArticle()
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
        self::assertSame('1', $article->getTopArticle());
        self::assertSame('1', $article->getCommentsDisabled());
        self::assertSame('1,2,3', $article->getReadAccess());
        self::assertSame('', $article->getImage());
        self::assertSame('', $article->getImageSource());
        self::assertSame('1,2,3', $article->getVotes());
    }

    public function testsaveExistingArticleNewLocale()
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
        self::assertSame('1', $article->getTopArticle());
        self::assertSame('0', $article->getCommentsDisabled());
        self::assertSame('1,2,3', $article->getReadAccess());
        self::assertSame('', $article->getImage());
        self::assertSame('', $article->getImageSource());
        self::assertSame('1,2,3', $article->getVotes());
    }

    public function testsaveVotes()
    {
        $this->articleMapper->saveVotes(1, 1);
        $this->articleMapper->saveVotes(1, 2);
        $article = $this->articleMapper->getArticleByIdLocale(1);

        self::assertNotNull($article);
        self::assertSame('1,2,', $article->getVotes());
    }

    public function testgetVotes()
    {
        $this->articleMapper->saveVotes(1, 1);
        $this->articleMapper->saveVotes(1, 2);

        self::assertSame('1,2,', $this->articleMapper->getVotes(1));
    }

    public function testgetVotesNoVotes()
    {
        self::assertSame('', $this->articleMapper->getVotes(1));
    }

    public function testdelete()
    {
        self::assertSame(1, $this->articleMapper->delete(1));

        $article = $this->articleMapper->getArticleByIdLocale(1);
        self::assertNull($article);
    }

    /**
     * Returns database schema sql statements to initialize database
     *
     * @return string
     */
    protected static function getSchemaSQLQueries()
    {
        $config = new ModuleConfig();
        $configUser = new UserConfig();
        $configAdmin = new AdminConfig();

        return $configAdmin->getInstallSql() . $configUser->getInstallSql() . $config->getInstallSql();
    }
}
