<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Search;

/**
 * Registry of available search indexers.
 *
 * Discovery is two-layered:
 * - MAP: explicit entries for modules whose indexer does not live in its
 *   own module directory (both currently live in the search module).
 * - Convention: every module that ships
 *   application/modules/<key>/indexers/<Key>.php with
 *   Modules\<Key>\Indexers\<Key> implementing Indexer is picked up
 *   automatically (autoloaded by \Ilch\Loader, no registration needed).
 *
 * A module therefore only has to deliver the indexer class; the queue
 * dispatch, the admin overview, the backfill and the global results page
 * pick it up on their own.
 *
 * The convention class name must equal the module key, so the search
 * module's own indexers/ directory (Forum.php, Article.php) can never
 * register a bogus "search" module: Modules\Search\Indexers\Search
 * does not exist.
 */
class Indexers
{
    /**
     * Explicit module key => indexer class FQCN for indexers that live
     * outside their own module directory.
     *
     * @var array<string, string>
     */
    public const MAP = [
        'forum'   => \Modules\Search\Indexers\Forum::class,
        'article' => \Modules\Search\Indexers\Article::class,
    ];

    /** Cached per request: module key => indexer class FQCN. */
    private static ?array $all = null;

    /**
     * Resolve the indexer for one module.
     *
     * @param string $module module key
     * @return Indexer|null null when the module has no indexer (class missing)
     */
    public static function get(string $module): ?Indexer
    {
        $class = self::MAP[$module] ?? self::conventionClass($module);
        if ($class === null || !class_exists($class) || !is_a($class, Indexer::class, true)) {
            return null;
        }

        return new $class();
    }

    /**
     * All module keys that currently have an indexer: the MAP entries plus
     * the convention modules found on disk (one glob + a few class_exists
     * calls, cached per request).
     *
     * @return array<string, string> module key => indexer class FQCN
     */
    public static function modules(): array
    {
        if (self::$all === null) {
            self::$all = [];
            foreach (self::MAP as $module => $class) {
                if (class_exists($class) && is_a($class, Indexer::class, true)) {
                    self::$all[$module] = $class;
                }
            }
            // Convention scan (same glob idiom as \Ilch\Plugin).
            foreach (glob(APPLICATION_PATH . '/modules/*/indexers') as $dir) {
                $module = strtolower(basename(dirname($dir)));
                if (isset(self::$all[$module])) {
                    continue;
                }
                $class = self::conventionClass($module);
                if ($class !== null && class_exists($class) && is_a($class, Indexer::class, true)) {
                    self::$all[$module] = $class;
                }
            }
        }

        return self::$all;
    }

    /**
     * Convention class name for one module key: Modules\<Key>\Indexers\<Key>.
     *
     * @param string $module module key
     * @return string|null null for keys that cannot name a class
     */
    private static function conventionClass(string $module): ?string
    {
        if (!preg_match('/^[a-z][a-z0-9_]*$/', $module)) {
            return null;
        }

        $key = ucfirst($module);
        return 'Modules\\' . $key . '\\Indexers\\' . $key;
    }
}
