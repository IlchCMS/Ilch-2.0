<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Gallery\Mappers;

use Modules\Media\Config\Config as MediaConfig;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Gallery\Config\Config as ModuleConfig;
use Modules\Gallery\Mappers\Image as ImageMapper;
use Modules\Gallery\Models\Image as ImageModel;

class ImageTest extends DatabaseTestCase
{
    /**
     * @var ImageMapper
     */
    protected Image $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new ImageMapper();
    }

    /**
     * Tests that getImageByGalleryId() returns images for a given gallery.
     */
    public function testGetImageByGalleryId()
    {
        $images = $this->out->getImageByGalleryId(1);

        self::assertIsArray($images);
        self::assertCount(2, $images);
        self::assertInstanceOf(ImageModel::class, $images[0]);
    }

    /**
     * Tests that getImageByGalleryId() returns correct fields for the first image.
     */
    public function testGetImageByGalleryIdFields()
    {
        $images = $this->out->getImageByGalleryId(1);

        self::assertEquals(200, $images[0]->getImageId());
        self::assertEquals('Landscape', $images[0]->getImageTitle());
        self::assertEquals('Mountain view', $images[0]->getImageDesc());
        self::assertEquals(1, $images[0]->getGalleryId());
        self::assertEquals(10, $images[0]->getVisits());
    }

    /**
     * Tests that getImageByGalleryId() returns correct fields for the second image.
     */
    public function testGetImageByGalleryIdSecond()
    {
        $images = $this->out->getImageByGalleryId(1);

        self::assertEquals(100, $images[1]->getImageId());
        self::assertEquals('Sunset', $images[1]->getImageTitle());
        self::assertEquals('Beautiful sunset', $images[1]->getImageDesc());
        self::assertEquals(5, $images[1]->getVisits());
    }

    /**
     * Tests that getImageByGalleryId() returns images for gallery 2.
     */
    public function testGetImageByGalleryIdSecondGallery()
    {
        $images = $this->out->getImageByGalleryId(2);

        self::assertIsArray($images);
        self::assertCount(1, $images);
        self::assertEquals(300, $images[0]->getImageId());
        self::assertEquals('Portrait', $images[0]->getImageTitle());
    }

    /**
     * Tests that getImageByGalleryId() returns empty array for no images.
     */
    public function testGetImageByGalleryIdEmpty()
    {
        $images = $this->out->getImageByGalleryId(9999);

        self::assertIsArray($images);
        self::assertCount(0, $images);
    }

    /**
     * Tests that getImageByGalleryId() defaults to g.id DESC ordering.
     */
    public function testGetImageByGalleryIdOrderDesc()
    {
        $images = $this->out->getImageByGalleryId(1);

        self::assertEquals(2, $images[0]->getId());
        self::assertEquals(1, $images[1]->getId());
    }

    /**
     * Tests that getImageById() returns the correct image.
     */
    public function testGetImageById()
    {
        $image = $this->out->getImageById(1);

        self::assertNotNull($image);
        self::assertEquals(1, $image->getId());
        self::assertEquals(100, $image->getImageId());
        self::assertEquals('Sunset', $image->getImageTitle());
        self::assertEquals('Beautiful sunset', $image->getImageDesc());
        self::assertEquals(1, $image->getGalleryId());
        self::assertEquals(5, $image->getVisits());
    }

    /**
     * Tests that getImageById() returns a different image.
     */
    public function testGetImageByIdSecond()
    {
        $image = $this->out->getImageById(3);

        self::assertNotNull($image);
        self::assertEquals(3, $image->getId());
        self::assertEquals(300, $image->getImageId());
        self::assertEquals('Portrait', $image->getImageTitle());
    }

    /**
     * Tests that getImageById() returns null for a non-existent id.
     */
    public function testGetImageByIdNotFound()
    {
        $image = $this->out->getImageById(9999);

        self::assertNull($image);
    }

    /**
     * Tests that getLastImageByGalleryId() returns the last image (highest id) for a gallery.
     */
    public function testGetLastImageByGalleryId()
    {
        $image = $this->out->getLastImageByGalleryId(1);

        self::assertNotNull($image);
        self::assertEquals(2, $image->getId());
        self::assertEquals(200, $image->getImageId());
        self::assertEquals('Landscape', $image->getImageTitle());
    }

    /**
     * Tests that getLastImageByGalleryId() returns null for no images.
     */
    public function testGetLastImageByGalleryIdNotFound()
    {
        $image = $this->out->getLastImageByGalleryId(9999);

        self::assertNull($image);
    }

    /**
     * Tests that getCountImageById() returns the correct count.
     */
    public function testGetCountImageById()
    {
        $count = $this->out->getCountImageById(1);

        self::assertEquals(2, $count);
    }

    /**
     * Tests that getCountImageById() returns correct count for gallery 2.
     */
    public function testGetCountImageByIdSecond()
    {
        $count = $this->out->getCountImageById(2);

        self::assertEquals(1, $count);
    }

    /**
     * Tests that getCountImageById() returns 0 for no images.
     */
    public function testGetCountImageByIdZero()
    {
        $count = $this->out->getCountImageById(9999);

        self::assertEquals(0, $count);
    }

    /**
     * Tests that getEntriesBy() filters by where clause.
     */
    public function testGetEntriesByWithWhere()
    {
        $images = $this->out->getEntriesBy(['g.gallery_id' => 1]);

        self::assertNotNull($images);
        self::assertCount(2, $images);
    }

    /**
     * Tests that getEntriesBy() returns null when no rows match.
     */
    public function testGetEntriesByWhereNoMatch()
    {
        $images = $this->out->getEntriesBy(['g.id' => 9999]);

        self::assertNull($images);
    }

    /**
     * Tests that getEntriesBy() applies custom ordering.
     */
    public function testGetEntriesByOrderAsc()
    {
        $images = $this->out->getEntriesBy(['g.gallery_id' => 1], ['g.id' => 'ASC']);

        self::assertNotNull($images);
        self::assertCount(2, $images);
        self::assertEquals(1, $images[0]->getId());
        self::assertEquals(2, $images[1]->getId());
    }

    /**
     * Tests that save() inserts a new image when id is 0.
     */
    public function testSaveInsert()
    {
        $model = new ImageModel();
        $model->setId(0);
        $model->setImageId(400);
        $model->setGalleryId(1);

        $newId = $this->out->save($model);

        self::assertGreaterThan(3, $newId);

        $image = $this->out->getImageById($newId);
        self::assertNotNull($image);
        self::assertEquals(400, $image->getImageId());
        self::assertEquals(1, $image->getGalleryId());
    }

    /**
     * Tests that save() updates an existing image when id is set.
     */
    public function testSaveUpdate()
    {
        $model = new ImageModel();
        $model->setId(1);
        $model->setImageId(999);
        $model->setGalleryId(2);

        $returnedId = $this->out->save($model);

        self::assertEquals(1, $returnedId);

        $image = $this->out->getImageById(1);
        self::assertNotNull($image);
        self::assertEquals(999, $image->getImageId());
        self::assertEquals(2, $image->getGalleryId());
    }

    /**
     * Tests that save() update does not affect other images.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new ImageModel();
        $model->setId(1);
        $model->setImageId(999);
        $model->setGalleryId(2);

        $this->out->save($model);

        $other = $this->out->getImageById(2);
        self::assertNotNull($other);
        self::assertEquals(200, $other->getImageId());
        self::assertEquals(1, $other->getGalleryId());
    }

    /**
     * Tests that saveVisits() updates the visits count.
     */
    public function testSaveVisits()
    {
        $model = new ImageModel();
        $model->setVisits(42);
        $model->setImageId(100);

        $result = $this->out->saveVisits($model);

        self::assertTrue($result);

        $image = $this->out->getImageById(1);
        self::assertNotNull($image);
        self::assertEquals(42, $image->getVisits());
    }

    /**
     * Tests that saveVisits() returns false when visits is 0.
     */
    public function testSaveVisitsZero()
    {
        $model = new ImageModel();
        $model->setVisits(0);
        $model->setImageId(100);

        $result = $this->out->saveVisits($model);

        self::assertFalse($result);
    }

    /**
     * Tests that saveImageTreat() updates title and description.
     */
    public function testSaveImageTreat()
    {
        $model = new ImageModel();
        $model->setId(1);
        $model->setImageTitle('New Title');
        $model->setImageDesc('New Description');

        $result = $this->out->saveImageTreat($model);

        self::assertTrue($result);

        $image = $this->out->getImageById(1);
        self::assertNotNull($image);
        self::assertEquals('New Title', $image->getImageTitle());
        self::assertEquals('New Description', $image->getImageDesc());
    }

    /**
     * Tests that deleteById() removes an image.
     */
    public function testDeleteById()
    {
        $result = $this->out->deleteById(1);

        self::assertTrue($result);
        self::assertNull($this->out->getImageById(1));

        $images = $this->out->getImageByGalleryId(1);
        self::assertCount(1, $images);
    }

    /**
     * Tests that deleteById() on a non-existent id does not remove other images.
     */
    public function testDeleteByIdNotFound()
    {
        $this->out->deleteById(9999);

        $images = $this->out->getImageByGalleryId(1);
        self::assertCount(2, $images);
    }

    /**
     * Tests that getListOfValidIds() returns all image ids.
     */
    public function testGetListOfValidIds()
    {
        $ids = $this->out->getListOfValidIds();

        self::assertIsArray($ids);
        self::assertCount(3, $ids);
    }

    /**
     * Tests that getListOfValidIds() filters by where clause.
     */
    public function testGetListOfValidIdsWithWhere()
    {
        $ids = $this->out->getListOfValidIds(['gallery_id' => 1]);

        self::assertIsArray($ids);
        self::assertCount(2, $ids);
    }

    /**
     * Tests that getListOfValidIds() returns empty array for no matches.
     */
    public function testGetListOfValidIdsEmpty()
    {
        $ids = $this->out->getListOfValidIds(['gallery_id' => 9999]);

        self::assertIsArray($ids);
        self::assertCount(0, $ids);
    }

    /**
     * Tests that media URL fields are populated via the LEFT JOIN.
     */
    public function testImageUrlFromMediaJoin()
    {
        $image = $this->out->getImageById(1);

        self::assertNotNull($image);
        self::assertEquals('/media/sunset.jpg', $image->getImageUrl());
        self::assertEquals('/media/sunset_thumb.jpg', $image->getImageThumb());
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $mediaConfig = new MediaConfig();

        return $mediaConfig->getInstallSql() . $config->getInstallSql();
    }
}
