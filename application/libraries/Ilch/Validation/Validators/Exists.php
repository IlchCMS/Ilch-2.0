<?php
/**
 * @copyright Ilch 2.0
 */

namespace Ilch\Validation\Validators;

use \Ilch\Registry;

/**
 * Exists validation class.
 */
class Exists extends Base
{
    /**
     * Default error key for this validator.
     *
     * @var string
     */
    protected $errorKey = 'validation.errors.exists.resourceNotFound';

    /**
     * Minimum parameter count needed.
     *
     * @var int
     */
    protected $minParams = 1;

    /**
     * Select instance
     *
     * @var Ilch\Database\Mysql\Select
     */
    protected $query;

    /**
     * Runs the validation.
     *
     * @return self
     */
    public function run()
    {
        if (empty($this->getValue()) && $this->getValue() !== 0 && $this->getValue() !== '0') {
            $this->setIsValid(true);

            return $this;
        }

        $this->query = Registry::get('db')->select();

        $column = $this->getParameter(1) ?? 'id';

        $this->query->fields($column);
        $this->query->from($this->getParameter(0));
        $this->query->andWhere([$column => $this->getValue()]);

        if ($this->hasConditions()) {
            $this->setConditions();
        }

        $result = $this->query->execute();

        $this->setIsValid($result->getNumRows() > 0);

        return $this;
    }

    /**
     * Appends the conditions returned from getConditions() to the query
     */
    protected function setConditions()
    {
        $conditions = $this->getConditions();

        if (count($conditions) % 2 !== 0) {
            throw new \InvalidArgumentException(get_class($this).': Wrong parameter count.');
        }

        $chunks = array_chunk($conditions, 2);
        $conditions = array();

        foreach ($chunks as $chunk) {
            $this->query->andWhere([$chunk[0] => $chunk[1]]);
        }
    }

    /**
     * Returns true if there are conditions in the rule definition
     *
     * @return boolean
     */
    protected function hasConditions()
    {
        return count($this->getParameters()) > 2;
    }

    /**
     * Returns an array with all condition parameters.
     *
     * @return array
     */
    protected function getConditions()
    {
        return array_slice($this->getParameters(), 2);
    }
}
