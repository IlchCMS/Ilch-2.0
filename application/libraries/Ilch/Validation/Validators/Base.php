<?php
/**
 * @copyright Ilch 2
 */

namespace Ilch\Validation\Validators;

use stdClass;

/**
 * Base validation class
 * Checks a string for a minimum and/or maximum length.
 */
abstract class Base
{
    /**
     * The validators name.
     *
     * @var [type]
     */
    protected $name;

    /**
     * The current field.
     *
     * @var string
     */
    protected $field;

    /**
     * The current fields value.
     *
     * @var string
     */
    protected $value;

    /**
     * All user-submitted input.
     *
     * @var array
     */
    protected $input;

    /**
     * All given parameters for this validator.
     *
     * @var array|null
     */
    protected $parameters;

    /**
     * Invert the result for this validator.
     *
     * @var bool
     * @since 2.1.43
     */
    protected $invertResult = false;

    /**
     * Defines whether logic can be negated.
     *
     * @var string
     * @since 2.1.43
     */
    protected $hasInvertLogic = false;

    /**
     * The validation result.
     *
     * @var bool
     */
    protected $result;

    /**
     * The parameters used for the error message.
     *
     * @var array
     */
    protected $errorParameters = [];

    /**
     * The validators default error translation key.
     *
     * @var string
     */
    protected $errorKey;

    /**
     * Default inverted error key for this validator.
     *
     * @var string
     * @since 2.1.43
     */
    protected $invertErrorKey = '';

    /**
     * Number of parameters a validator needs at all cost.
     *
     * @var int
     */
    protected $minParams = 0;

    /**
     * Constructor.
     *
     * @param stdClass $data The needed data
     */
    public function __construct(stdClass $data)
    {
        $this->setField($data->field);
        $this->setValue(array_dot($data->input, $data->field, ''));
        $this->setInput($data->input);
        $this->setParameters($data->parameters);

        if ($this->hasInvertLogic) {
            $this->setInvertResult($data->invertResult);
        }

        if (($this->minParams !== null && is_array($this->getParameters()) && count($this->getParameters()) < $this->minParams)) {
            throw new \InvalidArgumentException(get_class($this).' expects at least '.$this->minParams.' parameter(s) given: '
                .count($this->getParameters()));
        }
    }

    /**
     * Perform the validation task
     *  - should call ::setIsValid
     *  - for errors should use ::setErrorKey and optionally setErrorParameters
     *
     * @return null
     */
    abstract public function run();

    /**
     * Get the value of The current field.
     *
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set the value of The current field.
     *
     * @param string $field
     *
     * @return self
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get the value of The current fields value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the value of The current fields value.
     *
     * @param string $value
     *
     * @return self
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get the value of All user-submitted input.
     *
     * @return array
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * Set the value of All user-submitted input.
     *
     * @param array $input
     *
     * @return self
     */
    public function setInput(array $input)
    {
        $this->input = $input;

        return $this;
    }

    /**
     * Get the value of All given parameters for this validator.
     *
     * @return array|null
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Set the value of All given parameters for this validator.
     *
     * @param array|null $parameters
     *
     * @return self
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Get the value of invertResult for this validator.
     *
     * @return bool
     * @since 2.1.43
     */
    public function getInvertResult()
    {
        return $this->invertResult;
    }

    /**
     * Set the value of Invert Result for this validator.
     *
     * @param bool $invertResult
     * @return self
     * @since 2.1.43
     */
    public function setInvertResult(bool $invertResult)
    {
        $this->invertResult = $invertResult;

        return $this;
    }

    /**
     * Get the value of The validation result.
     *
     * @return bool
     */
    public function isValid()
    {
        return ($this->invertResult ? !$this->result : $this->result);
    }

    /**
     * Set the value of The validation result.
     *
     * @param bool $result
     *
     * @return self
     */
    public function setIsValid($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get the value of Error Key.
     *
     * @return string
     */
    public function getErrorKey()
    {
        return $this->errorKey;
    }

    /**
     * Set the value of Error Key.
     *
     * @param string $errorKey
     *
     * @return self
     */
    public function setErrorKey($errorKey)
    {
        $this->errorKey = $errorKey;

        return $this;
    }

    /**
     * Get the value of inverted Error Key.
     *
     * @return string
     * @since 2.1.43
     */
    public function getInvertErrorKey()
    {
        return $this->invertErrorKey;
    }

    /**
     * Set the value of inverted Error Key.
     *
     * @param string $invertErrorKey
     * @return self
     * @since 2.1.43
     */
    public function setInvertErrorKey($invertErrorKey)
    {
        $this->invertErrorKey = $invertErrorKey;

        return $this;
    }

    /**
     * Get the value of The parameters used for the error message.
     *
     * @return array
     */
    public function getErrorParameters()
    {
        return $this->errorParameters;
    }

    /**
     * Set the value of The parameters used for the error message.
     *
     * @param array $errorParameters
     *
     * @return self
     */
    public function setErrorParameters(array $errorParameters)
    {
        $this->errorParameters = $errorParameters;

        return $this;
    }

    /**
     * Returns the validators class name.
     *
     * @return string Class name (without namespace) in lowercase
     */
    public function getName()
    {
        $class = explode('\\', get_class($this));

        return strtolower(array_pop($class));
    }

    /**
     * Returns the value for the specified key from parameters.
     *
     * @param int $key The array index
     *
     * @return mixed
     */
    public function getParameter($key)
    {
        return $this->parameters[$key] ?? null;
    }
}
