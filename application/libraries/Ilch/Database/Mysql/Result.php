<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database\Mysql;

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
    public function __construct(\mysqli_result $dbResult, \Ilch\Database\Mysql $db)
    {
        $this->dbResult = $dbResult;
        $this->db = $db;
    }

    /**
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
     * @return mixed|null value of field; null if no field or row was found in result
     */
    public function fetchCell($name = null)
    {
        if (isset($name)) {
            if (is_int($name)) {
                $fieldNumber = $name;
            } else {
                $row = $this->dbResult->fetch_assoc();
                if (isset($row[$name])) {
                    return $row[$name];
                } else {
                    return null;
                }

            }
        }
        if (!isset($fieldNumber)) {
            $fieldNumber = 0;
        }
        $row = $this->dbResult->fetch_row();
        if (isset($row[$fieldNumber])) {
            return $row[$fieldNumber];
        }

        return null;
    }

    /**
     * Returns array with numerical and string keys
     * @param integer $type type of returned array
     * @return mixed
     */
    public function fetchArray($type = self::BOTH)
    {
        return $this->dbResult->fetch_array($type);
    }

    /**
     * Return array with associative
     * @return mixed
     */
    public function fetchRow()
    {
        return $this->dbResult->fetch_row();
    }

    /**
     * Return associative array of current rows
     * @return array
     */
    public function fetchAssoc()
    {
        return $this->dbResult->fetch_assoc();
    }

    /**
     * Returns a array with all rows of the result
     * @param mixed $keyField name/number of a "unique" field used for key of the returned array
     * @param integer $type type of returned array
     * @return array[]
     */
    public function fetchRows($keyField = null, $type = self::ASSOC)
    {
        $this->dbResult->data_seek(0);
        $results = [];
        if (isset($keyField)) {
            while ($row = $this->fetchArray($type)) {
                $results[$row[$keyField]] = $row;
            }
        } else {
            while ($row = $this->fetchArray($type)) {
                $results[] = $row;
            }
        }
        return $results;
    }

    /**
     * Returns a array with one field of each row
     * @param string $key
     * @param string $field
     * @return string[]
     */
    public function fetchList($key = null, $field = null)
    {
        if (null === $field && null !== $key) {
            $field = $key;
            $key = null;
        }
        $results = [];

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
}
