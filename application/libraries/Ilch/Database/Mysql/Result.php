<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database\Mysql;

use Ilch\Database\Mysql as DB;

class Result
{
    /** @var integer TYPE for fetchRows or fetchArray */
    const ASSOC = MYSQLI_ASSOC;
    /** @var integer TYPE for fetchRows or fetchArray */
    const BOTH = MYSQLI_BOTH;
    /** @var integer TYPE for fetchRows or fetchArray */
    const NUM = MYSQLI_NUM;

    /** @var \mysqli_result */
    protected $dbResult;

    /** @var \Ilch\Database\Mysql */
    protected $db;

    /**
     * @param \mysqli_result $dbResult
     * @param \Ilch\Database\Mysql $db
     */
    public function __construct(\mysqli_result $dbResult, DB $db)
    {
        $this->dbResult = $dbResult;
        $this->db = $db;
    }

    /**
     * Returns mysqli_result f.e. to use the Iterator interface
     * @return \mysqli_result
     */
    public function getMysqliResult()
    {
        return $this->dbResult;
    }

    /**
     * Fetches one field of the current result row
     *
     * @param integer|string|null $name name or number of field
     * @return string|false|null value of field; false if the field is not in the result, null if no more rows in the result
     */
    public function fetchCell($name = null)
    {
        if (isset($name)) {
            if (is_int($name)) {
                $fieldNumber = $name;
            } else {
                $row = $this->dbResult->fetch_assoc();
                if (!isset($row)) {
                    return null;
                } elseif (isset($row[$name])) {
                    return $row[$name];
                } else {
                    return false;
                }
            }
        } else {
            $fieldNumber = 0;
        }
        $row = $this->dbResult->fetch_row();
        if (!isset($row)) {
            return null;
        } elseif (isset($row[$fieldNumber])) {
            return $row[$fieldNumber];
        }

        return false;
    }

    /**
     * Returns array with numerical and string keys of the current row or null if no more rows are in the result
     * @param integer $type type of returned array
     * @return array|null
     */
    public function fetchArray($type = self::BOTH)
    {
        return $this->dbResult->fetch_array($type);
    }

    /**
     * Return array with indexed keys of the current row or null if no more rows are in the result
     * @return array|null
     */
    public function fetchRow()
    {
        return $this->dbResult->fetch_row();
    }

    /**
     * Return associative array of the current row or null if no more rows are in the result
     * @return array|null
     */
    public function fetchAssoc()
    {
        return $this->dbResult->fetch_assoc();
    }

    /**
     * Returns an object of the current row or null if no more rows are in the result
     * @param null $className
     * @param array $params
     * @return object|\stdClass
     */
    public function fetchObject($className = null, array $params = null)
    {
        return $this->dbResult->fetch_object($className, $params);
    }

    /**
     * Returns a array with all rows of the result
     * @param mixed $keyField name/number of a "unique" field used for key of the returned array
     * @param integer $type type of returned array
     * @return array[]
     */
    public function fetchRows($keyField = null, $type = self::ASSOC)
    {
        $this->setCurrentRow(0);
        $results = [];
        if (isset($keyField)) {
            while (null !== ($row = $this->fetchArray($type))) {
                $results[$row[$keyField]] = $row;
            }
        } else {
            while (null !== ($row = $this->fetchArray($type))) {
                $results[] = $row;
            }
        }
        return $results;
    }

    /**
     * Returns a array with one field of each row
     * @param string $field
     * @param string $keyField
     * @return string[]
     */
    public function fetchList($field = null, $keyField = null)
    {
        $this->setCurrentRow(0);
        $results = [];

        if (null === $field) {
            $fetchMethod = 'fetchRow';
            $field = 0;
        } else {
            $fetchMethod = 'fetchAssoc';
        }

        if (isset($keyField)) {
            while (null !== ($row = $this->$fetchMethod())) {
                $results[$row[$keyField]] = $row[$field];
            }
        } else {
            while (null !== ($row = $this->$fetchMethod())) {
                $results[] = $row[$field];
            }
        }

        return $results;
    }

    /**
     * Returns number of rows in the result
     * @return integer
     */
    public function getNumRows()
    {
        return $this->dbResult->num_rows;
    }

    /**
     * Returns number of fields in the result
     * @return integer
     */
    public function getFieldCount()
    {
        return $this->dbResult->field_count;
    }

    /**
     * Für eine Select Query, die mit useFoundRows aufgerufen wurde, kann so die FOUND_ROWS() aufgerufen werden
     * @return integer
     */
    public function getFoundRows()
    {
        return (int) $this->db->queryCell('SELECT FOUND_ROWS()');
    }

    /**
     * Set the internal pointer to the given position (must be between 0..getNumRow()-1)
     * @param int $position
     * @return bool
     */
    public function setCurrentRow($position)
    {
        return $this->dbResult->data_seek($position);
    }
}
