<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch;

class Server
{
    /**
     * @var string
     */
    protected $fetchMethod;

    /**
     * Checks which transfer methods are viable.
     *
     * @return null
     */
    public function init()
    {
        if (!empty($this->fetchMethod)) {
            return;
        }
    }
    
    public function getFile()
    {
    }
}
