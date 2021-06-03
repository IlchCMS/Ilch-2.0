<?php

namespace PHPUnit\Ilch;

use Symfony\Component\Yaml\Yaml;

/**
 * Class PhpunitDataset
 * @package PHPUnit\Ilch
 */
class PhpunitDataset extends DatabaseTestCase
{
    public function __construct($db)
    {
        parent::__construct();
        $this->db = $db;
    }

    /**
     * Load information from multiple files (yml).
     *
     * @param array $fullpaths full paths to yml files to load.
     */
    public function loadFromFiles(array $fullpaths): void
    {
        foreach ($fullpaths as $table => $fullpath) {
            // Only a table when it's an associative array.
            $table = \is_int($table) ? null : $table;
            $this->loadFromFile($fullpath, $table);
        }
    }

    /**
     * Load information from one file (yml).
     *
     * @param string $fullpath full path to yml file to load.
     */
    public function loadFromFile(string $fullpath): void
    {
        if (!file_exists($fullpath)) {
            throw new coding_exception('File not found: ' . $fullpath);
        }

        if (!is_readable($fullpath)) {
            throw new coding_exception('File not readable: ' . $fullpath);
        }

        $extension = strtolower(pathinfo($fullpath, PATHINFO_EXTENSION));
        if ($extension !== 'yml') {
            throw new coding_exception('Cannot handle files with extension: ' . $extension);
        }

        $this->loadFromString(file_get_contents($fullpath));
    }

    /**
     * Load information from a string (yaml).
     *
     * @param string $content contents of yaml file to load.
     */
    public function loadFromString(string $content): void
    {
        try {
            $parsedYaml = Yaml::parse($content);
            $this->sendToDatabase($parsedYaml);
        } catch (ParseException $exception) {
            printf('Unable to parse the YAML string: %s', $exception->getMessage());
        }
    }

    /**
     * Send all the information to the database.
     *
     * @param array $tables
     */
    public function sendToDatabase(array $tables): void
    {
        foreach($tables as $table => $rows) {
            $tableNotEmpty = (bool) $this->db->select('*')
                ->from($table)
                ->execute()
                ->fetchCell();

            if ($tableNotEmpty) {
                $this->db->truncate($table);
            }

            foreach($rows as $row => $columns) {
                    $this->db->insert($table)
                        ->values($columns)
                        ->execute();
            }
        }
    }
}
