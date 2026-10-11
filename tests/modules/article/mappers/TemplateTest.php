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
    protected PhpunitDataset $phpunitDataset;
    private Template $templateMapper;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/templates_table.yml');

        $this->templateMapper = new TemplateMapper();
    }

    public function testGetTemplates()
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

    public function testGetTemplatesLocale()
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

    public function testGetTemplatesEmptyString()
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

    public function testGetTemplatesPagination()
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

    public function testGetTemplateById()
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

    public function testGetTemplateByIdNull()
    {
        $template = $this->templateMapper->getTemplateById(0);

        self::assertNull($template);
    }

    public function testSave()
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

    public function testDelete()
    {
        $affectedRows = $this->templateMapper->delete(3);
        $template = $this->templateMapper->getTemplateById(3);

        self::assertEquals(1, $affectedRows);
        self::assertNull($template);
    }

    /**
     * Tests that getTemplates() returns an empty array (not null) when no templates exist.
     */
    public function testGetTemplatesEmpty()
    {
        $this->templateMapper->delete(1);
        $this->templateMapper->delete(2);
        $this->templateMapper->delete(3);

        $templates = $this->templateMapper->getTemplates();

        self::assertIsArray($templates);
        self::assertCount(0, $templates);
    }

    /**
     * Tests that getTemplates() returns an empty array for a locale without matches.
     */
    public function testGetTemplatesLocaleNoMatch()
    {
        $templates = $this->templateMapper->getTemplates('fr_FR');

        self::assertIsArray($templates);
        self::assertCount(0, $templates);
    }

    /**
     * Tests that getTemplates() combines locale filter and pagination.
     */
    public function testGetTemplatesLocaleWithPagination()
    {
        $pagination = new Pagination();
        $pagination->setRowsPerPage(1);

        $templates = $this->templateMapper->getTemplates('en_EN', $pagination);

        self::assertCount(1, $templates);
        self::assertSame('en_EN', $templates[0]->getLocale());
        self::assertSame('TestTitle2', $templates[0]->getTitle());
    }

    /**
     * Tests that getTemplateById() returns null for a non-existent id.
     */
    public function testGetTemplateByIdNotFound()
    {
        self::assertNull($this->templateMapper->getTemplateById(9999));
    }

    /**
     * Tests updating an existing template via save().
     */
    public function testSaveUpdate()
    {
        $model = new ArticleModel();
        $model->setId(1)
            ->setAuthorId(1)
            ->setTitle('UpdatedTitle')
            ->setTeaser('UpdatedTeaser')
            ->setContent('UpdatedContent')
            ->setDescription('UpdatedDescription')
            ->setKeywords('updated, keywords')
            ->setPerma('updatedtitle.html')
            ->setLocale('')
            ->setImage('')
            ->setImageSource('');

        $id = $this->templateMapper->save($model);

        self::assertSame(1, $id);

        $template = $this->templateMapper->getTemplateById(1);
        self::assertSame('UpdatedTitle', $template->getTitle());
        self::assertSame('UpdatedTeaser', $template->getTeaser());
        self::assertSame('UpdatedContent', $template->getContent());
        self::assertSame('UpdatedDescription', $template->getDescription());
        self::assertSame('updated, keywords', $template->getKeywords());
        self::assertSame('updatedtitle.html', $template->getPerma());
        self::assertSame('', $template->getLocale());
    }

    /**
     * Tests that updating one template does not affect the others.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new ArticleModel();
        $model->setId(1)
            ->setAuthorId(1)
            ->setTitle('ChangedTitle')
            ->setTeaser('TestTeaser')
            ->setContent('TestContent')
            ->setDescription('TestDescription')
            ->setKeywords('keyword1, keyword2')
            ->setPerma('testtitle.html')
            ->setLocale('')
            ->setImage('')
            ->setImageSource('');

        $this->templateMapper->save($model);

        $other = $this->templateMapper->getTemplateById(2);
        self::assertSame('TestTitle2', $other->getTitle());
        self::assertSame('testtitle2.html', $other->getPerma());
    }

    /**
     * Tests that save() with a non-existent id inserts a new row
     * (the model's id is ignored, auto-increment id is returned).
     */
    public function testSaveWithNonExistentIdInserts()
    {
        $model = new ArticleModel();
        $model->setId(9999)
            ->setAuthorId(1)
            ->setTitle('GhostTitle')
            ->setTeaser('GhostTeaser')
            ->setContent('GhostContent')
            ->setDescription('GhostDescription')
            ->setKeywords('ghost, keywords')
            ->setPerma('ghosttitle.html')
            ->setLocale('')
            ->setImage('')
            ->setImageSource('');

        $id = $this->templateMapper->save($model);

        self::assertSame(4, $id);
        self::assertNull($this->templateMapper->getTemplateById(9999));

        $template = $this->templateMapper->getTemplateById(4);
        self::assertSame('GhostTitle', $template->getTitle());
        self::assertCount(4, $this->templateMapper->getTemplates());
    }

    /**
     * Tests that delete() on a non-existent id returns 0 and keeps existing rows.
     */
    public function testDeleteNotFound()
    {
        $affectedRows = $this->templateMapper->delete(9999);

        self::assertSame(0, $affectedRows);
        self::assertCount(3, $this->templateMapper->getTemplates());
    }

    /**
     * Returns database schema SQL statements to initialize database
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $configUser = new UserConfig();
        $configAdmin = new AdminConfig();

        return $configAdmin->getInstallSql() . $configUser->getInstallSql() . $config->getInstallSql();
    }
}
