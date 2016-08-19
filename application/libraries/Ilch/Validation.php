<?php
/**
 * @copyright Ilch 2.0
 */

namespace Ilch;

use Ilch\Validation\Data;
use Ilch\Validation\ErrorBag;

/**
 * Validation class.
 */
class Validation
{
    protected static $builtInValidators = [
        // Usage: required
        'required' => '\Ilch\Validation\Validators\Required',

        // Usage: same:<field_name>[,strict]
        'same' => '\Ilch\Validation\Validators\Same',

        // Usage: captcha
        'captcha' => '\Ilch\Validation\Validators\Captcha',

        // Usage: url
        'url' => '\Ilch\Validation\Validators\Url',

        // Usage: email
        'email' => '\Ilch\Validation\Validators\Email',

        // Usage: unique:<table_name>[,<column_name>,<ignoreId>,<ignoreIdColumn>]
        'unique' => '\Ilch\Validation\Validators\Unique',

        // Usage: numeric
        'numeric' => '\Ilch\Validation\Validators\Numeric',

        // Usage: integer
        'integer' => '\Ilch\Validation\Validators\Integer',

        // Usage: size:<value>[,string]
        'size' => '\Ilch\Validation\Validators\Size',

        // Usage: min:<value>[,string]
        'min' => '\Ilch\Validation\Validators\Min',

        // Usage: max:<value>[,string]
        'max' => '\Ilch\Validation\Validators\Max',
    ];

    protected static $validators = [];
    protected static $customFieldAliases = [];

    protected $input;
    protected $rules;
    protected $breakChain;

    protected $errors;

    protected $errorBag;
    protected $translator;

    protected $fields_with_error = [];
    protected $passes = true;

    /**
     * Constructor.
     *
     * @param array $input An array with input
     * @param array $rules An array with validation rules
     */
    private function __construct($input, $rules, $breakChain, $autoRun)
    {
        $this->input = $input;
        $this->rules = $rules;
        $this->breakChain = $breakChain;
        $this->errorBag = new ErrorBag();
        $this->translator = Registry::get('translator');

        if ($autoRun) {
            $this->run();
        }
    }

    /**
     * Runs the validation.
     */
    public function run()
    {
        $availableValidators = self::getValidators();

        foreach ($this->rules as $field => $rules) {
            // Iterating over the rules
            foreach (explode('|', $rules) as $rule) {
                // Iterating over the rules of that field
                if (strpos($rule, ':') === false) {
                    $vRule = $rule;
                    $vData = new Data($field, array_dot($this->input, $field), [], $this->input);
                } else {
                    $parts = explode(':', $rule);
                    $vRule = $parts[0];

                    $vParams = [];
                    $params = explode(',', $parts[1]);

                    foreach ($params as $param) {
                        array_push($vParams, $param);
                    }

                    $vData = new Data($field, array_dot($this->input, $field), $vParams, $this->input);
                }

                if (isset($availableValidators[$vRule])) {
                    $validation = $this->validate($vRule, $vData);

                    if ($validation['result'] === false) {
                        $this->passes = false;

                        $args = [
                            $validation['error_key'],
                            $this->getTranslator()->trans($field),
                        ];

                        if (isset($validation['error_params'])) {
                            foreach ($validation['error_params'] as $param) {
                                if (is_array($param)) {
                                    if (isset($param[1]) && $param[1] === true) {
                                        array_push($args, $this->getTranslator()->trans($param[0]));
                                    } else {
                                        array_push($args, $param[0]);
                                    }
                                } else {
                                    array_push($args, $param);
                                }
                            }
                        }

                        $errorMessage = call_user_func_array([$this->getTranslator(), 'trans'], $args);
                        $this->getErrorBag()->addError($field, $errorMessage);

                        if ($this->breakChain) {
                            break;
                        }
                    }
                } else {
                    throw new \InvalidArgumentException('The validator "'.$vRule.'" has not been registered.');
                }
            }
        }
    }

    /**
     * Performs a validation.
     *
     * @param string $rule An alias of an existing validator
     * @param object $data A Data-Object with validation data
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
     * Generating all the error messages.
     *
     * @param object \Ilch\Translator $translator The translator instance
     *
     * @return array An array with translated error messages
     */
    public function getErrors($translator = null)
    {
        return $this->getErrorBag()->getErrors();
    }

    /**
     * Creates a new validation instance.
     *
     * @param array $input An array with inputs (e.g. user inputs)
     * @param array $rules An array with validation rules
     * @param bool [$breakChain = true]    Whether the validation should stop on validation errors or not
     * @param bool [$autoRun    = true]    If false you have to manually run the validation
     * @returns Object  A new Validation Object
     */
    public static function create($input, $rules, $breakChain = true, $autoRun = true)
    {
        return new self($input, $rules, $breakChain, $autoRun);
    }

    /**
     * Returns the validation result.
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->passes;
    }

    /**
     * Checks if the specified field has a validation error.
     *
     * @param string $field Field name
     *
     * @return bool
     */
    public function hasError($field)
    {
        return in_array($field, $this->fields_with_error);
    }

    /**
     * Returns an array with all field names which have validation errors.
     *
     * @return array
     */
    public function getFieldsWithError()
    {
        return $this->getErrorBag()->getErrorFields();
    }

    /**
     * Adds the specified validator.
     *
     * @param string        $alias     An alias for this validator
     * @param object|string $validator This must be a string pointing to a valid class or a Closure
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
     * Gets all validators (added and builtIn combined).
     *
     * @return array All Validators known at this time during runtime
     */
    public static function getValidators()
    {
        return self::$validators + self::$builtInValidators;
    }

    public static function setCustomFieldAliases($aliases)
    {
        self::$customFieldAliases = $aliases;
    }

    public function getErrorBag()
    {
        return $this->errorBag;
    }

    public function getTranslator()
    {
        return $this->translator;
    }
}
