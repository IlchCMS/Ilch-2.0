<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch;

use Ilch\Database\Mysql;

class Mapper
{
    /**
     * Hold the database adapter.
     *
     * @var Mysql|null
     */
    private $db;

    /**
     * Injects the database adapter to the mapper.
     */
    public function __construct()
    {
        $this->setDatabase(Registry::get('db'));
    }

    /**
     * Gets the database adapter.
     *
     * @return Mysql|null
     */
    public function getDatabase(): ?Database\Mysql
    {
        return $this->db;
    }

    /**
     * Shortcut for getDatabse.
     *
     * @return Mysql|null
     */
    public function db(): ?Database\Mysql
    {
        return $this->getDatabase();
    }

    /**
     * Sets the database adapter.
     *
     * @param Mysql|null $db
     * @return $this
     */
    public function setDatabase(?Database\Mysql $db): Mapper
    {
        $this->db = $db;
        return $this;
    }
}
