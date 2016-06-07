<?php 
/**
* @copyright Ilch 2.0
* @package ilch
*/

namespace Ilch\Database\Mysql\Expression;

/**
 * Placeholder for a Expression which will not be automatically quoted
 */
class Expression
{
    /** @var  string */
    protected $expression;

    /**
     * @param string $expression
     */
    public function __construct($expression)
    {
        $this->expression = (string) $expression;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->expression;
    }
} 
