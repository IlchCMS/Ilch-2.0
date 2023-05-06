<?php
/**
 * @copyright Ilch 2
 */

namespace Ilch\Validation\Validators;

use Ilch\Database\Mysql\Expression\Comparison;
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
     * @var bool
     * @since 2.1.43
     */
    protected $hasInvertLogic = true;

    /**
     * Default error key for this validator.
     *
     * @var string
     * @since 2.1.43
     */
    protected $invertErrorKey = 'validation.errors.unique.valueNotExists';

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
    public function run(): Unique
    {
        $db = Registry::get('db');

        $table = $this->getParameter(0);
        $column = $this->getParameter(1) ?? $this->getField();

        $ignoreId = $this->getParameter(2);
        $ignoreIdColumn = $this->getParameter(3) ?? 'id';

        $whereLeft = 'LOWER(`'.$column.'`)';
        $whereMiddle = '=';
        $whereRight = $db->escape(strtolower($this->getValue()), true);

        $where = new Comparison($whereLeft, $whereMiddle, $whereRight);

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
