<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;

defined('ACCESS') or die('no direct access');

class Model
{
    /**
     * Fills the model with data.
     *
     * @param array $data
     */
    public function fillWith($data)
    {
        foreach ($data as $key => $value) {
            $methodName = 'set'.ucfirst($key);

            if (method_exists($this, $methodName)) {
                $this->$methodName($value);
            }
        }
    }

    /**
     * Dynamic gets/set properties for not defined getter/setter.
     *
     * @param string $name
     * @param mixed[] $arguments
     * @return mixed[]
     */
    public function __call($name, $arguments) 
    {
        $action = substr($name, 0, 3);
        $property = lcfirst(substr($name, 3));

        if ($action == 'get' || $action == 'set') {
            if (property_exists($this, $property)) {
                if ($action == 'get') {
                    return $this->$property;
                } else {
                    /*
                     * @todo implement more arguments.
                     */
                    $this->$property = $arguments[0];
                }
            } else {
                throw new \BadMethodCallException('property "' . $property . '" not defined');
            }
        }
    }
}
