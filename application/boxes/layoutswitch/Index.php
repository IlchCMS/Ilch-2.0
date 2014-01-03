<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Boxes\Layoutswitch;
defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Box
{
    public function render()
    {
        $this->getView()->set('layouts', glob(APPLICATION_PATH.'/layouts/*'));
    }
}

