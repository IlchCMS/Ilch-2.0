<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Mappers;

use Ilch\Pagination;
use Modules\Admin\Config\Config as AdminConfig;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\User\Config\Config as ModuleConfig;
use Modules\User\Mappers\Media as MediaMapper;
use Modules\User\Models\Media as MediaModel;

class MediaTest extends DatabaseTestCase
{
    /**
     * @var MediaMapper
     */
    protected MediaMapper $out;
    protected PhpunitDataset $phpunitDataset;
    protected Pagination $pagination;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database_users_media.yml');
        $this->out = new MediaMapper();
        $this->pagination = new Pagination();
    }

    /**
     * Tests that getMediaListByEnding() returns all media of a user, newest first.
     */
    public function testGetMediaListByEnding()
    {
        $media = $this->out->getMediaListByEnding(1, 'jpg png mp4', $this->pagination);

        self::assertNotNull($media);
        self::assertCount(3, $media);
        self::assertInstanceOf(MediaModel::class, $media[0]);

        $ids = [];
        foreach ($media as $entry) {
            $ids[] = $entry->getId();
        }
        self::assertSame([3, 2, 1], $ids);
    }

    /**
     * Tests that getMediaListByEnding() returns the correct fields.
     */
    public function testGetMediaListByEndingFields()
    {
        $media = $this->out->getMediaListByEnding(1, 'png', $this->pagination);

        self::assertNotNull($media);
        self::assertCount(1, $media);
        self::assertSame(2, $media[0]->getId());
        self::assertEquals('media2', $media[0]->getName());
        self::assertEquals('media2.png', $media[0]->getUrl());
        self::assertEquals('thumb_media2.png', $media[0]->getUrlThumb());
        self::assertEquals('png', $media[0]->getEnding());
        self::assertEquals('2014-01-01 12:12:12', $media[0]->getDatetime());
    }

    /**
     * Tests that getMediaListByEnding() only returns media of the given user.
     */
    public function testGetMediaListByEndingSecondUser()
    {
        $media = $this->out->getMediaListByEnding(2, 'jpg', $this->pagination);

        self::assertNotNull($media);
        self::assertCount(1, $media);
        self::assertSame(4, $media[0]->getId());
        self::assertEquals('media4', $media[0]->getName());
        self::assertEquals('media4.jpg', $media[0]->getUrl());
    }

    /**
     * Tests that getMediaListByEnding() returns null when no media match.
     */
    public function testGetMediaListByEndingNoMatch()
    {
        self::assertNull($this->out->getMediaListByEnding(1, 'avi', $this->pagination));
        self::assertNull($this->out->getMediaListByEnding(1, '', $this->pagination));
    }

    /**
     * Tests that getMediaListByEnding() works without pagination.
     */
    public function testGetMediaListByEndingWithoutPagination()
    {
        $media = $this->out->getMediaListByEnding(1, 'jpg png mp4');

        self::assertNotNull($media);
        self::assertCount(3, $media);

        $ids = [];
        foreach ($media as $entry) {
            $ids[] = $entry->getId();
        }
        self::assertSame([3, 2, 1], $ids);
    }

    /**
     * Tests that getMediaListByEnding() returns null on an empty table.
     */
    public function testGetMediaListByEndingEmptyTable()
    {
        foreach ([1, 2, 3, 4] as $id) {
            $this->out->delMediaById($id);
        }

        self::assertNull($this->out->getMediaListByEnding(1, 'jpg png mp4', $this->pagination));
    }

    /**
     * Tests that save() inserts a new media entry.
     */
    public function testSave()
    {
        $model = new MediaModel();
        $model->setUserId(2)
            ->setName('media5')
            ->setUrl('media5.gif')
            ->setUrlThumb('thumb_media5.gif')
            ->setEnding('gif')
            ->setDatetime('2014-01-01 12:16:12');

        $this->out->save($model);

        $media = $this->out->getMediaListByEnding(2, 'gif', $this->pagination);
        self::assertCount(1, $media);
        self::assertGreaterThan(4, $media[0]->getId());
        self::assertEquals('media5', $media[0]->getName());
        self::assertEquals('media5.gif', $media[0]->getUrl());
        self::assertEquals('thumb_media5.gif', $media[0]->getUrlThumb());
        self::assertEquals('gif', $media[0]->getEnding());
        self::assertEquals('2014-01-01 12:16:12', $media[0]->getDatetime());
    }

    /**
     * Tests that save() does not affect media of other users.
     */
    public function testSaveDoesNotAffectOtherUsers()
    {
        $model = new MediaModel();
        $model->setUserId(2)
            ->setName('media5')
            ->setUrl('media5.gif')
            ->setUrlThumb('thumb_media5.gif')
            ->setEnding('gif')
            ->setDatetime('2014-01-01 12:16:12');

        $this->out->save($model);

        $media = $this->out->getMediaListByEnding(1, 'jpg png mp4', $this->pagination);
        self::assertCount(3, $media);

        $ids = [];
        foreach ($media as $entry) {
            $ids[] = $entry->getId();
        }
        self::assertSame([3, 2, 1], $ids);
    }

    /**
     * Tests that getMediaListScroll() returns media with a lower id, newest first.
     */
    public function testGetMediaListScroll()
    {
        $media = $this->out->getMediaListScroll(3);

        self::assertNotNull($media);
        self::assertCount(2, $media);
        self::assertInstanceOf(MediaModel::class, $media[0]);
        self::assertSame(2, $media[0]->getId());
        self::assertSame(1, $media[1]->getId());
    }

    /**
     * Tests that getMediaListScroll() returns all media for a high last id.
     */
    public function testGetMediaListScrollAll()
    {
        $media = $this->out->getMediaListScroll(9999);

        self::assertNotNull($media);
        self::assertCount(4, $media);

        $ids = [];
        foreach ($media as $entry) {
            $ids[] = $entry->getId();
        }
        self::assertSame([4, 3, 2, 1], $ids);
    }

    /**
     * Tests that getMediaListScroll() returns null when no older media exist.
     */
    public function testGetMediaListScrollNoOlderMedia()
    {
        self::assertNull($this->out->getMediaListScroll(1));
    }

    /**
     * Tests that getMediaListScroll() without a last id returns the first page.
     */
    public function testGetMediaListScrollWithoutLastId()
    {
        $media = $this->out->getMediaListScroll();

        self::assertNotNull($media);
        self::assertCount(4, $media);

        $ids = [];
        foreach ($media as $entry) {
            $ids[] = $entry->getId();
        }
        self::assertSame([4, 3, 2, 1], $ids);
    }

    /**
     * Tests that getMediaListScroll() returns null on an empty table.
     */
    public function testGetMediaListScrollEmptyTable()
    {
        foreach ([1, 2, 3, 4] as $id) {
            $this->out->delMediaById($id);
        }

        self::assertNull($this->out->getMediaListScroll(9999));
    }

    /**
     * Tests that delMediaById() removes the media entry and related gallery images.
     */
    public function testDelMediaById()
    {
        $this->out->delMediaById(2);

        $media = $this->out->getMediaListByEnding(1, 'jpg png mp4', $this->pagination);
        self::assertCount(2, $media);

        $ids = [];
        foreach ($media as $entry) {
            $ids[] = $entry->getId();
        }
        self::assertSame([3, 1], $ids);

        self::assertNull(
            $this->db->select('*')
                ->from('users_gallery_imgs')
                ->where(['image_id' => 2])
                ->execute()
                ->fetchAssoc()
        );
    }

    /**
     * Tests that delMediaById() removes the stored files.
     */
    public function testDelMediaByIdRemovesFiles()
    {
        $url = sys_get_temp_dir() . '/' . uniqid('media_') . '.jpg';
        $urlThumb = sys_get_temp_dir() . '/' . uniqid('media_thumb_') . '.jpg';
        file_put_contents($url, 'test');
        file_put_contents($urlThumb, 'test');

        $model = new MediaModel();
        $model->setUserId(1)
            ->setName('tempmedia')
            ->setUrl($url)
            ->setUrlThumb($urlThumb)
            ->setEnding('jpg')
            ->setDatetime('2014-01-01 12:16:12');
        $this->out->save($model);

        $id = null;
        foreach ($this->out->getMediaListByEnding(1, 'jpg', $this->pagination) as $entry) {
            if ($entry->getUrl() === $url) {
                $id = $entry->getId();
            }
        }
        self::assertNotNull($id);

        $this->out->delMediaById($id);

        self::assertFalse(file_exists($url));
        self::assertFalse(file_exists($urlThumb));

        $media = $this->out->getMediaListByEnding(1, 'jpg', $this->pagination);
        self::assertCount(1, $media);
        self::assertSame(1, $media[0]->getId());
    }

    /**
     * Tests that delMediaById() on a non-existent id does not throw.
     */
    public function testDelMediaByIdNotFound()
    {
        $this->out->delMediaById(9999);

        $media = $this->out->getMediaListByEnding(1, 'jpg png mp4', $this->pagination);
        self::assertCount(3, $media);
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
