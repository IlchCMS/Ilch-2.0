<?php
/**
 * @package ilch
 */

namespace Ilch;
defined('ACCESS') or die('no direct access');

class Server
{
    /**
     * @var string
     */
    protected $_fetchMethod;

    /**
     * Checks which transfer methods are viable.
     *
     * @return null
     */
    public function init()
    {
        if (!empty($this->_fetchMethod)) {
            return;
        }

        
    }
    
    public function getFile()
    {
        
    }
}
