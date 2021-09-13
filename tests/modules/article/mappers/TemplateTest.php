<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Article\Mappers;

use Ilch\Pagination;
use PHPUnit\Ilch\DatabaseTestCase;
use Modules\Article\Mappers\Template as TemplateMapper;
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
class TemplateTest extends DatabaseTestCase
{
    protected $phpunitDataset;
    private $templateMapper;

    public function setUp()
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/templates_table.yml');

        $this->templateMapper = new TemplateMapper();
    }

    public function testgetTemplates()
    {
        $templates = $this->templateMapper->getTemplates();

        self::assertCount(3, $templates);

        self::assertEquals(1, $templates[0]->getId());
        self::assertSame(1, $templates[0]->getAuthorId());
        self::assertSame('testtitle.html', $templates[0]->getPerma());
        self::assertSame('TestTitle', $templates[0]->getTitle());
        self::assertSame('TestTeaser', $templates[0]->getTeaser());
        self::assertSame('TestContent', $templates[0]->getContent());
        self::assertSame('TestDescription', $templates[0]->getDescription());
        self::assertSame('keyword1, keyword2', $templates[0]->getKeywords());
        self::assertSame('', $templates[0]->getLocale());
        self::assertSame('', $templates[0]->getImage());
        self::assertSame('', $templates[0]->getImageSource());

        self::assertEquals(2, $templates[1]->getId());
        self::assertSame(1, $templates[1]->getAuthorId());
        self::assertSame('testtitle2.html', $templates[1]->getPerma());
        self::assertSame('TestTitle2', $templates[1]->getTitle());
        self::assertSame('TestTeaser2', $templates[1]->getTeaser());
        self::assertSame('TestContent2', $templates[1]->getContent());
        self::assertSame('TestDescription2', $templates[1]->getDescription());
        self::assertSame('keyword1, keyword2', $templates[1]->getKeywords());
        self::assertSame('en_EN', $templates[1]->getLocale());
        self::assertSame('', $templates[1]->getImage());
        self::assertSame('', $templates[1]->getImageSource());

        self::assertEquals(3, $templates[2]->getId());
        self::assertSame(1, $templates[2]->getAuthorId());
        self::assertSame('testtitle3.html', $templates[2]->getPerma());
        self::assertSame('TestTitle3', $templates[2]->getTitle());
        self::assertSame('TestTeaser3', $templates[2]->getTeaser());
        self::assertSame('TestContent3', $templates[2]->getContent());
        self::assertSame('TestDescription3', $templates[2]->getDescription());
        self::assertSame('keyword1, keyword2', $templates[2]->getKeywords());
        self::assertSame('', $templates[2]->getLocale());
        self::assertSame('', $templates[2]->getImage());
        self::assertSame('', $templates[2]->getImageSource());
    }

    public function testgetTemplatesLocale()
    {
        $templates = $this->templateMapper->getTemplates('en_EN');

        self::assertCount(1, $templates);

        self::assertEquals(2, $templates[0]->getId());
        self::assertSame(1, $templates[0]->getAuthorId());
        self::assertSame('testtitle2.html', $templates[0]->getPerma());
        self::assertSame('TestTitle2', $templates[0]->getTitle());
        self::assertSame('TestTeaser2', $templates[0]->getTeaser());
        self::assertSame('TestContent2', $templates[0]->getContent());
        self::assertSame('TestDescription2', $templates[0]->getDescription());
        self::assertSame('keyword1, keyword2', $templates[0]->getKeywords());
        self::assertSame('en_EN', $templates[0]->getLocale());
        self::assertSame('', $templates[0]->getImage());
        self::assertSame('', $templates[0]->getImageSource());
    }

    public function testgetTemplatesEmptyString()
    {
        $templates = $this->templateMapper->getTemplates('');

        self::assertCount(2, $templates);

        self::assertEquals(1, $templates[0]->getId());
        self::assertSame(1, $templates[0]->getAuthorId());
        self::assertSame('testtitle.html', $templates[0]->getPerma());
        self::assertSame('TestTitle', $templates[0]->getTitle());
        self::assertSame('TestTeaser', $templates[0]->getTeaser());
        self::assertSame('TestContent', $templates[0]->getContent());
        self::assertSame('TestDescription', $templates[0]->getDescription());
        self::assertSame('keyword1, keyword2', $templates[0]->getKeywords());
        self::assertSame('', $templates[0]->getLocale());
        self::assertSame('', $templates[0]->getImage());
        self::assertSame('', $templates[0]->getImageSource());

        self::assertEquals(3, $templates[1]->getId());
        self::assertSame(1, $templates[1]->getAuthorId());
        self::assertSame('testtitle3.html', $templates[1]->getPerma());
        self::assertSame('TestTitle3', $templates[1]->getTitle());
        self::assertSame('TestTeaser3', $templates[1]->getTeaser());
        self::assertSame('TestContent3', $templates[1]->getContent());
        self::assertSame('TestDescription3', $templates[1]->getDescription());
        self::assertSame('keyword1, keyword2', $templates[1]->getKeywords());
        self::assertSame('', $templates[1]->getLocale());
        self::assertSame('', $templates[1]->getImage());
        self::assertSame('', $templates[1]->getImageSource());
    }

    public function testgetTemplatesPagination()
    {
        $pagination = new Pagination();

        // Without the pagination this would return 3 articles.
        $pagination->setRowsPerPage(2);
        $templates = $this->templateMapper->getTemplates(null, $pagination);

        self::assertCount(2, $templates);

        self::assertEquals(1, $templates[0]->getId());
        self::assertSame(1, $templates[0]->getAuthorId());
        self::assertSame('testtitle.html', $templates[0]->getPerma());
        self::assertSame('TestTitle', $templates[0]->getTitle());
        self::assertSame('TestTeaser', $templates[0]->getTeaser());
        self::assertSame('TestContent', $templates[0]->getContent());
        self::assertSame('TestDescription', $templates[0]->getDescription());
        self::assertSame('keyword1, keyword2', $templates[0]->getKeywords());
        self::assertSame('', $templates[0]->getLocale());
        self::assertSame('', $templates[0]->getImage());
        self::assertSame('', $templates[0]->getImageSource());

        self::assertEquals(2, $templates[1]->getId());
        self::assertSame(1, $templates[1]->getAuthorId());
        self::assertSame('testtitle2.html', $templates[1]->getPerma());
        self::assertSame('TestTitle2', $templates[1]->getTitle());
        self::assertSame('TestTeaser2', $templates[1]->getTeaser());
        self::assertSame('TestContent2', $templates[1]->getContent());
        self::assertSame('TestDescription2', $templates[1]->getDescription());
        self::assertSame('keyword1, keyword2', $templates[1]->getKeywords());
        self::assertSame('en_EN', $templates[1]->getLocale());
        self::assertSame('', $templates[1]->getImage());
        self::assertSame('', $templates[1]->getImageSource());
    }

    public function testgetTemplateById()
    {
        $template = $this->templateMapper->getTemplateById(1);

        self::assertEquals(1, $template->getId());
        self::assertSame(1, $template->getAuthorId());
        self::assertSame('testtitle.html', $template->getPerma());
        self::assertSame('TestTitle', $template->getTitle());
        self::assertSame('TestTeaser', $template->getTeaser());
        self::assertSame('TestContent', $template->getContent());
        self::assertSame('TestDescription', $template->getDescription());
        self::assertSame('keyword1, keyword2', $template->getKeywords());
        self::assertSame('', $template->getLocale());
        self::assertSame('', $template->getImage());
        self::assertSame('', $template->getImageSource());
    }

    public function testgetTemplateByIdNull()
    {
        $template = $this->templateMapper->getTemplateById(0);

        self::assertNull($template);
    }

    public function testsave()
    {
        $model = new ArticleModel();

        $model->setAuthorId(1)
            ->setDescription('TestDescription')
            ->setKeywords('keyword1, keyword2')
            ->setTitle('TestTitle')
            ->setTeaser('TestTeaser')
            ->setContent('TestContent')
            ->setPerma('testtitle.html')
            ->setLocale('')
            ->setImage('')
            ->setImageSource('');

        $articleId = $this->templateMapper->save($model);
        $template = $this->templateMapper->getTemplateById(4);

        self::assertEquals(4, $articleId);
        self::assertNotNull($template);

        self::assertEquals(4, $template->getId());
        self::assertSame(1, $template->getAuthorId());
        self::assertSame('testtitle.html', $template->getPerma());
        self::assertSame('TestTitle', $template->getTitle());
        self::assertSame('TestTeaser', $template->getTeaser());
        self::assertSame('TestContent', $template->getContent());
        self::assertSame('TestDescription', $template->getDescription());
        self::assertSame('keyword1, keyword2', $template->getKeywords());
        self::assertSame('', $template->getLocale());
        self::assertSame('', $template->getImage());
        self::assertSame('', $template->getImageSource());
    }

    public function testdelete()
    {
        $affectedRows = $this->templateMapper->delete(3);
        $template = $this->templateMapper->getTemplateById(3);

        self::assertEquals(1, $affectedRows);
        self::assertNull($template);
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
