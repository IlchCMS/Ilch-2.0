<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 * @author Tobias Schwarz <tobias.schwarz@gmx.eu>
 */

namespace Ilch\Filters;

/**
 * String To Lower filter class
 */
class StringToLower extends Base
{
    public function __construct($paramters = null)
    {

    }

    public function filter($data)
    {
        return strtolower($data);
    }
}
