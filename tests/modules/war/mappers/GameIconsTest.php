<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\War\Mappers;

use Modules\Admin\Config\Config as AdminConfig;
use Modules\User\Config\Config as UserConfig;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\War\Config\Config as ModuleConfig;
use Modules\War\Models\GameIcon as GameIconModel;

class GameIconsTest extends DatabaseTestCase
{
    /**
     * @var GameIcons
     */
    protected GameIcons $out;
    protected static array $fixtureFiles = [__DIR__ . '/../_files/mysql_database.yml'];

    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFiles(static::$fixtureFiles);
        $this->out = new GameIcons();
    }

    /**
     * Creates a game icon model with the given values.
     */
    private function createIcon(string $title, string $icon, int $id = 0): GameIconModel
    {
        $model = new GameIconModel();
        $model->setId($id)
            ->setTitle($title)
            ->setIcon($icon);

        return $model;
    }

    /**
     * Tests that getGameIcons() returns an empty array when the table is empty.
     */
    public function testGetGameIconsEmpty()
    {
        self::assertSame([], $this->out->getGameIcons());
    }

    /**
     * Tests that getGameIcons() returns all icons ordered by title ASC.
     */
    public function testGetGameIconsOrderedByTitle()
    {
        $this->out->save($this->createIcon('Warcraft', 'warcraft.png'));
        $this->out->save($this->createIcon('CS', 'cs.png'));
        $this->out->save($this->createIcon('Alpha', 'alpha.png'));

        $icons = $this->out->getGameIcons();

        self::assertCount(3, $icons);
        self::assertInstanceOf(GameIconModel::class, $icons[0]);

        self::assertSame('Alpha', $icons[0]->getTitle());
        self::assertSame('alpha.png', $icons[0]->getIcon());
        self::assertSame('CS', $icons[1]->getTitle());
        self::assertSame('Warcraft', $icons[2]->getTitle());
    }

    /**
     * Tests that getGameIconById() returns the matching icon.
     */
    public function testGetGameIconById()
    {
        $newId = $this->out->save($this->createIcon('CS', 'cs.png'));

        $icon = $this->out->getGameIconById($newId);

        self::assertNotNull($icon);
        self::assertSame($newId, $icon->getId());
        self::assertSame('CS', $icon->getTitle());
        self::assertSame('cs.png', $icon->getIcon());
    }

    /**
     * Tests that getGameIconById() returns null for a non-existent id.
     */
    public function testGetGameIconByIdNotFound()
    {
        self::assertNull($this->out->getGameIconById(9999));
    }

    /**
     * Tests that getGameIconMap() builds an id => [title, icon] lookup in title order.
     */
    public function testGetGameIconMap()
    {
        $csId = $this->out->save($this->createIcon('CS', 'cs.png'));
        $alphaId = $this->out->save($this->createIcon('Alpha', 'alpha.png'));

        $map = $this->out->getGameIconMap();

        self::assertCount(2, $map);

        // Order comes from getGameIcons() (title ASC): Alpha before CS.
        self::assertSame([$alphaId, $csId], array_keys($map));
        self::assertSame(['title' => 'Alpha', 'icon' => 'alpha.png'], $map[$alphaId]);
        self::assertSame(['title' => 'CS', 'icon' => 'cs.png'], $map[$csId]);
    }

    /**
     * Tests that getGameIconMap() returns an empty array when no icons exist.
     */
    public function testGetGameIconMapEmpty()
    {
        self::assertSame([], $this->out->getGameIconMap());
    }

    /**
     * Tests inserting a new icon via save() and the returned id.
     */
    public function testSaveInsert()
    {
        $newId = $this->out->save($this->createIcon('CS', 'cs.png'));

        self::assertIsInt($newId);
        self::assertGreaterThan(0, $newId);

        $icon = $this->out->getGameIconById($newId);
        self::assertNotNull($icon);
        self::assertSame('CS', $icon->getTitle());
        self::assertSame('cs.png', $icon->getIcon());
    }

    /**
     * Tests that save() with an existing id performs an update.
     */
    public function testSaveUpdate()
    {
        $newId = $this->out->save($this->createIcon('CS', 'cs.png'));
        $this->out->save($this->createIcon('Alpha', 'alpha.png'));

        $result = $this->out->save($this->createIcon('Counter-Strike', 'cs.png', $newId));

        self::assertSame($newId, $result);

        $icon = $this->out->getGameIconById($newId);
        self::assertNotNull($icon);
        self::assertSame('Counter-Strike', $icon->getTitle());

        // The other icon must be untouched.
        $titles = array_map(
            static fn (GameIconModel $icon): string => $icon->getTitle(),
            $this->out->getGameIcons()
        );
        self::assertContains('Alpha', $titles);
    }

    /**
     * Tests that delete() removes an icon.
     */
    public function testDelete()
    {
        $newId = $this->out->save($this->createIcon('CS', 'cs.png'));
        $this->out->save($this->createIcon('Alpha', 'alpha.png'));

        self::assertTrue($this->out->delete($newId));

        self::assertNull($this->out->getGameIconById($newId));
        self::assertCount(1, $this->out->getGameIcons());
    }

    /**
     * Tests that delete() on a non-existent id returns false and does not throw.
     */
    public function testDeleteNotFound()
    {
        self::assertFalse($this->out->delete(9999));

        self::assertSame([], $this->out->getGameIcons());
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
