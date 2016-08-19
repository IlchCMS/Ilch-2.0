<?php
/**
 * @copyright Ilch 2.0
 */

namespace Ilch\Validation\Validators;

use Ilch\Registry;

/**
 * Email validation class.
 */
class Unique extends Base
{
    protected $errorKey = 'validation.errors.unique.valueExists';
    protected $minParams = 1;
    protected $maxParams = 4;

    public function run()
    {
        $db = Registry::get('db');

        $table = $this->data->getParam(0);
        $column = is_null($this->data->getParam(1)) ? $this->data->getField() : $this->data->getParam(1);

        $ignoreId = $this->data->getParam(2);
        $ignoreIdColumn = is_null($this->data->getParam(3)) ? 'id' : $this->data->getParam(3);

        $whereLeft = 'LOWER(`'.$column.'`)';
        $whereMiddle = '=';
        $whereRight = $db->escape(strtolower($this->data->getValue()), true);

        $where = new \Ilch\Database\Mysql\Expression\Comparison($whereLeft, $whereMiddle, $whereRight);

        $result = $db->select()
            ->from($table)
            ->where([$where]);

        if (!is_null($ignoreId)) {
            $result = $result->andWhere([$ignoreIdColumn.' !=' => $ignoreId]);
        }

        $result = $result->execute();

        return [
            'result' => $result->getNumRows() === 0,
            'error_key' => $this->getErrorKey($this->data),
            'error_params' => [[$db->escape($this->data->getValue()), false]],
        ];
    }
}
