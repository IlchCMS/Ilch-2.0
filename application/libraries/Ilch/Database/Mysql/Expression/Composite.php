<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database\Mysql\Expression;

abstract class Composite implements CompositePart
{
    /**
     * @var array
     */
    protected $parts;

    /**
     * Separator has to be set in concrete class
     *
     * @var string
     */
    protected $separator;

    /**
     * @param array $parts
     */
    public function __construct(array $parts = [])
    {
        $this->addParts($parts);
    }

    /**
     * @param CompositePart $part
     * @return $this
     */
    public function add(CompositePart $part)
    {
        $this->parts[] = $part;
        return $this;
    }

    /**
     * @param array $parts
     * @return $this
     */
    public function addParts(array $parts)
    {
        foreach ($parts as $part) {
            $this->add($part);
        }
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if (!is_array($this->parts)) {
            return '';
        }

        if (count($this->parts) === 1) {
            return (string) $this->parts[0];
        }

        if (count($this->parts) > 0) {
            $parts = [];
            foreach ($this->parts as $part) {
                $parts[] = (string) $part;
            }
            return '(' . implode($this->separator, $parts) . ')';
        }

        return '';
    }
}
