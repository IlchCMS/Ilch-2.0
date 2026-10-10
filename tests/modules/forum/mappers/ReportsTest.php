<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Forum\Mappers;

use Modules\Admin\Config\Config as AdminConfig;
use Modules\User\Config\Config as UserConfig;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Forum\Config\Config as ModuleConfig;
use Modules\Forum\Mappers\Reports as ReportsMapper;
use Modules\Forum\Models\Report as ReportModel;

class ReportsTest extends DatabaseTestCase
{
    /**
     * @var ReportsMapper
     */
    protected Reports $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new ReportsMapper();
    }

    /**
     * Tests that getReports() returns all reports.
     */
    public function testGetReports()
    {
        $reports = $this->out->getReports();

        self::assertCount(2, $reports);
        self::assertInstanceOf(ReportModel::class, $reports[0]);
    }

    /**
     * Tests that getReportById() returns the correct report with joined fields.
     */
    public function testGetReportById()
    {
        $report = $this->out->getReportById(1);

        self::assertNotNull($report);
        self::assertEquals(1, $report->getId());
        self::assertEquals('2024-01-15 13:00:00', $report->getDate());
        self::assertEquals(1, $report->getPostId());
        self::assertEquals('1', $report->getReason());
        self::assertEquals('Spam link', $report->getDetails());
        self::assertEquals(1, $report->getUserId());
        self::assertEquals('Alice', $report->getUsername());
        self::assertEquals(2, $report->getForumId());
        self::assertEquals(1, $report->getTopicId());
    }

    /**
     * Tests that getReportById() returns null for a non-existent id.
     */
    public function testGetReportByIdNotFound()
    {
        self::assertNull($this->out->getReportById(9999));
    }

    /**
     * Tests that getReports() returns an empty array when no reports exist.
     */
    public function testGetReportsEmpty()
    {
        $this->out->deleteReport(1);
        $this->out->deleteReport(2);

        self::assertCount(0, $this->out->getReports());
    }

    /**
     * Tests adding a new report via addReport().
     */
    public function testAddReport()
    {
        $model = new ReportModel();
        $model->setPostId(1);
        $model->setReason('3');
        $model->setDetails('New report details');
        $model->setUserId(2);

        $this->out->addReport($model);

        $reports = $this->out->getReports();
        self::assertCount(3, $reports);

        $new = null;
        foreach ($reports as $report) {
            if ($report->getDetails() === 'New report details') {
                $new = $report;
            }
        }

        self::assertNotNull($new);
        self::assertEquals(1, $new->getPostId());
        self::assertEquals('3', $new->getReason());
        self::assertEquals(2, $new->getUserId());
        self::assertEquals('Bob', $new->getUsername());
        self::assertEquals(2, $new->getForumId());
        self::assertEquals(1, $new->getTopicId());
        self::assertNotEmpty($new->getDate());
    }

    /**
     * Tests that deleteReport() removes a report.
     */
    public function testDeleteReport()
    {
        $this->out->deleteReport(1);

        self::assertNull($this->out->getReportById(1));
        self::assertCount(1, $this->out->getReports());
    }

    /**
     * Tests that deleteReport() on a non-existent id does not throw.
     */
    public function testDeleteReportNotFound()
    {
        $this->out->deleteReport(9999);

        self::assertCount(2, $this->out->getReports());
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
