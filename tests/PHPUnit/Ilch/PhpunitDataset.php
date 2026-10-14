<?php

/**
 * @package ilch_phpunit
 */

namespace PHPUnit\Ilch;

use Ilch\Database\MySQL;
use Symfony\Component\Yaml\Yaml;

/**
 * Loads fixture data from YAML files/strings into the database.
 *
 * Plain helper class - it is NOT a PHPUnit test case.
 *
 * It is also the single place where fixture files are validated and
 * parsed: DatabaseTestCase::setUpBeforeClass() reuses parseFile() for its
 * one-time fixture rebuild, so a broken fixture fails with the same error
 * at both entry points.
 *
 * @package ilch_phpunit
 */
class PhpunitDataset
{
    /**
     * @var MySQL
     */
    private MySQL $db;

    /**
     * Binds the dataset loader to a database instance.
     *
     * @param MySQL $db
     */
    public function __construct(MySQL $db)
    {
        $this->db = $db;
    }

    /**
     * Load information from multiple files (yml).
     *
     * @param array $fullpaths full paths to yml files to load.
     */
    public function loadFromFiles(array $fullpaths): void
    {
        foreach ($fullpaths as $fullpath) {
            $this->loadFromFile($fullpath);
        }
    }

    /**
     * Load information from one file (yml).
     *
     * Existing rows are cleared with DELETE - never with TRUNCATE: TRUNCATE
     * is DDL and implicitly commits, which would silently kill the per-test
     * isolation transaction started in DatabaseTestCase::setUp().
     *
     * @param string $fullpath full path to yml file to load.
     */
    public function loadFromFile(string $fullpath): void
    {
        $this->sendToDatabase(self::parseFile($fullpath));
    }

    /**
     * Load information from a string (yaml).
     *
     * @param string $content contents of yaml file to load.
     * @throws \Symfony\Component\Yaml\Exception\ParseException
     */
    public function loadFromString(string $content): void
    {
        $this->sendToDatabase(self::parseYaml($content));
    }

    /**
     * Reads and parses a YAML fixture file into a table map (table => rows).
     *
     * The single place where fixture files are validated and parsed -
     * shared by loadFromFiles() and DatabaseTestCase::setUpBeforeClass()
     * so a missing, unreadable or malformed fixture fails fast with the
     * same error at both entry points.
     *
     * @param string $fullpath full path to yml file to load.
     * @return array table => rows
     */
    public static function parseFile(string $fullpath): array
    {
        if (!file_exists($fullpath)) {
            throw new \InvalidArgumentException('File not found: ' . $fullpath);
        }

        if (!is_readable($fullpath)) {
            throw new \InvalidArgumentException('File not readable: ' . $fullpath);
        }

        $extension = strtolower(pathinfo($fullpath, PATHINFO_EXTENSION));
        if ($extension !== 'yml') {
            throw new \InvalidArgumentException('Cannot handle files with extension: ' . $extension);
        }

        return self::parseYaml((string) file_get_contents($fullpath));
    }

    /**
     * Parses YAML content and validates that the root is a table map.
     *
     * @param string $content contents of yaml file to load.
     * @return array table => rows
     * @throws \Symfony\Component\Yaml\Exception\ParseException
     */
    private static function parseYaml(string $content): array
    {
        $parsedYaml = Yaml::parse($content);

        if (!is_array($parsedYaml)) {
            throw new \InvalidArgumentException(
                'Expected a table map at the root of the YAML fixture, got: ' . gettype($parsedYaml)
            );
        }

        return $parsedYaml;
    }

    /**
     * Send all the information to the database.
     *
     * @param array $tables
     */
    public function sendToDatabase(array $tables): void
    {
        foreach ($tables as $table => $rows) {
            $tableNotEmpty = (bool) $this->db->select('*')
                ->from($table)
                ->execute()
                ->fetchCell();

            if ($tableNotEmpty) {
                $this->db->query('SET FOREIGN_KEY_CHECKS = 0;');

                // DELETE instead of TRUNCATE: TRUNCATE is DDL and implicitly
                // commits, which would silently kill the per-test isolation
                // transaction started in DatabaseTestCase::setUp().
                $this->db->query('DELETE FROM `' . $table . '`;');

                $this->db->query('SET FOREIGN_KEY_CHECKS = 1;');
            }

            foreach ((array) $rows as $columns) {
                $this->db->insert($table)
                    ->values($columns)
                    ->execute();
            }
        }
    }
}
