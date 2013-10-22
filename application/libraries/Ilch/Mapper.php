<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;
defined('ACCESS') or die('no direct access');

class Mapper
{
    /**
     * Hold the database adapter.
     *
     * @var Ilch_Database_*
     */
    private $_db;

    /**
     * Injects the database adapter to the mapper.
     */
    public function __construct()
    {
        $this->_db = Registry::get('db');
    }

    /**
     * Gets the database adapter.
     *
     * @return Ilch_Database_*
     */
    public function getDatabase()
    {
        return $this->_db;
    }

    /**
     * Shortcut for getDatabse.
     *
     * @return Ilch_Database_*
     */
    public function db()
    {
        return $this->getDatabase();
    }

    /**
     * Sets the database adapter.
     *
     * @param Ilch_Database_*
     */
    public function setDatabase($db)
    {
        $this->_db = $db;
    }
}
