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
 * @package ilch_phpunit
 */
class PhpunitDataset
{
    /**
     * @var MySQL
     */
    private $db;

    public function __construct(MySQL $db)
    {
        $this->db = $db;
    }

    /**
     * Load information from multiple files (yml).
     *
     * @param array $fullpaths full paths to yml files to load.
     */
    public function loadFromFiles(array $fullpaths)
    {
        foreach ($fullpaths as $fullpath) {
            $this->loadFromFile($fullpath);
        }
    }

    /**
     * Load information from one file (yml).
     *
     * @param string $fullpath full path to yml file to load.
     * @param bool $truncate clear existing rows with TRUNCATE instead of
     * DELETE. TRUNCATE also resets the auto-increment counter of the table -
     * required for the one-time fixture load in
     * DatabaseTestCase::setUpBeforeClass(). Only use it OUTSIDE a
     * transaction: TRUNCATE is DDL and would implicitly commit a per-test
     * isolation transaction.
     */
    public function loadFromFile(string $fullpath, bool $truncate = false)
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

        $this->loadFromString((string) file_get_contents($fullpath), $truncate);
    }

    /**
     * Load information from a string (yaml).
     *
     * @param string $content contents of yaml file to load.
     * @param bool $truncate see loadFromFile().
     * @throws \Symfony\Component\Yaml\Exception\ParseException
     */
    public function loadFromString(string $content, bool $truncate = false)
    {
        $parsedYaml = Yaml::parse($content);

        if (!is_array($parsedYaml)) {
            throw new \InvalidArgumentException(
                'Expected a table map at the root of the YAML fixture, got: ' . gettype($parsedYaml)
            );
        }

        $this->sendToDatabase($parsedYaml, $truncate);
    }

    /**
     * Send all the information to the database.
     *
     * @param array $tables
     * @param bool $truncate see loadFromFile().
     */
    public function sendToDatabase(array $tables, bool $truncate = false)
    {
        foreach ($tables as $table => $rows) {
            $tableNotEmpty = (bool) $this->db->select('*')
                ->from($table)
                ->execute()
                ->fetchCell();

            if ($tableNotEmpty) {
                $this->db->query('SET FOREIGN_KEY_CHECKS = 0;');

                if ($truncate) {
                    // TRUNCATE resets the auto-increment counter to its initial
                    // value - exactly what the old per-test drop/recreate
                    // produced for the fixture's tables.
                    $this->db->truncate($table);
                } else {
                    // DELETE instead of TRUNCATE: TRUNCATE is DDL and implicitly
                    // commits, which would silently kill the per-test isolation
                    // transaction started in DatabaseTestCase::setUp().
                    $this->db->query('DELETE FROM `' . $table . '`;');
                }

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
