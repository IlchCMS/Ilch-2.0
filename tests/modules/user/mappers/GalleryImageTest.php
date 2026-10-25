<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Mappers;

use Modules\Admin\Config\Config as AdminConfig;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\User\Config\Config as ModuleConfig;
use Modules\User\Models\GalleryImage as GalleryImageModel;

class GalleryImageTest extends DatabaseTestCase
{
    /**
     * @var GalleryImage
     */
    protected GalleryImage $out;
    protected static array $fixtureFiles = [__DIR__ . '/../_files/mysql_database_users_gallery_imgs.yml'];

    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFiles(static::$fixtureFiles);
        $this->out = new GalleryImage();
    }

    /**
     * Tests that getImageById() returns the correct image.
     */
    public function testGetImageById()
    {
        $image = $this->out->getImageById(1);

        self::assertNotNull($image);
        self::assertInstanceOf(GalleryImageModel::class, $image);
        self::assertEquals(1, $image->getImageId());
        self::assertEquals('https://www.ilch.de/media1.jpg', $image->getImageUrl());
        self::assertEquals('Image 1', $image->getImageTitle());
        self::assertEquals('Description 1', $image->getImageDesc());
        self::assertEquals(1, $image->getCat());
        self::assertEquals(10, $image->getVisits());
        self::assertEquals(1, $image->getIsPublic());
    }

    /**
     * Tests that getImageById() returns null for a non-existent id.
     */
    public function testGetImageByIdNotFound()
    {
        self::assertNull($this->out->getImageById(9999));
    }

    /**
     * Tests that getImageById() works for an image whose media entry is missing.
     */
    public function testGetImageByIdWithMissingMedia()
    {
        $image = $this->out->getImageById(4);

        self::assertNotNull($image);
        self::assertEquals(4, $image->getId());
        self::assertEquals('Dangling image', $image->getImageTitle());
        self::assertEquals('References a media entry that does not exist', $image->getImageDesc());
        self::assertEquals(2, $image->getCat());
        self::assertEquals(3, $image->getVisits());

        // The gallery image still references the media id ...
        self::assertEquals(99, $image->getImageId());
        // ... but no url exists because of the LEFT JOIN.
        self::assertEquals(null, $image->getImageUrl());
    }

    /**
     * Tests that getImageById() sets the id of the underlying row (g.id),
     * so callers can identify, edit and delete it.
     */
    public function testGetImageByIdSetsId()
    {
        $image = $this->out->getImageById(1);

        self::assertNotNull($image);
        self::assertEquals(1, $image->getId());
    }

    /**
     * Tests that getImageByMediaId() returns the gallery image of the media entry.
     */
    public function testGetImageByMediaId()
    {
        $image = $this->out->getImageByMediaId(1);

        self::assertNotNull($image);
        self::assertInstanceOf(GalleryImageModel::class, $image);
        self::assertEquals(1, $image->getId());
        self::assertEquals(1, $image->getImageId());
        self::assertEquals(1, $image->getUserId());
        self::assertEquals('https://www.ilch.de/media1.jpg', $image->getImageUrl());
        self::assertEquals('https://www.ilch.de/thumb_media1.jpg', $image->getImageThumb());
        self::assertEquals('Image 1', $image->getImageTitle());
        self::assertEquals('Description 1', $image->getImageDesc());
        self::assertEquals(1, $image->getCat());
        self::assertEquals(10, $image->getVisits());
        self::assertEquals(1, $image->getIsPublic());
    }

    /**
     * Tests that getImageByMediaId() works for the second user.
     */
    public function testGetImageByMediaIdSecondUser()
    {
        $image = $this->out->getImageByMediaId(3);

        self::assertNotNull($image);
        self::assertEquals(3, $image->getId());
        self::assertEquals(3, $image->getImageId());
        self::assertEquals(2, $image->getUserId());
        self::assertEquals('Image 3', $image->getImageTitle());
        self::assertEquals(2, $image->getCat());
    }

    /**
     * Tests that getImageByMediaId() restricts the lookup to the given user.
     */
    public function testGetImageByMediaIdWithUserIdFilter()
    {
        $image = $this->out->getImageByMediaId(1, 1);

        self::assertNotNull($image);
        self::assertEquals(1, $image->getId());

        // The image of media 1 belongs to user 1, not to user 2.
        self::assertNull($this->out->getImageByMediaId(1, 2));
    }

    /**
     * Tests that getImageByMediaId() works for a dangling media reference.
     */
    public function testGetImageByMediaIdDangling()
    {
        $image = $this->out->getImageByMediaId(99);

        self::assertNotNull($image);
        self::assertEquals(4, $image->getId());
        self::assertEquals(99, $image->getImageId());
        self::assertEquals(2, $image->getUserId());
        self::assertEquals(null, $image->getImageUrl());
    }

    /**
     * Tests that getImageByMediaId() returns null when no gallery image
     * references the media entry.
     */
    public function testGetImageByMediaIdNotFound()
    {
        self::assertNull($this->out->getImageByMediaId(50));
        self::assertNull($this->out->getImageByMediaId(9999));
    }

    /**
     * Tests that getLastImageByGalleryId() returns the newest image of a category.
     */
    public function testGetLastImageByGalleryId()
    {
        $image = $this->out->getLastImageByGalleryId(1);

        self::assertNotNull($image);
        self::assertEquals(2, $image->getId());
        self::assertEquals(2, $image->getImageId());
        self::assertEquals('Image 2', $image->getImageTitle());
        self::assertEquals('Description 2', $image->getImageDesc());
        self::assertEquals('https://www.ilch.de/thumb_media2.jpg', $image->getImageThumb());
        self::assertEquals(1, $image->getCat());
        self::assertEquals(5, $image->getVisits());
        self::assertEquals(1, $image->getIsPublic());
    }

    /**
     * Tests that getLastImageByGalleryId() returns null for an empty category.
     */
    public function testGetLastImageByGalleryIdEmpty()
    {
        self::assertNull($this->out->getLastImageByGalleryId(9999));
    }

    /**
     * Tests that getLastImageByGalleryId() returns the newest image even when
     * its media entry is missing.
     */
    public function testGetLastImageByGalleryIdWithMissingMedia()
    {
        $image = $this->out->getLastImageByGalleryId(2);

        self::assertNotNull($image);
        self::assertEquals(4, $image->getId());
        self::assertEquals('Dangling image', $image->getImageTitle());
        self::assertEquals(99, $image->getImageId());
        self::assertEquals(2, $image->getCat());
        self::assertEquals(null, $image->getImageThumb());
    }

    /**
     * Tests that getLastImageByGalleryId() skips private images when $onlyPublic is true.
     */
    public function testGetLastImageByGalleryIdOnlyPublic()
    {
        // Add a private image as the newest entry of category 1.
        $model = new GalleryImageModel();
        $model->setUserId(1)
            ->setImageId('50')
            ->setCat(1)
            ->setIsPublic(0);
        $this->out->save($model);

        $last = $this->out->getLastImageByGalleryId(1);
        self::assertNotNull($last);
        self::assertGreaterThan(4, $last->getId());
        self::assertEquals(0, $last->getIsPublic());

        // With $onlyPublic the newest public image (id 2) must be returned.
        $lastPublic = $this->out->getLastImageByGalleryId(1, true);
        self::assertNotNull($lastPublic);
        self::assertEquals(2, $lastPublic->getId());
        self::assertEquals(1, $lastPublic->getIsPublic());
    }

    /**
     * Tests that getCountImageById() returns the number of images per category.
     */
    public function testGetCountImageById()
    {
        self::assertEquals(2, $this->out->getCountImageById(1));
        self::assertEquals(2, $this->out->getCountImageById(2));
        self::assertEquals(0, $this->out->getCountImageById(9999));
    }

    /**
     * Tests that getCountImageById() only counts public images when $onlyPublic is true.
     */
    public function testGetCountImageByIdOnlyPublic()
    {
        // Make image 1 private.
        $model = new GalleryImageModel();
        $model->setId(1)
            ->setImageTitle('Image 1')
            ->setImageDesc('Description 1')
            ->setIsPublic(0);
        $this->out->saveImageTreat($model);

        self::assertEquals(2, $this->out->getCountImageById(1));
        self::assertEquals(1, $this->out->getCountImageById(1, true));
    }

    /**
     * Tests that getImageByGalleryId() returns all images of a category in
     * descending id order.
     */
    public function testGetImageByGalleryId()
    {
        $images = $this->out->getImageByGalleryId(1);

        self::assertNotNull($images);
        self::assertCount(2, $images);
        self::assertInstanceOf(GalleryImageModel::class, $images[0]);

        self::assertEquals(2, $images[0]->getId());
        self::assertEquals(2, $images[0]->getImageId());
        self::assertEquals('https://www.ilch.de/media2.jpg', $images[0]->getImageUrl());
        self::assertEquals('https://www.ilch.de/thumb_media2.jpg', $images[0]->getImageThumb());
        self::assertEquals('Image 2', $images[0]->getImageTitle());
        self::assertEquals('Description 2', $images[0]->getImageDesc());
        self::assertEquals(5, $images[0]->getVisits());
        self::assertEquals(1, $images[0]->getCat());
        self::assertEquals(1, $images[0]->getIsPublic());

        self::assertEquals(1, $images[1]->getId());
        self::assertEquals(1, $images[1]->getImageId());
    }

    /**
     * Tests that getImageByGalleryId() returns null for an empty category.
     */
    public function testGetImageByGalleryIdEmpty()
    {
        self::assertNull($this->out->getImageByGalleryId(9999));
    }

    /**
     * Tests that getImageByGalleryId() returns the actual g.image_id for images
     * with a missing media entry, consistently with getImageById().
     * See testGetImageByIdWithMissingMedia() for comparison.
     */
    public function testGetImageByGalleryIdWithMissingMedia()
    {
        $images = $this->out->getImageByGalleryId(2);

        self::assertNotNull($images);
        self::assertCount(2, $images);

        // Ordered by id DESC: the dangling image (id 4) comes first.
        self::assertEquals(4, $images[0]->getId());
        self::assertEquals('Dangling image', $images[0]->getImageTitle());
        self::assertEquals(99, $images[0]->getImageId());
        self::assertEquals(null, $images[0]->getImageUrl());

        self::assertEquals(3, $images[1]->getId());
        self::assertEquals(3, $images[1]->getImageId());
    }

    /**
     * Tests that getImageByGalleryId() respects the pagination object.
     */
    public function testGetImageByGalleryIdWithPagination()
    {
        $pagination = new \Ilch\Pagination();

        $images = $this->out->getImageByGalleryId(2, $pagination);

        self::assertNotNull($images);
        // Cat 2 only contains 2 images, so the limit does not clip them.
        self::assertCount(2, $images);
        self::assertEquals(4, $images[0]->getId());
        self::assertEquals(3, $images[1]->getId());
        // The pagination object must report the total number of found rows.
        self::assertEquals(2, $pagination->getRows());
    }

    /**
     * Tests that getImageByGalleryId() only returns public images when $onlyPublic is true.
     */
    public function testGetImageByGalleryIdOnlyPublic()
    {
        // Make the newest image of category 1 private.
        $model = new GalleryImageModel();
        $model->setId(2)
            ->setImageTitle('Image 2')
            ->setImageDesc('Description 2')
            ->setIsPublic(0);
        $this->out->saveImageTreat($model);

        $images = $this->out->getImageByGalleryId(1, null, true);

        self::assertNotNull($images);
        self::assertCount(1, $images);
        self::assertEquals(1, $images[0]->getId());
        self::assertEquals(1, $images[0]->getIsPublic());
    }

    /**
     * Tests inserting a new gallery image via save() (id 0 => insert).
     */
    public function testSaveInsert()
    {
        // Media 1 is not part of category 3 in the fixture, so the insert is not
        // skipped by the (image_id, cat) duplicate check.
        $model = new GalleryImageModel();
        $model->setUserId(1)
            ->setImageId('1')
            ->setCat(3);

        $this->out->save($model);

        self::assertEquals(1, $this->out->getCountImageById(3));

        $images = $this->out->getImageByGalleryId(3);
        self::assertCount(1, $images);

        // The new image is the only one in the new category.
        $new = $images[0];
        self::assertGreaterThan(4, $new->getId());
        self::assertEquals(1, $new->getImageId());
        self::assertEquals('https://www.ilch.de/media1.jpg', $new->getImageUrl());
        self::assertEquals(3, $new->getCat());
        self::assertEquals(0, $new->getVisits());
        // save() does not persist title/description on insert.
        self::assertEquals('', $new->getImageTitle());
        self::assertEquals('', $new->getImageDesc());
        self::assertEquals(1, $new->getIsPublic());
    }

    /**
     * Tests that the original images are untouched after an insert.
     */
    public function testSaveInsertDoesNotAffectOthers()
    {
        $model = new GalleryImageModel();
        $model->setUserId(2)
            ->setImageId('3')
            ->setCat(3);

        $this->out->save($model);

        $image = $this->out->getImageById(1);
        self::assertNotNull($image);
        self::assertEquals(1, $image->getImageId());
        self::assertEquals('Image 1', $image->getImageTitle());
        self::assertEquals(10, $image->getVisits());
    }

    /**
     * Tests that save() stores the isPublic flag on insert.
     */
    public function testSaveInsertIsPublic()
    {
        $model = new GalleryImageModel();
        $model->setUserId(1)
            ->setImageId('1')
            ->setCat(9)
            ->setIsPublic(0);
        $this->out->save($model);

        $images = $this->out->getImageByGalleryId(9);
        self::assertCount(1, $images);
        self::assertEquals(0, $images[0]->getIsPublic());
    }

    /**
     * Tests updating an existing gallery image via save().
     */
    public function testSaveUpdate()
    {
        $model = new GalleryImageModel();
        $model->setId(1)
            ->setImageId('2')
            ->setCat(2);

        $this->out->save($model);

        $image = $this->out->getImageById(1);
        self::assertNotNull($image);
        self::assertEquals(2, $image->getImageId());
        self::assertEquals(2, $image->getCat());
        // save() only updates image_id and cat.
        self::assertEquals('Image 1', $image->getImageTitle());
        self::assertEquals(10, $image->getVisits());
    }

    /**
     * Tests that save() does not affect other images.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new GalleryImageModel();
        $model->setId(1)
            ->setImageId('2')
            ->setCat(2);

        $this->out->save($model);

        $other = $this->out->getImageById(2);
        self::assertNotNull($other);
        self::assertEquals(2, $other->getImageId());
        self::assertEquals(1, $other->getCat());
        self::assertEquals('Image 2', $other->getImageTitle());
    }

    /**
     * Tests that deleteById() removes an image.
     */
    public function testDeleteById()
    {
        $this->out->deleteById(1);

        self::assertNull($this->out->getImageById(1));
        self::assertEquals(1, $this->out->getCountImageById(1));

        $images = $this->out->getImageByGalleryId(1);
        self::assertCount(1, $images);
        self::assertEquals(2, $images[0]->getId());
    }

    /**
     * Tests that deleteById() on a non-existent id does not throw and leaves
     * the remaining images untouched.
     */
    public function testDeleteByIdNotFound()
    {
        $this->out->deleteById(9999);

        self::assertEquals(2, $this->out->getCountImageById(1));
        self::assertEquals(2, $this->out->getCountImageById(2));
    }

    /**
     * Tests that saveVisits() updates the visits of the image of a media entry.
     */
    public function testSaveVisits()
    {
        $model = new GalleryImageModel();
        $model->setImageId('1')
            ->setVisits(42);

        $this->out->saveVisits($model);

        $image = $this->out->getImageById(1);
        self::assertNotNull($image);
        self::assertEquals(42, $image->getVisits());
    }

    /**
     * Tests that saveVisits() stores a visits value of 0, so the visit count
     * can be reset.
     */
    public function testSaveVisitsZeroIsStored()
    {
        $model = new GalleryImageModel();
        $model->setImageId('1')
            ->setVisits(0);

        $this->out->saveVisits($model);

        $image = $this->out->getImageById(1);
        self::assertNotNull($image);
        self::assertEquals(0, $image->getVisits());
    }

    /**
     * Tests that saveVisits() updates ALL gallery images that reference the
     * same media entry (the update is done by image_id, not by row id).
     */
    public function testSaveVisitsUpdatesAllImagesWithSameMedia()
    {
        // Create a second gallery image that references the same media entry.
        $newModel = new GalleryImageModel();
        $newModel->setUserId(2)
            ->setImageId('1')
            ->setCat(3);
        $this->out->save($newModel);

        $model = new GalleryImageModel();
        $model->setImageId('1')
            ->setVisits(7);
        $this->out->saveVisits($model);

        $first = $this->out->getImageById(1);
        self::assertEquals(7, $first->getVisits());

        $secondImages = $this->out->getImageByGalleryId(3);
        self::assertCount(1, $secondImages);
        self::assertEquals(7, $secondImages[0]->getVisits());
    }

    /**
     * Tests that saveImageTreat() updates the title and description.
     */
    public function testSaveImageTreat()
    {
        $model = new GalleryImageModel();
        $model->setId(1)
            ->setImageTitle('Updated title')
            ->setImageDesc('Updated description');

        $this->out->saveImageTreat($model);

        $image = $this->out->getImageById(1);
        self::assertNotNull($image);
        self::assertEquals('Updated title', $image->getImageTitle());
        self::assertEquals('Updated description', $image->getImageDesc());
    }

    /**
     * Tests that saveImageTreat() does not affect other images.
     */
    public function testSaveImageTreatDoesNotAffectOthers()
    {
        $model = new GalleryImageModel();
        $model->setId(1)
            ->setImageTitle('Updated title')
            ->setImageDesc('Updated description');

        $this->out->saveImageTreat($model);

        $other = $this->out->getImageById(2);
        self::assertNotNull($other);
        self::assertEquals('Image 2', $other->getImageTitle());
        self::assertEquals('Description 2', $other->getImageDesc());
    }

    /**
     * Tests that deleteByImageId() removes the gallery image of the media entry
     * for the given user.
     */
    public function testDeleteByImageId()
    {
        $this->out->deleteByImageId(1, 1);

        self::assertNull($this->out->getImageById(1));

        $images = $this->out->getImageByGalleryId(1);
        self::assertCount(1, $images);
        self::assertEquals(2, $images[0]->getId());
    }

    /**
     * Tests that deleteByImageId() only deletes images that belong to the given user.
     */
    public function testDeleteByImageIdOtherUserUnaffected()
    {
        // Media 1's image belongs to user 1, so deleting as user 2 must do nothing.
        $this->out->deleteByImageId(1, 2);

        $image = $this->out->getImageById(1);
        self::assertNotNull($image);
        self::assertEquals(1, $image->getImageId());
        self::assertEquals(2, $this->out->getCountImageById(1));
    }

    /**
     * Tests that deleteByImageId() also removes images with a dangling media reference.
     */
    public function testDeleteByImageIdDangling()
    {
        $this->out->deleteByImageId(99, 2);

        self::assertNull($this->out->getImageById(4));

        $images = $this->out->getImageByGalleryId(2);
        self::assertCount(1, $images);
        self::assertEquals(3, $images[0]->getId());
    }

    /**
     * Tests that deleteByImageId() on a non-existent media entry does not throw
     * and leaves all images untouched.
     */
    public function testDeleteByImageIdNotFound()
    {
        $this->out->deleteByImageId(50, 1);

        self::assertEquals(2, $this->out->getCountImageById(1));
        self::assertEquals(2, $this->out->getCountImageById(2));
    }

    /**
     * Tests that saveImageTreat() updates the isPublic flag, including the falsy value 0.
     */
    public function testSaveImageTreatUpdatesIsPublic()
    {
        $model = new GalleryImageModel();
        $model->setId(1)
            ->setImageTitle('Image 1')
            ->setImageDesc('Description 1')
            ->setIsPublic(0);
        $this->out->saveImageTreat($model);

        $image = $this->out->getImageById(1);
        self::assertNotNull($image);
        self::assertEquals(0, $image->getIsPublic());
        self::assertEquals('Image 1', $image->getImageTitle());
    }

    /**
     * Returns database schema sql statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $configAdmin = new AdminConfig();

        return $configAdmin->getInstallSql() . $config->getInstallSql();
    }
}
