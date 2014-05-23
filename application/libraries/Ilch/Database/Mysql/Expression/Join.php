<?php
/**
 * Created by PhpStorm.
 * User: sebastian
 * Date: 18.05.14
 * Time: 08:51
 */

namespace Ilch\Database\Mysql\Expression;


class Join
{
    /** @var string */
    const INNER = 'INNER';

    /** @var string */
    const LEFT = 'LEFT';

    /** @var string */
    const RIGHT = 'RIGHT';

    /**
     * @var string|array
     */
    protected $table;

    /**
     * @var array
     */
    protected $conditions;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $fields;

    /**
     * @param string|array $table
     * @param string $type
     * @throws \InvalidArgumentException
     */
    function __construct($table, $type)
    {
        $allowedTypes = [self::INNER, self::LEFT, self::RIGHT];
        if (!in_array($type, $allowedTypes)) {
            throw new \InvalidArgumentException('invalid type, allowed: ' . implode(', ', $allowedTypes));
        }

        $this->table = $table;
        $this->type = $type;
    }

    /**
     * @return array|string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param array $fields
     * @return Join
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param array $conditions
     * @return Join
     */
    public function setConditions(array $conditions)
    {
        $this->conditions = $conditions;
        return $this;
    }

    /**
     * @return array
     */
    public function getConditions()
    {
        return $this->conditions;
    }
}
