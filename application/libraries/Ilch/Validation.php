<?php
/**
 * @copyright Ilch 2.0
 */

namespace Ilch;

use stdClass;
use Ilch\Validation\ErrorBag;

/**
 * Validation class.
 */
class Validation
{
    /**
     * Array with all built in validators.
     *
     * @var array
     */
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

    /**
     * Validators added on runtime.
     *
     * @var array
     */
    protected static $validators = [];

    /**
     * Custom field aliases.
     *
     * @var array
     */
    protected static $customFieldAliases = [];

    /**
     * Custom field aliases.
     *
     * @var array
     */
    protected static $customErrorKeys = [];

    /**
     * User input.
     *
     * @var array
     */
    protected $input;

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $rules;

    /**
     * Stop validation for field on error.
     *
     * @var bool
     */
    protected static $breakChain = true;

    /**
     * Auto run validation on creation.
     *
     * @var bool
     */
    protected static $autoRun = true;

    /**
     * Holds error messages.
     *
     * @var \Ilch\Validation\ErrorBag
     */
    protected $errorBag;

    /**
     * The translator instance.
     *
     * @var \Ilch\Translator
     */
    protected $translator;

    /**
     * Array with parsed validation rules.
     *
     * @var array
     */
    protected $validationRules = array();

    /**
     * Validation state.
     *
     * @var bool
     */
    protected $passes = true;

    /**
     * Creates a new validation instance.
     *
     * @param array $input An array with inputs (e.g. user inputs)
     * @param array $rules An array with validation rules
     *
     * @return object A new Validation Object
     */
    private function __construct($input, $rules)
    {
        $this->input = $input;
        $this->rules = $rules;
        $this->errorBag = new ErrorBag();
        $this->translator = Registry::get('translator');

        if (self::$autoRun) {
            $this->run();
        }
    }

    /**
     * Runs the validation.
     */
    public function run()
    {
        $this->parseRules();
        $this->validateRules();
    }

    /**
     * Parses the rule strings.
     */
    protected function parseRules()
    {
        foreach ($this->rules as $field => $rules) {
            foreach (explode('|', $rules) as $validator) {
                if (strpos($validator, ':') === false) {
                    $this->validationRules[$field][$validator] = null;

                    continue;
                }

                $parts = explode(':', $validator);

                $validator = $parts[0];
                $params = explode(',', $parts[1]);

                $this->validationRules[$field][$validator] = null;

                foreach ($params as $param) {
                    if (empty($param)) {
                        continue;
                    }

                    $this->validationRules[$field][$validator][] = $param;
                }
            }
        }
    }

    /**
     * Calls the validators using the parsed validation rules.
     */
    public function validateRules()
    {
        foreach ($this->validationRules as $field => $validators) {
            foreach ($validators as $validator => $parameters) {
                $data = new stdClass();
                $data->field = $field;
                $data->parameters = $parameters;
                $data->input = $this->input;

                $result = $this->checkResult($this->validate($validator, $data));

                if (self::$breakChain && !$result) {
                    break;
                }
            }
        }
    }

    /**
     * Parses a validator result.
     *
     * @param \Ilch\Validation\Validators\Base $validator A validator instance
     */
    protected function checkResult(\Ilch\Validation\Validators\Base $validator)
    {
        if ($validator->isValid() === false) {
            $this->handleError($validator);
        }

        return $validator->isValid();
    }

    /**
     * Handles the processing of the error messages.
     *
     * @param string $validator The validator instance
     */
    protected function handleError($validator)
    {
        $field = $validator->getField();
        $rawField = $validator->getField();
        $errorKey = $validator->getErrorKey();
        $errorParameters = $validator->getErrorParameters();

        if (isset(self::$customFieldAliases[$field])) {
            $field = self::$customFieldAliases[$field];
        }

        if (isset(self::$customErrorKeys[$field][$validator->getName()][$errorKey])) {
            $errorKey = self::$customErrorKeys[$field][$validator->getName()][$errorKey];
        }

        $translatorParameters = [];

        foreach ($errorParameters as $errorParameter) {
            $translatorParameters[] = $this->getTranslator()->trans($errorParameter);
        }

        array_unshift($translatorParameters, $errorKey, $this->getTranslator()->trans($field));

        $errorMessage = call_user_func_array([$this->getTranslator(), 'trans'], $translatorParameters);

        $this->getErrorBag()->addError($rawField, $errorMessage);
    }

    /**
     * Performs a validation.
     *
     * @param string $validator An alias of an existing validator
     * @param object $data      A Data-Object with validation data
     */
    protected function validate($validator, $data)
    {
        $validator = self::getValidators()[$validator];
        $validator = (new $validator($data))->run();

        return $validator;
    }

    /**
     * Returns an array with all errors.
     *
     * @return array An array with translated error messages
     */
    public function getErrors()
    {
        return $this->getErrorBag()->getErrors();
    }

    /**
     * Creates a new validation instance.
     *
     * @param array $input An array with inputs (e.g. user inputs)
     * @param array $rules An array with validation rules
     *
     * @return \Ilch\Validation A new Validation Object
     */
    public static function create($input, $rules)
    {
        return new self($input, $rules);
    }

    /**
     * Returns the validation result.
     *
     * @return bool
     */
    public function isValid()
    {
        return !$this->getErrorBag()->hasErrors();
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

    /**
     * Sets custom field aliases (instead of field name).
     *
     * @param string $aliases
     */
    public static function setCustomFieldAliases($aliases)
    {
        self::$customFieldAliases = $aliases;
    }

    /**
     * Sets custom error keys.
     *
     * @param string $errorKeys
     */
    public static function setCustomErrorKeys($errorKeys)
    {
        self::$customErrorKeys = $errorKeys;
    }

    /**
     * Returns the ErrorBag instance.
     *
     * @return \Ilch\Validation\ErrorBag
     */
    public function getErrorBag()
    {
        return $this->errorBag;
    }

    /**
     * Returns the Translator instance.
     *
     * @return \Ilch\Translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * Set the value of Stop validation for field on error.
     *
     * @param bool $breakChain
     */
    public static function setBreakChain($breakChain)
    {
        self::$breakChain = $breakChain;
    }

    /**
     * Set the value of Auto run validation on creation.
     *
     * @param bool $autoRun
     */
    public static function setAutoRun($autoRun)
    {
        self::$autoRun = $autoRun;
    }
}
