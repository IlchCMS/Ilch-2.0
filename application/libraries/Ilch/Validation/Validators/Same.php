<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Validation\Validators;

/**
 * Same validation class
 */
class Same extends Base
{
    protected $errorKey = 'validation.errors.same.fieldsDontMatch';

    /**
     * Runs the validation
     * @param   Object \Ilch\Validation\Data $data
     *     Possible Parameters:
     *         as: the targeted field
     *         strict: 0|1
     * @returns Array  Validation result
     */
    public function run()
    {
        $data = $this->data;

        if((bool) $data->getParam('strict') === true) {
            $result = $data->getValue() === $data->getParam('as');
        } else {
            $result = $data->getValue() == $data->getParam('as');
        }

        $as = isset($this->fieldAliases[$data->getParam('as')]) ? $this->fieldAliases[$data->getParam('as')] : $data->getParam('as');

        return [
            'result' => $result,
            'error_key' => $this->getErrorKey($data),
            'error_params' => [[$as, true]]
        ];
    }
}
