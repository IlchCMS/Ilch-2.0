<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Jobs\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Jobs\Config\Config as ModuleConfig;
use Modules\Jobs\Mappers\Jobs as JobsMapper;
use Modules\Jobs\Models\Jobs as JobsModel;

class JobsTest extends DatabaseTestCase
{
    /**
     * @var JobsMapper
     */
    protected Jobs $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new JobsMapper();
    }

    /**
     * Tests that getJobs() returns all entries from the seed data.
     */
    public function testGetJobs()
    {
        $jobs = $this->out->getJobs();

        self::assertNotNull($jobs);
        self::assertIsArray($jobs);
        self::assertCount(3, $jobs);
        self::assertInstanceOf(JobsModel::class, $jobs[0]);
    }

    /**
     * Tests that getJobs() returns correct fields for the first entry.
     */
    public function testGetJobsFields()
    {
        $jobs = $this->out->getJobs();

        self::assertEquals(1, $jobs[0]->getId());
        self::assertEquals('Web Developer', $jobs[0]->getTitle());
        self::assertEquals('Looking for an experienced web developer to join our team.', $jobs[0]->getText());
        self::assertEquals('hr@example.com', $jobs[0]->getEmail());
        self::assertTrue($jobs[0]->getShow());
    }

    /**
     * Tests that getJobs() returns correct fields for the second entry.
     */
    public function testGetJobsSecond()
    {
        $jobs = $this->out->getJobs();

        self::assertEquals(2, $jobs[1]->getId());
        self::assertEquals('Project Manager', $jobs[1]->getTitle());
        self::assertEquals('Experienced project manager needed for ongoing projects.', $jobs[1]->getText());
        self::assertEquals('jobs@example.com', $jobs[1]->getEmail());
        self::assertTrue($jobs[1]->getShow());
    }

    /**
     * Tests that getJobs() returns correct fields for the third entry (show = false).
     */
    public function testGetJobsThird()
    {
        $jobs = $this->out->getJobs();

        self::assertEquals(3, $jobs[2]->getId());
        self::assertEquals('UX Designer', $jobs[2]->getTitle());
        self::assertEquals('design@example.com', $jobs[2]->getEmail());
        self::assertFalse($jobs[2]->getShow());
    }

    /**
     * Tests that getJobs() returns null when no entries exist.
     */
    public function testGetJobsEmpty()
    {
        $this->out->delete(1);
        $this->out->delete(2);
        $this->out->delete(3);

        $jobs = $this->out->getJobs();

        self::assertNull($jobs);
    }

    /**
     * Tests that getJobs() filters by a where clause.
     */
    public function testGetJobsWithWhere()
    {
        $jobs = $this->out->getJobs(['show' => 1]);

        self::assertNotNull($jobs);
        self::assertCount(2, $jobs);
        self::assertEquals(1, $jobs[0]->getId());
        self::assertEquals(2, $jobs[1]->getId());
    }

    /**
     * Tests that getJobs() filters by show = 0.
     */
    public function testGetJobsWhereShowZero()
    {
        $jobs = $this->out->getJobs(['show' => 0]);

        self::assertNotNull($jobs);
        self::assertCount(1, $jobs);
        self::assertEquals(3, $jobs[0]->getId());
        self::assertEquals('UX Designer', $jobs[0]->getTitle());
    }

    /**
     * Tests that getJobs() returns null when no rows match the where clause.
     */
    public function testGetJobsWhereNoMatch()
    {
        $jobs = $this->out->getJobs(['id' => 9999]);

        self::assertNull($jobs);
    }

    /**
     * Tests that getEntriesBy() defaults to id DESC ordering.
     */
    public function testGetEntriesByDefaultOrderDesc()
    {
        $jobs = $this->out->getEntriesBy();

        self::assertNotNull($jobs);
        self::assertCount(3, $jobs);
        self::assertEquals(3, $jobs[0]->getId());
        self::assertEquals(2, $jobs[1]->getId());
        self::assertEquals(1, $jobs[2]->getId());
    }

    /**
     * Tests that getEntriesBy() applies custom ascending ordering.
     */
    public function testGetEntriesByOrderAsc()
    {
        $jobs = $this->out->getEntriesBy([], ['id' => 'ASC']);

        self::assertNotNull($jobs);
        self::assertCount(3, $jobs);
        self::assertEquals(1, $jobs[0]->getId());
        self::assertEquals(2, $jobs[1]->getId());
        self::assertEquals(3, $jobs[2]->getId());
    }

    /**
     * Tests that getEntriesBy() combines where and orderBy.
     */
    public function testGetEntriesByWhereAndOrder()
    {
        $jobs = $this->out->getEntriesBy(['show' => 1], ['id' => 'ASC']);

        self::assertNotNull($jobs);
        self::assertCount(2, $jobs);
        self::assertEquals(1, $jobs[0]->getId());
        self::assertEquals(2, $jobs[1]->getId());
    }

    /**
     * Tests that getJobsById() returns the correct entry.
     */
    public function testGetJobsById()
    {
        $job = $this->out->getJobsById(1);

        self::assertNotNull($job);
        self::assertEquals(1, $job->getId());
        self::assertEquals('Web Developer', $job->getTitle());
        self::assertEquals('Looking for an experienced web developer to join our team.', $job->getText());
        self::assertEquals('hr@example.com', $job->getEmail());
        self::assertTrue($job->getShow());
    }

    /**
     * Tests that getJobsById() returns an entry with show = false.
     */
    public function testGetJobsByIdShowFalse()
    {
        $job = $this->out->getJobsById(3);

        self::assertNotNull($job);
        self::assertEquals(3, $job->getId());
        self::assertEquals('UX Designer', $job->getTitle());
        self::assertFalse($job->getShow());
    }

    /**
     * Tests that getJobsById() returns null for a non-existent id.
     */
    public function testGetJobsByIdNotFound()
    {
        $job = $this->out->getJobsById(9999);

        self::assertNull($job);
    }

    /**
     * Tests that save() inserts a new entry when id is 0.
     */
    public function testSaveInsert()
    {
        $model = new JobsModel();
        $model->setId(0)
            ->setTitle('DevOps Engineer')
            ->setText('We need a DevOps engineer for cloud infrastructure.')
            ->setEmail('devops@example.com')
            ->setShow(true);

        $this->out->save($model);

        $jobs = $this->out->getJobs();
        self::assertCount(4, $jobs);

        $new = null;
        foreach ($jobs as $j) {
            if ($j->getId() > 3) {
                $new = $j;
                break;
            }
        }
        self::assertNotNull($new);
        self::assertGreaterThan(3, $new->getId());
        self::assertEquals('DevOps Engineer', $new->getTitle());
        self::assertEquals('We need a DevOps engineer for cloud infrastructure.', $new->getText());
        self::assertEquals('devops@example.com', $new->getEmail());
        self::assertTrue($new->getShow());
    }

    /**
     * Tests that save() inserts with show = false.
     */
    public function testSaveInsertShowFalse()
    {
        $model = new JobsModel();
        $model->setId(0)
            ->setTitle('Hidden Job')
            ->setText('Not publicly visible.')
            ->setEmail('hidden@example.com')
            ->setShow(false);

        $this->out->save($model);

        $visible = $this->out->getJobs(['show' => 1]);
        self::assertCount(2, $visible);

        $all = $this->out->getJobs();
        self::assertCount(4, $all);
    }

    /**
     * Tests that save() updates an existing entry when id is set.
     */
    public function testSaveUpdate()
    {
        $model = new JobsModel();
        $model->setId(1)
            ->setTitle('Senior Web Developer')
            ->setText('Updated job description.')
            ->setEmail('updated@example.com')
            ->setShow(false);

        $this->out->save($model);

        $job = $this->out->getJobsById(1);
        self::assertNotNull($job);
        self::assertEquals(1, $job->getId());
        self::assertEquals('Senior Web Developer', $job->getTitle());
        self::assertEquals('Updated job description.', $job->getText());
        self::assertEquals('updated@example.com', $job->getEmail());
        self::assertFalse($job->getShow());
    }

    /**
     * Tests that save() update does not affect other entries.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new JobsModel();
        $model->setId(1)
            ->setTitle('Changed')
            ->setText('Changed text')
            ->setEmail('changed@example.com')
            ->setShow(true);

        $this->out->save($model);

        $other = $this->out->getJobsById(2);
        self::assertNotNull($other);
        self::assertEquals('Project Manager', $other->getTitle());
        self::assertEquals('Experienced project manager needed for ongoing projects.', $other->getText());
        self::assertEquals('jobs@example.com', $other->getEmail());
        self::assertTrue($other->getShow());
    }

    /**
     * Tests that save() returns the new id on insert.
     */
    public function testSaveInsertReturnsId()
    {
        $model = new JobsModel();
        $model->setId(0)
            ->setTitle('New Role')
            ->setText('Brand new position.')
            ->setEmail('new@example.com')
            ->setShow(true);

        $newId = $this->out->save($model);

        self::assertGreaterThan(3, $newId);
        self::assertNotNull($this->out->getJobsById($newId));
    }

    /**
     * Tests that save() returns the id on update.
     */
    public function testSaveUpdateReturnsId()
    {
        $model = new JobsModel();
        $model->setId(2)
            ->setTitle('Updated PM')
            ->setText('Updated text.')
            ->setEmail('pm@example.com')
            ->setShow(true);

        $returnedId = $this->out->save($model);

        self::assertEquals(2, $returnedId);
    }

    /**
     * Tests that update() toggles show from 1 to 0.
     */
    public function testUpdateToggleShowOff()
    {
        // Entry 1 has show = 1
        $this->out->update(1);

        $job = $this->out->getJobsById(1);
        self::assertNotNull($job);
        self::assertFalse($job->getShow());
    }

    /**
     * Tests that update() toggles show from 0 to 1.
     */
    public function testUpdateToggleShowOn()
    {
        // Entry 3 has show = 0
        $this->out->update(3);

        $job = $this->out->getJobsById(3);
        self::assertNotNull($job);
        self::assertTrue($job->getShow());
    }

    /**
     * Tests that update() toggling twice restores the original value.
     */
    public function testUpdateToggleTwice()
    {
        // Entry 1 starts with show = 1
        $this->out->update(1);
        $this->out->update(1);

        $job = $this->out->getJobsById(1);
        self::assertNotNull($job);
        self::assertTrue($job->getShow());
    }

    /**
     * Tests that update() does not affect other fields.
     */
    public function testUpdateDoesNotChangeOtherFields()
    {
        $this->out->update(1);

        $job = $this->out->getJobsById(1);
        self::assertNotNull($job);
        self::assertEquals('Web Developer', $job->getTitle());
        self::assertEquals('Looking for an experienced web developer to join our team.', $job->getText());
        self::assertEquals('hr@example.com', $job->getEmail());
    }

    /**
     * Tests that delete() removes an entry.
     */
    public function testDelete()
    {
        $result = $this->out->delete(1);

        self::assertTrue($result);
        self::assertNull($this->out->getJobsById(1));

        $jobs = $this->out->getJobs();
        self::assertCount(2, $jobs);
        self::assertEquals(2, $jobs[0]->getId());
        self::assertEquals(3, $jobs[1]->getId());
    }

    /**
     * Tests that delete() on a non-existent id does not remove other entries.
     */
    public function testDeleteNotFound()
    {
        $this->out->delete(9999);

        $jobs = $this->out->getJobs();
        self::assertCount(3, $jobs);
    }

    /**
     * Tests that multiple deletes remove all entries.
     */
    public function testDeleteAll()
    {
        $this->out->delete(1);
        $this->out->delete(2);
        $this->out->delete(3);

        self::assertNull($this->out->getJobs());
    }

    /**
     * Tests that getJobs() with show filter returns only visible entries after toggle.
     */
    public function testGetJobsFilterAfterToggle()
    {
        // Toggle entry 1 from show=1 to show=0
        $this->out->update(1);

        $visible = $this->out->getJobs(['show' => 1]);
        self::assertCount(1, $visible);
        self::assertEquals(2, $visible[0]->getId());

        $hidden = $this->out->getJobs(['show' => 0]);
        self::assertCount(2, $hidden);
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();

        return $config->getInstallSql();
    }
}
