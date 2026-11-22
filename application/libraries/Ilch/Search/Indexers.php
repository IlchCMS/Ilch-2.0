<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Search;

/**
 * Registry of available search indexers.
 *
 * Adding a future module (e.g. 'user') means: new indexer class + one MAP
 * entry + enqueue() hook calls in that module's mappers.
 */
class Indexers
{
    /**
     * module key => indexer class FQCN
     *
     * @var array<string, string>
     */
    public const MAP = [
        'forum'   => \Modules\Search\Indexers\Forum::class,
        'article' => \Modules\Search\Indexers\Article::class,
    ];

    /**
     * Resolve the indexer for one module.
     *
     * @param string $module module key
     * @return Indexer|null null when the module has no indexer (class missing)
     */
    public static function get(string $module): ?Indexer
    {
        $class = self::MAP[$module] ?? null;
        if ($class === null || !class_exists($class)) {
            return null;
        }

        return new $class();
    }

    /**
     * All module keys that currently have an indexer.
     *
     * @return array<string, string> module key => indexer class FQCN
     */
    public static function modules(): array
    {
        $available = [];
        foreach (self::MAP as $module => $class) {
            if (class_exists($class)) {
                $available[$module] = $class;
            }
        }

        return $available;
    }
}
