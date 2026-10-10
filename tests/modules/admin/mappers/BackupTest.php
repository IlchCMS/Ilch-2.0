<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Admin\Config\Config as ModuleConfig;
use Modules\Admin\Mappers\Backup as BackupMapper;
use Modules\Admin\Models\Backup as BackupModel;

class BackupTest extends DatabaseTestCase
{
    /**
     * @var BackupMapper
     */
    protected Backup $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new BackupMapper();
    }

    /**
     * Tests that getBackups() returns all backups.
     */
    public function testGetBackups()
    {
        $backups = $this->out->getBackups();

        self::assertNotNull($backups);
        self::assertCount(2, $backups);
        self::assertInstanceOf(BackupModel::class, $backups[0]);
    }

    /**
     * Tests that getBackups() returns correct fields for the newest backup.
     */
    public function testGetBackupsFields()
    {
        $backups = $this->out->getBackups();

        // Ordered by id DESC.
        self::assertEquals(2, $backups[0]->getId());
        self::assertEquals('backup2', $backups[0]->getName());
        self::assertEquals('2024-01-02 11:00:00', $backups[0]->getDate());
    }

    /**
     * Tests that getBackups() returns correct fields for the second backup.
     */
    public function testGetBackupsSecond()
    {
        $backups = $this->out->getBackups();

        self::assertEquals(1, $backups[1]->getId());
        self::assertEquals('backup1', $backups[1]->getName());
        self::assertEquals('2024-01-01 10:00:00', $backups[1]->getDate());
    }

    /**
     * Tests that getBackups() returns null when no backups exist.
     */
    public function testGetBackupsEmpty()
    {
        // Delete all backups
        $this->out->delete(2);
        $this->out->delete(1);

        $backups = $this->out->getBackups();

        self::assertNull($backups);
    }

    /**
     * Tests that getBackupById() returns the correct backup.
     */
    public function testGetBackupById()
    {
        $backup = $this->out->getBackupById(1);

        self::assertNotNull($backup);
        self::assertEquals(1, $backup->getId());
        self::assertEquals('backup1', $backup->getName());
        self::assertEquals('2024-01-01 10:00:00', $backup->getDate());
    }

    /**
     * Tests that getBackupById() returns null for a non-existent id.
     */
    public function testGetBackupByIdNotFound()
    {
        $backup = $this->out->getBackupById(9999);

        self::assertNull($backup);
    }

    /**
     * Tests that getLastBackup() returns the newest backup.
     */
    public function testGetLastBackup()
    {
        $lastBackup = $this->out->getLastBackup();

        self::assertNotNull($lastBackup);
        self::assertEquals(2, $lastBackup->getId());
        self::assertEquals('backup2', $lastBackup->getName());
        self::assertEquals('2024-01-02 11:00:00', $lastBackup->getDate());
    }

    /**
     * Tests that getLastBackup() returns null when no backups exist.
     */
    public function testGetLastBackupEmpty()
    {
        // Delete all backups
        $this->out->delete(2);
        $this->out->delete(1);

        $lastBackup = $this->out->getLastBackup();

        self::assertNull($lastBackup);
    }

    /**
     * Tests inserting a new backup via save().
     */
    public function testSaveInsert()
    {
        $model = new BackupModel();
        $model->setName('newBackup')
            ->setDate('2024-02-01 08:00:00');

        $this->out->save($model);

        $backups = $this->out->getBackups();
        self::assertCount(3, $backups);

        // The new backup should be the first one (ordered by id DESC)
        $new = $backups[0];
        self::assertGreaterThan(2, $new->getId());
        self::assertEquals('newBackup', $new->getName());
        self::assertEquals('2024-02-01 08:00:00', $new->getDate());
    }

    /**
     * Tests that save() does not affect existing backups.
     */
    public function testSaveInsertDoesNotAffectOthers()
    {
        $model = new BackupModel();
        $model->setName('newBackup')
            ->setDate('2024-02-01 08:00:00');

        $this->out->save($model);

        $backup = $this->out->getBackupById(1);
        self::assertNotNull($backup);
        self::assertEquals('backup1', $backup->getName());
        self::assertEquals('2024-01-01 10:00:00', $backup->getDate());
    }

    /**
     * Tests that delete() removes a backup.
     */
    public function testDelete()
    {
        $this->out->delete(1);

        self::assertNull($this->out->getBackupById(1));

        // Remaining backup should still be present
        $backups = $this->out->getBackups();
        self::assertCount(1, $backups);
        self::assertEquals(2, $backups[0]->getId());
    }

    /**
     * Tests that delete() on a non-existent id does not throw.
     */
    public function testDeleteNotFound()
    {
        $this->out->delete(9999);

        // Existing backups should be unaffected
        $backups = $this->out->getBackups();
        self::assertCount(2, $backups);
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
