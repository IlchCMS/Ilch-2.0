<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Downloads\Tests;

use Modules\Admin\Config\Config as AdminModuleConfig;
use Modules\Media\Config\Config as MediaModuleConfig;
use Modules\User\Config\Config as UserModuleConfig;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Downloads\Config\Config as ModuleConfig;
use Modules\Downloads\Mappers\File as FileMapper;
use Modules\Downloads\Models\File as FileModel;

class FileTest extends DatabaseTestCase
{
    /**
     * @var FileMapper
     */
    protected FileMapper $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new FileMapper();
    }

    /**
     * Tests that getFileById() returns the correct file.
     */
    public function testGetFileById()
    {
        $file = $this->out->getFileById(1);

        self::assertNotNull($file);
        self::assertEquals(1, $file->getId());
        self::assertEquals('10', $file->getFileId());
        self::assertEquals('Ubuntu.iso', $file->getFileTitle());
        self::assertEquals('Ubuntu 22.04 LTS', $file->getFileDesc());
        self::assertEquals('/media/ubuntu.iso', $file->getFileImage());
        self::assertEquals('2', $file->getItemId());
        self::assertEquals(5, $file->getVisits());
    }

    /**
     * Tests that getFileById() joins media table and populates fileUrl.
     */
    public function testGetFileByIdWithMedia()
    {
        $file = $this->out->getFileById(1);

        self::assertNotNull($file);
        self::assertEquals('/media/ubuntu.iso', $file->getFileUrl());
    }

    /**
     * Tests that getFileById() returns null for a non-existent id.
     */
    public function testGetFileByIdNotFound()
    {
        $file = $this->out->getFileById(9999);

        self::assertNull($file);
    }

    /**
     * Tests that getFileById() populates access from downloads_files_access.
     */
    public function testGetFileByIdAccess()
    {
        $file = $this->out->getFileById(2);

        self::assertNotNull($file);
        self::assertNotEquals('', $file->getAccess());

        $accessGroups = explode(',', $file->getAccess());
        self::assertContains('2', $accessGroups);
    }

    /**
     * Tests that getLastFileByItemId() returns the latest file by id DESC.
     */
    public function testGetLastFileByItemId()
    {
        $file = $this->out->getLastFileByItemId(2);

        self::assertNotNull($file);
        self::assertEquals('Fedora.iso', $file->getFileTitle());
        self::assertEquals(3, $file->getVisits());
    }

    /**
     * Tests that getLastFileByItemId() returns null when no files exist for the item.
     */
    public function testGetLastFileByItemIdNoResults()
    {
        $file = $this->out->getLastFileByItemId(9999);

        self::assertNull($file);
    }

    /**
     * Tests that getLastFileByItemId() populates fileUrl from media join.
     */
    public function testGetLastFileByItemIdWithMedia()
    {
        $file = $this->out->getLastFileByItemId(2);

        self::assertNotNull($file);
        self::assertEquals('/media/fedora.iso', $file->getFileUrl());
    }

    /**
     * Tests that getCountOfFilesByItemId() returns the correct count.
     */
    public function testGetCountOfFilesByItemId()
    {
        $count = $this->out->getCountOfFilesByItemId(2);

        self::assertEquals(2, $count);
    }

    /**
     * Tests that getCountOfFilesByItemId() returns 0 when no files exist.
     */
    public function testGetCountOfFilesByItemIdZero()
    {
        $count = $this->out->getCountOfFilesByItemId(9999);

        self::assertEquals(0, $count);
    }

    /**
     * Tests that getFilesByItemId() returns all files for the given item.
     */
    public function testGetFilesByItemId()
    {
        $files = $this->out->getFilesByItemId(2);

        self::assertIsArray($files);
        self::assertCount(2, $files);
        self::assertInstanceOf(FileModel::class, $files[0]);
    }

    /**
     * Tests that getFilesByItemId() returns files ordered by id DESC.
     */
    public function testGetFilesByItemIdOrderDesc()
    {
        $files = $this->out->getFilesByItemId(2);

        self::assertEquals(2, $files[0]->getId());
        self::assertEquals(1, $files[1]->getId());
    }

    /**
     * Tests that getFilesByItemId() populates file fields correctly.
     */
    public function testGetFilesByItemIdFields()
    {
        $files = $this->out->getFilesByItemId(2);

        $file = $files[0];
        self::assertEquals(2, $file->getId());
        self::assertEquals('Fedora.iso', $file->getFileTitle());
        self::assertEquals('Fedora 39', $file->getFileDesc());
        self::assertEquals(3, $file->getVisits());
        self::assertEquals('2', $file->getItemId());
    }

    /**
     * Tests that getFilesByItemId() populates fileUrl and fileThumb from media join.
     */
    public function testGetFilesByItemIdMediaFields()
    {
        $files = $this->out->getFilesByItemId(2);

        $file = $files[0];
        self::assertEquals('/media/fedora.iso', $file->getFileUrl());
        self::assertEquals('/media/fedora_thumb.png', $file->getFileThumb());
    }

    /**
     * Tests that getFilesByItemId() returns an empty array when no files exist.
     */
    public function testGetFilesByItemIdNoResults()
    {
        $files = $this->out->getFilesByItemId(9999);

        self::assertIsArray($files);
        self::assertCount(0, $files);
    }

    /**
     * Tests that save() inserts a new file when id is null.
     */
    public function testSaveInsert()
    {
        $model = new FileModel();
        $model->setFileId('13');
        $model->setFileTitle('NewFile.zip');
        $model->setItemId(1);
        $model->setFileDesc('A new file');
        $model->setFileImage('/media/newfile.zip');
        $model->setVisits(0);

        $this->out->save($model);

        $count = $this->out->getCountOfFilesByItemId(1);
        self::assertEquals(1, $count);
    }

    /**
     * Tests that save() updates an existing file when id is set.
     */
    public function testSaveUpdate()
    {
        $model = new FileModel();
        $model->setId(1);
        $model->setFileId('10');
        $model->setFileTitle('UpdatedName.iso');
        $model->setItemId(2);

        $this->out->save($model);

        $file = $this->out->getFileById(1);
        self::assertNotNull($file);
        self::assertEquals('UpdatedName.iso', $file->getFileTitle());
    }

    /**
     * Tests that save() update does not affect other files.
     */
    public function testSaveUpdatePreservesOthers()
    {
        $model = new FileModel();
        $model->setId(1);
        $model->setFileId('10');
        $model->setFileTitle('Changed');
        $model->setItemId(2);

        $this->out->save($model);

        $file2 = $this->out->getFileById(2);
        self::assertEquals('Fedora.iso', $file2->getFileTitle());
    }

    /**
     * Tests that deleteById() removes a file.
     */
    public function testDeleteById()
    {
        $this->out->deleteById(1);

        self::assertNull($this->out->getFileById(1));

        $count = $this->out->getCountOfFilesByItemId(2);
        self::assertEquals(1, $count);
    }

    /**
     * Tests that deleteById() does not throw for a non-existent id.
     */
    public function testDeleteByIdNotFound()
    {
        $this->out->deleteById(9999);

        $files = $this->out->getFilesByItemId(2);
        self::assertCount(2, $files);
    }

    /**
     * Tests that saveVisits() updates the visits count.
     */
    public function testSaveVisits()
    {
        $model = new FileModel();
        $model->setFileId('10');
        $model->setVisits(99);

        $this->out->saveVisits($model);

        $file = $this->out->getFileById(1);
        self::assertEquals(99, $file->getVisits());
    }

    /**
     * Tests that saveVisits() does nothing when visits is 0.
     */
    public function testSaveVisitsZero()
    {
        $model = new FileModel();
        $model->setFileId('10');
        $model->setVisits(0);

        $this->out->saveVisits($model);

        $file = $this->out->getFileById(1);
        self::assertEquals(5, $file->getVisits());
    }

    /**
     * Tests that saveFileTreat() updates file metadata.
     */
    public function testSaveFileTreat()
    {
        $model = new FileModel();
        $model->setId(1);
        $model->setFileTitle('Renamed.iso');
        $model->setFileImage('/media/renamed.iso');
        $model->setFileDesc('Renamed description');

        $this->out->saveFileTreat($model);

        $file = $this->out->getFileById(1);
        self::assertEquals('Renamed.iso', $file->getFileTitle());
        self::assertEquals('/media/renamed.iso', $file->getFileImage());
        self::assertEquals('Renamed description', $file->getFileDesc());
    }

    /**
     * Tests that saveFileTreat() updates file access rights.
     */
    public function testSaveFileTreatAccess()
    {
        $model = new FileModel();
        $model->setId(1);
        $model->setFileTitle('Ubuntu.iso');
        $model->setFileImage('/media/ubuntu.iso');
        $model->setFileDesc('Ubuntu 22.04 LTS');
        $model->setAccess('2,3');

        $this->out->saveFileTreat($model);

        $file = $this->out->getFileById(1);
        self::assertNotEquals('', $file->getAccess());

        $accessGroups = explode(',', $file->getAccess());
        self::assertContains('2', $accessGroups);
        self::assertContains('3', $accessGroups);
        self::assertNotContains('1', $accessGroups);
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $userModuleConfig = new UserModuleConfig();
        $mediaModuleConfig = new MediaModuleConfig();
        $adminModuleConfig = new AdminModuleConfig();

        return $adminModuleConfig->getInstallSql() . $userModuleConfig->getInstallSql() . $mediaModuleConfig->getInstallSql() . $config->getInstallSql();
    }
}
