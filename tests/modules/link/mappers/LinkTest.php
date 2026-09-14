<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Link\Mappers;

use Modules\Admin\Config\Config as AdminConfig;
use Modules\User\Config\Config as UserConfig;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Link\Config\Config as ModuleConfig;
use Modules\Link\Mappers\Link as LinkMapper;
use Modules\Link\Models\Link as LinkModel;

class LinkTest extends DatabaseTestCase
{
    /**
     * @var LinkMapper
     */
    protected Link $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new LinkMapper();
    }

    /**
     * Tests that checkDB() returns true when the table exists.
     */
    public function testCheckDB()
    {
        self::assertTrue($this->out->checkDB());
    }

    /**
     * Tests that getLinks() returns all links.
     */
    public function testGetLinks()
    {
        $links = $this->out->getLinks();

        self::assertNotNull($links);
        self::assertCount(3, $links);
        self::assertInstanceOf(LinkModel::class, $links[0]);
    }

    /**
     * Tests that getLinks() returns correct fields for the first link.
     */
    public function testGetLinksFields()
    {
        $links = $this->out->getLinks();

        self::assertEquals(1, $links[0]->getId());
        self::assertEquals(1, $links[0]->getCatId());
        self::assertEquals(1, $links[0]->getPosition());
        self::assertEquals('ilch', $links[0]->getName());
        self::assertEquals('Content Management System', $links[0]->getDesc());
        self::assertEquals('https://www.ilch.de/include/images/linkus/468x60.png', $links[0]->getBanner());
        self::assertEquals('https://ilch.de', $links[0]->getLink());
        self::assertEquals(10, $links[0]->getHits());
    }

    /**
     * Tests that getLinks() returns correct fields for the second link.
     */
    public function testGetLinksSecond()
    {
        $links = $this->out->getLinks();

        self::assertEquals(2, $links[1]->getId());
        self::assertEquals(2, $links[1]->getCatId());
        self::assertEquals('PHP', $links[1]->getName());
        self::assertEquals('PHP resource', $links[1]->getDesc());
        self::assertEquals('https://php.net', $links[1]->getLink());
        self::assertEquals(5, $links[1]->getHits());
    }

    /**
     * Tests that getLinks() returns correct fields for the third link.
     */
    public function testGetLinksThird()
    {
        $links = $this->out->getLinks();

        self::assertEquals(3, $links[2]->getId());
        self::assertEquals(3, $links[2]->getCatId());
        self::assertEquals('Dribbble', $links[2]->getName());
        self::assertEquals('Design inspiration', $links[2]->getDesc());
        self::assertEquals('https://dribbble.com', $links[2]->getLink());
        self::assertEquals(3, $links[2]->getHits());
    }

    /**
     * Tests that getLinks() returns null when no links exist.
     */
    public function testGetLinksEmpty()
    {
        $this->out->delete(1);
        $this->out->delete(2);
        $this->out->delete(3);

        $links = $this->out->getLinks();

        self::assertNull($links);
    }

    /**
     * Tests that getLinks() filters by a where clause.
     */
    public function testGetLinksWithWhere()
    {
        $links = $this->out->getLinks(['l.cat_id' => 1]);

        self::assertNotNull($links);
        self::assertCount(1, $links);
        self::assertEquals(1, $links[0]->getId());
        self::assertEquals('ilch', $links[0]->getName());
    }

    /**
     * Tests that getLinks() returns null when no rows match the where clause.
     */
    public function testGetLinksWhereNoMatch()
    {
        $links = $this->out->getLinks(['l.id' => 9999]);

        self::assertNull($links);
    }

    /**
     * Tests that getLinkById() returns the correct link.
     */
    public function testGetLinkById()
    {
        $link = $this->out->getLinkById(1);

        self::assertNotNull($link);
        self::assertEquals(1, $link->getId());
        self::assertEquals('ilch', $link->getName());
        self::assertEquals('Content Management System', $link->getDesc());
        self::assertEquals('https://ilch.de', $link->getLink());
        self::assertEquals(1, $link->getCatId());
        self::assertEquals(10, $link->getHits());
    }

    /**
     * Tests that getLinkById() returns the second link.
     */
    public function testGetLinkByIdSecond()
    {
        $link = $this->out->getLinkById(2);

        self::assertNotNull($link);
        self::assertEquals(2, $link->getId());
        self::assertEquals('PHP', $link->getName());
        self::assertEquals(5, $link->getHits());
    }

    /**
     * Tests that getLinkById() returns null for a non-existent id.
     */
    public function testGetLinkByIdNotFound()
    {
        $link = $this->out->getLinkById(9999);

        self::assertNull($link);
    }

    /**
     * Tests that getLinksByCatId() returns links belonging to a category.
     */
    public function testGetLinksByCatId()
    {
        $links = $this->out->getLinksByCatId(1);

        self::assertNotNull($links);
        self::assertCount(1, $links);
        self::assertEquals(1, $links[0]->getId());
        self::assertEquals('ilch', $links[0]->getName());
    }

    /**
     * Tests that getLinksByCatId() returns links for the second category.
     */
    public function testGetLinksByCatIdSecond()
    {
        $links = $this->out->getLinksByCatId(2);

        self::assertNotNull($links);
        self::assertCount(1, $links);
        self::assertEquals(2, $links[0]->getId());
        self::assertEquals('PHP', $links[0]->getName());
    }

    /**
     * Tests that getLinksByCatId() returns null when no links exist for the category.
     */
    public function testGetLinksByCatIdEmpty()
    {
        $links = $this->out->getLinksByCatId(9999);

        self::assertNull($links);
    }

    /**
     * Tests that updatePositionById() updates the position.
     */
    public function testUpdatePositionById()
    {
        $result = $this->out->updatePositionById(1, 5);

        self::assertTrue($result);

        $link = $this->out->getLinkById(1);
        self::assertNotNull($link);
        self::assertEquals(5, $link->getPosition());
    }

    /**
     * Tests that updatePositionById() does not affect other links.
     */
    public function testUpdatePositionByIdDoesNotAffectOthers()
    {
        $this->out->updatePositionById(1, 5);

        $other = $this->out->getLinkById(2);
        self::assertNotNull($other);
        self::assertEquals(1, $other->getPosition());
    }

    /**
     * Tests that save() inserts a new link when id is 0.
     */
    public function testSaveInsert()
    {
        $model = new LinkModel();
        $model->setId(0)
            ->setName('GitHub')
            ->setDesc('Code hosting platform')
            ->setLink('https://github.com')
            ->setBanner('https://github.com/logo.png')
            ->setCatId(1)
            ->setPosition(2)
            ->setHits(0)
            ->setAccess('');

        $newId = $this->out->save($model);

        self::assertGreaterThan(3, $newId);

        $link = $this->out->getLinkById($newId);
        self::assertNotNull($link);
        self::assertEquals('GitHub', $link->getName());
        self::assertEquals('Code hosting platform', $link->getDesc());
        self::assertEquals('https://github.com', $link->getLink());
        self::assertEquals(1, $link->getCatId());
        self::assertEquals(2, $link->getPosition());
        self::assertEquals(0, $link->getHits());
    }

    /**
     * Tests that save() updates an existing link when id is set.
     */
    public function testSaveUpdate()
    {
        $model = new LinkModel();
        $model->setId(1)
            ->setName('Updated ilch')
            ->setDesc('Updated description')
            ->setLink('https://ilch.de')
            ->setBanner('https://www.ilch.de/include/images/linkus/468x60.png')
            ->setCatId(1)
            ->setPosition(1)
            ->setHits(10)
            ->setAccess('');

        $returnedId = $this->out->save($model);

        self::assertEquals(1, $returnedId);

        $link = $this->out->getLinkById(1);
        self::assertNotNull($link);
        self::assertEquals(1, $link->getId());
        self::assertEquals('Updated ilch', $link->getName());
        self::assertEquals('Updated description', $link->getDesc());
    }

    /**
     * Tests that save() update does not affect other links.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new LinkModel();
        $model->setId(1)
            ->setName('Changed')
            ->setDesc('Changed desc')
            ->setLink('https://changed.com')
            ->setBanner('https://changed.com/banner.png')
            ->setCatId(1)
            ->setPosition(1)
            ->setHits(10)
            ->setAccess('');

        $this->out->save($model);

        $other = $this->out->getLinkById(2);
        self::assertNotNull($other);
        self::assertEquals('PHP', $other->getName());
        self::assertEquals('PHP resource', $other->getDesc());
        self::assertEquals('https://php.net', $other->getLink());
    }

    /**
     * Tests that save() with id 0 performs an insert, not an update.
     */
    public function testSaveZeroIdInserts()
    {
        $before = $this->out->getLinks();
        self::assertCount(3, $before);

        $model = new LinkModel();
        $model->setId(0)
            ->setName('New Entry')
            ->setDesc('New desc')
            ->setLink('https://new.com')
            ->setBanner('https://new.com/banner.png')
            ->setCatId(1)
            ->setPosition(4)
            ->setHits(0)
            ->setAccess('');

        $this->out->save($model);

        $after = $this->out->getLinks();
        self::assertCount(4, $after);

        // Original links untouched
        self::assertEquals('ilch', $after[0]->getName());
        self::assertEquals('PHP', $after[1]->getName());
        self::assertEquals('Dribbble', $after[2]->getName());
    }

    /**
     * Tests that save() returns the id on update.
     */
    public function testSaveUpdateReturnsId()
    {
        $model = new LinkModel();
        $model->setId(2)
            ->setName('Updated PHP')
            ->setDesc('Updated')
            ->setLink('https://php.net')
            ->setBanner('https://php.net/banner.png')
            ->setCatId(2)
            ->setPosition(1)
            ->setHits(5)
            ->setAccess('');

        $returnedId = $this->out->save($model);

        self::assertEquals(2, $returnedId);
    }

    /**
     * Tests that delete() removes a link.
     */
    public function testDelete()
    {
        $result = $this->out->delete(1);

        self::assertTrue($result);
        self::assertNull($this->out->getLinkById(1));

        $links = $this->out->getLinks();
        self::assertCount(2, $links);
        self::assertEquals(2, $links[0]->getId());
        self::assertEquals(3, $links[1]->getId());
    }

    /**
     * Tests that delete() on a non-existent id does not remove other links.
     */
    public function testDeleteNotFound()
    {
        $this->out->delete(9999);

        $links = $this->out->getLinks();
        self::assertCount(3, $links);
    }

    /**
     * Tests that multiple deletes remove all links.
     */
    public function testDeleteAll()
    {
        $this->out->delete(1);
        $this->out->delete(2);
        $this->out->delete(3);

        self::assertNull($this->out->getLinks());
    }

    /**
     * Tests that getLinks() with a cat_id filter returns only matching links after adding a new one.
     */
    public function testGetLinksFilterAfterInsert()
    {
        $model = new LinkModel();
        $model->setId(0)
            ->setName('New Link')
            ->setDesc('New')
            ->setLink('https://new.com')
            ->setBanner('https://new.com/banner.png')
            ->setCatId(2)
            ->setPosition(2)
            ->setHits(0)
            ->setAccess('');

        $this->out->save($model);

        $links = $this->out->getLinksByCatId(2);
        self::assertCount(2, $links);

        // The original PHP link should still be there
        $foundOriginal = false;
        foreach ($links as $l) {
            if ($l->getId() == 2) {
                $foundOriginal = true;
                break;
            }
        }
        self::assertTrue($foundOriginal);
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
