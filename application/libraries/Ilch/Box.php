<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;
defined('ACCESS') or die('no direct access');

class Box
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
     * Shortcut for getDatabse.
     *
     * @return Ilch_Database_*
     */
    public function db()
    {
        return $this->_db;
    }
}
