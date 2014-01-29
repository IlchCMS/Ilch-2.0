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
}
