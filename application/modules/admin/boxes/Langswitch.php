<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Boxes;

class Langswitch extends \Ilch\Box
{
    public function render()
    {
        $this->getView()->set('language', $this->getTranslator()->getLocale());
    }
}
