<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout\Helper\Hmenu;
defined('ACCESS') or die('no direct access');

class Model
{
    public function add($key, $value)
    {
        $this->_data[$key] = $value;
    }
    
    public function get($key)
    {
        return $this->_data[$key];
    }
}
