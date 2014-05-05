<?php
/**
 * Created by PhpStorm.
 * User: sebastian
 * Date: 01.05.14
 * Time: 16:10
 */

namespace Ilch\Database\Mysql\Expression;

class AndX extends Composite
{
    /**
     * @var string
     */
    protected $separator = ' AND ';
}
