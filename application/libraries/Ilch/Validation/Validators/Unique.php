<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Validation\Validators;

use Ilch\Registry;

/**
 * Email validation class
 */
class Unique extends Base
{
    protected $errorKey = 'validation.errors.unique.valueExists';

    public function run()
    {
        $db = Registry::get('db');

        $whereLeft = 'LOWER(`' . $this->data->getParam('column') . '`)';
        $whereMiddle = '=';
        $whereRight = $db->escape(strtolower($this->data->getValue()), true);

        $where = new \Ilch\Database\Mysql\Expression\Comparison($whereLeft, $whereMiddle, $whereRight);

        $result = $db->select()
            ->from($this->data->getParam('table'))
            ->where($where)
            ->execute();

        return [
            'result' => $result->getNumRows() === 0,
            'error_key' => $this->getErrorKey($this->data),
            'error_params' => [[$db->escape($this->data->getValue()), false]],
        ];
    }
}
