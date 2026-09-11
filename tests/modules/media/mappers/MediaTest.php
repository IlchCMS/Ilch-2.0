<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Media\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Media\Config\Config as ModuleConfig;
use Modules\Media\Mappers\Media as MediaMapper;
use Modules\Media\Models\Media as MediaModel;

class MediaTest extends DatabaseTestCase
{
    /**
     * @var MediaMapper
     */
    protected Media $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new MediaMapper();
    }

    /**
     * Tests that getMediaList() returns all media entries.
     */
    public function testGetMediaListAllEntries()
    {
        $list = $this->out->getMediaList();

        self::assertNotNull($list);
        self::assertCount(5, $list);
        self::assertInstanceOf(MediaModel::class, $list[0]);
    }

    /**
     * Tests that getMediaList() returns entries ordered by id DESC (default).
     */
    public function testGetMediaListOrderDesc()
    {
        $list = $this->out->getMediaList();

        self::assertEquals(5, $list[0]->getId());
        self::assertEquals(4, $list[1]->getId());
        self::assertEquals(3, $list[2]->getId());
        self::assertEquals(2, $list[3]->getId());
        self::assertEquals(1, $list[4]->getId());
    }

    /**
     * Tests that getMediaList() with sortOder ASC returns entries ordered by id ASC.
     */
    public function testGetMediaListOrderAsc()
    {
        $list = $this->out->getMediaList(null, 'ASC');

        self::assertEquals(1, $list[0]->getId());
        self::assertEquals(2, $list[1]->getId());
        self::assertEquals(5, $list[4]->getId());
    }

    /**
     * Tests that getMediaList() populates all model fields correctly.
     */
    public function testGetMediaListFields()
    {
        $list = $this->out->getMediaList();

        $entry = null;
        foreach ($list as $e) {
            if ($e->getId() === 1) {
                $entry = $e;
                break;
            }
        }

        self::assertNotNull($entry);
        self::assertEquals('landscape.jpg', $entry->getName());
        self::assertEquals('upload/landscape.jpg', $entry->getUrl());
        self::assertEquals('upload/landscape_thumb.jpg', $entry->getUrlThumb());
        self::assertEquals('jpg', $entry->getEnding());
        self::assertEquals('2024-01-15 10:30:00', $entry->getDatetime());
        self::assertEquals(1, $entry->getCatId());
        self::assertEquals('Images', $entry->getCatName());
    }

    /**
     * Tests that getMediaList() LEFT JOINs media_cats and sets catName to null
     * when the referenced category does not exist.
     */
    public function testGetMediaListMissingCategory()
    {
        $list = $this->out->getMediaList();

        $entry = null;
        foreach ($list as $e) {
            if ($e->getId() === 5) {
                $entry = $e;
                break;
            }
        }

        self::assertNotNull($entry);
        self::assertEquals(99, $entry->getCatId());
        self::assertNull($entry->getCatName());
    }

    /**
     * Tests that getMediaList() returns null when the table is empty.
     */
    public function testGetMediaListEmpty()
    {
        $this->db->delete('media')->execute();

        $list = $this->out->getMediaList();

        self::assertNull($list);
    }

    /**
     * Tests that getMediaListAll() returns all media entries in DESC order.
     */
    public function testGetMediaListAll()
    {
        $list = $this->out->getMediaListAll();

        self::assertNotNull($list);
        self::assertCount(5, $list);
        self::assertEquals(5, $list[0]->getId());
        self::assertEquals(1, $list[4]->getId());
    }

    /**
     * Tests that getMediaListAll() returns null when no rows exist.
     */
    public function testGetMediaListAllEmpty()
    {
        $this->db->delete('media')->execute();

        $list = $this->out->getMediaListAll();

        self::assertNull($list);
    }

    /**
     * Tests that getMediaListByEnding() filters by a single ending.
     */
    public function testGetMediaListByEndingSingle()
    {
        $list = $this->out->getMediaListByEnding('pdf');

        self::assertNotNull($list);
        self::assertCount(1, $list);
        self::assertEquals('report.pdf', $list[0]->getName());
        self::assertEquals('pdf', $list[0]->getEnding());
    }

    /**
     * Tests that getMediaListByEnding() filters by multiple space-separated endings.
     */
    public function testGetMediaListByEndingMultiple()
    {
        $list = $this->out->getMediaListByEnding('jpg png');

        self::assertNotNull($list);
        self::assertCount(4, $list);

        foreach ($list as $entry) {
            self::assertContains($entry->getEnding(), ['jpg', 'png']);
        }
    }

    /**
     * Tests that getMediaListByEnding() returns null when no rows match.
     */
    public function testGetMediaListByEndingNoMatch()
    {
        $list = $this->out->getMediaListByEnding('mp4');

        self::assertNull($list);
    }

    /**
     * Tests that getMediaListByEnding() respects the ASC sort order.
     */
    public function testGetMediaListByEndingOrderAsc()
    {
        $list = $this->out->getMediaListByEnding('jpg', null, 'ASC');

        self::assertNotNull($list);
        self::assertCount(2, $list);
        self::assertEquals(1, $list[0]->getId());
        self::assertEquals(5, $list[1]->getId());
    }

    /**
     * Tests that getMediaListScroll() returns null when lastId is null
     * (the mapper applies WHERE id < 0, matching no rows).
     */
    public function testGetMediaListScrollNoLastId()
    {
        $list = $this->out->getMediaListScroll();

        self::assertNull($list);
    }

    /**
     * Tests that getMediaListScroll() returns only entries with id < lastId.
     */
    public function testGetMediaListScrollWithLastId()
    {
        $list = $this->out->getMediaListScroll(3);

        self::assertNotNull($list);
        self::assertCount(2, $list); // id=1 and id=2
        self::assertEquals(2, $list[0]->getId());
        self::assertEquals(1, $list[1]->getId());
    }

    /**
     * Tests that getMediaListScroll() returns null when lastId is lower than all ids.
     */
    public function testGetMediaListScrollNoResults()
    {
        $list = $this->out->getMediaListScroll(0);

        self::assertNull($list);
    }

    /**
     * Tests that getCatList() returns all categories.
     */
    public function testGetCatList()
    {
        $cats = $this->out->getCatList();

        self::assertIsArray($cats);
        self::assertCount(3, $cats);
        self::assertInstanceOf(MediaModel::class, $cats[0]);
    }

    /**
     * Tests that getCatList() returns entries ordered by id DESC.
     */
    public function testGetCatListOrder()
    {
        $cats = $this->out->getCatList();

        self::assertEquals(3, $cats[0]->getId());
        self::assertEquals('Videos', $cats[0]->getCatName());
        self::assertEquals(1, $cats[2]->getId());
        self::assertEquals('Images', $cats[2]->getCatName());
    }

    /**
     * Tests that getCatList() returns an empty array when no categories exist.
     */
    public function testGetCatListEmpty()
    {
        $this->db->delete('media_cats')->execute();

        $cats = $this->out->getCatList();

        self::assertIsArray($cats);
        self::assertCount(0, $cats);
    }

    /**
     * Tests that getCatById() returns the correct category.
     */
    public function testGetCatById()
    {
        $cat = $this->out->getCatById(2);

        self::assertNotNull($cat);
        self::assertEquals(2, $cat->getId());
        self::assertEquals('Documents', $cat->getCatName());
    }

    /**
     * Tests that getCatById() returns null for a non-existent id.
     */
    public function testGetCatByIdNotFound()
    {
        $cat = $this->out->getCatById(9999);

        self::assertNull($cat);
    }

    /**
     * Tests that getCatByName() returns the correct category.
     */
    public function testGetCatByName()
    {
        $cat = $this->out->getCatByName('Images');

        self::assertNotNull($cat);
        self::assertEquals(1, $cat->getId());
        self::assertEquals('Images', $cat->getCatName());
    }

    /**
     * Tests that getCatByName() returns null for a non-existent name.
     */
    public function testGetCatByNameNotFound()
    {
        $cat = $this->out->getCatByName('NonExistentCategory');

        self::assertNull($cat);
    }

    /**
     * Tests that getByWhere() returns a single media entry matching the criteria.
     */
    public function testGetByWhere()
    {
        $media = $this->out->getByWhere(['id' => 3]);

        self::assertNotNull($media);
        self::assertEquals(3, $media->getId());
        self::assertEquals('report.pdf', $media->getName());
        self::assertEquals('pdf', $media->getEnding());
        self::assertEquals(2, $media->getCatId());
    }

    /**
     * Tests that getByWhere() filters by multiple columns.
     */
    public function testGetByWhereMultipleColumns()
    {
        $media = $this->out->getByWhere(['ending' => 'png', 'cat' => 1]);

        self::assertNotNull($media);
        self::assertEquals(2, $media->getId());
        self::assertEquals('logo.png', $media->getName());
    }

    /**
     * Tests that getByWhere() returns null when no row matches.
     */
    public function testGetByWhereNotFound()
    {
        $media = $this->out->getByWhere(['id' => 9999]);

        self::assertNull($media);
    }

    /**
     * Tests that save() inserts a new media entry when id is 0 (non-existent).
     */
    public function testSaveInsert()
    {
        $model = new MediaModel();
        $model->setId(0);
        $model->setUrl('upload/newfile.gif');
        $model->setUrlThumb('upload/newfile_thumb.gif');
        $model->setName('newfile.gif');
        $model->setDatetime('2024-07-01 12:00:00');
        $model->setEnding('gif');
        $model->setCatId(1);

        $this->out->save($model);

        $count = $this->db->select('id')->from('media')->execute()->fetchRows();
        self::assertCount(6, $count);

        $saved = $this->out->getByWhere(['name' => 'newfile.gif']);
        self::assertNotNull($saved);
        self::assertEquals('upload/newfile.gif', $saved->getUrl());
        self::assertEquals('gif', $saved->getEnding());
        self::assertEquals(1, $saved->getCatId());
    }

    /**
     * Tests that save() updates an existing media entry when id matches.
     *
     * Note: the mapper sets cat to '0' on update (by design).
     */
    public function testSaveUpdate()
    {
        $model = new MediaModel();
        $model->setId(1);
        $model->setUrl('upload/updated.jpg');
        $model->setUrlThumb('upload/updated_thumb.jpg');
        $model->setName('updated.jpg');
        $model->setDatetime('2024-08-15 18:30:00');
        $model->setEnding('jpg');
        $model->setCatId(1);

        $this->out->save($model);

        $saved = $this->out->getByWhere(['id' => 1]);
        self::assertNotNull($saved);
        self::assertEquals('upload/updated.jpg', $saved->getUrl());
        self::assertEquals('updated.jpg', $saved->getName());
        self::assertEquals('2024-08-15 18:30:00', $saved->getDatetime());
        self::assertEquals(0, (int)$saved->getCatId());
    }

    /**
     * Tests that save() with catId 0 on insert stores 0 as the category.
     */
    public function testSaveInsertWithZeroCat()
    {
        $model = new MediaModel();
        $model->setId(0);
        $model->setUrl('upload/uncat.png');
        $model->setUrlThumb('');
        $model->setName('uncat.png');
        $model->setDatetime('2024-09-01 08:00:00');
        $model->setEnding('png');
        $model->setCatId(0);

        $this->out->save($model);

        $saved = $this->out->getByWhere(['name' => 'uncat.png']);
        self::assertNotNull($saved);
        self::assertEquals(0, $saved->getCatId());
    }

    /**
     * Tests that delMediaById() removes the media row from the database.
     */
    public function testDelMediaById()
    {
        $this->out->delMediaById(1);

        self::assertNull($this->out->getByWhere(['id' => 1]));

        $remaining = $this->out->getMediaList();
        self::assertCount(4, $remaining);
    }

    /**
     * Tests that delMediaById() does not throw for a non-existent id
     * (file_exists returns false, DB delete matches 0 rows).
     */
    public function testDelMediaByIdNotFound()
    {
        // Should not throw
        $this->out->delMediaById(9999);

        // All 5 entries should remain
        $list = $this->out->getMediaList();
        self::assertCount(5, $list);
    }

    /**
     * Tests that saveCat() inserts a new category and returns its id.
     */
    public function testSaveCat()
    {
        $model = new MediaModel();
        $model->setCatName('Audio');

        $id = $this->out->saveCat($model);

        self::assertGreaterThan(3, $id);

        $cat = $this->out->getCatById($id);
        self::assertNotNull($cat);
        self::assertEquals('Audio', $cat->getCatName());
    }

    /**
     * Tests that delCatById() removes a category.
     */
    public function testDelCatById()
    {
        $this->out->delCatById(3);

        self::assertNull($this->out->getCatById(3));

        $cats = $this->out->getCatList();
        self::assertCount(2, $cats);
    }

    /**
     * Tests that setCat() updates the category assignment on a media entry.
     */
    public function testSetCat()
    {
        $model = new MediaModel();
        $model->setId(4);
        $model->setCatId(2);

        $this->out->setCat($model);

        $saved = $this->out->getByWhere(['id' => 4]);
        self::assertEquals(2, $saved->getCatId());
    }

    /**
     * Tests that treatCat() renames an existing category.
     */
    public function testTreatCat()
    {
        $model = new MediaModel();
        $model->setCatId(1);
        $model->setCatName('Pictures');

        $this->out->treatCat($model);

        $cat = $this->out->getCatById(1);
        self::assertEquals('Pictures', $cat->getCatName());
    }

    /**
     * Tests that treatCat() does not affect other categories.
     */
    public function testTreatCatPreservesOthers()
    {
        $model = new MediaModel();
        $model->setCatId(1);
        $model->setCatName('Pictures');

        $this->out->treatCat($model);

        $cat2 = $this->out->getCatById(2);
        self::assertEquals('Documents', $cat2->getCatName());
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
