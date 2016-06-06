<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database\Mysql\Expression;

class Comparison implements CompositePart
{
    /**
     * @var string
     */
    protected $left;

    /**
     * @var string
     */
    protected $operator;

    /**
     * @var $right
     */
    protected $right;

    /**
     * @param string $left
     * @param string $operator
     * @param string $right
     */
    public function __construct($left, $operator, $right)
    {
        $this->left = $left;
        $this->operator = $operator;
        $this->right = $right;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->left . ' ' . $this->operator . ' ' . $this->right;
    }
}
