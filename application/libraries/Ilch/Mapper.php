<?php
/**
 * @copyright Ilch 2.0
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

    /**
     * Simple helper for triggering events
     * @param string $event
     * @param array $args
     */
    protected function trigger($event, array $args) {
        trigger($event, new Event($event, $args));
    }
}
