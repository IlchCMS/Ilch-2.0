<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Validation\Validators;

/**
 * Length validation class
 * Checks a string for a minimum and/or maximum length
 */
class Length extends Base
{
    public function run()
    {
        $data = $this->data;

        $min = is_null($data->getParam('min')) ? null : (int) $data->getParam('min');
        $max = is_null($data->getParam('max')) ? null : (int) $data->getParam('max');

        $val = $data->getValue();

        if($min !== null && $max !== null) {
            // Min and max value specified
            $result = strlen($val) >= $min && strlen($val) <= $max;
            $errorKey = 'validation.errors.length.tooShortAndOrTooLong';
            $errorParams = [[$min, false], [$max, false]];
        } elseif($min !== null && $max === null) {
            // Only a min value specified
            $result = strlen($val) >= $min;
            $errorKey = 'validation.errors.length.tooShort';
            $errorParams = [[$min, false]];
        } elseif($min === null && $max !== null) {
            // Only a max value specified
            $result = strlen($val) <= $max;
            $errorKey = 'validation.errors.length.tooLong';
            $errorParams = [[$max, false]];
        } else {
            throw new \RuntimeException("No 'min' and/or 'max' value(s) defined.");
        }

        if($data->getParam('customErrorAlias') !== null) {
            $errorKey = $data->getParam('customErrorAlias');
        }

        return [
            'result' => $result,
            'error_key' => $errorKey,
            'error_params' => $errorParams,
        ];
    }
}
