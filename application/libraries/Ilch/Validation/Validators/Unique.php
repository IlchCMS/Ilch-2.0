<?php
/**
 * @copyright Ilch 2
 */

namespace Ilch\Validation\Validators;

use Ilch\Registry;

/**
 * Email validation class.
 */
class Unique extends Base
{
    /**
     * Default error key for this validator.
     *
     * @var string
     */
    protected $errorKey = 'validation.errors.unique.valueExists';

    /**
     * Defines whether logic can be negated.
     *
     * @since Version 2.1.43
     *
     * @var bool
     */
    protected $hasInvertLogic = true;

    /**
     * Default error key for this validator.
     *
     * @since Version 2.1.43
     *
     * @var string
     */
    protected $invertErrorKey = 'validation.errors.required.valueNotExists';

    /**
     * Minimum parameter count needed.
     *
     * @var int
     */
    protected $minParams = 1;

    /**
     * Runs the validation.
     *
     * @return self
     */
    public function run()
    {
        $db = Registry::get('db');

        $table = $this->getParameter(0);
        $column = $this->getParameter(1) ?? $this->getField();

        $ignoreId = $this->getParameter(2);
        $ignoreIdColumn = $this->getParameter(3) ?? 'id';

        $whereLeft = 'LOWER(`'.$column.'`)';
        $whereMiddle = '=';
        $whereRight = $db->escape(strtolower($this->getValue()), true);

        $where = new \Ilch\Database\Mysql\Expression\Comparison($whereLeft, $whereMiddle, $whereRight);

        $result = $db->select()
            ->from($table)
            ->where([$where]);

        if ($ignoreId !== null) {
            $result = $result->andWhere([$ignoreIdColumn.' !=' => $ignoreId]);
        }

        $result = $result->execute();

        $this->setIsValid($result->getNumRows() === 0);
        $this->setErrorParameters([$this->getValue()]);

        return $this;
    }
}
