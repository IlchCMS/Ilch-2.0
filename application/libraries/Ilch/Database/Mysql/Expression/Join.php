<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
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
    protected $conditionsType = 'and';

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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param array $fields
     * @return Join
     */
    public function setFields($fields): Join
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param array $conditions
     * @param string|null $type 'and' or 'or'
     * @return Join
     */
    public function setConditions(array $conditions, $type = null): Join
    {
        $this->conditions = $conditions;
        if (isset($type) && in_array($type, ['and', 'or'])) {
            $this->conditionsType = $type;
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * @return string
     */
    public function getConditionsType(): string
    {
        return $this->conditionsType;
    }
}
