<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Smilies\Controllers;

use Modules\Smilies\Mappers\Smilies as SmiliesMapper;

class Iframe extends \Ilch\Controller\Frontend
{
    public function smiliesAction()
    {
        $smiliesMapper = new SmiliesMapper();

        $this->getLayout()->setFile('modules/admin/layouts/iframe');

        $this->getView()->set('smilies', $smiliesMapper->getSmilies());
    }
}
