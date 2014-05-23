<?php
/**
 * Created by PhpStorm.
 * User: sebastian
 * Date: 01.05.14
 * Time: 16:11
 */

namespace Ilch\Database\Mysql\Expression;


class OrX extends Composite
{
    /**
     * @var string
     */
    protected $separator = ' OR ';
}
