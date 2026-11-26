<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Search;

use Modules\Search\Indexers\Article;
use Modules\Search\Indexers\Forum;

// The base class lives in this test directory and is not autoloadable
// (same pattern as TestView in PaginationTest).
require_once __DIR__ . '/SearchFixtureTestCase.php';

/**
 * Tests for the two-layered indexer discovery (\Ilch\Search\Indexers):
 * the explicit MAP plus the <key>/indexers/<Key> convention scan.
 */
class IndexersTest extends SearchFixtureTestCase
{
    /**
     * Tests that the MAP indexers (forum/article) resolve through get().
     */
    public function testMapIndexersAreResolved()
    {
        self::assertInstanceOf(Forum::class, Indexers::get('forum'));
        self::assertInstanceOf(Article::class, Indexers::get('article'));
    }

    /**
     * Tests that the fixture convention module is discovered from disk.
     */
    public function testConventionFixtureIndexerIsResolved()
    {
        $indexer = Indexers::get(self::FIXTURE_MODULE_KEY);
        self::assertInstanceOf('Modules\Fixturemod\Indexers\Fixturemod', $indexer);
    }

    /**
     * Tests that a module without an indexer resolves to null.
     */
    public function testUnknownModuleHasNoIndexer()
    {
        self::assertNull(Indexers::get('doesnotexist'));
    }

    /**
     * Tests the phantom "search" guard: the convention requires the class
     * name to equal the module key, and Modules\Search\Indexers\Search does
     * not exist - so the search module's own indexers/ directory (Forum.php,
     * Article.php) can never register a bogus "search" module.
     */
    public function testSearchModuleCannotRegisterItself()
    {
        self::assertNull(Indexers::get('search'));
        self::assertArrayNotHasKey('search', Indexers::modules());
    }

    /**
     * Tests that modules() lists the MAP entries plus every convention
     * module found on disk (here: the fixture module).
     */
    public function testModulesListsMapAndConventionModules()
    {
        $modules = Indexers::modules();

        self::assertSame(Forum::class, $modules['forum']);
        self::assertSame(Article::class, $modules['article']);
        self::assertSame('Modules\Fixturemod\Indexers\Fixturemod', $modules[self::FIXTURE_MODULE_KEY]);
        self::assertCount(3, $modules);
    }

    /**
     * Tests that the convention resolver rejects keys that cannot name a
     * class (uppercase start, leading digit, dashes).
     */
    public function testConventionResolverRejectsInvalidKeys()
    {
        self::assertNull(Indexers::get('Fixturemod'));
        self::assertNull(Indexers::get('7bad'));
        self::assertNull(Indexers::get('bad-key'));
    }

    /**
     * Tests that a convention class that exists but does not implement
     * Indexer is ignored by both get() and modules().
     */
    public function testClassWithoutIndexerInterfaceIsIgnored()
    {
        eval('namespace Modules\Bogusmod\Indexers; class Bogusmod {}');
        $indexerDir = APPLICATION_PATH . '/modules/bogusmod/indexers';
        mkdir($indexerDir, 0777, true);
        file_put_contents($indexerDir . '/Bogusmod.php', "<?php\n// marker file, the class is defined via eval in the test\n");

        try {
            self::assertNull(Indexers::get('bogusmod'));
            self::assertArrayNotHasKey('bogusmod', Indexers::modules());
        } finally {
            // The base tearDown() resets the discovery cache again.
            unlink($indexerDir . '/Bogusmod.php');
            rmdir($indexerDir);
            rmdir(APPLICATION_PATH . '/modules/bogusmod');
        }
    }
}
