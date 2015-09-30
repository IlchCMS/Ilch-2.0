<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 * @author Tobias Schwarz <tobias.schwarz@gmx.eu>
 */

namespace Ilch\Filters;

/**
 * Base filter class
 */
abstract class Base
{
    /**
     * Returns the filtered content
     *
     * @param mixed $parameters
     */
    abstract public function __construct($parameters = null);

    abstract public function filter($data);
}
