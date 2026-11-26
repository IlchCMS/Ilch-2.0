<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Search;

use PHPUnit\Ilch\DatabaseTestCase;
use Modules\Search\Config\Config as SearchModuleConfig;

/**
 * Base class for search tests that need the shared search schema plus the
 * "fixturemod" fixture module on disk.
 *
 * The fixture module mirrors the indexer convention exactly:
 *
 *   application/modules/fixturemod/indexers/Fixturemod.php
 *   (namespace Modules\Fixturemod\Indexers, class Fixturemod)
 *
 * It is created in setUp() and removed in tearDown(), and the per-request
 * discovery cache of \Ilch\Search\Indexers is reset around every test, so
 * discovery is re-run from disk for every single test method.
 */
abstract class SearchFixtureTestCase extends DatabaseTestCase
{
    public const FIXTURE_MODULE_KEY = 'fixturemod';
    public const FIXTURE_MODULE_DIR = APPLICATION_PATH . '/modules/fixturemod';

    /**
     * Schema for the search tests: the search tables (queue, documents,
     * progress), the FULLTEXT index the engine uses, and the admin module's
     * logs table — \Ilch\Search\Queue writes failures there, mirroring
     * production where the admin module (a system module) is always
     * installed.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new SearchModuleConfig();

        return $config->getInstallSql()
            . 'ALTER TABLE [prefix]_search_documents ADD FULLTEXT KEY ft_title_body (title, body);'
            . 'CREATE TABLE IF NOT EXISTS `[prefix]_logs` ('
            . ' `user_id` VARCHAR(255) NOT NULL,'
            . ' `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,'
            . ' `info` VARCHAR(255) NOT NULL,'
            . ' INDEX `logs_user_info_date` (`user_id`(50), `info`(50), `date`)'
            . ') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->resetIndexersCache();
        $this->installFixtureModule();
        $this->resetFixtureIndexerState();
    }

    protected function tearDown(): void
    {
        $this->removeFixtureModule();
        $this->resetIndexersCache();
        $this->resetFixtureIndexerState();

        parent::tearDown();
    }

    /**
     * Creates the fixture module directory with its convention indexer file.
     *
     * The file is (re)written on every test; once the class is loaded into
     * the process, the file is never parsed again (class_exists() short
     * circuits before the autoloader runs).
     *
     * @return void
     */
    private function installFixtureModule(): void
    {
        $indexerDir = self::FIXTURE_MODULE_DIR . '/indexers';
        if (!is_dir($indexerDir)) {
            mkdir($indexerDir, 0777, true);
        }
        file_put_contents($indexerDir . '/Fixturemod.php', self::fixtureIndexerSource());
    }

    /**
     * Removes the fixture module directory again (guarded: a failed setUp()
     * must not make the tearDown() fail as well).
     *
     * @return void
     */
    private function removeFixtureModule(): void
    {
        $file = self::FIXTURE_MODULE_DIR . '/indexers/Fixturemod.php';
        if (is_file($file)) {
            unlink($file);
        }
        $indexerDir = self::FIXTURE_MODULE_DIR . '/indexers';
        if (is_dir($indexerDir)) {
            rmdir($indexerDir);
        }
        if (is_dir(self::FIXTURE_MODULE_DIR)) {
            rmdir(self::FIXTURE_MODULE_DIR);
        }
    }

    /**
     * Resets the per-request discovery cache of \Ilch\Search\Indexers so
     * every test method sees the modules fresh.
     *
     * @return void
     */
    private function resetIndexersCache(): void
    {
        $prop = new \ReflectionProperty(Indexers::class, 'all');
        $prop->setValue(null, null);
    }

    /**
     * Resets the static call log / failure list of the fixture indexer
     * (only when the class is already loaded into this process).
     *
     * @return void
     */
    private function resetFixtureIndexerState(): void
    {
        if (class_exists('Modules\Fixturemod\Indexers\Fixturemod', false)) {
            \Modules\Fixturemod\Indexers\Fixturemod::$calls = [];
            \Modules\Fixturemod\Indexers\Fixturemod::$failOn = [];
        }
    }

    /**
     * PHP source of the fixture indexer, written to the fixture module dir
     * (autoloaded by \Ilch\Loader like any real module indexer).
     *
     * @return string
     */
    private static function fixtureIndexerSource(): string
    {
        return <<<'FIXTURE'
<?php

namespace Modules\Fixturemod\Indexers;

use Ilch\Database\Mysql;
use Ilch\Registry;
use Ilch\Search\Indexer;
use Ilch\Search\Store;

/**
 * Test fixture indexer for the "fixturemod" module.
 *
 * indexDoc() writes one document (docType 'entry') into the shared store;
 * doc ids listed in $failOn make indexDoc() throw (queue failure tests).
 * Unknown doc types drop a stale document. backfillBatch() has no source
 * table and returns the frontier unchanged.
 */
class Fixturemod implements Indexer
{
    public const MODULE = 'fixturemod';

    /** @var array<int, array<string, mixed>> */
    public static array $calls = [];

    /** @var int[] indexDoc() throws for these doc ids */
    public static array $failOn = [];

    private Mysql $db;

    public function __construct(?Mysql $db = null)
    {
        $this->db = $db ?? Registry::get('db');
    }

    public function indexDoc(string $docType, int $docId, string $locale = ''): void
    {
        self::$calls[] = ['op' => 'indexDoc', 'docType' => $docType, 'docId' => $docId, 'locale' => $locale];

        if (in_array($docId, self::$failOn, true)) {
            throw new \RuntimeException('Fixture index failure for doc id ' . $docId);
        }

        if ($docType !== 'entry') {
            // Unknown doc type: drop any stale document row.
            (new Store($this->db))->deleteDoc(self::MODULE, $docType, $docId, $locale);
            return;
        }

        (new Store($this->db))->upsert([
            'module'       => self::MODULE,
            'docType'      => 'entry',
            'docId'        => $docId,
            'locale'       => $locale,
            'parentId'     => null,
            'scopeKey'     => '',
            'scopeId'      => null,
            'title'        => 'Fixture entry ' . $docId,
            'body'         => 'Fixture body ' . $docId,
            'accessGroups' => '3',
            'dateActivity' => null,
            'meta'         => null,
            'contentHash'  => 'fixture-' . $docId,
        ]);
    }

    public function deleteDoc(string $docType, int $docId, string $locale = ''): void
    {
        self::$calls[] = ['op' => 'deleteDoc', 'docType' => $docType, 'docId' => $docId, 'locale' => $locale];
        (new Store($this->db))->deleteDoc(self::MODULE, $docType, $docId, $locale);
    }

    public function reindexScope(string $scopeKey, int $scopeId): void
    {
        self::$calls[] = ['op' => 'reindexScope', 'scopeKey' => $scopeKey, 'scopeId' => $scopeId];
    }

    public function deleteScope(string $scopeKey, int $scopeId): void
    {
        self::$calls[] = ['op' => 'deleteScope', 'scopeKey' => $scopeKey, 'scopeId' => $scopeId];
        (new Store($this->db))->deleteByScope(self::MODULE, $scopeKey, $scopeId);
    }

    public function backfillBatch(int $frontier, int $limit): int
    {
        return $frontier;
    }
}
FIXTURE;
    }
}
