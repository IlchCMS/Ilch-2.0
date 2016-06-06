<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database\Mysql\Expression;

class AndX extends Composite
{
    /**
     * @var string
     */
    protected $separator = ' AND ';
}
