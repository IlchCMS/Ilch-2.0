<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database\Mysql;
defined('ACCESS') or die('no direct access');

class Insert extends QueryBuilder
{
    /**
     * @var string
     */
    protected $_type = 'insert';

    /**
     * Gets delete query builder sql.
     *
     * @return string
     */
    public function generateSql()
    { 
        $sql = 'INSERT INTO `[prefix]_'.$this->_table.'` ( ';
        $sqlFields = array();
        $sqlValues = array();

        foreach ($this->_fields as $key => $value) {
            if ($value === null) {
                continue;
            }

            $sqlFields[] = '`' . $key . '`';
        }

        $sql .= implode(',', $sqlFields);
        $sql .= ') VALUES (';

        foreach ($this->_fields as $key => $value) {
            if ($value === null) {
                continue;
            }

            $sqlValues[] = '"' . $this->_db->escape($value) . '"';
        }

        $sql .= implode(',', $sqlValues) . ')';

        return $sql;
    }
}
