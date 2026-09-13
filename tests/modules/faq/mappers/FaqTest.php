<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Faq\Mappers;

use Modules\Admin\Config\Config as AdminConfig;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Faq\Config\Config as ModuleConfig;
use Modules\User\Config\Config as UserConfig;
use Modules\Faq\Models\Faq as FaqModel;

class FaqTest extends DatabaseTestCase
{
    /**
     * @var Faq
     */
    protected Faq $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new Faq();
    }

    /**
     * Tests that checkDB() returns true when the faqs table exists.
     */
    public function testCheckDB()
    {
        self::assertTrue($this->out->checkDB());
    }

    /**
     * Tests that getFaqs() with no group filter returns all faqs.
     */
    public function testGetFaqsAll()
    {
        $faqs = $this->out->getFaqs([], [], null);

        self::assertNotNull($faqs);
        self::assertCount(2, $faqs);
        self::assertInstanceOf(FaqModel::class, $faqs[0]);
    }

    /**
     * Tests that getFaqs() with default groupIds ('3'/guest) returns only
     * faqs whose category has read_access_all = 1.
     */
    public function testGetFaqsGuestDefault()
    {
        // Faq 1 is in Cat 1 (read_access_all=1), Faq 2 is in Cat 2 (no guest access).
        $faqs = $this->out->getFaqs();

        self::assertNotNull($faqs);
        self::assertCount(1, $faqs);
        self::assertEquals(1, $faqs[0]->getId());
        self::assertEquals('What is Ilch?', $faqs[0]->getQuestion());
    }

    /**
     * Tests that getFaqs() with groupIds '1' (admin) returns both faqs.
     */
    public function testGetFaqsAdminGroup()
    {
        $faqs = $this->out->getFaqs([], [], '1');

        self::assertNotNull($faqs);
        self::assertCount(2, $faqs);
    }

    /**
     * Tests that getFaqs() returns correct fields.
     */
    public function testGetFaqsFields()
    {
        $faqs = $this->out->getFaqs([], [], null);

        self::assertEquals(1, $faqs[0]->getId());
        self::assertEquals(1, $faqs[0]->getCatId());
        self::assertEquals('What is Ilch?', $faqs[0]->getQuestion());
        self::assertEquals('Ilch is a CMS.', $faqs[0]->getAnswer());

        self::assertEquals(2, $faqs[1]->getId());
        self::assertEquals(2, $faqs[1]->getCatId());
        self::assertEquals('How to install?', $faqs[1]->getQuestion());
        self::assertEquals('Follow the guide.', $faqs[1]->getAnswer());
    }

    /**
     * Tests that getFaqById() returns the correct faq.
     */
    public function testGetFaqById()
    {
        $faq = $this->out->getFaqById(1);

        self::assertNotNull($faq);
        self::assertEquals(1, $faq->getId());
        self::assertEquals(1, $faq->getCatId());
        self::assertEquals('What is Ilch?', $faq->getQuestion());
        self::assertEquals('Ilch is a CMS.', $faq->getAnswer());
    }

    /**
     * Tests that getFaqById() returns null for a non-existent id.
     */
    public function testGetFaqByIdNotFound()
    {
        $faq = $this->out->getFaqById(9999);

        self::assertNull($faq);
    }

    /**
     * Tests that getFaqsByCatId() returns faqs belonging to the given category.
     */
    public function testGetFaqsByCatId()
    {
        $faqs = $this->out->getFaqsByCatId(1);

        self::assertNotNull($faqs);
        self::assertCount(1, $faqs);
        self::assertEquals(1, $faqs[0]->getId());
        self::assertEquals('What is Ilch?', $faqs[0]->getQuestion());
    }

    /**
     * Tests that getFaqsByCatId() returns null when no faqs exist for the category.
     */
    public function testGetFaqsByCatIdEmpty()
    {
        $faqs = $this->out->getFaqsByCatId(9999);

        self::assertNull($faqs);
    }

    /**
     * Tests that search() finds a faq by question text.
     */
    public function testSearch()
    {
        $results = $this->out->search('Ilch', null);

        self::assertNotNull($results);
        self::assertCount(1, $results);
        self::assertEquals('What is Ilch?', $results[0]->getQuestion());
    }

    /**
     * Tests that search() with group filter excludes unauthorized faqs.
     */
    public function testSearchWithGroupFilter()
    {
        // 'install' only matches Faq 2 which is in Cat 2 (no guest access)
        $results = $this->out->search('install');

        self::assertNull($results);
    }

    /**
     * Tests that search() with admin group finds Faq 2.
     */
    public function testSearchWithAdminGroup()
    {
        $results = $this->out->search('install', '1');

        self::assertNotNull($results);
        self::assertCount(1, $results);
        self::assertEquals('How to install?', $results[0]->getQuestion());
    }

    /**
     * Tests that search() returns null when no match is found.
     */
    public function testSearchNoResults()
    {
        $results = $this->out->search('nonexistenttermxyz', null);

        self::assertNull($results);
    }

    /**
     * Tests inserting a new faq via save().
     */
    public function testSaveInsert()
    {
        $model = new FaqModel();
        $model->setId(0)
            ->setCatId(1)
            ->setQuestion('What license?')
            ->setAnswer('MIT.');

        $result = $this->out->save($model);

        self::assertGreaterThan(2, $result);

        $faqs = $this->out->getFaqs([], [], null);
        self::assertCount(3, $faqs);

        $new = null;
        foreach ($faqs as $faq) {
            if ($faq->getQuestion() === 'What license?') {
                $new = $faq;
                break;
            }
        }
        self::assertNotNull($new);
        self::assertEquals(1, $new->getCatId());
        self::assertEquals('MIT.', $new->getAnswer());
    }

    /**
     * Tests updating an existing faq via save().
     */
    public function testSaveUpdate()
    {
        $model = new FaqModel();
        $model->setId(1)
            ->setCatId(1)
            ->setQuestion('Updated question?')
            ->setAnswer('Updated answer.');

        $this->out->save($model);

        $faq = $this->out->getFaqById(1);
        self::assertNotNull($faq);
        self::assertEquals(1, $faq->getId());
        self::assertEquals('Updated question?', $faq->getQuestion());
        self::assertEquals('Updated answer.', $faq->getAnswer());
    }

    /**
     * Tests that update does not affect other faqs.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new FaqModel();
        $model->setId(1)
            ->setCatId(1)
            ->setQuestion('Changed')
            ->setAnswer('Changed answer');

        $this->out->save($model);

        $other = $this->out->getFaqById(2);
        self::assertNotNull($other);
        self::assertEquals('How to install?', $other->getQuestion());
        self::assertEquals('Follow the guide.', $other->getAnswer());
    }

    /**
     * Tests that delete() removes a faq.
     */
    public function testDelete()
    {
        $this->out->delete(1);

        self::assertNull($this->out->getFaqById(1));

        $faqs = $this->out->getFaqs([], [], null);
        self::assertCount(1, $faqs);
        self::assertEquals(2, $faqs[0]->getId());
    }

    /**
     * Tests that delete() on a non-existent id does not throw.
     */
    public function testDeleteNotFound()
    {
        $this->out->delete(9999);

        $faqs = $this->out->getFaqs([], [], null);
        self::assertCount(2, $faqs);
    }

    /**
     * Tests that getFaqs() with array groupIds works the same as string.
     */
    public function testGetFaqsWithArrayGroupIds()
    {
        $byString = $this->out->getFaqs([], [], '1');
        $byArray = $this->out->getFaqs([], [], [1]);

        self::assertCount(2, $byString);
        self::assertCount(2, $byArray);
    }

    /**
     * Tests that save() with id 0 performs an insert, not an update.
     */
    public function testSaveZeroIdInserts()
    {
        $before = $this->out->getFaqs([], [], null);
        self::assertCount(2, $before);

        $model = new FaqModel();
        $model->setId(0)
            ->setCatId(1)
            ->setQuestion('New entry?')
            ->setAnswer('New answer.');

        $this->out->save($model);

        $after = $this->out->getFaqs([], [], null);
        self::assertCount(3, $after);

        // Original faqs untouched
        self::assertEquals('What is Ilch?', $after[0]->getQuestion());
        self::assertEquals('How to install?', $after[1]->getQuestion());
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $userConfig = new UserConfig();
        $adminConfig = new AdminConfig();

        return $adminConfig->getInstallSql() . $userConfig->getInstallSql() . $config->getInstallSql();
    }
}
