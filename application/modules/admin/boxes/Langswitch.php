<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Boxes;

defined('ACCESS') or die('no direct access');

class Langswitch extends \Ilch\Box
{
    public function render()
    {
        $this->getView()->set('language', $this->getTranslator()->getLocale());
    }
}
