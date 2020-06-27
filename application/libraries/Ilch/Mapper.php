<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch;

class Mapper
{
    /**
     * Hold the database adapter.
     *
     * @var \Ilch\Database\Mysql
     */
    private $db;

    /**
     * Injects the database adapter to the mapper.
     */
    public function __construct()
    {
        $this->db = Registry::get('db');
    }

    /**
     * Gets the database adapter.
     *
     * @return \Ilch\Database\Mysql
     */
    public function getDatabase()
    {
        return $this->db;
    }

    /**
     * Shortcut for getDatabse.
     *
     * @return \Ilch\Database\Mysql
     */
    public function db()
    {
        return $this->getDatabase();
    }

    /**
     * Sets the database adapter.
     *
     * @param \Ilch\Database\Mysql
     */
    public function setDatabase($db)
    {
        $this->db = $db;
    }
}
