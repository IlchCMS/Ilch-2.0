<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;
defined('ACCESS') or die('no direct access');

class Mapper
{
    /**
     * @var string
     */
    protected $_primaryKey = 'id';
    
    /**
     * @var string
     */
    protected $_table = '';
    
    protected $_model;

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

    /**
     * Deletes entry from database.
     *
     * @todo method not ready now.
     * @param integer $id
     * @return boolean
     */
    public function delete($id)
    {
        return $this->db()->delete($this->_table)
            ->where(array($this->_primaryKey => $id))
            ->execute();
    }

    /**
     * Gets entry from database.
     *
     * @todo method not ready now.
     * @param type $id
     * @return \Ilch\_model
     */
    public function getById($id)
    {
        $row = $this->db()->selectRow('*')
            ->from($this->_table)
            ->where(array($this->_primaryKey => $id))
            ->execute();
        
        $model = new $this->_model();
        $model->fillWith($row);
        
        return $model;
    }

    /**
     * Gets entries from database.
     *
     * @todo method not ready now.
     * @param type $array
     */
    public function getBy($array)
    {
        $rows = $this->db()->selectArray()
            ->from($this->_table)
            ->where($array)
            ->execute();
        $models = array();

        foreach ($rows as $row) {
            $model = new $this->_model();
            $model->fillWith($row);
            $models[] = $model;
        }
        
        return $models;
    }
}
