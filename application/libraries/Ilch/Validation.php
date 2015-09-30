<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;

use Ilch\Validation\Data;
use Ilch\Validation\Error;

/**
 * Validation class
 */
class Validation
{

    protected static $builtInValidators = [
        'required'  => '\Ilch\Validation\Validators\Required',      // Parameters: none
        'same'      => '\Ilch\Validation\Validators\Same',          // Parameters: strict:0|1, as:field_name
        'captcha'   => '\Ilch\Validation\Validators\Captcha',       // Parameters: none
        'length'    => '\Ilch\Validation\Validators\Length',        // Parameters: min: int, max: int
        'url'       => '\Ilch\Validation\Validators\Url',           // Parameters: none
        'email'     => '\Ilch\Validation\Validators\Email',         // Parameters: none
    ];

    protected static $validators = [];
    protected static $customFieldAliases = [];

    protected $input;
    protected $rules;
    protected $breakChain;

    protected $errors;
    protected $fields_with_error = [];
    protected $passes = true;

    /**
     * Constructor
     *
     * @param Array $input An array with input
     * @param Array $rules An array with validation rules
     */
    private function __construct($input, $rules, $breakChain, $autoRun)
    {
        $this->input = $input;
        $this->rules = $rules;
        $this->breakChain = $breakChain;

        if ($autoRun) {
            $this->run();
        }
    }

    /**
     * Runs the validation
     */
    public function run()
    {
        $availableValidators = self::getValidators();

        foreach($this->rules as $field => $rules) {
            // Iterating over the rules
            foreach(explode("|", $rules) as $rule) {
                // Iterating over the rules of that field
                if (strpos($rule, ",") === false) {
                    $vRule = $rule;
                    $vData = new Data($field, array_dot($this->input, $field), [], $this->input);
                } else {
                    $params = explode(",", $rule);
                    $vRule = $params[0];
                    unset($params[0]);

                    $vParams = [];

                    foreach($params as $param) {
                        if (strpos($rule, ":") === false) {
                            $vParams[] = trim($param);
                        } else {
                            $p = explode(":", $param);
                            $vParams[trim($p[0])] = trim($p[1]);
                        }
                    }
                    $vData = new Data($field, array_dot($this->input, $field), $vParams, $this->input);
                }

                if (isset($availableValidators[$vRule])) {
                    $validation = $this->validate($vRule, $vData);

                    if ($validation['result'] === false) {
                        $this->fields_with_error[] = $field;
                        $this->passes = false;

                        if ($this->breakChain) {
                            $this->errors[$field] = new Error($field, $validation['error_key'], (isset($validation['error_params']) ? $validation['error_params'] : []));
                            break;
                        }

                        $this->errors[$field][] = new Error($field, $validation['error_key'], (isset($validation['error_params']) ? $validation['error_params'] : []));
                    }
                } else {
                    throw new \InvalidArgumentException('The validator "'.$vRule.'" has not been registered.');
                }
            }
        }
    }

    /**
     * Performs a validation
     * @param String $rule An alias of an existing validator
     * @param Object $data A Data-Object with validation data
     */
    protected function validate($rule, $data)
    {
        $validator = self::getValidators()[$rule];
        if (($validator instanceof \Closure)) {
            $result = $validator($data);
        } else {
            $result = (new $validator($data, self::$customFieldAliases))->run();
        }

        return $result;
    }

    /**
     * Generating all the error messages
     * @param   Object \Ilch\Translator $translator The translator instance
     * @returns Array  An array with translated error messages
     */
    public function getErrors(\Ilch\Translator $translator)
    {
        if(empty($this->errors))
            return null;

        $errorMessages = [];
        $validationErrors = [];

        foreach($this->errors as $errors) {
            if(!$this->breakChain) {
                foreach($errors as $error) {
                    $validationErrors[] = $error;
                }
            } else {
                $validationErrors[] = $errors;
            }
        }

        foreach($validationErrors as $error) {

            $params = [];

            foreach($error->getParams() as $param) {
                if($param['translate'] === true) {
                    $params[] = $translator->trans($param['value']);
                } else {
                    $params[] = $param['value'];
                }
            }

            $field = isset(self::$customFieldAliases[$error->getField()]) ? self::$customFieldAliases[$error->getField()] : $error->getField();

            $args = [
                $error->getKey(),
                $translator->trans($field),
            ];

            $args = array_merge($args, $params);

            $errorMessages[] = call_user_func_array([$translator, 'trans'], $args);
        }

        return $errorMessages;
    }

    /**
     * Creates a new validation instance
     * @param   Array   $input       An array with inputs (e.g. user inputs)
     * @param   Array   $rules       An array with validation rules
     * @param Boolean [$breakChain = true]    Whether the validation should stop on validation errors or not
     * @param Boolean [$autoRun    = true]    If false you have to manually run the validation
     * @returns Object  A new Validation Object
     */
    public static function create($input, $rules, $breakChain = true, $autoRun = true)
    {
        return new Validation($input, $rules, $breakChain, $autoRun);
    }

    /**
     * Returns the validation result
     * @returns Boolean
     */
    public function isValid()
    {
        return $this->passes;
    }


    /**
     * Checks if the specified field has a validation error
     * @param   String  $field Field name
     * @returns Boolean
     */
    public function hasError($field)
    {
        return in_array($field, $this->fields_with_error);
    }

    /**
     * Returns an array with all field names which have validation errors
     * @returns Array
     */
    public function getFieldsWithError()
    {
        return $this->fields_with_error;
    }

    /**
     * Adds the specified validator
     * @param String        $alias     An alias for this validator
     * @param Object|String $validator This must be a string pointing to a valid class or a Closure
     */
    public static function addValidator($alias, $validator)
    {
        if (isset(self::$builtInValidators[$alias]) || isset(self::$validators[$alias])) {
            throw new \InvalidArgumentException('Validator alias "'.$alias.'" is already in use.');
        }

        if (!(is_object($validator) && ($validator instanceof \Closure))
            && (is_string($validator) && !class_exists($validator))) {
            throw new \InvalidArgumentException('Validator "'.$alias.'" is not a valid class or closure');
        }

        self::$validators[$alias] = $validator;
    }

    /**
     * Gets all validators (added and builtIn combined)
     * @returns Array All Validators known at this time during runtime
     */
    public static function getValidators()
    {
        return self::$validators + self::$builtInValidators;
    }

    public static function setCustomFieldAliases($aliases)
    {
        self::$customFieldAliases = $aliases;
    }
}
